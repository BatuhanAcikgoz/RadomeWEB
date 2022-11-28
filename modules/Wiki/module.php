<?php

/*
 * Wiki module made by reflexLabs 
 * reflexLabs.com
 */

class Wiki_Module extends Module {
    private DB $_db;
	private $_wiki_language;

	public function __construct($wiki_language, $pages) {

        $name = 'Wiki';
		$author = '<a href="https://reflexlabs.xyz/" target="_blank">reflexLabs</a>';
		$module_version = '1.2.1';
		$radome_version = '2.1.0';

		parent::__construct($this, $name, $author, $module_version, $radome_version);

		$pages->add('Wiki', '/wiki', 'pages/wiki/index.php');
		$pages->add('Wiki', '/wiki/sayfa', 'pages/wiki/page.php');
		$pages->add('Wiki', '/panel/wiki', 'pages/panel/index.php');
		$pages->add('Wiki', '/sorgu/begen', 'queries/like.php');

		$this->_db = DB::getInstance();
		$this->_wiki_language = $wiki_language;

	}

    public function onInstall() {
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
			DB::getInstance()->createTable("wiki_settings", "`id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(20) NOT NULL, `value` varchar(8192) NOT NULL, PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			DB::getInstance()->createTable("wiki_pages", "`id` int(11) NOT NULL AUTO_INCREMENT, `parent` varchar(48) NOT NULL, `nameid` varchar(48) NOT NULL, `title` varchar(48) NOT NULL, `button` varchar(48) NOT NULL, `icon` varchar(96) NOT NULL, `context` longtext NOT NULL, `views` int(11) NOT NULL DEFAULT '0', `likes` int(11) NOT NULL DEFAULT '0', `likeable` int(11) NOT NULL DEFAULT '1', `enabled` int(11) NOT NULL DEFAULT '1', PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			DB::getInstance()->createTable("wiki_likes", "`id` int(11) NOT NULL AUTO_INCREMENT, `username` varchar(20) NOT NULL, `pageid` varchar(48) NOT NULL, PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
		} catch(Exception $e){}
		try {
			$this->_db->insert('wiki_settings', [
				'name' => 'home_page',
				'value' => '<div><span style="font-size:20px"><strong>RadomeWEB Wiki Sayfasına Hoşgeldin!</strong></span><br />Bu kısımda istediğin kadar wiki sayfası oluşturabilirsin,<br />Düğme metnini, başlığı, simgeyi, urlyi ve daha bir çok şeyi düzenleyebilirsin.<br /><br /><strong>Admin panelinden istediğin değişikliği yapabilirsin.</strong><br /><br /><strong>Not:</strong>&nbsp;Ayrıca bu kısımı&nbsp;<strong><u><a href="/panel/wiki">Admin Paneli -&gt; Wiki</a></u></strong>.<br /><br />Bağlantılar:</div><ul><li>Desteği bu  <strong><a rel="nofollow noopener" target="_blank" href="https://discord.verira.com">Discord</a></strong> sunucusundan alabilirsiniz.</li></ul>'
			]);

			$this->_db->insert('wiki_pages', [
				'title' => 'Hoşgeldiniz',
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
				'context' => '&lt;span style=&quot;font-size:36px;&quot;&gt;&lt;strong&gt;Komutlar&lt;/strong&gt;&lt;/span&gt;&lt;br /&gt;Bu kısımdan oyun içerisindeki bazı komutlara göz atabilirsin.&lt;br /&gt;&amp;nbsp;&lt;ul&gt;&lha ot;li&gt;&lt;strong&gt;/msg&lt;/strong&gt; [player] [message]: Hedef kişiye özel mesaj gönderir.&lt;/li&gt;&lt;li&gt;&lt;strong&gt;/fly&lt;/strong&gt;: Premiumlar için uçmayı sağlar.&lt;/li&gt;&lt;li&gt;&lt;strong&gt;/customkit&lt;/strong&gt;: Sunucu içinde custom kit oluşturmanıza olanak sağlar.&lt;/li&gt;&lt;li&gt;&lt;strong&gt;/duel&lt;/strong&gt; [player] : Hedef oyuncuya duello isteği gönderir. &lt;/li&gt;&lt;li&gt;&lt;strong&gt;/ffa&lt;/strong&gt;: FFA oyun moduna gir.&lt;/li&gt;&lt;/ul&gt;Check &lt;strong&gt;/yardım&lt;/strong&gt; oyunda yardım almak için yazın.',
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
				'context' => '&lt;span style=&quot;font-size:36px;&quot;&gt;&lt;strong&gt;RANKS&lt;/strong&gt;&lt;/span&gt;&lt;br /&gt;This page contains list of available ranks in our server.',
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
				'context' => '&lt;span style=&quot;font-size:36px;&quot;&gt;&lt;strong&gt;PERKS&lt;/strong&gt;&lt;/span&gt;&lt;br /&gt;This page contains list of available perks for each rank in our server.',
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
				'context' => '&lt;span style=&quot;font-size:36px;&quot;&gt;&lt;strong&gt;DISCORD&lt;/strong&gt;&lt;/span&gt;&lt;ul&gt;&lt;li&gt;Feel free to join to our &lt;a rel=&quot;nofollow noopener&quot; target=&quot;_blank&quot; href=&quot;http://discord.gg/link&quot;&gt;&lt;strong&gt;Discord server&lt;/strong&gt;&lt;/a&gt;.&lt;/li&gt;&lt;/ul&gt;',
				'views' => '0',
				'likes' => '0',
				'likeable' => '1',
				'enabled' => '1'
			]);
		} catch(Exception $e){}
    }
	
    public function onUninstall() {

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

    public function onDisable() {
    }

    public function getDebugInfo(): array {
        return [];
    
        // Services
        $services_list = [];
        foreach (Services::getInstance()->getAll() as $service) {
            $services_list[] = [
                'id' => Output::getClean($service->getId()),
                'name' => Output::getClean($service->getName()),
            ];
        }
	}	

    public function onPageLoad($user, $pages, $cache, $smarty, $navs, $widgets, $template) {

		PermissionHandler::registerPermissions('Wiki', [
			'admincp.wiki' => $this->_wiki_language->get('wiki', 'wiki')
		]);

		if(defined('PANEL_PAGE') && PANEL_PAGE == 'wiki'){
			$template->addJSFiles([
				  (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/ckeditor/plugins/spoiler/js/spoiler.js' => [],
				  (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/ckeditor/ckeditor.js' => [],
			]);
		}

	  	$cache->setCache('nav_location');
		if(!$cache->isCached('wiki_location')){
			$wiki_location = 1;
			$cache->store('wiki_location', 1);
		} else {
			$wiki_location = $cache->retrieve('wiki_location');
		}

		$cache->setCache('navbar_order');
		if(!$cache->isCached('wiki_order')){
			$wiki_order = 4;
			$cache->store('wiki_order', 4);
		} else {
			$wiki_order = $cache->retrieve('wiki_order');
		}

		$cache->setCache('navbar_icons');
		if(!$cache->isCached('wiki_icon')) {
			$icon = '<i class="fas fa-book"></i>';
			$cache->store('wiki_order', $icon);
		} else {
			$icon = $cache->retrieve('wiki_icon');
		}
		
		switch($wiki_location){
			case 1:
				$navs[0]->add('wiki', $this->_wiki_language->get('wiki', 'wiki'), URL::build('/wiki'), 'top', null, $wiki_order, $icon);
				break;
			case 2:
				$navs[0]->addItemToDropdown('more_dropdown', 'wiki', $this->_wiki_language->get('wiki', 'wiki'), URL::build('/wiki'), 'top', null, $icon, $wiki_order);
				break;
			case 3:
				$navs[0]->add('wiki', $this->_wiki_language->get('wiki', 'wiki'), URL::build('/wiki'), 'footer', null, $wiki_order, $icon);
				break;
		}

		if(defined('BACK_END')){
			if($user->hasPermission('admincp.wiki')){
				$cache->setCache('panel_sidebar');
				if(!$cache->isCached('wiki_new_order')){
					$order = 10;
					$cache->store('wiki_new_order', 10);
				} else {
					$order = $cache->retrieve('wiki_new_order');
				}

				if(!$cache->isCached('wiki_icon')){
					$icon = '<i class="fab fa-wikipedia-w"></i>';
					$cache->store('wiki_icon', $icon);
				} else {
					$icon = $cache->retrieve('wiki_icon');
				}	
				$navs[2]->add('wiki_divider', mb_strtoupper($this->_wiki_language->get('wiki', 'wiki'), 'UTF-8'), 'divider', 'top', null, $order, '');
				$navs[2]->add('wiki', $this->_wiki_language->get('wiki', 'wiki'), URL::build('/panel/wiki'), 'top', null, $order + 0.1, $icon);
			}
		}
    }	
}

