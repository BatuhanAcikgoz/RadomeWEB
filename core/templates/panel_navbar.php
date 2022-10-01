<?php
/*
 *  Made by Reeignn
 *  https://github.com/Verira/RadomeWEB
 *  RadomeWEB v2.1
 *
 *  License: GPL-3.0
 *
 *  Panel navbar
 */

// Assign to Smarty variables
$smarty->assign([
    'SITE_NAME' => Output::getClean(SITE_NAME),
    'PANEL_INDEX' => URL::build('/panel'),
    'NAV_LINKS' => $staffcp_nav->returnNav('top'),
    'VIEW_SITE' => $language->get('admin', 'view_site'),
    'PAGE_LOAD_TIME' => PAGE_LOAD_TIME,
    'SUPPORT' => $language->get('admin', 'support'),
    'SOURCE' => $language->get('admin', 'source'),
    'NOTICES' => Core_Module::getNotices(),
    'NO_NOTICES' => $language->get('admin', 'no_notices'),
    'MODE_TOGGLE' => $language->get('admin', 'mode_toggle')
]);
