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

spl_autoload_register(function ($class) {
    $path = join(DIRECTORY_SEPARATOR, [ROOT_PATH, 'modules', 'Magaza', 'classes', 'DTO', $class . '.php']);
    if (file_exists($path)) {
        require_once($path);
    }
});



// Load classes
spl_autoload_register(function ($class) {
    $path = join(DIRECTORY_SEPARATOR, [ROOT_PATH, 'modules', 'Magaza', 'classes', $class . '.php']);
    if (file_exists($path)) {
        require_once($path);
    }
});

require_once(ROOT_PATH . '/modules/Magaza/module.php');
$module = new Magaza_Module($language, $store_language, $pages, $cache, $endpoints);