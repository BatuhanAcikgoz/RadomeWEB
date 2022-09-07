<?php

/*
 * Wiki module made by reflexLabs 
 * reflexLabs.com
 */

class Wiki_Module extends Module {

	private $_wiki_language;

	public function __construct($wiki_language, $pages) {

        $name = 'Wiki';
		$author = '<a href="https://reflexlabs.xyz/" target="_blank">reflexLabs</a>';
		$module_version = '1.2.1';
		$nameless_version = '2.0.2';

		parent::__construct($this, $name, $author, $module_version, $nameless_version);

		$pages->add('Wiki', '/wiki', 'pages/wiki/index.php');
		$pages->add('Wiki', '/wiki/page', 'pages/wiki/page.php');
		$pages->add('Wiki', '/panel/wiki', 'pages/panel/index.php');
		$pages->add('Wiki', '/queries/like', 'queries/like.php');

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

		if(!$engine || is_[$engine])
			$engine = 'InnoDB';

		if(!$charset || is_[$charset])
			$charset = 'latin1';

		$queries = new Queries();
		try {
			$queries->createTable("wiki_settings", "`id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(20) NOT NULL, `value` varchar(8192) NOT NULL, PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			$queries->createTable("wiki_pages", "`id` int(11) NOT NULL AUTO_INCREMENT, `parent` varchar(48) NOT NULL, `nameid` varchar(48) NOT NULL, `title` varchar(48) NOT NULL, `button` varchar(48) NOT NULL, `icon` varchar(96) NOT NULL, `context` longtext NOT NULL, `views` int(11) NOT NULL DEFAULT '0', `likes` int(11) NOT NULL DEFAULT '0', `likeable` int(11) NOT NULL DEFAULT '1', `enabled` int(11) NOT NULL DEFAULT '1', PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			$queries->createTable("wiki_likes", "`id` int(11) NOT NULL AUTO_INCREMENT, `username` varchar(20) NOT NULL, `pageid` varchar(48) NOT NULL, PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
		} catch(Exception $e){}
		try {
			$queries->create('wiki_settings', [
				'name' => 'home_page',
				'value' => '<div><span style="font-size:20px"><strong>Welcome to your new Wiki library!</strong></span><br />WIKI Module allows you to create unlimited amount of wiki pages,<br />Includes the ability to modify button text, title, icon, context and even the url&nbsp;ID!<br /><br /><strong>Go ahead and create your own library!</strong><br /><br /><strong>Note:</strong>&nbsp;Also, this home page section are editable in&nbsp;<strong><u><a href="/panel/wiki">StaffCP -&gt; Wiki</a></u></strong>.<br /><br />Useful links:</div><ul><li>Support through our <strong><a rel="nofollow noopener" target="_blank" href="https://discord.com/invite/es9hWUCPKN">Discord</a></strong>.</li></ul>'
			]);

			$queries->create('wiki_pages', [
				'title' => 'Welcome',
				'parent' => 'null',
				'nameid' => 'welcome',
				'button' => 'Welcome',
				'icon' => 'fas fa-users',
				'context' => '&lt;span style=&quot;font-size:36px;&quot;&gt;&lt;strong&gt;WELCOME&lt;/strong&gt;&lt;/span&gt;&lt;br /&gt;This page contains links and helpful information to beginners in our server.',
				'views' => '0',
				'likes' => '0',
				'likeable' => '1',
				'enabled' => '1'
			]);
			$queries->create('wiki_pages', [
				'title' => 'Rules',
				'parent' => 'welcome',
				'nameid' => 'rules',
				'button' => 'Rules',
				'icon' => 'fas fa-book',
				'context' => '&lt;span style=&quot;font-size:36px;&quot;&gt;&lt;strong&gt;RULES&lt;/strong&gt;&lt;/span&gt;&lt;ul&gt;&lt;li&gt;Please check out our &lt;a rel=&quot;nofollow noopener&quot; target=&quot;_blank&quot; href=&quot;http://website.com/rules&quot;&gt;&lt;strong&gt;rules&lt;/strong&gt;&lt;/a&gt;.&lt;/li&gt;&lt;/ul&gt;',
				'views' => '0',
				'likes' => '0',
				'likeable' => '1',
				'enabled' => '1'
			]);
			$queries->create('wiki_pages', [
				'title' => 'Guides & Tips',
				'parent' => 'welcome',
				'nameid' => 'guide',
				'button' => 'Guide',
				'icon' => 'fas fa-question',
				'context' => '&lt;span style=&quot;font-size:36px;&quot;&gt;&lt;strong&gt;GUIDES & TIPS&lt;/strong&gt;&lt;/span&gt;&lt;br /&gt;This page contains guides and tips to beginners in our server.',
				'views' => '0',
				'likes' => '0',
				'likeable' => '1',
				'enabled' => '1'
			]);
			$queries->create('wiki_pages', [
				'title' => 'Pro Tips',
				'parent' => 'welcome',
				'nameid' => 'protips',
				'button' => 'Pro Tips',
				'icon' => 'fas fa-exclamation',
				'context' => '&lt;span style=&quot;font-size:36px;&quot;&gt;&lt;strong&gt;PRO TIPS&lt;/strong&gt;&lt;/span&gt;&lt;br /&gt;This page contains a pro tips to advenced players and begginers both.',
				'views' => '0',
				'likes' => '0',
				'likeable' => '1',
				'enabled' => '1'
			]);
			$queries->create('wiki_pages', [
				'title' => 'Commands',
				'parent' => 'null',
				'nameid' => 'commands',
				'button' => 'Commands',
				'icon' => 'fas fa-terminal',
				'context' => '&lt;span style=&quot;font-size:36px;&quot;&gt;&lt;strong&gt;COMMANDS&lt;/strong&gt;&lt;/span&gt;&lt;br /&gt;Here some of our useful commands ingame:&lt;br /&gt;&amp;nbsp;&lt;ul&gt;&lt;li&gt;&lt;strong&gt;/Pm&lt;/strong&gt; [player] [message]: Send a private message&lt;/li&gt;&lt;li&gt;&lt;strong&gt;/Pay&lt;/strong&gt; [player] [money]: Transfer to other player some money&lt;/li&gt;&lt;li&gt;&lt;strong&gt;/Info&lt;/strong&gt;: Get info about current game version&lt;/li&gt;&lt;li&gt;&lt;strong&gt;/Feed&lt;/strong&gt;: Feed yourself once in a hour&lt;/li&gt;&lt;li&gt;&lt;strong&gt;/Balance&lt;/strong&gt;: Check wallet balance&lt;/li&gt;&lt;/ul&gt;Check &lt;strong&gt;/help&lt;/strong&gt; ingame for more commands.',
				'views' => '0',
				'likes' => '0',
				'likeable' => '1',
				'enabled' => '1'
			]);
			$queries->create('wiki_pages', [
				'title' => 'Permissions',
				'parent' => 'null',
				'nameid' => 'permissions',
				'button' => 'Permissions',
				'icon' => 'fas fa-user-lock',
				'context' => '&lt;span style=&quot;font-size:36px;&quot;&gt;&lt;strong&gt;PERMISSIONS&lt;/strong&gt;&lt;/span&gt;&lt;br /&gt;This page contains list of permissions.',
				'views' => '0',
				'likes' => '0',
				'likeable' => '1',
				'enabled' => '1'
			]);
			$queries->create('wiki_pages', [
				'title' => 'Ranks',
				'parent' => 'null',
				'nameid' => 'ranks',
				'button' => 'Ranks',
				'icon' => 'fas fa-star',
				'context' => '&lt;span style=&quot;font-size:36px;&quot;&gt;&lt;strong&gt;RANKS&lt;/strong&gt;&lt;/span&gt;&lt;br /&gt;This page contains list of available ranks in our server.',
				'views' => '0',
				'likes' => '0',
				'likeable' => '1',
				'enabled' => '1'
			]);
			$queries->create('wiki_pages', [
				'title' => 'Perks',
				'parent' => 'ranks',
				'nameid' => 'perks',
				'button' => 'Perks',
				'icon' => 'fas fa-grin-stars',
				'context' => '&lt;span style=&quot;font-size:36px;&quot;&gt;&lt;strong&gt;PERKS&lt;/strong&gt;&lt;/span&gt;&lt;br /&gt;This page contains list of available perks for each rank in our server.',
				'views' => '0',
				'likes' => '0',
				'likeable' => '1',
				'enabled' => '1'
			]);
			$queries->create('wiki_pages', [
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

		if(!$engine || is_[$engine])
			$engine = 'InnoDB';

		if(!$charset || is_[$charset])
			$charset = 'latin1';
			
		$queries = new Queries();
		try {
			$group = $queries->getWhere('groups', ['id', '=', 2]);
			$group = $group[0];
			
			$group_permissions = json_decode($group->permissions, TRUE);
			$group_permissions['admincp.wiki'] = 1;
			
			$group_permissions = json_encode($group_permissions);
			$queries->update('groups', 2, ['permissions' => $group_permissions]);

			//update
			try{
				$sql = "SHOW COLUMNS FROM ".Config::get('mysql/prefix')."wiki_pages WHERE Field = ?";
				$res = DB::getInstance()->query($sql,["views"]);
				if(!$res->first()){
					DB::getInstance()->alterTable("wiki_pages", "views", "int(11) NOT NULL DEFAULT '0'");
				}
				$res = DB::getInstance()->query($sql,["likes"]);
				if(!$res->first()){
					DB::getInstance()->alterTable("wiki_pages", "likes", "int(11) NOT NULL DEFAULT '0'");
				}
				$res = DB::getInstance()->query($sql,["enabled"]);
				if(!$res->first()){
					DB::getInstance()->alterTable("wiki_pages", "enabled", "int(11) NOT NULL DEFAULT '1'");
				}
				$res = DB::getInstance()->query($sql,["likeable"]);
				if(!$res->first()){
					DB::getInstance()->alterTable("wiki_pages", "likeable", "int(11) NOT NULL DEFAULT '1'");
				}
			} catch(Exception $e){}
		} catch(Exception $e){}

		try {
			if(!$queries->tableExists("wiki_likes")){
				$queries->createTable("wiki_likes", "`id` int(11) NOT NULL AUTO_INCREMENT, `username` varchar(20) NOT NULL, `pageid` varchar(48) NOT NULL, PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
			}
		} catch(Exception $e){}
	}

    public function onDisable() {
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
			$icon = '';
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

