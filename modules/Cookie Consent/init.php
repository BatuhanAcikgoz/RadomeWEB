<?php
/*
 *  Made by Reeignn
 *  https://github.com/Verira/RadomeWEB
 *  RadomeWEB v2.1
 *
 *  License: GPL-3.0
 *
 *  Cookie Consent initialisation file
 */

require_once ROOT_PATH . '/modules/Cookie Consent/module.php';

$cookie_language = new Language(ROOT_PATH . '/modules/Cookie Consent/language');

$module = new CookieConsent_Module($language, $cookie_language, $pages);
