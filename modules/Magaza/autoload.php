<?php
/*
 *  Made by Partydragen
 *  https://partydragen.com/resources/resource/5-store-module/
 *  https://partydragen.com/
 *  RadomeWEB version 2.1.1
 *
 *  License: MIT
 *
 *  Magaza autoload file
 */

// Load classes
spl_autoload_register(function ($class) {
    $path = join(DIRECTORY_SEPARATOR, [ROOT_PATH, 'modules', 'Magaza', 'classes', $class . '.php']);
    if (file_exists($path)) {
        require_once($path);
    }
});

// Load classes
spl_autoload_register(function ($class) {
    $path = join(DIRECTORY_SEPARATOR, [ROOT_PATH, 'modules', 'Magaza', 'classes', 'DTO', $class . '.php']);
    if (file_exists($path)) {
        require_once($path);
    }
});

// Load classes
spl_autoload_register(function ($class) {
    $path = join(DIRECTORY_SEPARATOR, [ROOT_PATH, 'modules', 'Magaza', 'classes', 'Events', $class . '.php']);
    if (file_exists($path)) {
        require_once($path);
    }
});

// Load classes
spl_autoload_register(function ($class) {
    $path = join(DIRECTORY_SEPARATOR, [ROOT_PATH, 'modules', 'Magaza', 'classes', 'Tasks', $class . '.php']);
    if (file_exists($path)) {
        require_once($path);
    }
});

require_once(ROOT_PATH . '/modules/Magaza/hooks/CheckoutAddProductHook.php');
require_once(ROOT_PATH . '/modules/Magaza/hooks/PriceAdjustmentHook.php');
require_once(ROOT_PATH . '/modules/Magaza/hooks/ParseActionCommandListener.php');