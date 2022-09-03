<?php
/*
 *  Made by Samerton | Revamped by Xemah
 *    https://github.com/NamelessMC/Nameless/
 *    NamelessMC version 2.0.0
 *
 *    License: MIT
 *
 *    DefaultRevamp Template
 */

class DefaultRevamp_Template extends TemplateBase {

    private array $_template;

    /** @var Language */
    private Language $_language;

    /** @var User */
    private User $_user;

    /** @var Pages */
    private Pages $_pages;

    public function __construct($cache, $smarty, $language, $user, $pages) {
        $template = [
            'name' => 'RadomeWEB',
            'version' => '2.0.2',
            'nl_version' => '2.0.2',
            'author' => '<a href="https://batuhanacikgoz.com.tr/" target="_blank">Reeignn</a>',
        ];

        $template['path'] = (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/custom/templates/' . $template['name'] . '/';

        parent::__construct($template['name'], $template['version'], $template['nl_version'], $template['author']);

        $this->_settings = ROOT_PATH . '/custom/templates/RadomeWEB/template_settings/settings.php';

        $this->assets()->include([
            AssetTree::FONT_AWESOME,
            AssetTree::JQUERY,
            AssetTree::JQUERY_COOKIE,
        ]);

        $this->addCSSFiles([
            $template['path'] . 'css/fomantic.min.css' => [],
        ]);

        $this->addCSSFiles([
			'https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css' => array('integrity' => 'sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2', 'crossorigin' => 'anonymous'),
			(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/toastr/toastr.min.css' => array('rel' => 'preload', 'as' => 'style', 'onload' => "this.onload=null;this.rel='stylesheet'"),
			$template['path'] . 'css/new-radomeweb.css?v=' . Output::getClean($radomeweb_local_version) => array(),
			'https://use.fontawesome.com/releases/v5.15.1/css/all.css' => array('rel' => 'preload', 'as' => 'style', 'onload' => "this.onload=null;this.rel='stylesheet'")
		]);

		if (Output::getClean($font) !== "Verdana") {
            $this->addCSSFiles(array(
				'https://fonts.googleapis.com/css2?family=' . Output::getClean($font) . '&display=swap' => array('rel' => 'preload', 'as' => 'style', 'onload' => "this.onload=null;this.rel='stylesheet'")
        	));
		}

        $this->addCSSFiles([
			'https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css' => array('integrity' => 'sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2', 'crossorigin' => 'anonymous'),
			(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/toastr/toastr.min.css' => array('rel' => 'preload', 'as' => 'style', 'onload' => "this.onload=null;this.rel='stylesheet'"),
			$template['path'] . 'css/new-radomeweb.css?v=' . Output::getClean($radomeweb_local_version) => array(),
			'https://use.fontawesome.com/releases/v5.15.1/css/all.css' => array('rel' => 'preload', 'as' => 'style', 'onload' => "this.onload=null;this.rel='stylesheet'")
        ]);

        $this->addCSSFiles([
            $template['path'] . 'css/fomantic.min.css' => [],
        ]);
		
        $this->addJSFiles([
            $template['path'] . 'js/fomantic.min.js' => [],
        ]);

        $this->addJSFiles([
			'https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js' => array('integrity' => 'sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=', 'crossorigin' => 'anonymous'),
			'https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js' => array('integrity' => 'sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx', 'crossorigin' => 'anonymous'),
			'https://cdn.jsdelivr.net/npm/sweetalert2@11.1.4/dist/sweetalert2.all.min.js' => array('integrity' => 'sha256-dOvlmZEDY4iFbZBwD8WWLNMbYhevyx6lzTpfVdo0asA=', 'crossorigin' => 'anonymous', 'defer' => true),
			(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/toastr/toastr.min.js' => array(),
			(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/js/jquery.cookie.js' => array()
        ]);		

        $smarty->assign('TEMPLATE', $template);

        // Other variables
        $smarty->assign('FORUM_SPAM_WARNING_TITLE', $language->get('general', 'warning'));

        $cache->setCache('template_settings');
        $smartyDarkMode = true;
        $smartyNavbarColour = '';

        if (defined('DARK_MODE') && DARK_MODE == '1') {
            $smartyDarkMode = true;
        }

        if ($cache->isCached('navbarColour')) {
            $navbarColour = $cache->retrieve('navbarColour');

            if ($navbarColour != 'white') {
                $smartyNavbarColour = $navbarColour . ' inverted';
            }
        }

        $smarty->assign([
            'DEFAULT_REVAMP_DARK_MODE' => $smartyDarkMode,
            'DEFAULT_REVAMP_NAVBAR_EXTRA_CLASSES' => $smartyNavbarColour
        ]);

        $this->_template = $template;
        $this->_language = $language;
        $this->_user = $user;
        $this->_pages = $pages;
    }

    public function onPageLoad() {
        $page_load = microtime(true) - PAGE_START_TIME;
        define('PAGE_LOAD_TIME', $this->_language->get('general', 'page_loaded_in', ['time' => round($page_load, 3)]));

        $this->addCSSFiles([
            $this->_template['path'] . 'css/custom.css?v=200' => []
        ]);

        $route = (isset($_GET['route']) ? rtrim($_GET['route'], '/') : '/');

		$JSVariables = [
            'siteName' => Output::getClean(SITE_NAME),
		    'siteURL' => URL::build('/'),
		    'fullSiteUrl' => Util::getSelfURL() . ltrim(URL::build('/'), '/'),
			'page' => PAGE,
			'pjsPath' => $this->_template['path'] . 'js/particles.json?v=2',
			'copied' => $this->_language->get('general', 'copied'),
			'close' => $this->_language->get('general', 'close'),
		    'loading' => $this->_language->get('general', 'loading'),
		    'cookieNotice' => $this->_language->get('general', 'cookie_notice'),
		    'noMessages' => $this->_language->get('user', 'no_messages'),
		    'newMessage1' => $this->_language->get('user', '1_new_message'),
		    'newMessagesX' => $this->_language->get('user', 'x_new_messages'),
		    'noAlerts' => $this->_language->get('user', 'no_alerts'),
		    'newAlert1' => $this->_language->get('user', '1_new_alert'),
		    'newAlertsX' => $this->_language->get('user', 'x_new_alerts'),
		    'debugging' => ((defined('DEBUGGING') && DEBUGGING == 1) ? '1' : '0'),
		    'loggedIn' => ($this->_user->isLoggedIn() ? '1' : '0'),
		    'cookie'  => (defined('COOKIE_NOTICE') ? '1' : '0'),
		    'confirmDelete' => $this->_language->get('general', 'confirm_deletion'),
		    'offline' => $this->_language->get('general', 'offline'),
		    'noPlayersOnline' => $this->_language->get('general', 'no_players_online'),
		    'bungeeInstance' => $this->_language->get('general', 'bungee_instance'),
		    'online' => $this->_language->get('general', 'online'),
		    'avatarSource' => Util::getAvatarSource(),
		    'andMoreX' => $this->_language->get('general', 'and_x_more'),
		    'loadingTime' => ((defined('PAGE_LOADING') && PAGE_LOADING == 1) ? PAGE_LOAD_TIME : ''),
		    'route' => $route
		];

        if (strpos($route, '/forum/topic/') !== false || PAGE == 'profile') {
            $this->assets()->include([
                AssetTree::JQUERY_UI,
            ]);
        }

        $JSVars = '';
        $i = 0;
        foreach ($JSVariables as $var => $value) {
            $JSVars .= ($i == 0 ? 'var ' : ', ') . $var . ' = "' . $value . '"';
            $i++;
        }

        $this->addJSScript($JSVars);

        $this->addJSFiles([
            $this->_template['path'] . 'js/core/core.js?v=202' => [],
            $this->_template['path'] . 'js/core/user.js' => [],
            $this->_template['path'] . 'js/core/pages.js?v=202' => [],
            $this->_template['path'] . 'js/scripts.js' => [],
        ]);

        foreach ($this->_pages->getAjaxScripts() as $script) {
            $this->addJSScript('$.getJSON(\'' . $script . '\', function(data) {});');
        }
    }
}

$template = new DefaultRevamp_Template($cache, $smarty, $language, $user, $pages);
$template_pagination = ['div' => 'ui mini pagination menu', 'a' => '{x}item'];
