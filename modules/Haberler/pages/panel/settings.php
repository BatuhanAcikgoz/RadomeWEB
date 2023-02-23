<?php
/*
 *  Made by Partydragen
 *  https://github.com/RadomeWEB/Radome/
 *  RadomeWEB version 2.0.0-pr12
 *
 *  License: MIT
 *
 *  Panel haberlers page
 */

// Can the user view the panel?
if (!$user->handlePanelPageLoad('admincp.haberlers')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'haberler';
const PANEL_PAGE = 'haberler_settings';
$page_title = $haberler_language->get('haberler', 'haberlers');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

if (Input::exists()) {
    if (Token::check()) {
        // Update link location
        if (isset($_POST['link_location'])) {
            switch ($_POST['link_location']) {
                case 1:
                case 2:
                case 3:
                case 4:
                    $location = $_POST['link_location'];
                    break;
                default:
                    $location = 1;
            }
        } else {
            $location = 1;
        }

        // Update Link location cache
        $cache->setCache('nav_location');
        $cache->store('haberler_location', $location);

        Util::setSetting('haberler_reactions', (isset($_POST['use_reactions']) && $_POST['use_reactions'] == 'on') ? '1' : 0);

        Session::flash('admin_haberlers_settings', $haberler_language->get('haberler', 'settings_updated_successfully'));
    } else {
        // Invalid token
        Session::flash('admin_haberlers_settings', $language->get('general', 'invalid_token'));
    }
    Redirect::to(URL::build('/panel/haberlers/settings'));
}

// Retrieve Link Location from cache
$cache->setCache('nav_location');
$link_location = $cache->retrieve('haberler_location');

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

if (Session::exists('admin_haberlers_settings')) {
    $success = Session::flash('admin_haberlers_settings');
}

if (isset($success)) {
    $smarty->assign([
        'SUCCESS' => $success,
        'SUCCESS_TITLE' => $language->get('general', 'success')
    ]);
}

if (isset($errors) && count($errors)) {
    $smarty->assign([
        'ERRORS' => $errors,
        'ERRORS_TITLE' => $language->get('general', 'error')
    ]);
}

$smarty->assign([
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'HABERLER' => $haberler_language->get('haberler', 'haberler'),
    'SETTINGS' => $language->get('admin', 'settings'),
    'LINK_LOCATION' => $language->get('admin', 'page_link_location'),
    'LINK_LOCATION_VALUE' => $link_location,
    'LINK_NAVBAR' => $language->get('admin', 'page_link_navbar'),
    'LINK_MORE' => $language->get('admin', 'page_link_more'),
    'LINK_FOOTER' => $language->get('admin', 'page_link_footer'),
    'LINK_NONE' => $language->get('admin', 'page_link_none'),
    'USE_REACTIONS' => $haberler_language->get('haberler', 'use_reactions'),
    'USE_REACTIONS_VALUE' => Util::getSetting('haberler_reactions') === '1',
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit')
]);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('haberler/haberlers_settings.tpl', $smarty);
