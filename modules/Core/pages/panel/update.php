<?php
/*
 *  Made by Reeignn
 *  https://github.com/Verira/RadomeWEB
 *  RadomeWEB v2.1
 *
 *  License: GPL-3.0
 *
 *  Panel update page
 */

if (!$user->handlePanelPageLoad('admincp.update')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

if (isset($_GET['recheck'])) {
    $cache->setCache('update_check');
    if ($cache->isCached('update_check')) {
        $cache->erase('update_check');
    }

    Redirect::to(URL::build('/panel/guncelleme'));
}

const PAGE = 'panel';
const PARENT_PAGE = 'update';
const PANEL_PAGE = 'update';
$page_title = $language->get('admin', 'update');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

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


// PHP version check
if (PHP_VERSION_ID < 70400) {
    $smarty->assign('PHP_WARNING', $language->get('admin', 'upgrade_php_version'));
}

$smarty->assign([
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'UPDATE' => $language->get('admin', 'update'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
    'UP_TO_DATE' => $language->get('admin', 'up_to_date'),
    'CHECK_AGAIN' => $language->get('admin', 'check_again'),
    'CHECK_AGAIN_LINK' => URL::build('/panel/guncelleme/', 'recheck=true'),
    'WARNING' => $language->get('general', 'warning'),
    'CANCEL' => $language->get('general', 'cancel'),
]);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate('core/update.tpl', $smarty);
