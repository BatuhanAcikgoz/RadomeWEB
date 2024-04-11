<?php
/*
 *  Made by Reeignn
 *  https://github.com/Verira/RadomeWEB
 *  RadomeWEB v2.1
 *
 *  License: GPL-3.0
 *
 *  Core initialisation file
 */

// Ensure module has been installed
$module_installed = $cache->retrieve('module_core');
if (!$module_installed) {
    // Hasn't been installed
    // Need to run the installer
    die('Run the installer first!');
}

require_once ROOT_PATH . '/modules/Core/includes/constants/constants.php';
require_once ROOT_PATH . '/modules/Core/module.php';

$module = new Core_Module($language, $pages, $user, $navigation, $cache, $endpoints);
