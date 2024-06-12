<?php
/*
 *  Made by Partydragen
 *  https://github.com/partydragen/Radome-Formlar
 *  RadomeWEB version 2.1.2
 *
 *  License: MIT
 *
 *  Formlar module autoload file
 */

// Load classes
spl_autoload_register(function ($class) {
    $path = join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'modules', 'Formlar', 'classes', $class . '.php'));
    if (file_exists($path)) {
        require_once($path);
    }
});

// Load classes
spl_autoload_register(function ($class) {
    $path = join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'modules', 'Formlar', 'classes', 'Events', $class . '.php'));
    if (file_exists($path)) {
        require_once($path);
    }
});

// Load classes
spl_autoload_register(function ($class) {
    $path = join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'modules', 'Formlar', 'classes', 'SubmissionSources', $class . '.php'));
    if (file_exists($path)) {
        require_once($path);
    }
});

// Load classes
spl_autoload_register(function ($class) {
    $path = join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'modules', 'Formlar', 'hooks', $class . '.php'));
    if (file_exists($path)) {
        require_once($path);
    }
});