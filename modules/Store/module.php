<?php
/*
 *  Made by Partydragen
 *  https://partydragen.com/resources/resource/5-store-module/
 *  https://partydragen.com/
 *  RadomeWEB version 2.0.0-pr13
 *
 *  License: MIT
 *
 *  Store module - panel payments page
 */

class Store_Module extends Module {
    private DB $_db;
    private $_store_language, $_language, $_cache, $_store_url;

    public function __construct($language, $store_language, $pages, $cache, $endpoints) {
        $this->_db = DB::getInstance();
        $this->_language = $language;
        $this->_store_language = $store_language;
        $this->_cache = $cache;

        $name = 'Store';
        $author = '<a href="https://batuhanacikgoz.com.tr/" target="_blank" rel="nofollow noopener">Reeignn</a>';
        $module_version = '1.4.3';
        $radome_version = '1.0';

        parent::__construct($this, $name, $author, $module_version, $radome_version);

        // Get variables from cache
        $cache->setCache('store_settings');
        if ($cache->isCached('store_url')) {
            $this->_store_url = Output::getClean(rtrim($cache->retrieve('store_url'), '/'));
        } else {
            $this->_store_url = '/magaza';
        }

        // Define URLs which belong to this module
        $pages->add('Store', $this->_store_url, 'pages/store/index.php', 'store', true);
        $pages->add('Store', $this->_store_url . '/kategori', 'pages/store/category.php', 'product', true);
        $pages->add('Store', $this->_store_url . '/checkout', 'pages/store/checkout.php');
        $pages->add('Store', $this->_store_url . '/kontrol', 'pages/store/check.php');
        $pages->add('Store', $this->_store_url . '/iptal', 'pages/store/cancel.php');
        $pages->add('Store', $this->_store_url . '/goruntule', 'pages/store/view.php');
        $pages->add('Store', '/magaza/islem', 'pages/backend/process.php');
        $pages->add('Store', '/magaza/listener', 'pages/backend/listener.php');
        $pages->add('Store', '/panel/magaza/genel_ayarlar', 'pages/panel/general_settings.php');
        $pages->add('Store', '/panel/magaza/gateways', 'pages/panel/gateways.php');
        $pages->add('Store', '/panel/magaza/urunler', 'pages/panel/products.php');
        $pages->add('Store', '/panel/magaza/urun', 'pages/panel/product.php');
        $pages->add('Store', '/panel/magaza/kategoriler', 'pages/panel/categories.php');
        $pages->add('Store', '/panel/magaza/odemeler', 'pages/panel/payments.php');
        $pages->add('Store', '/panel/magaza/baglantilar', 'pages/panel/connections.php');
        $pages->add('Store', '/panel/magaza/alanlar', 'pages/panel/fields.php');
        $pages->add('Store', '/panel/kullanicilar/magaza', 'pages/panel/users_store.php');

        $pages->add('Store', '/kullanici/magaza', 'pages/user/store.php');

        EventHandler::registerEvent('paymentPending',  $store_language->get('admin', 'payment_pending'));
        EventHandler::registerEvent('paymentCompleted', $store_language->get('admin', 'payment_completed'));
        EventHandler::registerEvent('paymentRefunded', $store_language->get('admin', 'payment_refunded'));
        EventHandler::registerEvent('paymentReversed', $store_language->get('admin', 'payment_reversed'));
        EventHandler::registerEvent('paymentDenied', $store_language->get('admin', 'payment_denied'));

        $endpoints->loadEndpoints(ROOT_PATH . '/modules/Store/includes/endpoints');

        // Check if module version changed
    }

    public function onInstall() {
        // Initialise
        $this->initialise();
    }

    public function onUninstall() {
        // Not necessary
    }

    public function onEnable() {
        // Check if we need to initialise again
        $this->initialise();
    }

    public function onDisable() {
        // Not necessary
    }

    public function onPageLoad($user, $pages, $cache, $smarty, $navs, $widgets, $template) {
        // Add link to navbar
        $cache->setCache('nav_location');
        if (!$cache->isCached('store_location')) {
            $link_location = 1;
            $cache->store('store_location', 1);
        } else {
            $link_location = $cache->retrieve('store_location');
        }

        $cache->setCache('navbar_order');
        if (!$cache->isCached('store_order')) {
            $store_order = 21;
            $cache->store('store_order', 21);
        } else {
            $store_order = $cache->retrieve('store_order');
        }

        $cache->setCache('navbar_icons');
        if (!$cache->isCached('store_icon'))
            $icon = '<i class="fas fa-store"></i>';
        else
            $icon = $cache->retrieve('store_icon');

        $cache->setCache('store_settings');
        if ($cache->isCached('navbar_position'))
            $navbar_pos = $cache->retrieve('navbar_position');
        else
            $navbar_pos = 'top';

        switch ($link_location) {
            case 1:
                // Navbar
                $navs[0]->add('store', $this->_store_language->get('general', 'store'), URL::build($this->_store_url), 'top', null, $store_order, $icon);
            break;
            case 2:
                // "More" dropdown
                $navs[0]->addItemToDropdown('more_dropdown', 'store', $this->_store_language->get('general', 'store'), URL::build($this->_store_url), 'top', null, $icon, $store_order);
            break;
            case 3:
                // Footer
                $navs[0]->add('store', $this->_store_language->get('general', 'store'), URL::build($this->_store_url), 'footer', null, $store_order, $icon);
            break;
        }

        $navs[1]->add('cc_store', $this->_store_language->get('general', 'store'), URL::build('/kullanici/magaza'), 'top', null, 10);

		// Widgets
		// Latest purchases
		require_once(ROOT_PATH . '/modules/Store/widgets/LatestPurchasesWidget.php');
		$widgets->add(new LatestStorePurchasesWidget($smarty, $this->_language, $this->_store_language, $cache));

        if (defined('BACK_END')) {
            // Define permissions which belong to this module
            PermissionHandler::registerPermissions('Store', [
                'staffcp.store' => $this->_language->get('moderator', 'staff_cp') . ' &raquo; ' . $this->_store_language->get('general', 'store'),
                'staffcp.store.settings' => $this->_language->get('moderator', 'staff_cp') . ' &raquo; ' . $this->_store_language->get('admin', 'settings'),
                'staffcp.store.gateways' => $this->_language->get('moderator', 'staff_cp') . ' &raquo; ' . $this->_store_language->get('admin', 'gateways'),
                'staffcp.store.products' => $this->_language->get('moderator', 'staff_cp') . ' &raquo; ' . $this->_store_language->get('admin', 'products'),
                'staffcp.store.payments' => $this->_language->get('moderator', 'staff_cp') . ' &raquo; ' . $this->_store_language->get('admin', 'payments'),
                'staffcp.store.connections' => $this->_language->get('moderator', 'staff_cp') . ' &raquo; ' . $this->_store_language->get('admin', 'connections'),
                'staffcp.store.fields' => $this->_language->get('moderator', 'staff_cp') . ' &raquo; ' . $this->_store_language->get('admin', 'fields'),
            ]);

            if ($user->hasPermission('staffcp.store')) {
                $cache->setCache('panel_sidebar');
                if (!$cache->isCached('store_order')) {
                    $order = 10;
                    $cache->store('store_order', 10);
                } else {
                    $order = $cache->retrieve('store_order');
                }

                $navs[2]->add('store_divider', mb_strtoupper($this->_store_language->get('general', 'store')), 'divider', 'top', null, $order, '');

                if (!$cache->isCached('store_configuration_icon')) {
                    $icon = '<i class="nav-icon fas fa-wrench"></i>';
                    $cache->store('store_configuration_icon', $icon);
                } else
                    $icon = $cache->retrieve('store_configuration_icon');

                $navs[2]->addDropdown('store_configuration', $this->_store_language->get('admin', 'store_configuration'), 'top', $order + 0.1, $icon);

                if ($user->hasPermission('staffcp.store.settings')) {
                    if (!$cache->isCached('store_settings_icon')) {
                        $icon = '<i class="nav-icon fas fa-cogs"></i>';
                        $cache->store('store_settings_icon', $icon);
                    } else
                        $icon = $cache->retrieve('store_settings_icon');

                    $navs[2]->addItemToDropdown('store_configuration', 'general_settings', $this->_language->get('admin', 'general_settings'), URL::build('/panel/magaza/genel_ayarlar'), 'top', null, $icon, $order + 0.2);
                }

                if ($user->hasPermission('staffcp.store.gateways')) {
                    if (!$cache->isCached('store_gateways_icon')) {
                        $icon = '<i class="nav-icon far fa-credit-card"></i>';
                        $cache->store('store_gateways_icon', $icon);
                    } else
                        $icon = $cache->retrieve('store_gateways_icon');

                    $navs[2]->addItemToDropdown('store_configuration', 'store_gateways', $this->_store_language->get('admin', 'gateways'), URL::build('/panel/magaza/gateways'), 'top', null, $icon, $order + 0.3);
                }

                if ($user->hasPermission('staffcp.store.connections')) {
                    if (!$cache->isCached('store_connections_icon')) {
                        $icon = '<i class="nav-icon fas fa-plug"></i>';
                        $cache->store('store_connections_icon', $icon);
                    } else
                        $icon = $cache->retrieve('store_connections_icon');

                    $navs[2]->addItemToDropdown('store_configuration', 'store_connections', $this->_store_language->get('admin', 'service_connections'), URL::build('/panel/magaza/baglantilar'), 'top', null, $icon, $order + 0.4);
                }

                if ($user->hasPermission('staffcp.store.fields')) {
                    if (!$cache->isCached('store_fields_icon')) {
                        $icon = '<i class="nav-icon fas fa-id-card"></i>';
                        $cache->store('store_fields_icon', $icon);
                    } else
                        $icon = $cache->retrieve('store_fields_icon');

                    $navs[2]->addItemToDropdown('store_configuration', 'store_fields', $this->_store_language->get('admin', 'fields'), URL::build('/panel/magaza/alanlar'), 'top', null, $icon, $order + 0.5);
                }

                if ($user->hasPermission('staffcp.store.products')) {
                    if (!$cache->isCached('store_products_icon')) {
                        $icon = '<i class="nav-icon fas fa-box-open"></i>';
                        $cache->store('store_products_icon', $icon);
                    } else
                        $icon = $cache->retrieve('store_products_icon');

                    $navs[2]->add('store_products', $this->_store_language->get('general', 'products'), URL::build('/panel/magaza/urunler'), 'top', null, ($order + 0.6), $icon);
                }

                if ($user->hasPermission('staffcp.store.payments')) {
                    if (!$cache->isCached('store_payments_icon')) {
                        $icon = '<i class="nav-icon fas fa-donate"></i>';
                        $cache->store('store_payments_icon', $icon);
                    } else
                        $icon = $cache->retrieve('store_payments_icon');

                    $navs[2]->add('store_payments', $this->_store_language->get('admin', 'payments'), URL::build('/panel/magaza/odemeler'), 'top', null, ($order + 0.7), $icon);
                }
            }

            if ($user->hasPermission('staffcp.store.payments'))
                Core_Module::addUserAction($this->_store_language->get('general', 'store'), URL::build('/panel/kullanicilar/magaza/', 'user={id}'));

            if (defined('PANEL_PAGE') && PANEL_PAGE == 'dashboard') {
                // Dashboard graph
                $latest_payments = $this->_db->query('SELECT id, created FROM rw_store_payments WHERE created > ? AND status_id = 1 ORDER BY created ASC', [strtotime('-1 week')])->results();

                $cache->setCache('dashboard_graph');
                if ($cache->isCached('payments_data')) {
                    $output = $cache->retrieve('payments_data');

                } else {
                    $output = [];

                    $output['datasets']['payments']['label'] = 'store_language/admin/payments'; // for $store_language->get('admin', 'payments');
                    $output['datasets']['payments']['colour'] = '#4cf702';

                    foreach ($latest_payments as $payment) {
                        $date = date('d M Y', $payment->created);
                        $date = '_' . strtotime($date);

                        if (isset($output[$date]['payments'])) {
                            $output[$date]['payments'] += 1;
                        } else {
                            $output[$date]['payments'] = 1;
                        }
                    }

                    // Fill in missing dates, set payments to 0
                    $start = strtotime('-1 week');
                    $start = date('d M Y', $start);
                    $start = strtotime($start);
                    $end = strtotime(date('d M Y'));
                    while ($start <= $end) {
                        if (!isset($output['_' . $start]['payments'])) {
                            $output['_' . $start]['payments'] = 0;
                        }

                        $start = strtotime('+1 day', $start);
                    }

                    // Sort by date
                    ksort($output);

                    $cache->store('payments_data', $output, 120);
                }

                Core_Module::addDataToDashboardGraph($this->_language->get('admin', 'overview'), $output);
            }
        }
    }

    public function getDebugInfo(): array {
        // Services
        $services_list = [];
        foreach (Services::getInstance()->getAll() as $service) {
            $services_list[] = [
                'id' => Output::getClean($service->getId()),
                'name' => Output::getClean($service->getName()),
            ];
        }

        // Connections
        $connections_list = [];
        $connections_query = $this->_db->query('SELECT * FROM rw_store_connections')->results();
        foreach ($connections_query as $data) {
            $connections_list[] = [
                'id' => (int)$data->id,
                'name' => Output::getClean($data->name),
                'service_id' => $data->service_id,
                'last_fetch' => (int)$data->last_fetch,
            ];
        }

        // Fields
        $fields_list = [];
        $fields_query = $this->_db->query('SELECT * FROM rw_store_fields')->results();
        foreach ($fields_query as $data) {
            $fields_list[] = [
                'id' => $data->id,
                'identifier' => Output::getClean($data->identifier),
                'type' => $data->type,
                'required' => $data->required,
                'min' => $data->min,
                'max' => $data->max,
                'options' => Output::getClean($data->options),
            ];
        }

        // Products
        $products_list = [];
        $products_query = $this->_db->query('SELECT * FROM rw_store_products WHERE deleted = 0 ORDER BY `order` ASC')->results();
        foreach ($products_query as $data) {
            $product = new Product(null, null, $data);

            $connections = [];
            foreach ($product->getConnections() as $connection) {
                $connections[] = $connection->id;
            }

            $fields = [];
            foreach ($product->getFields() as $field) {
                $fields[] = $field->id;
            }

            $actions = [];
            foreach ($product->getActions() as $action) {
                $action_connections = [];
                if ($action->data()->own_connections) {
                    foreach ($action->getConnections() as $connection) {
                        $action_connections[] = $connection->id;
                    }
                }

                $actions[] = [
                    'id' => $action->data()->id,
                    'trigger' => $action->data()->type,
                    'command' => $action->data()->command,
                    'require_online' => $action->data()->require_online,
                    'own_connections' => $action->data()->own_connections,
                    'service_id' => $action->data()->service_id,
                    'connections' => $action_connections,
                ];
            }

            $products_list[] = [
                'id' => $product->data()->id,
                'name' => Output::getClean($product->data()->name),
                'price' => Output::getClean($product->data()->price),
                'hidden' => $product->data()->hidden,
                'disabled' => $product->data()->disabled,
                'connections' => $connections,
                'fields' => $fields,
                'actions' => $actions
            ];
        }

        return ['services' => $services_list, 'connections' => $connections_list, 'fields' => $fields_list, 'products' => $products_list];
    }

    private function initialise() {
        // Generate tables
        if (!$this->_db->showTables('store_agreements')) {
            try {
                $this->_db->createTable('store_agreements', ' `id` int(11) NOT NULL AUTO_INCREMENT, `user_id` int(11) NOT NULL, `player_id` int(11) NOT NULL, `agreement_id` varchar(32) NOT NULL, `status_id` int(11) NOT NULL DEFAULT \'0\', `email` varchar(128) NOT NULL, `payment_method` int(11) NOT NULL, `verified` tinyint(1) NOT NULL, `payer_id` varchar(64) NOT NULL, `last_payment_date` int(11) NOT NULL, `next_billing_date` int(11) NOT NULL, `created` int(11) NOT NULL, `updated` int(11) NOT NULL, PRIMARY KEY (`id`)');
            } catch (Exception $e) {
                // Error
            }
        }

        if (!$this->_db->showTables('store_categories')) {
            try {
                $this->_db->createTable('store_categories', ' `id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(128) NOT NULL, `description` mediumtext, `image` varchar(128) DEFAULT NULL, `only_subcategories` tinyint(1) NOT NULL DEFAULT \'0\', `parent_category` int(11) DEFAULT NULL, `hidden` tinyint(1) NOT NULL DEFAULT \'0\', `disabled` tinyint(1) NOT NULL DEFAULT \'0\', `order` int(11) NOT NULL, `deleted` int(11) NOT NULL DEFAULT \'0\', PRIMARY KEY (`id`)');
            } catch (Exception $e) {
                // Error
            }
        }

        if (!$this->_db->showTables('store_products')) {
            try {
                $this->_db->createTable('store_products', ' `id` int(11) NOT NULL AUTO_INCREMENT, `category_id` int(11) NOT NULL, `name` varchar(128) NOT NULL, `price` varchar(8) NOT NULL, `description` mediumtext, `image` varchar(128) DEFAULT NULL, `global_limit` varchar(128) DEFAULT NULL, `user_limit` varchar(128) DEFAULT NULL, `required_products` varchar(128) DEFAULT NULL, `required_groups` varchar(128) DEFAULT NULL, `required_integrations` varchar(128) DEFAULT NULL, `payment_type` tinyint(1) NOT NULL DEFAULT \'1\', `hidden` tinyint(1) NOT NULL DEFAULT \'0\', `disabled` tinyint(1) NOT NULL DEFAULT \'0\', `order` int(11) NOT NULL, `deleted` int(11) NOT NULL DEFAULT \'0\', PRIMARY KEY (`id`)');
            } catch (Exception $e) {
                // Error
            }
        }

        if (!$this->_db->showTables('store_products_connections')) {
            try {
                $this->_db->createTable('store_products_connections', ' `id` int(11) NOT NULL AUTO_INCREMENT, `product_id` int(11) NOT NULL, `action_id` int(11) DEFAULT NULL, `connection_id` int(11) NOT NULL, PRIMARY KEY (`id`)');
            } catch (Exception $e) {
                // Error
            }
        }

        if (!$this->_db->showTables('store_products_fields')) {
            try {
                $this->_db->createTable("store_products_fields", " `id` int(11) NOT NULL AUTO_INCREMENT, `product_id` int(11) NOT NULL, `field_id` int(11) NOT NULL, PRIMARY KEY (`id`)");
            } catch (Exception $e) {
                // Error
            }
        }

        if (!$this->_db->showTables('store_products_actions')) {
            try {
                $this->_db->createTable('store_products_actions', ' `id` int(11) NOT NULL AUTO_INCREMENT, `product_id` int(11) NOT NULL, `type` int(11) NOT NULL DEFAULT \'1\', `service_id` int(11) NOT NULL, `command` varchar(2048) NOT NULL, `require_online` tinyint(1) NOT NULL DEFAULT \'1\', `own_connections` tinyint(1) NOT NULL DEFAULT \'0\', `order` int(11) NOT NULL, PRIMARY KEY (`id`)');
            } catch (Exception $e) {
                // Error
            }
        }

        if (!$this->_db->showTables('store_pending_actions')) {
            try {
                $this->_db->createTable('store_pending_actions', ' `id` int(11) NOT NULL AUTO_INCREMENT, `order_id` int(11) NOT NULL, `action_id` int(11) NOT NULL, `product_id` int(11) NOT NULL, `customer_id` int(11) DEFAULT NULL, `connection_id` int(11) NOT NULL, `type` int(11) NOT NULL DEFAULT \'1\', `command` varchar(2048) NOT NULL, `require_online` tinyint(1) NOT NULL DEFAULT \'1\', `status` tinyint(1) NOT NULL DEFAULT \'0\', `order` int(11) NOT NULL, PRIMARY KEY (`id`)');
            } catch (Exception $e) {
                // Error
            }
        }

        if (!$this->_db->showTables('store_orders')) {
            try {
                $this->_db->createTable('store_orders', ' `id` int(11) NOT NULL AUTO_INCREMENT, `user_id` int(11) DEFAULT NULL, `from_customer_id` int(11) NOT NULL, `to_customer_id` int(11) NOT NULL, `created` int(11) NOT NULL, `ip` varchar(128) DEFAULT NULL, PRIMARY KEY (`id`)');
            } catch (Exception $e) {
                // Error
            }
        }

        if (!$this->_db->showTables('store_orders_products')) {
            try {
                $this->_db->createTable('store_orders_products', ' `id` int(11) NOT NULL AUTO_INCREMENT, `order_id` int(11) NOT NULL, `product_id` int(11) NOT NULL, `quantity` int(11) NOT NULL DEFAULT \'1\', PRIMARY KEY (`id`)');
            } catch (Exception $e) {
                // Error
            }
        }

        if (!$this->_db->showTables('store_orders_products_fields')) {
            try {
                $this->_db->createTable("store_orders_products_fields", " `id` int(11) NOT NULL AUTO_INCREMENT, `order_id` int(11) NOT NULL, `product_id` int(11) NOT NULL, `field_id` int(11) NOT NULL, `value` TEXT NOT NULL, PRIMARY KEY (`id`)");
            } catch (Exception $e) {
                // Error
            }
        }

        if (!$this->_db->showTables('store_payments')) {
            try {
                $this->_db->createTable('store_payments', ' `id` int(11) NOT NULL AUTO_INCREMENT, `order_id` int(11) NOT NULL, `gateway_id` int(11) NOT NULL, `payment_id` varchar(64) DEFAULT NULL, `agreement_id` varchar(64) DEFAULT NULL, `transaction` varchar(32) DEFAULT NULL, `amount` varchar(11) DEFAULT NULL, `currency` varchar(11) DEFAULT NULL, `fee` varchar(11) DEFAULT NULL, `status_id` int(11) NOT NULL DEFAULT \'0\', `created` int(11) NOT NULL, `last_updated` int(11) NOT NULL, PRIMARY KEY (`id`)');
            } catch (Exception $e) {
                // Error
            }
        }

        if (!$this->_db->showTables('store_customers')) {
            try {
                $this->_db->createTable('store_customers', ' `id` int(11) NOT NULL AUTO_INCREMENT, `user_id` int(11) DEFAULT NULL, `integration_id` int(11) NOT NULL, `username` varchar(64) DEFAULT NULL, `identifier` varchar(64) DEFAULT NULL, `cents` bigint(20) NOT NULL DEFAULT \'0\', PRIMARY KEY (`id`)');
            } catch (Exception $e) {
                // Error
            }
        }

        if (!$this->_db->showTables('store_connections')) {
            try {
                $this->_db->createTable('store_connections', ' `id` int(11) NOT NULL AUTO_INCREMENT, `service_id` int(11) NOT NULL, `name` varchar(64) NOT NULL, `data` text DEFAULT NULL, `last_fetch` int(11) NOT NULL DEFAULT \'0\', PRIMARY KEY (`id`)');
            } catch (Exception $e) {
                // Error
            }
        }

        if (!$this->_db->showTables('store_settings')) {
            try {
                $this->_db->createTable('store_settings', ' `id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(64) NOT NULL, `value` text, PRIMARY KEY (`id`)');
            } catch (Exception $e) {
                // Error
            }

            $this->_db->insert('store_settings', [
                'name' => 'checkout_complete_content',
                'value' => 'Ödemeniz için teşekkürler. Ödemenizin işleme koyulması 15 dakika kadar sürebilir.'
            ]);

            $this->_db->insert('store_settings', [
                'name' => 'currency',
                'value' => 'TL'
            ]);

            $this->_db->insert('store_settings', [
                'name' => 'currency_symbol',
                'value' => '₺'
            ]);

            $this->_db->insert('store_settings', [
                'name' => 'allow_guests',
                'value' => 0
            ]);

            $this->_db->insert('store_settings', [
                'name' => 'player_login',
                'value' => 0
            ]);
        }

        if (!$this->_db->showTables('store_gateways')) {
            try {
                $this->_db->createTable('store_gateways', ' `id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(64) NOT NULL, `displayname` varchar(64) NOT NULL, `enabled` tinyint(1) NOT NULL DEFAULT \'0\', PRIMARY KEY (`id`)');
            } catch (Exception $e) {
                // Error
            }

            $this->_db->insert('store_gateways', [
                'name' => 'PayPal',
                'displayname' => 'PayPal'
            ]);

            $this->_db->insert('store_gateways', [
                'name' => 'PayPalBusiness',
                'displayname' => 'PayPal'
            ]);

            $this->_db->insert('store_gateways', [
                'name' => 'Kredi',
                'displayname' => 'Kredi',
                'enabled' => 1
            ]);
        }

        if (!$this->_db->showTables('store_fields')) {
            try {
                $this->_db->createTable("store_fields", " `id` int(11) NOT NULL AUTO_INCREMENT, `identifier` varchar(32) NOT NULL, `description` varchar(255) NOT NULL, `type` int(11) NOT NULL, `required` tinyint(1) NOT NULL DEFAULT '0', `min` int(11) NOT NULL DEFAULT '0', `max` int(11) NOT NULL DEFAULT '0', `options` text NULL, `regex` varchar(64) DEFAULT NULL, `default_value` varchar(64) NOT NULL DEFAULT '', `deleted` int(11) NOT NULL DEFAULT '0', `order` int(11) NOT NULL DEFAULT '1', PRIMARY KEY (`id`)");

                $this->_db->insert('store_fields', [
                    'identifier' => 'quantity',
                    'description' => 'Quantity',
                    'type' => '4',
                    'required' => '1',
                    'min' => '1',
                    'max' => '2',
                    'default_value' => '1',
                    'order' => '0'
                ]);
            } catch (Exception $e) {
                // Error
            }
        }

        try {
            // Update main admin group permissions
            $group = $this->_db->get('groups', ['id', '=', 2])->results();
            $group = $group[0];

            $group_permissions = json_decode($group->permissions, TRUE);
            $group_permissions['staffcp.store'] = 1;
            $group_permissions['staffcp.store.settings'] = 1;
            $group_permissions['staffcp.store.products'] = 1;
            $group_permissions['staffcp.store.payments'] = 1;
            $group_permissions['staffcp.store.gateways'] = 1;
            $group_permissions['staffcp.store.connections'] = 1;
            $group_permissions['staffcp.store.fields'] = 1;

            $group_permissions = json_encode($group_permissions);
            $this->_db->update('groups', 2, ['permissions' => $group_permissions]);
        } catch (Exception $e) {
            // Error
        }
    }
}