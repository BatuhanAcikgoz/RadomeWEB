<?php
/*
 *  Made by Partydragen
 *  https://partydragen.com/resources/resource/5-store-module/
 *  https://partydragen.com/
 *  RadomeWEB version 2.0.0-pr13
 *
 *  License: MIT
 *
 *  Magaza initialisation file
 */

// Language
$store_language = new Language(ROOT_PATH . '/modules/Magaza/language', LANGUAGE);

require_once(ROOT_PATH . '/modules/Magaza/autoload.php');

require_once(ROOT_PATH . '/modules/Magaza/module.php');
$module = new Magaza_Module($language, $store_language, $pages, $cache, $endpoints);