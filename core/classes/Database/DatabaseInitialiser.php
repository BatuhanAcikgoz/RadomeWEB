<?php

class DatabaseInitialiser {

    private DB $_db;
    private Cache $_cache;

    private function __construct() {
        $this->_db = DB::getInstance();
        $this->_cache = new Cache(['name' => 'radome', 'extension' => '.cache', 'path' => ROOT_PATH . '/cache/']);
    }

    public static function runPreUser() {
        $instance = new self();
        $instance->initialiseGroups();
        $instance->initialiseLanguages();
        $instance->initialiseModules();
        $instance->initialiseIntegrations();
        $instance->initialiseSettings();
        $instance->initialiseTasks();
        $instance->initialiseTemplates();
        $instance->initialiseWidgets();
        
    }

    public static function runPostUser() {
        $instance = new self();
        $instance->initialiseForum();
        $instance->initialisePermissions();
        $instance->initialiseEklenti();
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
            'permissions' => '{"administrator":1,"admincp.core":1,"admincp.core.api":1,"admincp.core.seo":1,"admincp.core.general":1,"admincp.core.avatars":1,"admincp.core.fields":1,"admincp.core.debugging":1,"admincp.core.emails":1,"admincp.core.queue":1,"admincp.core.navigation":1,"admincp.core.announcements":1,"admincp.core.reactions":1,"admincp.core.registration":1,"admincp.core.social_media":1,"admincp.core.terms":1,"admincp.errors":1,"admincp.core.placeholders":1,"admincp.members":1,"admincp.integrations":1,"admincp.integrations.edit":1,"admincp.discord":1,"admincp.minecraft":1,"admincp.minecraft.authme":1,"admincp.minecraft.servers":1,"admincp.minecraft.query_errors":1,"admincp.minecraft.banners":1,"admincp.modules":1,"admincp.pages":1,"admincp.security":1,"admincp.security.acp_logins":1,"admincp.security.template":1,"admincp.styles":1,"admincp.styles.panel_templates":1,"admincp.styles.templates":1,"admincp.styles.templates.edit":1,"admincp.styles.images":1,"admincp.update":1,"admincp.users":1,"admincp.users.edit":1,"admincp.groups":1,"admincp.groups.self":1,"admincp.widgets":1,"modcp.ip_lookup":1,"modcp.punishments":1,"modcp.punishments.warn":1,"modcp.punishments.ban":1,"modcp.punishments.banip":1,"modcp.punishments.revoke":1,"modcp.reports":1,"modcp.profile_banner_reset":1,"usercp.messaging":1,"usercp.signature":1,"admincp.forums":1,"usercp.private_profile":1,"usercp.nickname":1,"usercp.profile_banner":1,"profile.private.bypass":1, "admincp.security.all":1,"admincp.core.hooks":1,"admincp.security.group_sync":1,"admincp.core.emails_mass_message":1,"modcp.punishments.reset_avatar":1,"usercp.gif_avatar":1}',
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

        Settings::set('member_list_viewable_groups', json_encode([1, 2, 3, 4]), 'Members');
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

    private function initialiseEklenti(): void{

        $this->_db->query('ALTER TABLE `rw_forms_replies_fields` ADD INDEX `rw_forms_replies_fields_idx_submission_id` (`submission_id`)');

        $groups = $this->_db->query('SELECT id, staff FROM rw_groups')->results();
        $forms = $this->_db->query('SELECT * FROM rw_forms')->results();
        foreach ($forms as $form) { 
        $this->_db->insert('forms_permissions', array(
            'group_id' => 0,
            'form_id' => $form->id,
            'post' => $form->guest,
            'view_own' => 0,
            'view' => 0,
            'can_delete' => 0
        ));

        foreach ($groups as $group) {
            $this->_db->insert('forms_permissions', array(
                'group_id' => $group->id,
                'form_id' => $form->id,
                'post' => 1,
                'view_own' => $form->can_view,
                'view' => ($group->staff == 1 ? 1 : 0),
                'can_delete' => ($group->staff == 1 ? 1 : 0)
            ));
        }
        }
        $this->_db->insert('forms', array(
            'url' => '/destek',
            'title' => 'Destek',
            'guest' => 1,
            'link_location' => 1,
            'icon' => '<i class="fas fa-ticket-alt"></i>'                    
        ));

        $groups = $this->_db->query('SELECT id, staff FROM rw_groups')->results();
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
        $this->_db->insert('forms_fields', array(
            'form_id' => 1,
            'name' => 'Kategori',
            'type' => 2,
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
            'type' => 1,
            'max' => '30',
            'min' => '3',
            'required' => 1,
            'order' => 2
        ));
        $this->_db->insert('forms_fields', array(
            'form_id' => 1,
            'name' => 'Mesaj',
            'type' => 3,
            'max' => '400',
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
        $this->_db->insert('store_fields', [
            'identifier' => 'price',
            'description' => 'Pay what you want',
            'type' => '4',
            'required' => '1',
            'min' => '1',
            'max' => '9',
            'default_value' => '',
            'order' => '0'
        ]);
        $gateway_exists = $this->_db->get('store_gateways', ['name', '=', 'Kredi']);
        if (!$gateway_exists->count()) {
            $this->_db->insert('store_gateways', [
                'name' => 'Kredi',
                'displayname' => 'Kredi',
                'enabled' => 1
            ]);
        }

        $this->_db->insert('store_gateways', [
            'name' => 'PayPal',
            'displayname' => 'PayPal'
        ]);

        $this->_db->insert('store_gateways', [
            'name' => 'PayPalBusiness',
            'displayname' => 'PayPal'
        ]);
        DB::getInstance()->insert('vote_sites', [
            'site' => 'https://minecraft-mp.com',
            'name' => 'Minecraft-MP (Örnek)'
        ]);
        DB::getInstance()->insert('vote_sites', [
            'site' => 'https://topg.org/tr/',
            'name' => 'TOPG (Örnek)'
        ]);
        DB::getInstance()->insert('mc_servers', [
            'ip' => 'oyna.zorapvp.xyz',
            'query_ip' => 'oyna.zorapvp.xyz',
            'name' => 'ZoraPvP',
            'is_default' => '1',
            'display' => '1',
            'pre' => '0',
            'player_list' => '1',
            'parent_server' => '0',
            'bungee' => '0',
            'bedrock' => '0',
            'port' => '25565',
            'query_port' => '25565',
            'banner_background' => 'background.png',
            'show_ip' => '1',
            'order' => '1'
        ]);
        DB::getInstance()->insert('settings', [
            'name' => 'discord',
            'value' => '821855877514133504',
            'module' => NULL
        ]);
        $this->_db->insert('wiki_settings', [
            'name' => 'home_page',
            'value' => '<div><span style="font-size:20px"><strong>RadomeWEB Wiki Sayfasına Hoşgeldin!</strong></span><br />Bu kısımda istediğin kadar wiki sayfası oluşturabilirsin,<br />Düğme metnini, başlığı, simgeyi, urlyi ve daha bir çok şeyi düzenleyebilirsin.<br /><br /><strong>Admin panelinden istediğin değişikliği yapabilirsin.</strong><br /><br /><strong>Not:</strong>&nbsp;Ayrıca bu kısımı&nbsp;<strong><u><a href="/panel/wiki">Admin Paneli -&gt; Wiki</a></u></strong>.<br /><br />Bağlantılar:</div><ul><li>Desteği bu  <strong><a rel="nofollow noopener" target="_blank" href="https://discord.verira.com">Discord</a></strong> sunucusundan alabilirsiniz.</li></ul>'
        ]);

        $this->_db->insert('wiki_pages', [
            'title' => 'Hoş geldiniz',
            'parent' => 'null',
            'nameid' => 'welcome',
            'button' => 'Hoşgeldiniz',
            'icon' => 'fas fa-users',
            'context' => '&lt;span style=&quot;font-size:36px;&quot;&gt;&lt;strong&gt;Hoşgeldiniz&lt;/strong&gt;&lt;/span&gt;&lt;br /&gt;Bu sayfa, sunucumuzdaki yeni başlayanlar için bağlantılar ve faydalı bilgiler içerir.',
            'views' => '0',
            'likes' => '0',
            'likeable' => '1',
            'enabled' => '1'
        ]);
        $this->_db->insert('wiki_pages', [
            'title' => 'Kurallar',
            'parent' => 'welcome',
            'nameid' => 'rules',
            'button' => 'Kurallar',
            'icon' => 'fas fa-book',
            'context' => '&lt;span style=&quot;font-size:36px;&quot;&gt;&lt;strong&gt;Kurallar&lt;/strong&gt;&lt;/span&gt;&lt;ul&gt;&lt;li&gt;Kurallarımızı bu linkten görüntüleyebilirsiniz &lt;a rel=&quot;nofollow noopener&quot; target=&quot;_blank&quot; href=&quot;https://verira.com/kullanim-sozlesmesi/',
            'views' => '0',
            'likes' => '0',
            'likeable' => '1',
            'enabled' => '1'
        ]);
        $this->_db->insert('wiki_pages', [
            'title' => 'Kılavuzlar & İpuçları',
            'parent' => 'welcome',
            'nameid' => 'guide',
            'button' => 'Kılavuz',
            'icon' => 'fas fa-question',
            'context' => '&lt;span style=&quot;font-size:36px;&quot;&gt;&lt;strong&gt;Kılavuzlar & İpuçları&lt;/strong&gt;&lt;/span&gt;&lt;br /&gt;Bu sayfa sunucumuzda yeni başlayanlar için kılavuzlar ve ipuçları içerir.',
            'views' => '0',
            'likes' => '0',
            'likeable' => '1',
            'enabled' => '1'
        ]);
        $this->_db->insert('wiki_pages', [
            'title' => 'Detaylı Bilgilendirme',
            'parent' => 'welcome',
            'nameid' => 'protips',
            'button' => 'Detaylı Bilgilendirme',
            'icon' => 'fas fa-exclamation',
            'context' => '&lt;span style=&quot;font-size:36px;&quot;&gt;&lt;strong&gt;Detaylı Bilgilendirme&lt;/strong&gt;&lt;/span&gt;&lt;br /&gt;Bu sayfa, sunucumuzdaki ileri düzey kullanıcılar ve oyuncular için bağlantılar ve faydalı bilgiler içerir.',
            'views' => '0',
            'likes' => '0',
            'likeable' => '1',
            'enabled' => '1'
        ]);
        $this->_db->insert('wiki_pages', [
            'title' => 'Komutlar',
            'parent' => 'null',
            'nameid' => 'commands',
            'button' => 'Komutlar',
            'icon' => 'fas fa-terminal',
            'context' => '&lt;span style=&quot;font-size:36px;&quot;&gt;&lt;strong&gt;Komutlar&lt;/strong&gt;&lt;/span&gt;&lt;br /&gt;Bu kısımdan oyun içerisindeki bazı komutlara göz atabilirsin.&lt;br /&gt;&amp;nbsp;&lt;ul&gt;&lt;li&gt;&lt;strong&gt;/msg&lt;/strong&gt; [player] [message]: Hedef kişiye özel mesaj gönderir.&lt;/li&gt;&lt;li&gt;&lt;strong&gt;/fly&lt;/strong&gt;: Premiumlar için uçmayı sağlar.&lt;/li&gt;&lt;li&gt;&lt;strong&gt;/customkit&lt;/strong&gt;: Sunucu içinde custom kit oluşturmanıza olanak sağlar.&lt;/li&gt;&lt;li&gt;&lt;strong&gt;/duel&lt;/strong&gt; [player] : Hedef oyuncuya duello isteği gönderir. &lt;/li&gt;&lt;li&gt;&lt;strong&gt;/ffa&lt;/strong&gt;: FFA oyun moduna gir.&lt;/li&gt;&lt;/ul&gt;Check &lt;strong&gt;/yardım&lt;/strong&gt; oyunda yardım almak için yazın.',
            'views' => '0',
            'likes' => '0',
            'likeable' => '1',
            'enabled' => '1'
        ]);
        $this->_db->insert('wiki_pages', [
            'title' => 'Yetkiler',
            'parent' => 'null',
            'nameid' => 'permissions',
            'button' => 'Yetkiler',
            'icon' => 'fas fa-user-lock',
            'context' => '&lt;span style=&quot;font-size:36px;&quot;&gt;&lt;strong&gt;Yetkiler&lt;/strong&gt;&lt;/span&gt;&lt;br /&gt;Bu sayfa yetkilerin listesini içeriyor.',
            'views' => '0',
            'likes' => '0',
            'likeable' => '1',
            'enabled' => '1'
        ]);
        $this->_db->insert('wiki_pages', [
            'title' => 'Rütbeler',
            'parent' => 'null',
            'nameid' => 'ranks',
            'button' => 'Rütbeler',
            'icon' => 'fas fa-star',
            'context' => '&lt;span style=&quot;font-size:36px;&quot;&gt;&lt;strong&gt;RANKS&lt;/strong&gt;&lt;/span&gt;&lt;br /&gt;Bu sayfa sunucumuzda yer alan rütbeleri gösterir.',
            'views' => '0',
            'likes' => '0',
            'likeable' => '1',
            'enabled' => '1'
        ]);
        $this->_db->insert('wiki_pages', [
            'title' => 'Ayrıcalıklar',
            'parent' => 'ranks',
            'nameid' => 'perks',
            'button' => 'Ayrıcalıklar',
            'icon' => 'fas fa-grin-stars',
            'context' => '&lt;span style=&quot;font-size:36px;&quot;&gt;&lt;strong&gt;Ayrıcalıklar&lt;/strong&gt;&lt;/span&gt;&lt;br /&gt;Bu sayfa sunucumuzda yer alan ayrıcalıkları gösterir.',
            'views' => '0',
            'likes' => '0',
            'likeable' => '1',
            'enabled' => '1'
        ]);
        $this->_db->insert('wiki_pages', [
            'title' => 'Discord',
            'parent' => 'null',
            'nameid' => 'discord',
            'button' => 'Discord',
            'icon' => 'fab fa-discord',
            'context' => '&lt;span style=&quot;font-size:36px;&quot;&gt;&lt;strong&gt;Discord Sunucumuz&lt;/strong&gt;&lt;/span&gt;&lt;ul&gt;&lt;li&gt; &lt;a rel=&quot;nofollow noopener&quot; target=&quot;_blank&quot; href=&quot;https://discord.gg/v3ERpXEBc5&quot;&gt;&lt;strong&gt;Discord sunucumuza &lt;/strong&gt;&lt;/a&gt;katılarak radomeweb hakkında detaylı destek alabilirsiniz.&lt;/li&gt;&lt;/ul&gt;',
            'views' => '0',
            'likes' => '0',
            'likeable' => '1',
            'enabled' => '1'
        ]);
    }

    private function initialisePermissions(): void{
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
            $group_permissions['admincp.vote'] = 1;
            $group_permissions['admincp.wiki'] = 1;
            $group_permissions['admincp.infractions.settings'] = 1;
			$group_permissions['infractions.view'] = 1;
            $group_permissions['admincp.iframe'] = 1;
            $group_permissions['forms.anonymous'] = 1;
            $group_permissions['forms.manage'] = 1;
            $group_permissions['forms.view-submissions'] = 1;
            $group_permissions['forms.manage-submission'] = 1;
            $group_permissions['forms.anonymous'] = 1;

            $group_permissions = json_encode($group_permissions);
			DB::getInstance()->update('groups', 2, array('permissions' => $group_permissions));
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
            'name' => 'Haberler',
            'enabled' => true,
        ]);

        $this->_db->insert('modules', [
            'name' => 'Discord Entegrasyonu',
            'enabled' => true,
        ]);

        $this->_db->insert('modules', [
            'name' => 'Cookie Consent',
            'enabled' => true,
        ]);
        
        $this->_db->insert('modules', [
            'name' => 'Formlar',
            'enabled' => true,
        ]);
        
        $this->_db->insert('modules', [
            'name' => 'Iframe',
            'enabled' => true,
        ]);

        $this->_db->insert('modules', [
            'name' => 'Cezalar',
            'enabled' => true,
        ]);

        $this->_db->insert('modules', [
            'name' => 'Magaza',
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

        $this->_db->insert('modules', [
            'name' => 'Members',
            'enabled' => true,
        ]);

        $this->_cache->setCache('modulescache');
        $this->_cache->store('enabled_modules', [
            [
                'name' => 'Core',
                'priority' => 1
            ],
            [
                'name' => 'Haberler',
                'priority' => 3
            ],
            [
                'name' => 'Forum',
                'priority' => 4
            ],
            [
                'name' => 'Discord Entegrasyonu',
                'priority' => 7
            ],
            [
                'name' => 'Cookie Consent',
                'priority' => 10
            ],
            [
                'name' => 'Formlar',
                'priority' => 13
            ],
            [
                'name' => 'Iframe',
                'priority' => 16
            ],
            [
                'name' => 'Cezalar',
                'priority' => 19
            ],
            [
                'name' => 'Magaza',
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
            [
                'name' => 'Members',
                'priority' => 31
            ],
        ]);

        $this->_cache->store('module_core', true);
        $this->_cache->store('module_forum', true);
    }

    private function initialiseIntegrations(): void {
        $this->_db->insert('integrations', [
            'name' => 'Minecraft',
            'enabled' => true,
            'can_unlink' => false,
            'required' => true,
        ]);

        // TODO: should this be in the DiscordIntegration module...?
        $this->_db->insert('integrations', [
            'name' => 'Discord',
            'enabled' => true,
            'can_unlink' => true,
            'required' => false
        ]);
    }


    private function initialiseSettings(): void {
        Settings::set('registration_enabled', '1');
        Settings::set('displaynames', '0');
        Settings::set('recaptcha', '0');
        Settings::set('recaptcha_type', 'Recaptcha3');
        Settings::set('recaptcha_login', '0');
        Settings::set('email_verification', '1');
        Settings::set('radome_version', '2.0.2');
        Settings::set('version_checked', date('U'));
        Settings::set('phpmailer', '0');
        Settings::set('phpmailer_type', 'smtp');
        Settings::set('verify_accounts', '1');
        Settings::set('user_avatars', '0');
        Settings::set('forum_layout', '1');
        Settings::set('avatar_site', 'cravatar');
        Settings::set(Settings::MINECRAFT_INTEGRATION, '1');
        Settings::set('discord_integration', '0');
        Settings::set('avatar_type', 'helmavatar');
        Settings::set('home_type', 'news');
        Settings::set('forum_reactions', '1');
        Settings::set('error_reporting', '0');
        Settings::set('page_loading', '0');
        Settings::set('unique_id', substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 62));
        Settings::set('use_api', 0);
        Settings::set('mc_api_key', SecureRandom::alphanumeric());
        Settings::set('query_type', 'internal');
        Settings::set('player_list_limit', '20');
        Settings::set('followers', '0');
        Settings::set('language', '1');
        Settings::set('timezone', $_SESSION['install_timezone']);
        Settings::set('maintenance', '0');
        Settings::set('maintenance_message', 'Sitemiz şu anda bakım modundadır. Daha sonra tekrar deneyiniz.');
        Settings::set('authme', 0);
        Settings::set('default_avatar_type', 'minecraft');
        Settings::set('private_profile', '1');
        Settings::set('validate_user_action', '{"action":"promote","group":1}');
        Settings::set('login_method', 'email');
        Settings::set('username_sync', '1');
        Settings::set('status_page', '1');
        Settings::set('placeholders', '1');
        Settings::set('checkout_complete_content', 'Ödemeniz için teşekkürler. Ödemenizin işleme koyulması 15 dakika kadar sürebilir.', 'Magaza');
        Settings::set('currency', 'TL', 'Magaza');
        Settings::set('currency_symbol', '₺', 'Magaza');
        Settings::set('allow_guests', 0, 'Magaza');
        Settings::set('player_login', 0, 'Magaza');
        Settings::set('tier_list_page', 1);
        Settings::set('vote_message', 'Sevdiğiniz sunucuya bu kısımdan oy verip ödüllerin sahibi olabilirsiniz', 'Vote');
        Settings::set('mcmp_key', '', 'Vote');

        $this->_db->insert('privacy_terms', [
            'name' => 'terms',
            'value' => '<p>Web sitesi kurallarımıza ve bu web sitesi ve katılımınız için geçerli olabilecek tüm yasalara bağlı kalmayı kabul ediyorsunuz.</p><p>Web sitesi yönetimi, hesabınızı herhangi bir zamanda feshetme, herhangi bir içeriği silme hakkına sahiptir. yayınlamış olabilir ve IP adresiniz ve web sitesine girdiğiniz herhangi bir veri, site personeline moderatörlük görevlerinde yardımcı olmak için kaydedilir.</p><p>Site yönetimi, bu hüküm ve koşulları ve herhangi bir değişiklik yapma hakkına sahiptir. site kuralları, herhangi bir noktada uyarı yapmadan. Herhangi bir değişiklikten haberdar olsanız da, bu şartları ve kuralları istediğiniz zaman kontrol etmek sizin sorumluluğunuzdadır.</p>'
        ]);

        $this->_db->insert('privacy_terms', [
            'name' => 'cookies',
            'value' => '<span style="font-size:18px"><strong>Çerezler nedir?</strong></span><br />Çerezler, bir web sitesi tarafından cihazınızda saklanan, web tarayıcınıza özgü küçük dosyalardır. Web tarayıcısı, web sitesiyle her iletişim kurduğunda bu dosyaları web sitesine gönderir.<br />Çerezler, bu web sitesi tarafından aşağıda özetlenen çeşitli nedenlerle kullanılmaktadır.<br /><br /><strong>Gerekli çerezler</strong><br />Bu web sitesinin çalışması için gerekli çerezler gereklidir. Bunlar, web sitesi tarafından oturumunuzu sürdürmek için kullanılır, diğer önemli davranışların yanı sıra herhangi bir form göndermenize, web sitesinde oturum açmanıza olanak tanır. Bunları web sitesi içinde devre dışı bırakmak mümkün değildir, ancak çerezleri tarayıcınız üzerinden tamamen devre dışı bırakabilirsiniz.<br /><br /><strong>İşlevsel çerezler</strong><br />İşlevsel çerezler, web sitesinin çalışmasına izin verir. seçtiğiniz gibi. Örneğin, &quot;Beni Hatırla&quot; Giriş yaptığınızda bu seçenek, gelecekteki ziyaretlerinizde otomatik olarak oturum açmanız için işlevsel bir çerez oluşturacaktır.<br /><br /><strong>Analitik çerezler</strong><br />Analitik çerezler hem bu web sitesine hem de herhangi bir üçüncü web sitesine izin verir. Kullanıcı hakkında kişisel olarak tanımlanamayan verileri toplamak için bu web sitesi tarafından kullanılan taraf hizmetleri. Bu, bizim (web sitesi personelinin) kullanıcı deneyimini iyileştirmeye ve web sitesinin nasıl kullanıldığını anlamaya devam etmemizi sağlar.<br /><br />Çerezler hakkında <a rel="nofollow noopener" dahil olmak üzere çevrimiçi olarak daha fazla bilgi bulunabilir. target="_blank" href="https://ico.org.uk/your-data-matters/online/cookies/">ICOnun web sitesi</a>; tarayıcı.<br /><br /><span style="font-size:18px"><strong>Çerez kullanımını yapılandırma</strong></span><br />Varsayılan olarak, bu tarafından yalnızca gerekli çerezler kullanılır İnternet sitesi. Ancak, bazı web sitesi işlevleri, çerez kullanımı etkinleştirilene kadar kullanılamayabilir.<br />Bu web sitesindeki çerez bildirimi açılır penceresini kullanarak çerez kullanımını etkinleştirebilir veya buna izin vermemeye devam edebilirsiniz. Tercihinizi güncellemek isterseniz, aşağıdaki düğmeyi tıklayarak çerez bildirimi açılır penceresi yeniden etkinleştirilebilir.'
        ]);

        $this->_db->insert('privacy_terms', [
            'name' => 'privacy',
            'value' => 'The following privacy policy outlines how your data is used on our website.<br /><br /><strong>Data</strong><br />Basic non-identifiable information about your user on the website is collected; the majority of which is provided during registration, such as email addresses and usernames.<br />In addition to this, IP addresses for registered users are stored within the system to aid with moderation duties. This includes spam prevention, and detecting alternative accounts.<br /><br />Accounts can be deleted by a site administrator upon request, which will remove all data relating to your user from our system.<br /><br /><strong>Cookies</strong><br />Cookies are used to store small pieces of non-identifiable information with your consent. In order to consent to the use of cookies, you must either close the cookie notice (as explained within the notice) or register on our website.<br />Data stored by cookies include any recently viewed topic IDs, along with a unique, unidentifiable hash upon logging in and selecting &quot;Remember Me&quot; to automatically log you in next time you visit.'
        ]);

        $radome_terms = 'Bu site RadomeWEB kullanılarak oluşturulmuştur. ' .
                        'Verira firması için yapılmış olup site tamamen site sorumluluğu ' .
                        'tamamen site yöneticisine aittir. Verira çalışanları veya RadomeWEB ' .
                        'yapımcıları herhangi bir sorumluluk kabul etmez.';
        Settings::set('t_and_c', 'Sitemize kayıt olarak şu maddeleri kabul etmiş sayılırsınız:<p>' . $radome_terms . '</p>');
    }

    private function initialiseTasks(): void {
        GenerateSitemap::schedule(new Language('core', 'tr_TR'));
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
            'order' => 1,
            'pages' => '["index","forum","vote","form-1"]'
        ]);

        $this->_db->insert('widgets', [
            'name' => 'Statistics',
            'enabled' => true,
            'order' => 3,
            'pages' => '["index","forum","vote","form-1"]'
        ]);
        $this->_db->insert('widgets', [
            'name' => 'User Query Widget',
            'enabled' => true,
            'order' => 4,
            'pages' => '["index","forum","vote","form-1"]'
        ]);

        $this->_db->insert('widgets', [
            'name' => 'Latest Purchases',
            'enabled' => true,
            'order' => 5,
            'pages' => '["index","forum","vote","form-1"]'
        ]);

        $this->_db->insert('widgets', [
            'name' => 'Discord',
            'enabled' => true,
            'order' => 7,
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
            'forum_title' => 'Haberler',
            'forum_description' => 'Sunucu hakkında haberler!',
            'forum_order' => 1,
            'forum_type' => 'category'
        ]);        

        $this->_db->insert('forums', [
            'forum_title' => 'Haberler',
            'forum_description' => 'Sunucu hakkında haberler!',
            'forum_order' => 2,
            'parent' => 1,
            'forum_type' => 'forum'
        ]);

        $this->_db->insert('haberlers', [
            'haber_title' => 'RadomeWEB Minecraft Website Scripti',
            'post_creator' => 1,
            'post_content' =>
                '<p>RadomeWEB Gelişmiş Minecraft WebScripti</p>
                <ul>
                <li>🙋 Haberler</li>
                <li>🧭 Destek sistemi</li>
                <li>🗳️ Verilen oyları ve en &ccedil;ok oy verenleri sitede g&ouml;r&uuml;nt&uuml;leme ( minecraft-mp API )</li>
                <li>📧 Şifre sıfırlama, hesap onaylama ve php mail sistemi</li>
                <li>📚 Wiki sistemi ile sitenizi ve sunucunuzu oyunculara kolayca tanıtın</li>
                <li>🔨 AdvancedBan, LiteBans banlarını sitede g&ouml;r&uuml;nt&uuml;leme</li>
                <li>🛒 Mağaza sistemi ile &uuml;r&uuml;nler ekleme &uuml;r&uuml;nleri kategorize etme ve VeriraAPI, PayTR ile &ouml;deme alma imkanı</li>
                <li>📃 IFrame destekli &ouml;zel sayfalar ile sitenizde kendi sayfalarınızı oluşturabilirsiniz</li>
                <li>👥 OAuth desteği ile siteye discord veya google hesabı kullanarak kayıt olabilirsiniz</li>
                <li>🎮 Minecraft entegrasyonu
                <ul>
                <li>Bedrock veya Java edition sunucularınızın durumunu g&ouml;r&uuml;nt&uuml;leyin</li>
                <li>RadomeWEB Eklentisi
                <ul>
                <li>Mağaza bağlantısı ile sunucuya komut g&ouml;nderme</li>
                <li>Vault ranklarını RadomeWEB ile eşitleme ( oyundan -&gt; siteye )</li>
                <li>Oyun sohbetinde site duyurularını g&ouml;r&uuml;nt&uuml;leme</li>
                <li>Authme desteği ile site i&ccedil;erisinden kayıt olma</li>
                <li>Siteden yasaklanan oyuncunun sunucudan da yasaklanması &ouml;zelliği</li>
                <li>PlaceholderAPI datalarını siteye g&ouml;nderip Lider Tablosunda g&ouml;sterme &ouml;zelliği.</li>
                </ul>
                </li>
                </ul>
                </li>
                <li>🗨️ Discord entegrasyonu
                <ul>
                <li>Webhook: Satın alımlar. kredi yatıranlar, yeni destek a&ccedil;anlar, siteye kayıt olanlar, siteden ceza yiyenler gibi daha bir &ccedil;ok şeyi discord sunucunuzda g&ouml;sterebilirsiniz.</li>
                <li>Radome-DiscordBOT
                <ul>
                <li>RadomeWEB ile discord hesaplarını linkleme</li>
                <li>Discord rolleri ile site rollerini eşitleme</li>
                </ul>
                </li>
                </ul>
                </li>
                <li>⚙️ PHP 8 ve PDO kapalı kaynak altyapısı sayesinde a&ccedil;ıksız bir site deneyimi</li>
                <li>✨ SEO y&ouml;neticisi ile sitenizi googleda bir adım &ouml;ne taşıyın.</li>
                <li>🗺️ Widget: Widget ile sitenizde &ccedil;oğu şeyi g&ouml;r&uuml;nt&uuml;leyebilirsiniz: ( Son satın alımlar, Discord, Sunucu durumu, Site İstatistiği vs. )</li>
                <li>🖌️ &Ouml;zelleştirilebilir tema: site renklerini, slider ayarlarını, g&ouml;rselleri, başlıkları ve daha bir &ccedil;ok şeyi admin panelinden d&uuml;zenleyebilirsiniz</li>
                <li>🚩 İngilizce ve T&uuml;rk&ccedil;e dil desteği</li>
                </ul>
                <h4>&nbsp;</h4>
                <p>&nbsp;</p>
                <p>&nbsp;</p>'
            ,
            'post_date' => date('Y-m-d H:i:s'),
            'created' => date('U')
        ]);

        // Permissions
        for ($i = 0; $i < 4; $i++) {
            for ($n = 1; $n < 3; $n++) {
                $this->_db->insert('forums_permissions', [
                    'group_id' => $i,
                    'forum_id' => $n,
                    'view' => true,
                    'create_topic' => ($i == 0 ? 0 : 0),
                    'edit_topic' => ($i == 0 ? 0 : 0),
                    'create_post' => ($i == 0 ? 0 : 0),
                    'view_other_topics' => true,
                    'moderate' => (($i == 2 || $i == 3) ? 1 : 0)
                ]);
            }
        }

        $this->_db->insert('forums_permissions', [
            'group_id' => 2,
            'forum_id' => 2,
            'view' => true,
            'create_topic' => 1,
            'edit_topic' => 1,
            'create_post' => 1,
            'view_other_topics' => true,
            'moderate' => 1
        ]);

        // Forum Labels
        $this->_db->query("DELETE FROM rw_forums WHERE `rw_forums`.`id` = 1");
    }
}
