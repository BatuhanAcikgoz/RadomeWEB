<?php 
/*
 *	Made by Samerton and Partydragen
 *  https://github.com/samerton/Radome-Cezalar
 *  RadomeWEB version 2.0.0-pr13
 *
 *  License: MIT
 *
 *  Cezalar initialisation file
 */
 
 // Initialise infractions language
$infractions_language = new Language(ROOT_PATH . '/modules/Cezalar/language', LANGUAGE);

// Initialise module
require_once(ROOT_PATH . '/modules/Cezalar/module.php');
$module = new Cezalar_Module($language, $infractions_language, $pages);
