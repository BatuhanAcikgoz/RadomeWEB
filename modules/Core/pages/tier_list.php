<?php
/*
 *  Made by Aberdeener
 *  https://github.com/RadomeWEB/Radome/
 *  RadomeWEB version 2.0.0-pr13
 *
 *  License: MIT
 *
 *  Leaderboards page
 */

$tier_list_db = DB::getInstance()->query("SELECT * FROM rw_tier_list")->results();

if (!count($tier_list_db)) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

// Placeholders enabled?
if (Util::getSetting('tier_list_page') !== '1') {
    require_once(ROOT_PATH . '/404.php');
    die();
}

const PAGE = 'leaderboards';
$page_title = $language->get('admin', 'tier_list');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

$tier_list_lt1_data = [];
$tier_list_lt2_data = [];
$tier_list_lt3_data = [];
$tier_list_lt4_data = [];
$tier_list_lt5_data = [];

$tier_list_ht1_data = [];
$tier_list_ht2_data = [];
$tier_list_ht3_data = [];
$tier_list_ht4_data = [];
$tier_list_ht5_data = [];


$smarty->assign([
    'PLAYER' => $language->get('admin', 'placeholders_player'),
    'SCORE' => $language->get('admin', 'placeholders_score'),
    'LAST_UPDATED' => $language->get('admin', 'placeholders_last_updated'),
    'LEADERBOARDS' => $language->get('general', 'leaderboards'),
    'LEADERBOARD_PLACEHOLDERS' => $tier_list_db,
    'LEADERBOARD_PLACEHOLDERS_DATA' => $leaderboard_placeholders_data,
    'TIER_LIST_LT1_DATA' => $tier_list_lt1_data,
    'TIER_LIST_LT2_DATA' => $tier_list_lt2_data,
    'TIER_LIST_LT3_DATA' => $tier_list_lt3_data,
    'TIER_LIST_LT4_DATA' => $tier_list_lt4_data,
    'TIER_LIST_LT5_DATA' => $tier_list_lt5_data,
    'TIER_LIST_HT1_DATA' => $tier_list_ht1_data,
    'TIER_LIST_HT2_DATA' => $tier_list_ht2_data,
    'TIER_LIST_HT3_DATA' => $tier_list_ht3_data,
    'TIER_LIST_HT4_DATA' => $tier_list_ht4_data,
    'TIER_LIST_HT5_DATA' => $tier_list_ht5_data
]);

$template->addJSScript('
    window.onLoad = showTable(null, true);

    function showTable(name, first = false) {

        if (name === null) {
            name = $(".leaderboard_tab").first().attr("name");
        }

        if (!first) {
            disableTabs();
            hideTables();
        }

        $("#tab-" + name).addClass("active");
        $("#table-" + name).show();
    }

    function disableTabs() {
        $(".leaderboard_tab").each(function(i, e) {
            $(e).removeClass("active");
        });
    }

    function hideTables() {
        $(".leaderboard_table").each(function(i, e) {
            $(e).hide();
        });
    }
');

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
$template->displayTemplate('tier_list.tpl', $smarty);