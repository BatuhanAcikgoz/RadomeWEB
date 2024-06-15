<?php
/**
 * Magaza customer class
 *
 * @package Modules\Magaza
 * @author Partydragen
 * @version 2.2.0
 * @license MIT
 */
class Customer {

    private static array $_customers_cache = [];

    private DB $_db;
    private User $_user;

    /**
     * @var object|null The customer's data. Basically just the row from `rw_store_customers` where the customer ID is the key.
     */
    private $_data;

    /**
     * @var bool Whether this customer is logged in or not.
     */
    private bool $_isLoggedIn = false;

    public function __construct(?User $user = null, string $value = null, string $field = 'id') {
        $this->_db = DB::getInstance();

        if ($user != null && $user->exists()) {
            // Load customer by RadomeWEB User
            if (!$this->find($user->data()->id, 'user_id')) {
                // Customer data for RadomeWEB User missing, Register it
                $this->create(['user_id' => $user->data()->id, 'integration_id' => 0]);
            }

            $this->_user = $user;
        } else if ($value != null) {
            $this->find($value, $field);

        } else if (Session::exists('store_customer')) {
            $customer = Session::get('store_customer');

            if ($this->find($customer, 'id')) {
                $this->_isLoggedIn = true;
            }
        }
    }

    /**
     * Find a customer by unique identifier (ID, username, identifier, etc).
     * Loads instance variables for this class.
     *
     * @param string|null $value Unique identifier.
     * @param string $field What column to check for their unique identifier in.
     *
     * @return bool True/false on success or failure respectfully.
     */
    public function find(string $value = null, string $field = 'id'): bool {
        if (isset(self::$_customers_cache["$value.$field"])) {
            $this->_data = self::$_customers_cache["$value.$field"];
            return true;
        }

        if ($field == 'id' || $field == 'user_id') {
            $data = $this->_db->query('SELECT `c`.`id`, `c`.`user_id`, `c`.`integration_id`, `c`.`cents`, IFNULL(`c`.`username`, ui.username) as username, IFNULL(`c`.`identifier`, `ui`.`identifier`) as identifier FROM `rw_store_customers` AS c LEFT JOIN rw_users_integrations AS ui ON c.user_id=ui.user_id AND ui.integration_id=1 WHERE `c`.`'.$field.'` = ?', [$value]);
        } else {
            $data = $this->_db->get('store_customers', [$field, '=', $value]);
        }

        if ($data->count()) {
            $this->_data = $data->first();
            self::$_customers_cache["$value.$field"] = $this->_data;
            return true;
        }

        return false;
    }

    /**
     * Update customer data in the database.
     *
     * @param array $fields Column names and values to update.
     */
    public function update(array $fields = []) {
        if (!$this->_db->update('store_customers', $this->data()->id, $fields)) {
            throw new Exception('There was a problem updating customer');
        }
    }

    /**
     * Create a new customer.
     *
     * @param array $fields Column names and values to insert to database.
     */
    public function create(array $fields = []) {
        if (!$this->_db->insert('store_customers', $fields)) {
            throw new Exception('There was a problem registering the customer');
        }
        $last_id = $this->_db->lastId();

        $data = $this->_db->get('store_customers', ['id', '=', $last_id]);
        if ($data->count()) {
            $this->_data = $data->first();
        }
        
        return $last_id;
    }

    /**
     * Does this customer exist?
     *
     * @return bool Whether the customer exists (has data) or not.
     */
    public function exists(): bool {
        return (!empty($this->_data));
    }

    /**
     * Get the customer data.
     *
     * @return object|null This customer data.
     */
    public function data() {
        return $this->_data;
    }
    
    /**
     * Try to get the RadomeWEB User for this customer
     *
     * @return User RadomeWEB User
     */
    public function getUser(): User {
        return $this->_user ??= (function (): User {
            if ($this->data()->user_id != null) {
                return new User($this->data()->user_id);
            } else if ($this->data()->identifier != null) {
                $integration = Integrations::getInstance()->getIntegration('Minecraft');
                if ($integration != null) {
                    $integration_user = new IntegrationUser($integration, str_replace('-', '', $this->data()->identifier), 'identifier');
                    if ($integration_user->exists()) {
                        return $integration_user->getUser();
                    }
                }
            }

            return new User($this->data()->username, 'username');
        })();
    }

    /**
     * Get the customer credits.
     *
     * @return float The customer credits balance.
     */
    public function getCredits() {
        return number_format($this->_data->cents / 100, 2, '.', '');
    }

    /**
     * Add credits to the customer.
     *
     * @param int $cents The amount of cents to add to their balance.
     * @param string $info Transaction information.
     * @return int Returns the Transaction id.
     */
    public function addCents(int $cents, string $info = '', int $user_id = null): int {
        $this->_db->query('UPDATE rw_store_customers SET cents = cents + ? WHERE id = ?', [$cents, $this->_data->id]);

        $this->_db->insert('store_transactions', [
            'customer_id' => $this->_data->id,
            'received_by' => $user_id,
            'action' => 'add_cents',
            'cents' => $cents,
            'time' => date('U'),
            'info' => $info
        ]);

        return $this->_db->lastId();
    }

    /**
     * Remove credits from the customer.
     *
     * @param int $cents The amount of cents to remove from their balance.
     * @param string $info Transaction information.
     * @return int Returns the Transaction id.
     */
    public function removeCents(int $cents, string $info = '', int $user_id = null): int {
        $this->_db->query('UPDATE rw_store_customers SET cents = cents - ? WHERE id = ?', [$cents, $this->_data->id]);

        $this->_db->insert('store_transactions', [
            'customer_id' => $this->_data->id,
            'received_by' => $user_id,
            'action' => 'remove_cents',
            'cents' => '-' . $cents,
            'time' => date('U'),
            'info' => $info
        ]);

        return $this->_db->lastId();
    }

    public function getPayments(): array {
        $payments_list = [];

        $payments = DB::getInstance()->query('SELECT rw_store_payments.*, order_id, user_id FROM rw_store_payments LEFT JOIN rw_store_orders ON order_id=rw_store_orders.id WHERE rw_store_orders.user_id = ? ORDER BY created DESC', [$this->_data->user_id]);
        if ($payments->count()) {
            $payments = $payments->results();

            foreach  ($payments as $paymentQuery) {
                $payment = new Payment($paymentQuery->id);

                $payments_list[] = [
                    'status_id' => $paymentQuery->status_id,
                    'status' => $payment->getStatusHtml(),
                    'currency' => Output::getPurified($paymentQuery->currency),
                    'amount' => Magaza::fromCents($paymentQuery->amount_cents),
                    'amount_format' => Output::getPurified(
                        Magaza::formatPrice(
                            $paymentQuery->amount_cents,
                            $paymentQuery->currency,
                            Magaza::getCurrencySymbol(),
                            STORE_CURRENCY_FORMAT,
                        )
                    ),
                    'date' => date('d M Y, H:i', $paymentQuery->created),
                    'link' => URL::build('/panel/magaza/odemeler', 'payment=' . Output::getClean($paymentQuery->id))
                ];
            }
        }

        return $payments_list;
    }
    
    public function getPurchasedProducts(): array {
        $products = [];

        $bought_products = DB::getInstance()->query('SELECT DISTINCT(product_id) FROM `rw_store_orders_products` INNER JOIN rw_store_orders ON rw_store_orders.id=rw_store_orders_products.order_id INNER JOIN rw_store_payments ON rw_store_payments.order_id=rw_store_orders_products.order_id WHERE to_customer_id = ?', [$this->data()->id]);
        if ($bought_products->count()) {
            foreach ($bought_products->results() as $product) {
                $products[$product->product_id] = $product->product_id;
            }
        }
        
        return $products;
    }
    
    public function login($username, $save = true) {
        $validation_method = Settings::get('username_validation_method', 'radome', 'Magaza');
        if ($validation_method == 'radome') {
            $validation_method = 'no_validation';
        }

        switch ($validation_method) {

            case 'no_validation':
                if ($this->find($username, 'username')) {
                    // Customer already exist in database
                    $this->_isLoggedIn = true;
                    if ($save)
                        Session::put('store_customer', $this->data()->id);

                    return true;
                } else {
                    // Register new customer
                    $this->create([
                        'integration_id' => 1,
                        'username' => $username,
                        'identifier' => null
                    ]);
                    $this->_isLoggedIn = true;

                    if ($save)
                        Session::put('store_customer', $this->data()->id);

                    return true;
                }
        }

        return false;
    }

    /**
     * Log the customer out if logged in by session.
     */
    public function logout(): void {
        if (Session::exists('store_customer')) {
            Session::delete('store_customer');
            $this->_isLoggedIn = false;
        }
    }

    /**
     * Get if this customer is currently logged in or not.
     *
     * @return bool Whether they're logged in.
     */
    public function isLoggedIn(): bool {
        return $this->_isLoggedIn;
    }

    public function getIdentifier(): string {
        if ($this->exists()) {
            if (strlen($this->_data->identifier) == 32) {
                return Output::getClean($this->formatUUID($this->_data->identifier));
            }

            return Output::getClean($this->_data->identifier ?? 'none');
        }

        return 'none';
    }

    public function getUsername(): string {
        if ($this->exists()) {
            return Output::getClean($this->_data->username ?? ($this->getUser()->exists() ? $this->getUser()->data()->username : 'Unknown'));
        }

        return 'Unknown';
    }
}
