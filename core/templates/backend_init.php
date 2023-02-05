<?php
/*
 *  Made by Reeignn
 *  https://github.com/Verira/RadomeWEB
 *  RadomeWEB v2.1
 *
 *  License: GPL-3.0
 *
 *  Backend template initialisation
 */

const BACK_END = true;

$template_path = ROOT_PATH . '/custom/panel_templates/' . PANEL_TEMPLATE;
$smarty->setCompileDir(ROOT_PATH . '/cache/templates_c');

if (file_exists(ROOT_PATH . '/custom/panel_templates/' . PANEL_TEMPLATE . '/template.php')) {
    $smarty->setTemplateDir(ROOT_PATH . '/custom/panel_templates/' . PANEL_TEMPLATE);
    require(ROOT_PATH . '/custom/panel_templates/' . PANEL_TEMPLATE . '/template.php');
} else {
    $smarty->setTemplateDir(ROOT_PATH . '/custom/panel_templates/Default');
    require(ROOT_PATH . '/custom/panel_templates/Default/template.php');
}

$cache->setCache('backgroundcache');
$logo_image = $cache->retrieve('logo_image');

if (!empty($logo_image)) {
    $smarty->assign('PANEL_LOGO_IMAGE', Output::getClean($logo_image));
}

$favicon_image = $cache->retrieve('favicon_image');

if (!empty($favicon_image)) {
    $smarty->assign('FAVICON', Output::getClean($favicon_image));
}

$smarty->assign('TITLE', $page_title);
