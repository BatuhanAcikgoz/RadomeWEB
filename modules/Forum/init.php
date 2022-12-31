<?php
/*
 *  Made by Reeignn
 *  https://github.com/Verira/RadomeWEB
 *  RadomeWEB v2.1
 *
 *  License: GPL-3.0
 *
 *  Forum initialisation file
 */

// Ensure module has been installed
$cache->setCache('modulescache');
$module_installed = $cache->retrieve('module_forum');
if (!$module_installed) {
    // Hasn't been installed
    // Need to run the installer

    $exists = DB::getInstance()->showTables('forums');
    if (empty($exists)) {
        die('Run the installer first!');
    }

    $cache->store('module_forum', true);
}

const FORUM = true;

// Initialise forum language
$forum_language = new Language(ROOT_PATH . '/modules/Forum/language');

// Profile page tab
if (!isset($profile_tabs)) {
    $profile_tabs = [];
}
$profile_tabs['forum'] = ['title' => $forum_language->get('forum', 'forum'), 'smarty_template' => 'forum/profile_tab.tpl', 'require' => ROOT_PATH . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'Forum' . DIRECTORY_SEPARATOR . 'profile_tab.php'];

// Following topics UserCP sidebar
$cc_nav->add('cc_following_topics', $forum_language->get('forum', 'following_topics'), URL::build('/kullanici/takip_edilen_konular'));

// Initialise module
require_once(ROOT_PATH . '/modules/Forum/module.php');
$module = new Forum_Module($language, $forum_language, $pages);
