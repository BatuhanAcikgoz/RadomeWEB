<?php

class DatabaseInitialiser {

    private DB $_db;
    private Cache $_cache;

    private function __construct() {
        $this->_db = DB::getInstance();
        $this->_cache = new Cache();
    }

    public static function runPreUser() {
        $instance = new self();
        $instance->initialiseGroups();
        $instance->initialiseLanguages();
        $instance->initialiseModules();
        $instance->initialiseIntegrations();
        $instance->initialiseReactions();
        $instance->initialiseSettings();
        $instance->initialiseTemplates();
        $instance->initialiseWidgets();
    }

    public static function runPostUser() {
        $instance = new self();
        $instance->initialiseForum();
    }

    private function initialiseGroups(): void {
        $this->_db->insert('groups', [
            'name' => 'Üye',
            'group_html' => '<span class="badge badge-success">Üye</span>',
            'permissions' => '{"usercp.messaging":1,"usercp.signature":1,"usercp.nickname":1,"usercp.private_profile":1,"usercp.profile_banner":1}',
            'default_group' => true,
            'order' => 3
        ]);

        $this->_db->insert('groups', [
            'name' => 'Yönetici',
            'group_html' => '<span class="badge badge-danger">Yönetici</span>',
            'group_username_color' => '#ff0000',
            'group_username_css' => '',
            'admin_cp' => true,
            'permissions' => '{"administrator":1,"admincp.core":1,"admincp.core.api":1,"admincp.core.seo":1,"admincp.core.general":1,"admincp.core.avatars":1,"admincp.core.fields":1,"admincp.core.debugging":1,"admincp.core.emails":1,"admincp.core.navigation":1,"admincp.core.announcements":1,"admincp.core.reactions":1,"admincp.core.registration":1,"admincp.core.social_media":1,"admincp.core.terms":1,"admincp.errors":1,"admincp.core.placeholders":1,"admincp.integrations":1,"admincp.integrations.edit":1,"admincp.discord":1,"admincp.minecraft":1,"admincp.minecraft.authme":1,"admincp.minecraft.verification":1,"admincp.minecraft.servers":1,"admincp.minecraft.query_errors":1,"admincp.minecraft.banners":1,"admincp.modules":1,"admincp.pages":1,"admincp.security":1,"admincp.security.acp_logins":1,"admincp.security.template":1,"admincp.styles":1,"admincp.styles.panel_templates":1,"admincp.styles.templates":1,"admincp.styles.templates.edit":1,"admincp.styles.images":1,"admincp.update":1,"admincp.users":1,"admincp.users.edit":1,"admincp.groups":1,"admincp.groups.self":1,"admincp.widgets":1,"modcp.ip_lookup":1,"modcp.punishments":1,"modcp.punishments.warn":1,"modcp.punishments.ban":1,"modcp.punishments.banip":1,"modcp.punishments.revoke":1,"modcp.reports":1,"modcp.profile_banner_reset":1,"usercp.messaging":1,"usercp.signature":1,"admincp.forums":1,"usercp.private_profile":1,"usercp.nickname":1,"usercp.profile_banner":1,"profile.private.bypass":1, "admincp.security.all":1,"admincp.core.hooks":1,"admincp.security.group_sync":1,"admincp.core.emails_mass_message":1,"modcp.punishments.reset_avatar":1,"usercp.gif_avatar":1}',
            'order' => 1,
            'staff' => true,
        ]);

        $this->_db->insert('groups', [
            'name' => 'Moderator',
            'group_html' => '<span class="badge badge-primary">Moderator</span>',
            'admin_cp' => true,
            'permissions' => '{"modcp.ip_lookup":1,"modcp.punishments":1,"modcp.punishments.warn":1,"modcp.punishments.ban":1,"modcp.punishments.banip":1,"modcp.punishments.revoke":1,"modcp.reports":1,"admincp.users":1,"modcp.profile_banner_reset":1,"usercp.messaging":1,"usercp.signature":1,"usercp.private_profile":1,"usercp.nickname":1,"usercp.profile_banner":1,"profile.private.bypass":1}',
            'order' => 2,
            'staff' => true,
        ]);

        $this->_db->insert('groups', [
            'name' => 'Onaylanmamış Üye',
            'group_html' => '<span class="badge badge-secondary">Onaylanmamış Üye</span>',
            'group_username_color' => '#6c757d',
            'permissions' => '{}',
            'order' => 4
        ]);
    }

    private function initialiseLanguages(): void {
        foreach (Language::LANGUAGES as $short_code => $meta) {
            $this->_db->insert('languages', [
                'name' => $meta['name'],
                'short_code' => $short_code,
                'is_default' => (Session::get('default_language') == $short_code) ? 1 : 0
            ]);
        }

        $this->_cache->setCache('languagecache');
        $this->_cache->store('language', Session::get('default_language'));
    }

    private function initialiseModules(): void {
 
        $this->_db->insert('modules', [
            'name' => 'Core',
            'enabled' => true,
        ]);

        $this->_db->insert('modules', [
            'name' => 'Forum',
            'enabled' => true,
        ]);

        $this->_db->insert('modules', [
            'name' => 'Discord Integration',
            'enabled' => true,
        ]);

        $this->_db->insert('modules', [
            'name' => 'Cookie Consent',
            'enabled' => true,
        ]);
        
        $this->_db->insert('modules', [
            'name' => 'Forms',
            'enabled' => true,
        ]);
        
        $this->_db->insert('modules', [
            'name' => 'Iframe',
            'enabled' => true,
        ]);

        $this->_db->insert('modules', [
            'name' => 'Infractions',
            'enabled' => true,
        ]);

        $this->_db->insert('modules', [
            'name' => 'Store',
            'enabled' => true,
        ]);

        $this->_db->insert('modules', [
            'name' => 'Vote',
            'enabled' => true,
        ]);

        $this->_db->insert('modules', [
            'name' => 'Wiki',
            'enabled' => true,
        ]);

        $this->_cache->setCache('modulescache');
        $this->_cache->store('enabled_modules', [
            [
                'name' => 'Core',
                'priority' => 1
            ],
            [
                'name' => 'Forum',
                'priority' => 4
            ],
            [
                'name' => 'Discord Integration',
                'priority' => 7
            ],
            [
                'name' => 'Cookie Consent',
                'priority' => 10
            ],
            [
                'name' => 'Forms',
                'priority' => 13
            ],
            [
                'name' => 'Iframe',
                'priority' => 16
            ],
            [
                'name' => 'Infractions',
                'priority' => 19
            ],
            [
                'name' => 'Store',
                'priority' => 22
            ],
            [
                'name' => 'Vote',
                'priority' => 25
            ],
            [
                'name' => 'Wiki',
                'priority' => 28
            ],
        ]);

        $this->_cache->store('module_core', true);
        $this->_cache->store('module_forum', true);
    }

    private function forms() {
        // Generate tables
        if (!$this->_db->showTables('forms')) {
            try {
                $this->_db->createTable("forms", " `id` int(11) NOT NULL AUTO_INCREMENT, `url` varchar(32) NOT NULL, `title` varchar(32) NOT NULL, `guest` tinyint(1) NOT NULL DEFAULT '0', `link_location` tinyint(1) NOT NULL DEFAULT '1', `icon` varchar(64) NULL, `can_view` tinyint(1) NOT NULL DEFAULT '0', `captcha` tinyint(1) NOT NULL DEFAULT '0', `content` mediumtext NULL DEFAULT NULL, `comment_status` int(11) NOT NULL DEFAULT '0', `source` varchar(32) NOT NULL DEFAULT 'forms', `forum_id` int(11) NOT NULL DEFAULT '0', PRIMARY KEY (`id`)");

                $this->_db->insert('forms', array(
                    'url' => '/destek',
                    'title' => 'Destek Talebi',
                    'guest' => 0,
                    'link_location' => 1,
                    'icon' => '<i class="fas fa-ticket-alt"></i>'                    
                ));
            } catch (Exception $e) {
                // Error
            }
        }

        if (!$this->_db->showTables('forms_permissions')) {
            try {
                $this->_db->createTable("forms_permissions", " `id` int(11) NOT NULL AUTO_INCREMENT, `form_id` int(11) NOT NULL, `group_id` int(11) NOT NULL, `post` tinyint(1) NOT NULL DEFAULT '1', `view_own` tinyint(1) NOT NULL DEFAULT '1', `view` tinyint(1) NOT NULL DEFAULT '0', `can_delete` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`)");

                $groups = $this->_db->query('SELECT id, staff FROM nl2_groups')->results();
                $this->_db->insert('forms_permissions', array(
                    'group_id' => 0,
                    'form_id' => 1,
                    'post' => 0,
                    'view_own' => 0,
                    'view' => 0,
                    'can_delete' => 0
                ));

                foreach ($groups as $group) {
                    $this->_db->insert('forms_permissions', array(
                        'group_id' => $group->id,
                        'form_id' => 1,
                        'post' => 1,
                        'view_own' => 1,
                        'view' => ($group->staff == 1 ? 1 : 0),
                        'can_delete' => ($group->staff == 1 ? 1 : 0)
                    ));
                }
            } catch (Exception $e) {
                // Error
            }
        }  

        if (!$this->_db->showTables('forms_comments')) {
            try {
                $this->_db->createTable("forms_comments", " `id` int(11) NOT NULL AUTO_INCREMENT, `form_id` int(11) NOT NULL, `user_id` int(11) NOT NULL, `created` int(11) NOT NULL, `anonymous` tinyint(1) NOT NULL DEFAULT '0', `content` mediumtext NOT NULL, PRIMARY KEY (`id`)");
            } catch (Exception $e) {
                // Error
            }
        }

        if (!$this->_db->showTables('forms_fields')) {
            try {
                $this->_db->createTable("forms_fields", " `id` int(11) NOT NULL AUTO_INCREMENT, `form_id` int(11) NOT NULL, `name` varchar(255) NOT NULL, `type` int(11) NOT NULL, `required` tinyint(1) NOT NULL DEFAULT '0', `min` int(11) NOT NULL DEFAULT '0', `max` int(11) NOT NULL DEFAULT '0', `placeholder` varchar(255) NULL DEFAULT NULL, `options` text NULL, `info` text NULL, `deleted` tinyint(1) NOT NULL DEFAULT '0', `order` int(11) NOT NULL DEFAULT '1', PRIMARY KEY (`id`)");
                
                $this->_db->insert('forms_fields', array(
                    'form_id' => 1,
                    'name' => 'Kategori',
                    'type' => 1,
                    'required' => 1,
                    'order' => 1,
                    'options' => 'Hile / Küfür
                    ,Yetkili Başvuru
                    ,Ödeme Öncesi
                    ,Ödeme Sonrası
                    ,Ceza İtiraf'
                ));
                $this->_db->insert('forms_fields', array(
                    'form_id' => 1,
                    'name' => 'Başlık',
                    'type' => 4,
                    'max' => '30',
                    'min' => '3',
                    'required' => 1,
                    'order' => 2
                ));
                $this->_db->insert('forms_fields', array(
                    'form_id' => 1,
                    'name' => 'Mesaj',
                    'type' => 4,
                    'max' => '30',
                    'min' => '3',
                    'required' => 1,
                    'order' => 3
                ));      
                $this->_db->insert('forms_fields', array(
                    'form_id' => 1,
                    'name' => 'Belge / Görsel',
                    'type' => 10,
                    'required' => 0,
                    'order' => 4
                ));                            
            } catch (Exception $e) {
                // Error
            }
        }

        if (!$this->_db->showTables('forms_replies')) {
            try {
                $this->_db->createTable("forms_replies", " `id` int(11) NOT NULL AUTO_INCREMENT, `form_id` int(11) NOT NULL, `user_id` int(11) NULL, `updated_by` int(11) NULL, `created` int(11) NOT NULL, `updated` int(11) NOT NULL, `content` mediumtext NULL DEFAULT NULL, `status_id` int(11) NOT NULL DEFAULT '1', PRIMARY KEY (`id`)");
            } catch (Exception $e) {
                // Error
            }
        }

        if (!$this->_db->showTables('forms_replies_fields')) {
            try {
                $this->_db->createTable("forms_replies_fields", " `id` int(11) NOT NULL AUTO_INCREMENT, `submission_id` int(11) NOT NULL, `field_id` int(11) NOT NULL, `value` TEXT NOT NULL, PRIMARY KEY (`id`)");
                
                $this->_db->createQuery('ALTER TABLE `nl2_forms_replies_fields` ADD INDEX `nl2_forms_replies_fields_idx_submission_id` (`submission_id`)');
            } catch (Exception $e) {
                // Error
            }
        }

        if (!$this->_db->showTables('forms_statuses')) {
            try {
                $this->_db->createTable("forms_statuses", " `id` int(11) NOT NULL AUTO_INCREMENT, `html` varchar(1024) NOT NULL, `open` tinyint(1) NOT NULL, `fids` varchar(128) NULL, `gids` varchar(128) NULL, `color` varchar(32) NULL DEFAULT NULL, `deleted` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`)");
                
                $this->_db->insert('forms_statuses', array(
                    'html' => '<span class="badge badge-success">Açık</span>',
                    'open' => 1,
                    'fids' => '1',
                    'gids' => '2,3'
                ));
                $this->_db->insert('forms_statuses', array(
                    'html' => '<span class="badge badge-danger">Kapandı</span>',
                    'open' => 0,
                    'fids' => '1',
                    'gids' => '2,3'
                ));
                $this->_db->insert('forms_statuses', array(
                    'html' => '<span class="badge badge-warning">İşleme Alındı</span>',
                    'open' => 1,
                    'fids' => '1',
                    'gids' => '2,3'
                ));
            } catch (Exception $e) {
                // Error
            }
        }

        try {
            // Update main admin group permissions
            $group = $this->_db->get('groups', array('id', '=', 2))->results();
            $group = $group[0];
            
            $group_permissions = json_decode($group->permissions, TRUE);
            $group_permissions['forms.manage'] = 1;
            $group_permissions['forms.view-submissions'] = 1;
            $group_permissions['forms.manage-submission'] = 1;
            $group_permissions['forms.anonymous'] = 1;
            
            $group_permissions = json_encode($group_permissions);
            $this->_db->update('groups', 2, array('permissions' => $group_permissions));
        } catch (Exception $e) {
            // Error
        }
    }
    
    public function iframe()
    {

        try {
            DB::getInstance()->createTable("iframe_pages", " `id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(255) NOT NULL, `url` varchar(255) NOT NULL, PRIMARY KEY (`id`)");
            DB::getInstance()->createTable("iframe_data", " `id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(255) NOT NULL, `src` varchar(5000) NOT NULL, `iframe_size` varchar(255) NOT NULL, `page_id` int(11) NOT NULL, `description` text NULL, `footer_description` text NULL, PRIMARY KEY (`id`)");
            $group = DB::getInstance()->get('groups', ['id', '=', 2])->results();
            $group = $group[0];

            $group_permissions = json_decode($group->permissions, TRUE);
            $group_permissions['admincp.iframe'] = 1;

            $group_permissions = json_encode($group_permissions);
            DB::getInstance()->update('groups', 2, ['permissions' => $group_permissions]);
        } catch (Exception $e) {
            // Error
        }
    }

    public function infractions(){
		// Install module
		try {
			// Update main admin group permissions
			$group = DB::getInstance()->get('groups', array('id', '=', 2));
			$group = $group->first();
			
			$group_permissions = json_decode($group->permissions, true);
			$group_permissions['admincp.infractions.settings'] = 1;
			$group_permissions['infractions.view'] = 1;
			
			$group_permissions = json_encode($group_permissions);
			DB::getInstance()->update('groups', 2, array('permissions' => $group_permissions));
		} catch(Exception $e){
			// Error
		}
	}    

    private function store() {
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
                'value' => 'Thanks for your payment, It can take up to 15 minutes for your payment to be processed'
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
                'name' => 'Store Credits',
                'displayname' => 'Store Credits',
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

    private function vote() {
        // Generate tables
		try {
            if (!DB::getInstance()->showTables('vote_sites')) {
                DB::getInstance()->createTable("vote_sites", " `id` int(11) NOT NULL AUTO_INCREMENT, `site` varchar(512) NOT NULL, `name` varchar(64) NOT NULL, PRIMARY KEY (`id`)");

                DB::getInstance()->insert('vote_sites', [
                    'site' => 'https://minecraft-mp.com',
                    'name' => 'Minecraft-MP (Örnek)'
                ]);
                DB::getInstance()->insert('vote_sites', [
                    'site' => 'https://topg.org/tr/',
                    'name' => 'TOPG (Örnek)'
                ]);
            }
		} catch (Exception $e) {
			// Error
		}

        try {
            if (!DB::getInstance()->showTables('vote_settings')) {
                DB::getInstance()->createTable("vote_settings", " `id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(20) NOT NULL, `value` varchar(2048) NOT NULL, PRIMARY KEY (`id`)");

                // Insert data
                DB::getInstance()->insert('vote_settings', [
                    'name' => 'vote_message',
                    'value' => 'Sevdiğiniz sunucuya bu kısımdan oy verip ödüllerin sahibi olabilirsiniz'
                ]);
            }
        } catch (Exception $e) {
            // Error
        }

		try {
			// Update main admin group permissions
			$group = DB::getInstance()->get('groups', ['id', '=', 2])->results();
            $group = $group[0];

			$group_permissions = json_decode($group->permissions, TRUE);
			$group_permissions['admincp.vote'] = 1;

			$group_permissions = json_encode($group_permissions);
			DB::getInstance()->update('groups', 2, ['permissions' => $group_permissions]);
		} catch (Exception $e) {
			// Error
		}
    }

    public function onEnable() {
		try {
			$engine = Config::get('mysql/engine');
			$charset = Config::get('mysql/charset');
		} catch(Exception $e){
			$engine = 'InnoDB';
			$charset = 'utf8mb4';
		}

		if(!$engine || is_array($engine))
			$engine = 'InnoDB';

		if(!$charset || is_array($charset))
			$charset = 'latin1';
			
		try {
            $group = $this->_db->get('groups', ['id', '=', 2])->results();
			$group = $group[0];
			
			$group_permissions = json_decode($group->permissions, TRUE);
			$group_permissions['admincp.wiki'] = 1;
			
			$group_permissions = json_encode($group_permissions);
			$this->_db->update('groups', 2, ['permissions' => $group_permissions]);

			//update
			try{
				$sql = "SHOW COLUMNS FROM ".Config::get('mysql/prefix')."wiki_pages WHERE Field = ?";
				$res = DB::getInstance()->query($sql,["views"]);
				if(!$res->first()){
					DB::getInstance()->createTable("wiki_pages", "views", "int(11) NOT NULL DEFAULT '0'");
				}
				$res = DB::getInstance()->query($sql,["likes"]);
				if(!$res->first()){
					DB::getInstance()->createTable("wiki_pages", "likes", "int(11) NOT NULL DEFAULT '0'");
				}
				$res = DB::getInstance()->query($sql,["enabled"]);
				if(!$res->first()){
					DB::getInstance()->createTable("wiki_pages", "enabled", "int(11) NOT NULL DEFAULT '1'");
				}
				$res = DB::getInstance()->query($sql,["likeable"]);
				if(!$res->first()){
					DB::getInstance()->createTable("wiki_pages", "likeable", "int(11) NOT NULL DEFAULT '1'");
				}
			} catch(Exception $e){}
		} catch(Exception $e){}

		try {
			if(!$this->_db->showTables("wiki_likes")){
				DB::getInstance()->createTable("wiki_likes", "`id` int(11) NOT NULL AUTO_INCREMENT, `username` varchar(20) NOT NULL, `pageid` varchar(48) NOT NULL, PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			}
		} catch(Exception $e){}
	}   

    private function initialiseIntegrations(): void {
        $this->_db->insert('integrations', [
            'name' => 'Minecraft',
            'enabled' => true,
            'can_unlink' => false,
            'required' => false,
        ]);

        // TODO: should this be in the DiscordIntegration module...?
        $this->_db->insert('integrations', [
            'name' => 'Discord',
            'enabled' => true,
            'can_unlink' => true,
            'required' => false
        ]);
    }

    private function initialiseReactions(): void {
        $this->_db->insert('reactions', [
            'name' => 'Like',
            'html' => '<i class="fas fa-thumbs-up text-success"></i>',
            'enabled' => true,
            'type' => 2
        ]);

        $this->_db->insert('reactions', [
            'name' => 'Dislike',
            'html' => '<i class="fas fa-thumbs-down text-danger"></i>',
            'enabled' => true,
            'type' => 0
        ]);

        $this->_db->insert('reactions', [
            'name' => 'Meh',
            'html' => '<i class="fas fa-meh text-warning"></i>',
            'enabled' => true,
            'type' => 1
        ]);
    }

    private function initialiseSettings(): void {
        Util::setSetting('registration_enabled', '1');
        Util::setSetting('displaynames', '0');
        Util::setSetting('uuid_linking', '1');
        Util::setSetting('recaptcha', '0');
        Util::setSetting('recaptcha_type', 'Recaptcha3');
        Util::setSetting('recaptcha_login', '0');
        Util::setSetting('email_verification', '1');
        Util::setSetting('nameless_version', '2.0.2');
        Util::setSetting('version_checked', date('U'));
        Util::setSetting('phpmailer', '0');
        Util::setSetting('phpmailer_type', 'smtp');
        Util::setSetting('verify_accounts', '0');
        Util::setSetting('user_avatars', '0');
        Util::setSetting('forum_layout', '1');
        Util::setSetting('avatar_site', 'cravatar');
        Util::setSetting('mc_integration', '1');
        Util::setSetting('discord_integration', '0');
        Util::setSetting('avatar_type', 'helmavatar');
        Util::setSetting('home_type', 'news');
        Util::setSetting('forum_reactions', '1');
        Util::setSetting('error_reporting', '0');
        Util::setSetting('page_loading', '0');
        Util::setSetting('unique_id', substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 62));
        Util::setSetting('use_api', 0);
        Util::setSetting('mc_api_key', SecureRandom::alphanumeric());
        Util::setSetting('external_query', '0');
        Util::setSetting('followers', '0');
        Util::setSetting('language', '1');
        Util::setSetting('timezone', 'Europe/Istanbul');
        Util::setSetting('maintenance', '0');
        Util::setSetting('maintenance_message', 'Sitemiz şu anda bakım modundadır. Daha sonra tekrar deneyiniz.');
        Util::setSetting('authme', 0);
        Util::setSetting('default_avatar_type', 'minecraft');
        Util::setSetting('private_profile', '1');
        Util::setSetting('validate_user_action', '{"action":"promote","group":1}');
        Util::setSetting('login_method', 'email');
        Util::setSetting('username_sync', '1');
        Util::setSetting('status_page', '1');
        Util::setSetting('placeholders', '1');

        $this->_db->insert('privacy_terms', [
            'name' => 'terms',
            'value' => '<p>You agree to be bound by our website rules and any laws which may apply to this website and your participation.</p><p>The website administration have the right to terminate your account at any time, delete any content you may have posted, and your IP address and any data you input to the website is recorded to assist the site staff with their moderation duties.</p><p>The site administration have the right to change these terms and conditions, and any site rules, at any point without warning. Whilst you may be informed of any changes, it is your responsibility to check these terms and the rules at any point.</p>'
        ]);

        $this->_db->insert('privacy_terms', [
            'name' => 'cookies',
            'value' => '<span style="font-size:18px"><strong>What are cookies?</strong></span><br />Cookies are small files which are stored on your device by a website, unique to your web browser. The web browser will send these files to the website each time it communicates with the website.<br />Cookies are used by this website for a variety of reasons which are outlined below.<br /><br /><strong>Necessary cookies</strong><br />Necessary cookies are required for this website to function. These are used by the website to maintain your session, allowing for you to submit any forms, log into the website amongst other essential behaviour. It is not possible to disable these within the website, however you can disable cookies altogether via your browser.<br /><br /><strong>Functional cookies</strong><br />Functional cookies allow for the website to work as you choose. For example, enabling the &quot;Remember Me&quot; option as you log in will create a functional cookie to automatically log you in on future visits.<br /><br /><strong>Analytical cookies</strong><br />Analytical cookies allow both this website, and any third party services used by this website, to collect non-personally identifiable data about the user. This allows us (the website staff) to continue to improve the user experience and understand how the website is used.<br /><br />Further information about cookies can be found online, including the <a rel="nofollow noopener" target="_blank" href="https://ico.org.uk/your-data-matters/online/cookies/">ICO&#39;s website</a> which contains useful links to further documentation about configuring your browser.<br /><br /><span style="font-size:18px"><strong>Configuring cookie use</strong></span><br />By default, only necessary cookies are used by this website. However, some website functionality may be unavailable until the use of cookies has been opted into.<br />You can opt into, or continue to disallow, the use of cookies using the cookie notice popup on this website. If you would like to update your preference, the cookie notice popup can be re-enabled by clicking the button below.'
        ]);

        $this->_db->insert('privacy_terms', [
            'name' => 'privacy',
            'value' => 'The following privacy policy outlines how your data is used on our website.<br /><br /><strong>Data</strong><br />Basic non-identifiable information about your user on the website is collected; the majority of which is provided during registration, such as email addresses and usernames.<br />In addition to this, IP addresses for registered users are stored within the system to aid with moderation duties. This includes spam prevention, and detecting alternative accounts.<br /><br />Accounts can be deleted by a site administrator upon request, which will remove all data relating to your user from our system.<br /><br /><strong>Cookies</strong><br />Cookies are used to store small pieces of non-identifiable information with your consent. In order to consent to the use of cookies, you must either close the cookie notice (as explained within the notice) or register on our website.<br />Data stored by cookies include any recently viewed topic IDs, along with a unique, unidentifiable hash upon logging in and selecting &quot;Remember Me&quot; to automatically log you in next time you visit.'
        ]);

        $nameless_terms = 'This website uses "Nameless" website software. The ' .
                        '"Nameless" software creators will not be held responsible for any content ' .
                        'that may be experienced whilst browsing this site, nor are they responsible ' .
                        'for any loss of data which may come about, for example a hacking attempt. ' .
                        'The website is run independently from the software creators, and any content' .
                        ' is the responsibility of the website administration.';
        Util::setSetting('t_and_c', 'By registering on our website, you agree to the following:<p>' . $nameless_terms . '</p>');
    }

    private function initialiseTemplates(): void {
        $this->_db->insert('templates', [
            'name' => 'RadomeWEB',
            'enabled' => true,
            'is_default' => true,
        ]);

        $this->_cache->setCache('templatecache');
        $this->_cache->store('default', 'RadomeWEB');

        $this->_db->insert('panel_templates', [
            'name' => 'Default',
            'enabled' => true,
            'is_default' => true,
        ]);
        $this->_cache->store('panel_default', 'Default');

        $config_path = Config::get('core.path');
        if (!empty($config_path)) {
            $config_path = '/' . trim($config_path, '/');
        }

        $this->_cache->setCache('backgroundcache');
        $this->_cache->store('banner_image', $config_path . '/uploads/template_banners/homepage_bg_trimmed.jpg');
    }

    private function initialiseWidgets(): void {
        $this->_db->insert('widgets', [
            'name' => 'Server Status',
            'enabled' => true,
            'pages' => '["index","forum","vote","form-1"]'
        ]);

        $this->_db->insert('widgets', [
            'name' => 'Statistics',
            'enabled' => true,
            'pages' => '["index","forum","vote","form-1"]'
        ]);

        $this->_db->insert('widgets', [
            'name' => 'Latest Purchases',
            'enabled' => true,
            'pages' => '["index","forum","vote","form-1"]'
        ]);

        $this->_db->insert('widgets', [
            'name' => 'Discord',
            'enabled' => true,
            'pages' => '["index","forum","vote","form-1"]'
        ]);


        $this->_cache->setCache('Core-widgets');
        $this->_cache->store('enabled', [
            'Server Status' => 1,
            'Statistics' => 1,
            'Latest Purchases' => 1,
            'Discord' => 1
        ]);
    }

    private function initialiseForum() {
        $this->_db->insert('forums', [
            'forum_title' => 'Category',
            'forum_description' => 'The first forum category!',
            'forum_order' => 1,
            'forum_type' => 'category'
        ]);

        $this->_db->insert('forums', [
            'forum_title' => 'Forum',
            'forum_description' => 'The first discussion forum!',
            'forum_order' => 2,
            'parent' => 1,
            'forum_type' => 'forum',
            'news' => 1
        ]);

        $this->_db->insert('topics', [
            'forum_id' => 2,
            'topic_title' => 'Welcome to NamelessMC!',
            'topic_creator' => 1,
            'topic_last_user' => 1,
            'topic_date' => date('U'),
            'topic_reply_date' => date('U'),
            'label' => null
        ]);

        $this->_db->insert('posts', [
            'forum_id' => 2,
            'topic_id' => 1,
            'post_creator' => 1,
            'post_content' => Output::getClean(
                '&lt;p&gt;Welcome!&lt;/p&gt;
                    &lt;p&gt;To get started with NamelessMC, visit your StaffCP using the blue gear icon in the top right of your screen.&lt;/p&gt;
                    &lt;p&gt;If you need support, visit our Discord server: &lt;a href=&quot;https://discord.gg/nameless&quot; target=&quot;_blank&quot; rel=&quot;noopener&quot;&gt;https://discord.gg/nameless&lt;/a&gt;&lt;/p&gt;
                    &lt;p&gt;Thank you and enjoy,&lt;/p&gt;
                    &lt;p&gt;The NamelessMC Development team.&lt;/p&gt;'
            ),
            'post_date' => date('Y-m-d H:i:s'),
            'created' => date('U'),
            'last_edited' => date('U'),
        ]);

        // Must be updated afterwards due of foreign key
        $this->_db->update('forums', 2, [
            'last_post_date' => date('U'),
            'last_user_posted' => 1,
            'last_topic_posted' => 1,
        ]);

        // Permissions
        for ($i = 0; $i < 4; $i++) {
            for ($n = 1; $n < 3; $n++) {
                $this->_db->insert('forums_permissions', [
                    'group_id' => $i,
                    'forum_id' => $n,
                    'view' => true,
                    'create_topic' => ($i == 0 ? 0 : 1),
                    'edit_topic' => ($i == 0 ? 0 : 1),
                    'create_post' => ($i == 0 ? 0 : 1),
                    'view_other_topics' => true,
                    'moderate' => (($i == 2 || $i == 3) ? 1 : 0)
                ]);
            }
        }

        // Forum Labels
        $this->_db->insert('forums_labels', [
            'name' => 'Default',
            'html' => '<span class="badge badge-default">{x}</span>'
        ]);

        $this->_db->insert('forums_labels', [
            'name' => 'Primary',
            'html' => '<span class="badge badge-primary">{x}</span>'
        ]);

        $this->_db->insert('forums_labels', [
            'name' => 'Success',
            'html' => '<span class="badge badge-success">{x}</span>'
        ]);

        $this->_db->insert('forums_labels', [
            'name' => 'Info',
            'html' => '<span class="badge badge-info">{x}</span>'
        ]);

        $this->_db->insert('forums_labels', [
            'name' => 'Warning',
            'html' => '<span class="badge badge-warning">{x}</span>'
        ]);

        $this->_db->insert('forums_labels', [
            'name' => 'Danger',
            'html' => '<span class="badge badge-danger">{x}</span>'
        ]);
    }
}
