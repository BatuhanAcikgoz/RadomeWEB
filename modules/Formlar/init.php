<?php 
/*
 *  Made by Partydragen
 *  https://github.com/partydragen/Radome-Formlar
 *  RadomeWEB version 2.0.0-pr13
 *
 *  License: MIT
 *
 *  Formlar module initialisation file
 */
 
// Initialise forms language
$forms_language = new Language(ROOT_PATH . '/modules/Formlar/language', LANGUAGE);

require_once(ROOT_PATH . '/modules/Formlar/autoload.php');

// Initialise module
require_once(ROOT_PATH . '/modules/Formlar/module.php');
$module = new Formlar_Module($language, $forms_language, $pages, $user, $navigation, $cache, $endpoints);