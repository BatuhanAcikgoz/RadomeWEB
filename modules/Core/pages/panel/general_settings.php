<?php
/*
 *  Made by Reeignn
 *  https://github.com/Verira/RadomeWEB
 *  RadomeWEB v2.1
 *
 *  License: GPL-3.0
 *
 *  Panel general settings page
 */

if (!$user->handlePanelPageLoad('admincp.core.general')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'core_configuration';
const PANEL_PAGE = 'general_settings';
$page_title = $language->get('admin', 'general_settings');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

// Handle input
if (isset($_GET['do'])) {
    if ($_GET['do'] == 'installLanguage') {
        // Install new language
        $languages = glob('custom' . DIRECTORY_SEPARATOR . 'languages' . DIRECTORY_SEPARATOR . '*');
        foreach ($languages as $item) {
            // cursed
            $short_code = explode('.', explode(DIRECTORY_SEPARATOR, $item)[2])[0];

            // Is it already in the database?
            $exists = DB::getInstance()->get('languages', ['short_code', $short_code])->results();
            if (!count($exists)) {
                // No, add it now
                DB::getInstance()->insert('languages', [
                    // If they try and install a language which is not "official", default to the short code for the name
                    'name' => Language::LANGUAGES[$short_code]['name'] ?? $short_code,
                    'short_code' => $short_code
                ]);
            }
        }

        Session::flash('general_language', $language->get('admin', 'installed_languages'));
    } else {
        if ($_GET['do'] == 'updateLanguages') {
            $active_language = DB::getInstance()->get('languages', ['is_default', true])->results();
            if (count($active_language)) {
                DB::getInstance()->query('UPDATE rw_users SET language_id = ?', [$active_language[0]->id]);
                $language = new Language('core', $active_language[0]->short_code);
            }

            Session::flash('general_language', $language->get('admin', 'updated_user_languages'));
        }
    }

    Redirect::to(URL::build('/panel/genel_ayarlar'));
}

// Deal with input
if (Input::exists()) {
    if (Token::check()) {
        // Validate input
        $validation = Validate::check($_POST, [
            'sitename' => [
                Validate::REQUIRED => true,
                Validate::MIN => 2,
                Validate::MAX => 64
            ],
            'contact_email' => [
                Validate::REQUIRED => true,
                Validate::MIN => 3,
                Validate::MAX => 255
            ]
        ])->messages([
            'sitename' => $language->get('admin', 'missing_sitename'),
            'contact_email' => $language->get('admin', 'missing_contact_address')
        ]);

        if ($validation->passed()) {
            // Update settings
            // Sitename
            Util::setSetting('sitename', Input::get('sitename'));

            // Email address
            Util::setSetting('incoming_email', Input::get('contact_email'));

            // Language
            // Get current default language
            DB::getInstance()->update('languages', ['is_default', true], [
                'is_default' => false,
            ]);

            $language_id = DB::getInstance()->get('languages', ['id', Input::get('language')])->results();
            $language_short_code = Output::getClean($language_id[0]->short_code);
            $language_id = $language_id[0]->id;

            DB::getInstance()->update('languages', $language_id, [
                'is_default' => true,
            ]);

            // Update cache
            $cache->setCache('languagecache');
            $cache->store('language', $language_short_code);

            // Timezone
            try {
                Util::setSetting('timezone', $_POST['timezone']);
            } catch (Exception $e) {
                $errors = [$e->getMessage()];
            }

            $home_type = 'news';

            Util::setSetting('home_type', $home_type);

            // Private profile

            // Registration displaynames
            Util::setSetting('displaynames', (isset($_POST['displaynames']) && $_POST['displaynames'] == 'true') ? '1' : '0');

            // Update config
            if (is_writable(ROOT_PATH . '/' . implode(DIRECTORY_SEPARATOR, ['core', 'config.php']))) {
                Config::setMultiple([
                    'core.friendly' => true,
                    'core.force_https' => true,
                ]);
            } else {
                $errors = [$language->get('admin', 'config_not_writable')];
            }

            // Login method
            DB::getInstance()->update('settings', ['name', 'login_method'], [
                'value' => $_POST['login_method']
            ]);

            Log::getInstance()->log(Log::Action('admin/core/general'));

            Session::flash('general_language', $language->get('admin', 'settings_updated_successfully'));

            // Redirect in case URL type has changed
            if (!isset($errors)) {
                Redirect::to('/panel/genel_ayarlar');
            }
        } else {
            $errors = $validation->errors();
        }
    } else {
        // Invalid token
        $errors = [$language->get('general', 'invalid_token')];
    }
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

if (Session::exists('general_language')) {
    $success = Session::flash('general_language');
}

if (isset($success)) {
    $smarty->assign([
        'SUCCESS_TITLE' => $language->get('general', 'success'),
        'SUCCESS' => $success
    ]);
}

if (isset($errors) && count($errors)) {
    $smarty->assign([
        'ERRORS_TITLE' => $language->get('general', 'error'),
        'ERRORS' => $errors
    ]);
}

// Get form values
$contact_email = DB::getInstance()->get('settings', ['name', 'incoming_email'])->results();
$contact_email = Output::getClean($contact_email[0]->value);

$languages = DB::getInstance()->get('languages', ['id', '<>', 0])->results();
$count = count($languages);
for ($i = 0; $i < $count; $i++) {
    $language_path = implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'custom', 'languages', $languages[$i]->short_code . '.json']);
    if (!file_exists($language_path)) {
        unset($languages[$i]);
    }
}

$timezone = Util::getSetting('timezone');
$timezone = $timezone[0]->value;
$private_profile = Util::getSetting('private_profile');

$displaynames = Util::getSetting('displaynames');
$private_profile = DB::getInstance()->get('settings', ['name', 'private_profile'])->results();
$method = Util::getSetting('login_method');

$smarty->assign([
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'CONFIGURATION' => $language->get('admin', 'configuration'),
    'GENERAL_SETTINGS' => $language->get('admin', 'general_settings'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
    'SITE_NAME_LABEL' => $language->get('admin', 'sitename'),
    'CONTACT_EMAIL_ADDRESS' => $language->get('admin', 'contact_email_address'),
    'CONTACT_EMAIL_ADDRESS_VALUE' => $contact_email,
    'INFO' => $language->get('general', 'info'),
    'DEFAULT_LANGUAGE' => $language->get('admin', 'default_language'),
    'DEFAULT_LANGUAGE_HELP' => $language->get('admin', 'default_language_help', [
        'docLinkStart' => "<a href='https://docs.radome.web.tr/home#translations' target='_blank'>",
        'docLinkEnd' => '</a>'
    ]),
    'DEFAULT_LANGUAGE_VALUES' => $languages,
    'INSTALL_LANGUAGE_LINK' => URL::build('/panel/genel_ayarlar/', 'do=installLanguage'),
    'INSTALL_LANGUAGE' => $language->get('admin', 'install_language'),
    'UPDATE_USER_LANGUAGES_LINK' => URL::build('/panel/genel_ayarlar/', 'do=updateLanguages'),
    'UPDATE_USER_LANGUAGES' => $language->get('admin', 'update_user_languages'),
    'UPDATE_USER_LANGUAGES_INFO' => $language->get('admin', 'update_user_languages_warning'),
    'YES' => $language->get('general', 'yes'),
    'NO' => $language->get('general', 'no'),
    'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
    'DEFAULT_TIMEZONE' => $language->get('admin', 'default_timezone'),
    'DEFAULT_TIMEZONE_LIST' => Util::listTimezones(),
    'DEFAULT_TIMEZONE_VALUE' => $timezone,
    'HOMEPAGE_NEWS' => $language->get('admin', 'homepage_news'),
    'ENABLED' => $language->get('admin', 'enabled'),
    'DISABLED' => $language->get('admin', 'disabled'),
    'LOGIN_METHOD' => $language->get('admin', 'login_method'),
    'LOGIN_METHOD_VALUE' => $method,
    'EMAIL' => $language->get('user', 'email'),
    'EMAIL_OR_USERNAME' => $language->get('user', 'email_or_username'),
    'USERNAME' => $language->get('user', 'username'),
]);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('core/general_settings.tpl', $smarty);
