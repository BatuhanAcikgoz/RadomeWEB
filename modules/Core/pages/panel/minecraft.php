<?php
/*
 *  Made by Reeignn
 *  https://github.com/Verira/RadomeWEB
 *  RadomeWEB v2.1
 *
 *  License: GPL-3.0
 *
 *  Panel Minecraft page
 */

if (!$user->handlePanelPageLoad('admincp.minecraft')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'integrations';
const PANEL_PAGE = 'minecraft';
$page_title = $language->get('admin', 'minecraft');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

if (Input::exists()) {
    // Check token
    if (Token::check()) {
        // Valid token
        // Process input
        if (isset($_POST['enable_minecraft'])) {
            // Either enable or disable Minecraft integration
            Settings::set(Settings::MINECRAFT_INTEGRATION, Input::get('enable_minecraft'));
        }

    } else {
        // Invalid token
        $errors = [$language->get('general', 'invalid_token')];

    }
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

if (isset($success)) {
    $smarty->assign([
        'SUCCESS' => $success,
        'SUCCESS_TITLE' => $language->get('general', 'success')
    ]);
}

if (isset($errors) && count($errors)) {
    $smarty->assign([
        'ERRORS' => $errors,
        'ERRORS_TITLE' => $language->get('general', 'error')
    ]);
}

// Check if Minecraft integration is enabled
$minecraft_enabled = Settings::get(Settings::MINECRAFT_INTEGRATION);

$smarty->assign([
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'INTEGRATIONS' => $language->get('admin', 'integrations'),
    'MINECRAFT' => $language->get('admin', 'minecraft'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
    'ENABLE_MINECRAFT_INTEGRATION' => $language->get('admin', 'enable_minecraft_integration'),
    'MINECRAFT_ENABLED' => $minecraft_enabled
]);

if ($minecraft_enabled == 1) {

    if ($user->hasPermission('admincp.minecraft.servers')) {
        $smarty->assign([
            'SERVERS' => $language->get('admin', 'minecraft_servers'),
            'SERVERS_LINK' => URL::build('/panel/minecraft/sunucular')
        ]);
    }

    if ($user->hasPermission('admincp.minecraft.banners') && function_exists('exif_imagetype')) {
        $smarty->assign([
            'BANNERS' => $language->get('admin', 'server_banners'),
            'BANNERS_LINK' => URL::build('/panel/minecraft/bannerlar')
        ]);
    }

    if ($user->hasPermission('admincp.core.placeholders')) {
        $smarty->assign([
            'PLACEHOLDERS' => $language->get('admin', 'placeholders'),
            'PLACEHOLDERS_LINK' => URL::build('/panel/minecraft/placeholderlar')
        ]);
    }

    if ($user->hasPermission('admincp.core.servers')) {
        $smarty->assign([
            'TIER_LIST' => $language->get('admin', 'tier_list'),
            'TIER_LIST_LINK' => URL::build('/panel/minecraft/tier_list')
        ]);
    }
}

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('integrations/minecraft/minecraft.tpl', $smarty);
