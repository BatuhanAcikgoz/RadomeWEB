<?php
/*
 *  Made by Samerton
 *  https://github.com/RadomeWEB/Radome/tree/v2/
 *  RadomeWEB version 2.0.0-pr7
 *
 *  License: MIT
 *
 *  Iframe By VertisanPRO
 */

$INFO_MODULE = [
    'name' => 'Iframe',
    'author' => '<a href="https://batuhanacikgoz.com.tr" target="_blank" rel="nofollow noopener">Reeignn</a>',
    'module_ver' => '1.3.0',
    'rw_ver' => '3.0.0',
];

$IframeLanguage = new Language(ROOT_PATH . '/modules/' . $INFO_MODULE['name'] . '/language', LANGUAGE);

$GLOBALS['IframeLanguage'] = $IframeLanguage;

require_once(ROOT_PATH . '/modules/' . $INFO_MODULE['name'] . '/module.php');

$module = new Iframe($language, $pages, $INFO_MODULE);
