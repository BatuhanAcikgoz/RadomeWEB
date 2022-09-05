<?php

/*
 * Wiki module made by reflexLabs 
 * reflexLabs.com
 */

$wiki_language = new Language(ROOT_PATH . '/modules/Wiki/language', LANGUAGE);

require_once(ROOT_PATH . '/modules/Wiki/module.php');
$module = new Wiki_Module($wiki_language, $pages);