<?php
/*
 *  Made by Reeignn
 *  https://github.com/Verira/RadomeWEB
 *  RadomeWEB v2.1
 *
 *  License: GPL-3.0
 *
 *  Haberler initialisation file
 */

// Ensure module has been installed
$cache->setCache('modulescache');
$module_installed = $cache->retrieve('module_haberler');
if (!$module_installed) {
    // Hasn't been installed
    // Need to run the installer

    $exists = DB::getInstance()->showTables('haberlers');
    if (empty($exists)) {
        die('Run the installer first!');
    }

    $cache->store('module_haberler', true);
}

const FORUM = true;

// Initialise haberler language
$haberler_language = new Language(ROOT_PATH . '/modules/Haberler/language');

/*
 *  Temp methods for front page module, profile page tab + admin sidebar; likely to change in the future
 */
// Front page module
if (!isset($front_page_modules)) {
    $front_page_modules = [];
}
$front_page_modules[] = 'modules/Haberler/front_page.php';

// Profile page tab
if (!isset($profile_tabs)) {
    $profile_tabs = [];
}
$profile_tabs['haberler'] = ['title' => $haberler_language->get('haberler', 'haberler'), 'smarty_template' => 'haberler/profile_tab.tpl', 'require' => ROOT_PATH . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'Haberler' . DIRECTORY_SEPARATOR . 'profile_tab.php'];

// Following habers UserCP sidebar
$cc_nav->add('cc_following_habers', $haberler_language->get('haberler', 'following_habers'), URL::build('/kullanici/takip_edilen_konular'));

// Initialise module
require_once(ROOT_PATH . '/modules/Haberler/module.php');
$module = new Haberler_Module($language, $haberler_language, $pages);
