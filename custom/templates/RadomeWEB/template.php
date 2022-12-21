<?php
/*
 *
 *  RadomeWEB Template
 */

class RadomeWEB_Template extends TemplateBase {

    private array $_template;

    /** @var Language */
	private Language $_language;

    /** @var User */
	private User $_user;
	
    /** @var Pages */
	private Pages $_pages;

	public function __construct($cache, $smarty, $language, $user, $pages){

		$radomeweb_language = new Language(ROOT_PATH . '/custom/templates/RadomeWEB/template_settings/language', LANGUAGE);

		require(ROOT_PATH . '/custom/templates/RadomeWEB/template_settings/vars.php');

		$template = [
			'name' => 'RadomeWEB',
			'version' => Output::getClean($radomeweb_local_version),
			'nl_version' => '2.0.2',
			'author' => '<a href="' . Output::getClean($radomeweb_url) . '" target="_blank" rel="nofollow noopener">Verira.com</a>',
		];
		
		$template['path'] = (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/custom/templates/' . $template['name'] . '/';

		parent::__construct($template['name'], $template['version'], $template['nl_version'], $template['author']);

		$this->_settings = ROOT_PATH . '/custom/templates/RadomeWEB/template_settings/settings.php';

		$cache->setCache('radomeweb_template');

		foreach ($radomeweb_settings_array as $value) {
    		$setting_name = $value[0];
    		if ($cache->isCached($value[0])) {
        		$$setting_name = $cache->retrieve($value[0]);
    		} else {  
				$$setting_name = $value[1];
				$cache->store($value[0], $value[1]);
    		}
		}

		$this->addCSSFiles([
			'https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css' => ['integrity' => 'sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2', 'crossorigin' => 'anonymous'],
			(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/toastr/toastr.min.css' => ['rel' => 'preload', 'as' => 'style', 'onload' => "this.onload=null;this.rel='stylesheet'"],
			$template['path'] . 'css/radomeweb.css?v=' . Output::getClean($radomeweb_local_version) => [],
			'https://use.fontawesome.com/releases/v5.15.1/css/all.css' => ['rel' => 'preload', 'as' => 'style', 'onload' => "this.onload=null;this.rel='stylesheet'"]
		]);


            $this->addCSSFiles([
				'https://fonts.googleapis.com/css2?family=' . Output::getClean($font) . '&display=swap' => ['rel' => 'preload', 'as' => 'style', 'onload' => "this.onload=null;this.rel='stylesheet'"]
        	]);
		

		$this->addJSScript('var particles = "' . Output::getClean($particles) .'"; var swal_server_copy = "' . $radomeweb_language->get('language', 'swal_server_copy') .'";');

		$this->addJSFiles([
			'https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js' => [],
			'https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js' => ['integrity' => 'sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx', 'crossorigin' => 'anonymous'],
			'https://cdn.jsdelivr.net/npm/sweetalert2@11.1.4/dist/sweetalert2.all.min.js' => ['integrity' => 'sha256-dOvlmZEDY4iFbZBwD8WWLNMbYhevyx6lzTpfVdo0asA=', 'crossorigin' => 'anonymous', 'defer' => true],
			(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/toastr/toastr.min.js' => [],
			(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/js/jquery.cookie.js' => [],
		]);

		if((null !== Output::getClean($particles)) && Output::getClean($particles) == "yes"){
			$this->addJSFiles([
				$template['path'] . 'js/particles.min.js' => []
			]);
		}

		define('CHATBOX_SCRIPT', $template['path'] . 'js/core/chatbox.js?v=5');

		$logo_size_trimmed = trim($logo_size, 'px');
		$bg_height_trimmed = trim($bg_height, 'px');
		$logo_size_m_trimmed = trim($logo_size_m, 'px');
		$bg_height_m_trimmed = trim($bg_height_m, 'px');

		$box_margin_final = (-92.5 + ((92.5 - $bg_height_trimmed) / 2));

		if (Output::getClean($al) == "yes") {
			$logo_margin_final = ((($logo_size_trimmed * -1) + (($logo_size_trimmed - $bg_height_trimmed) / 2)) - 5);
			$logo_margin_m_final = ((($logo_size_m_trimmed * -1) + (($logo_size_m_trimmed - $bg_height_m_trimmed) / 2)) - 5);
		} else {
			$logo_margin_final = (($logo_size_trimmed * -1) + (($logo_size_trimmed - $bg_height_trimmed) / 2));
			$logo_margin_m_final = (($logo_size_m_trimmed * -1) + (($logo_size_m_trimmed - $bg_height_m_trimmed) / 2));
		}

		$this->addCSSStyle('@media (min-width: 801px) {.box {margin-top: '. $box_margin_final .'px !important;}}');
		$this->addCSSStyle('@media (min-width: 801px) {.logo {height: '. Output::getClean($logo_size) .'; margin: '. $logo_margin_final .'px 0}}');
		$this->addCSSStyle('@media (max-width: 800px) {.logo {height: '. Output::getClean($logo_size_m) .'; margin: '. $logo_margin_m_final .'px 0}}');

		if (Output::getClean($header_bg_webp) !== "") {
			$this->addCSSStyle('.webp .header {background: url(\'' . Output::getClean($header_bg_webp) . '\') no-repeat center top;}');
			$this->addCSSStyle('.no-webp .header {background: url(\'' . Output::getClean($header_bg) . '\') no-repeat center top;}');
		} else {
			$this->addCSSStyle('.header {background: url(\'' . Output::getClean($header_bg) . '\') no-repeat center top;}');
		}

		$this->addCSSStyle('.swal2-confirm, .color-overlay, .nav-tabs, .button-checkbox>.active, .navbar-theme, .blockquote>a:first-child, .modal-header, .spoiler .spoiler-toggle, .spoiler .spoiler-title, .page-item.active .page-link, .panel-theme .panel-heading, .footer-theme, .footer-text-bar, .header-theme, .footer-card-theme, .card-footer-theme, .btn-theme, .profile-theme .nav-link, .user-theme .nav-link, .card-inverse .header-theme, .badge-theme, #toast-container, .radomeweb-navbar-menu .nav-header, .progress-bar, .popover-header {background-color: '. Output::getClean($p_color) .' !important;}');
		$this->addCSSStyle('.dark ::-webkit-scrollbar-track, .dark ::-webkit-scrollbar-corner, .dark body  {background-color: '. Output::getClean($s_color) .';}');
		$this->addCSSStyle('.header {height: '. Output::getClean($bg_height) .';}');
		$this->addCSSStyle('@media only screen and (max-width: 768px) {.header {height: '. Output::getClean($bg_height_m) .';}}');
		$this->addCSSStyle('.swal2-confirm:hover, .spoiler .spoiler-toggle:hover, .spoiler .spoiler-title:hover, .btn-older-news:hover, .btn-theme:hover {background-color: '. Output::getClean($p_color) .' !important; filter: brightness(0.85)}');
		$this->addCSSStyle('.panel-theme, .page-item.active .page-link {border-color: '. Output::getClean($p_color) .';}');
		if((null !== Output::getClean($card_rounded)) && Output::getClean($card_rounded) == "yes"){
			$this->addCSSStyle('.card{border-radius:.5rem;} .card-header:first-child{border-radius:.5rem .5rem 0 0} .alert{border-radius:.5rem;} .btn-older-news{border-radius: .5rem !important;} .avatar-img{border-radius:.15rem;}');
		} else {
			$this->addCSSStyle('.card{border-radius:0;} .card-header:first-child{border-radius:0;} .alert{border-radius:0;} .btn-older-news{border-radius: 0 !important;}');
		}
		if((null !== Output::getClean($font)) && Output::getClean($font) == "Montserrat"){
			$this->addCSSStyle('body {font-family: "Montserrat", sans-serif}');
		} elseif((null !== Output::getClean($font)) && Output::getClean($font) == "Coda") {
			$this->addCSSStyle('body {font-family: "Coda", cursive}');
		} else {
			$this->addCSSStyle('body {font-family: "Verdana", sans-serif}');
		}

		if((null !== Output::getClean($links)) && Output::getClean($links) == "wgh"){
			$this->addCSSStyle('.nav-link:hover {color: #D0D0D0 !important;} .nav-link, .nav-link:focus {color: #FFF;}');
		} else {
			$this->addCSSStyle('.nav-link:hover {color: #FFF !important;} .nav-link, .nav-link:focus {color: #D0D0D0;}');
		}
		if((null !== Output::getClean($custom_css)) && Output::getClean($custom_css) !== ""){
			$this->addCSSStyle(Output::getClean($custom_css));
		}

        if ((null !== Output::getClean($navbar)) && Output::getClean($navbar) == "top") {
			$this->addCSSStyle('.card{border: transparent}');
        }

		if((null !== Output::getClean($navbar_size)) && Output::getClean($navbar_size) == "small"){
			$this->addCSSStyle('.navbar, .footer-text-bar {padding: 10px 0;} .nav-link {padding: 0.2em 0 !important} .footer-text {padding: 0.2em 15px 0.2em 0px !important;} .cf-footer {margin-top: 9px; margin-bottom: 9px;}');
		} elseif((null !== Output::getClean($navbar_size)) && Output::getClean($navbar_size) == "large") {
			$this->addCSSStyle('.navbar, .footer-text-bar {padding: 20px 0;} .nav-link {padding: 0.5em 0 !important} .footer-text {padding: 0.5em 15px 0.5em 0px !important;}');
		} else {
			$this->addCSSStyle('.navbar, .footer-text-bar {padding: 15px 0;} .nav-link {padding: 0.4em 0 !important} .footer-text {padding: 0.4em 15px 0.4em 0px !important;}');
		}

		foreach ($radomeweb_settings_array as $value) {
            if ($value[2] !== '') {
				$output_value = $value[0];
				$smarty->assign($value[2], $$output_value);
            }
        }


		
		$smarty->assign('THEME_radomeweb_URL', Output::getClean($radomeweb_url));
		$smarty->assign('THEME_LOCAL_VERSION', Output::getClean($radomeweb_local_version));
		$smarty->assign('THEME_TS_PATH', $template['path'] . 'js/core/ts.js?v=3');
		$smarty->assign('THEME_MOD_PATH', $template['path'] . 'js/core/mod.min.js');

		$smarty->assign('MENU', $radomeweb_language->get('language', 'menu'));
		$smarty->assign('DISCORD_BOX_COPY', $radomeweb_language->get('language', 'discord_box_copy'));
		$smarty->assign('TS_TITLE', $radomeweb_language->get('language', 'ts_title'));
		$smarty->assign('TS_BUTTON', $radomeweb_language->get('language', 'ts_button'));
		$smarty->assign('SERVER_BOX_TITLE', $radomeweb_language->get('language', 'server_box_title'));
		$smarty->assign('NEWS_BUTTON', $radomeweb_language->get('language', 'news_button'));
		$smarty->assign('NEWS_ERROR_TITLE', $radomeweb_language->get('language', 'news_error_title'));
		$smarty->assign('NEWS_ERROR_DESC', $radomeweb_language->get('language', 'news_error_desc'));
		$smarty->assign('ABOUT_TITLE', $radomeweb_language->get('language', 'about_title'));
		$smarty->assign('DISCORD_BOX_STATUS_1', $radomeweb_language->get('language', 'discord_box_status_1'));
		$smarty->assign('DISCORD_BOX_STATUS_2', $radomeweb_language->get('language', 'discord_box_status_2'));
		$smarty->assign('DISCORD_BOX_TITLE', $radomeweb_language->get('language', 'discord_box_title'));
		$smarty->assign('FOOTER_CREDIT_2', $radomeweb_language->get('language', 'footer_credit_2'));
		$smarty->assign('FOOTER_LINKS', $radomeweb_language->get('language', 'links_footer'));

		// External Updater

		$smarty->assign('TEMPLATE', $template);

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
			'siteIcon' => $cache->retrieve('logo_image'),
            'siteURL' => URL::build('/'),
            'fullSiteUrl' => URL::getSelfURL() . ltrim(URL::build('/'), '/'),
            'page' => PAGE,
            'avatarSource' => AvatarSource::getUrlToFormat(),
            'copied' => $this->_language->get('general', 'copied'),
			'close' => $this->_language->get('general', 'close'),
            'cookieNotice' => $this->_language->get('general', 'cookie_notice'),
            'noMessages' => $this->_language->get('user', 'no_messages'),
            'newMessage1' => $this->_language->get('user', '1_new_message'),
            'newMessagesX' => $this->_language->get('user', 'x_new_messages'),
            'noAlerts' => $this->_language->get('user', 'no_alerts'),
            'newAlert1' => $this->_language->get('user', '1_new_alert'),
            'newAlertsX' => $this->_language->get('user', 'x_new_alerts'),
            'bungeeInstance' => $this->_language->get('general', 'bungee_instance'),
            'andMoreX' => $this->_language->get('general', 'and_x_more'),
            'onePlayerOnline' => $this->_language->get('general', 'currently_1_player_online'),
            'xPlayersOnline' => $this->_language->get('general', 'currently_x_players_online'),
            'noPlayersOnline' => $this->_language->get('general', 'no_players_online'),
			'online' => $this->_language->get('general', 'online'),
            'offline' => $this->_language->get('general', 'offline'),
            'confirmDelete' => $this->_language->get('general', 'confirm_deletion'),
            'debugging' => (defined('DEBUGGING') && DEBUGGING == 1) ? '1' : '0',
            'loggedIn' => $this->_user->isLoggedIn() ? '1' : '0',
            'cookie' => defined('COOKIE_NOTICE') ? '1' : '0',
            'loadingTime' => Util::getSetting('page_loading') === '1' ? PAGE_LOAD_TIME : '',
			'pjsPath' => $this->_template['path'] . 'js/particles.json?v=2',
            'route' => $route,
            'csrfToken' => Token::get(),
        ];

        if (strpos($route, '/forum/konu/') !== false || PAGE == 'profile') {
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

$cache->setCache('settings');
if(!$cache->isCached('discord_count')){
  	$Discord_Server_ID = Discord::getGuildId();
  	$discord_api = file_get_contents('https://discord.com/api/guilds/'.$Discord_Server_ID.'/widget.json');
  	$discord_api_decode = json_decode($discord_api, true);
  	$discord_api_online = $discord_api_decode["presence_count"];
  	$cache->store('discord_count', $discord_api_online, 300);
} else {
  	$discord_api_online = $cache->retrieve('discord_count');
}
$smarty->assign('DISCORD_API_COUNT', $discord_api_online);

$template = new RadomeWEB_Template($cache, $smarty, $language, $user, $pages);
