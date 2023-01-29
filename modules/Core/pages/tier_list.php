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

const PAGE = 'tier_list';
$page_title = $language->get('admin', 'tiler_list');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

$leaderboard_placeholders_data = [];
$leaderboard_users = [];

$timeago = new TimeAgo(TIMEZONE);

foreach ($tier_list_db as $leaderboard_placeholder) {
    // Get all rows from user placeholder table with this placeholders server id + name
    $lt1 = "4";
    $data = DB::getInstance()->query("SELECT rw_users.id, rw_users.username, rw_users_groups.group_id FROM rw_users LEFT JOIN rw_users_groups ON rw_users.id = rw_users_groups.user_id WHERE group_id = ?", [$lt1])->results();

    
    if (!count($data)) {
        continue;
    }

    // TODO: move this to placeholders class
    foreach ($data as $rowlt1) {
        $rowlt1 = [
            'username' => $data[0]->$username,
            'group_id' => $data[0]->$group_id,
            'avatar' => $data[0]->$username,
        ];
    }
}

$smarty->assign([
    'PLAYER' => $language->get('admin', 'placeholders_player'),
    'SCORE' => $language->get('admin', 'placeholders_score'),
    'LAST_UPDATED' => $language->get('admin', 'placeholders_last_updated'),
    'LEADERBOARDS' => $language->get('general', 'leaderboards'),
    'LEADERBOARD_PLACEHOLDERS' => $tier_list_db,
    'LEADERBOARD_PLACEHOLDERS_DATA' => $leaderboard_placeholders_data,
    'ROWLT1' => $rowlt1
]);

$template->addJSScript('
    window.onLoad = showTable(null, null, true);

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