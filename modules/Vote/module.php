<?php 
/*
 *	Made by Partydragen
 *  https://github.com/partydragen/Vote-Module
 *  https://partydragen.com
 *  RadomeWEB version 2.0.0
 *
 *  License: MIT
 *
 *  Vote module info file
 */

class Vote_Module extends Module {
	private $_vote_language;

	public function __construct($vote_language, $pages, $cache) {
		$this->_vote_language = $vote_language;

		$name = 'Vote';
		$author = '<a href="https://batuhanacikgoz.com.tr" target="_blank" rel="nofollow noopener">Reeignn</a>';
		$module_version = '2.3.3';
		$radome_version = '2.0.2';

		parent::__construct($this, $name, $author, $module_version, $radome_version);

		// Define URLs which belong to this module
		$pages->add('Vote', '/vote', 'pages/vote.php', 'vote', true);
		$pages->add('Vote', '/panel/vote', 'pages/panel/vote.php');

		// Check if module version changed
		$cache->setCache('vote_module_cache');
		if (!$cache->isCached('module_version')) {
			$cache->store('module_version', $module_version);
		} else {
			if ($module_version != $cache->retrieve('module_version')) {
				// Version have changed, Perform actions
                $this->initialiseUpdate($cache->retrieve('module_version'));
				$cache->store('module_version', $module_version);

				if ($cache->isCached('update_check')) {
                    $cache->erase('update_check');
                }
			}
		}
	}

	public function onInstall() {
        // Initialise
        $this->initialise();
	}

	public function onUninstall() {
		// No actions necessary
	}

	public function onEnable() {
        // Check if we need to initialise again
        $this->initialise();
	}

	public function onDisable() {
		// No actions necessary
	}

	public function onPageLoad($user, $pages, $cache, $smarty, $navs, $widgets, $template) {
		// AdminCP
		PermissionHandler::registerPermissions('Vote', [
			'admincp.vote' => $this->_vote_language->get('vote', 'vote')
		]);

		// navigation link location
		$cache->setCache('nav_location');
		if (!$cache->isCached('vote_location')) {
			$link_location = 1;
			$cache->store('vote_location', 1);
		} else {
			$link_location = $cache->retrieve('vote_location');
		}

		// Navigation icon
		$cache->setCache('navbar_icons');
		if (!$cache->isCached('vote_icon')) {
			$icon = '<i class="fas fa-vote-yea"></i>';
		} else {
			$icon = $cache->retrieve('vote_icon');
		}

		// Navigation order
		$cache->setCache('navbar_order');
		if (!$cache->isCached('vote_order')) {
			// Create cache entry now
			$vote_order = 3;
			$cache->store('vote_order', 3);
		} else {
			$vote_order = $cache->retrieve('vote_order');
		}

		switch($link_location) {
			case 1:
				// Navbar
				$navs[0]->add('vote', $this->_vote_language->get('vote', 'vote'), URL::build('/vote'), 'top', null, $vote_order, $icon);
			break;
			case 2:
				// "More" dropdown
				$navs[0]->addItemToDropdown('more_dropdown', 'vote', $this->_vote_language->get('vote', 'vote'), URL::build('/vote'), 'top', null, $icon, $vote_order);
			break;
			case 3:
				// Footer
				$navs[0]->add('vote', $this->_vote_language->get('vote', 'vote'), URL::build('/vote'), 'footer', null, $vote_order, $icon);
			break;
		}

		if (defined('BACK_END')) {
			if ($user->hasPermission('admincp.vote')) {
				$cache->setCache('panel_sidebar');
				if (!$cache->isCached('vote_order')) {
					$order = 20;
					$cache->store('vote_order', 20);
				} else {
					$order = $cache->retrieve('vote_order');
				}

				if (!$cache->isCached('vote_icon')) {
					$icon = '<i class="nav-icon fas fa-cogs"></i>';
					$cache->store('vote_icon', $icon);
				} else {
					$icon = $cache->retrieve('vote_icon');
				}

				$navs[2]->add('vote_divider', mb_strtoupper($this->_vote_language->get('vote', 'vote'), 'UTF-8'), 'divider', 'top', null, $order, '');
				$navs[2]->add('vote', $this->_vote_language->get('vote', 'vote'), URL::build('/panel/vote'), 'top', null, $order + 0.1, $icon);
			}
		}
	}

    public function getDebugInfo(): array {
        return [];
    }

    private function initialiseUpdate($old_version){
        $old_version = str_replace([".", "-"], "", $old_version);

        if ($old_version < 234) {
            if (DB::getInstance()->showTables('vote_settings')) {
                $message = DB::getInstance()->query('SELECT * FROM nl2_vote_settings WHERE name = \'vote_message\'');
                if ($message->count()) {
                    Util::setSetting('vote_message', $message->first()->value, 'Vote');
                }

                DB::getInstance()->query('DROP TABLE nl2_vote_settings');
            }
        }
    }

    private function initialise() {
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
    }
}
