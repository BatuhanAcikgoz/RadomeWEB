<?php
/*
 *  Made by Partydragen
 *  https://partydragen.com/resources/resource/5-store-module/
 *  https://partydragen.com/
 *
 *  License: MIT
 *
 *  Magaza module
 */

class Magaza {
    private $_db,
            $_cache;

   /**
     * @var array The list of the active sales.
     */
    private static array $_active_sales;



    /**
     * @var Language Instance of Language class for translations
     */
    private static Language $_store_language;

    // Constructor, connect to database
    public function __construct($cache, $store_language) {
        $this->_db = DB::getInstance();

        $this->_cache = $cache;
    }

    public function getMagazaURL(): string {
        return Util::getSetting('store_path', '/magaza', 'Magaza');
    }

    // Get all products
    public function getProducts() {
        $products_list = [];

        $products = $this->_db->query('SELECT * FROM rw_store_products WHERE deleted = 0 ORDER BY `order` ASC')->results();
        foreach ($products as $data) {
            $product = new Product(null, null, $data);

            $products_list[] = $product;
        }

        return $products_list;
    }
    
    // Get all payments
    public function getAllPayments() {
        $payments = $this->_db->query('SELECT rw_store_payments.*, identifier, username, order_id, rw_store_orders.user_id, to_customer_id FROM rw_store_payments LEFT JOIN rw_store_orders ON order_id=rw_store_orders.id LEFT JOIN rw_store_customers ON to_customer_id=rw_store_customers.id ORDER BY created DESC')->results();

        return $payments;
    }
    
    // Get all categories
    public function getAllCategories() {
        $categories = $this->_db->query('SELECT * FROM rw_store_categories WHERE deleted = 0 ORDER BY `order` ASC')->results();

        $categories_array = [];
        foreach ($categories as $category) {
            $categories_array[] = [
                'id' => Output::getClean($category->id),
                'name' => Output::getClean($category->name)
            ];
        }

        return $categories_array;
    }
    
    // Get all connections
    public function getAllConnections() {
        $connections = $this->_db->query('SELECT * FROM rw_store_connections')->results();

        $connections_array = [];
        foreach ($connections as $connection) {
            $connections_array[] = [
                'id' => Output::getClean($connection->id),
                'name' => Output::getClean($connection->name)
            ];
        }

        return $connections_array;
    }
    
    // Get navbar menu
    public function getNavbarMenu($active) {
        $store_url = $this->getMagazaURL();
        $categories = [];

        $categories_query = DB::getInstance()->query('SELECT * FROM rw_store_categories WHERE parent_category IS NULL AND disabled = 0 AND hidden = 0 AND deleted = 0 ORDER BY `order` ASC')->results();
        if (count($categories_query)) {
            foreach ($categories_query as $item) {
                $subcategories_query = DB::getInstance()->query('SELECT id, `name` FROM rw_store_categories WHERE parent_category = ? AND disabled = 0 AND hidden = 0 AND deleted = 0 ORDER BY `order` ASC', [$item->id])->results();

                $subcategories = [];
                $sub_active = false;
                if (count($subcategories_query)) {
                    foreach ($subcategories_query as $subcategory) {
                        $sub_active = Output::getClean($active) == Output::getClean($subcategory->name);

                        $subcategories[] = [
                            'url' => URL::build($store_url . '/kategori/' . Output::getClean($subcategory->id)),
                            'title' => Output::getClean($subcategory->name),
                            'active' => $sub_active
                        ];
                    }
                }

                $categories[$item->id] = [
                    'url' => URL::build($store_url . '/kategori/' . Output::getClean($item->id)),
                    'title' => Output::getClean($item->name),
                    'subcategories' => $subcategories,
                    'active' => !$sub_active && Output::getClean($active) == Output::getClean($item->name),
                    'only_subcategories' => Output::getClean($item->only_subcategories)
                ];
            }
        }

        return $categories;
    }

    /**
     * @return Language The current language instance for translations
     */
    public static function getLanguage(): Language {
        if (!isset(self::$_store_language)) {
            self::$_store_language = new Language(ROOT_PATH . '/modules/Magaza/language');
        }

        return self::$_store_language;
    }

    public static function getMagazaPath(): string {
        return Util::getSetting('store_path', '/magaza', 'Magaza');
    }

    public static function getCurrency(): string {
        return Util::getSetting('currency', 'TL', 'Magaza');
    }

    public static function getCurrencySymbol(): string {
        return Util::getSetting('currency_symbol', '₺', 'Magaza');
    }

    /**
     * Helper function to format price with currency
     *
     * @param $price_cents int Price
     * @param $currencyCode string Currency code (eg GBP, USD, EUR)
     * @param $currencySymbol string Currency symbol
     * @param $format ?string Format
     * @return string Formatted price with currency
     */
    public static function formatPrice(int $price_cents, string $currencyCode, string $currencySymbol, ?string $format = '{currencySymbol}{price} {currencyCode}'): string {
        return str_replace([
            '{currencyCode}',
            '{currencySymbol}',
            '{price}'
        ], [
            $currencyCode,
            $currencySymbol,
            sprintf('%0.2f', $price_cents / 100),
        ], $format);
    }

    /**
     * Get the active sales .
     *
     * @return Product[] The products for this order.
     */
    public static function getActiveSales(): array {
        return self::$_active_sales ??= (function (): array {
            $active_sales = [];

            $sales = DB::getInstance()->query('SELECT * FROM rw_store_sales WHERE start_date < ? AND expire_date > ? ORDER BY `expire_date` DESC', [date('U'), date('U')])->results();

            return $sales;
        })();
    }

    /*
     *  Check for Module updates
     *  Returns JSON object with information about any updates
     */
    public static function toCents($value): int {
        return (int) (string) ((float) preg_replace("/[^0-9.]/", "", $value) * 100);
    }

    public static function fromCents(int $cents): string {
        return sprintf('%0.2f', $cents / 100);
    }
}