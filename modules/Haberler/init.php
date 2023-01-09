<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
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

    $cache->store('module_haberler', true);
}

const HABERLER = true;

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

// Following topics UserCP sidebar
$cc_nav->add('cc_following_topics', $haberler_language->get('haberler', 'following_topics'), URL::build('/user/following_topics'));

// Initialise module
require_once(ROOT_PATH . '/modules/Haberler/module.php');
$module = new Haberler_Module($language, $haberler_language, $pages);
