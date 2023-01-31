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
$page_title = $language->get('admin', 'tier_list');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

if (!$cache->isCached('tier_list_db')) {

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

    foreach ($tier_list_db as $leaderboard_placeholder) {
        // Get all rows from user placeholder table with this placeholders server id + name

        $datalt1 = DB::getInstance()->query("SELECT rw_users.id, rw_users_groups.group_id, rw_tier_list.name, rw_tier_list.lt1 FROM rw_users JOIN rw_tier_list LEFT JOIN rw_users_groups ON rw_users.id = rw_users_groups.user_id LEFT JOIN rw_groups ON rw_groups.id = rw_tier_list.lt1 WHERE rw_users_groups.group_id = ? AND rw_tier_list.name = ? AND rw_tier_list.lt1 = ?", [$leaderboard_placeholder->lt1, $leaderboard_placeholder->name, $leaderboard_placeholder->lt1])->results();
        $datalt2 = DB::getInstance()->query("SELECT rw_users.id, rw_users_groups.group_id, rw_tier_list.name, rw_tier_list.lt2 FROM rw_users JOIN rw_tier_list LEFT JOIN rw_users_groups ON rw_users.id = rw_users_groups.user_id LEFT JOIN rw_groups ON rw_groups.id = rw_tier_list.lt2 WHERE rw_users_groups.group_id = ? AND rw_tier_list.name = ? AND rw_tier_list.lt2 = ?", [$leaderboard_placeholder->lt2, $leaderboard_placeholder->name, $leaderboard_placeholder->lt2])->results();
        $datalt3 = DB::getInstance()->query("SELECT rw_users.id, rw_users_groups.group_id, rw_tier_list.name, rw_tier_list.lt3 FROM rw_users JOIN rw_tier_list LEFT JOIN rw_users_groups ON rw_users.id = rw_users_groups.user_id LEFT JOIN rw_groups ON rw_groups.id = rw_tier_list.lt3 WHERE rw_users_groups.group_id = ? AND rw_tier_list.name = ? AND rw_tier_list.lt2 = ?", [$leaderboard_placeholder->lt3, $leaderboard_placeholder->name, $leaderboard_placeholder->lt3])->results();
        $datalt4 = DB::getInstance()->query("SELECT rw_users.id, rw_users_groups.group_id, rw_tier_list.name, rw_tier_list.lt4 FROM rw_users JOIN rw_tier_list LEFT JOIN rw_users_groups ON rw_users.id = rw_users_groups.user_id LEFT JOIN rw_groups ON rw_groups.id = rw_tier_list.lt4 WHERE rw_users_groups.group_id = ? AND rw_tier_list.name = ? AND rw_tier_list.lt3 = ?", [$leaderboard_placeholder->lt4, $leaderboard_placeholder->name, $leaderboard_placeholder->lt4])->results();
        $datalt5 = DB::getInstance()->query("SELECT rw_users.id, rw_users_groups.group_id, rw_tier_list.name, rw_tier_list.lt5 FROM rw_users JOIN rw_tier_list LEFT JOIN rw_users_groups ON rw_users.id = rw_users_groups.user_id LEFT JOIN rw_groups ON rw_groups.id = rw_tier_list.lt5 WHERE rw_users_groups.group_id = ? AND rw_tier_list.name = ? AND rw_tier_list.lt4 = ?", [$leaderboard_placeholder->lt5, $leaderboard_placeholder->name, $leaderboard_placeholder->lt5])->results();

        $dataht1 = DB::getInstance()->query("SELECT rw_users.id, rw_users_groups.group_id, rw_tier_list.name, rw_tier_list.ht1 FROM rw_users JOIN rw_tier_list LEFT JOIN rw_users_groups ON rw_users.id = rw_users_groups.user_id LEFT JOIN rw_groups ON rw_groups.id = rw_tier_list.ht1 WHERE rw_users_groups.group_id = ? AND rw_tier_list.name = ? AND rw_tier_list.ht1 = ?", [$leaderboard_placeholder->ht1, $leaderboard_placeholder->name, $leaderboard_placeholder->ht1])->results();
        $dataht2 = DB::getInstance()->query("SELECT rw_users.id, rw_users_groups.group_id, rw_tier_list.name, rw_tier_list.ht2 FROM rw_users JOIN rw_tier_list LEFT JOIN rw_users_groups ON rw_users.id = rw_users_groups.user_id LEFT JOIN rw_groups ON rw_groups.id = rw_tier_list.ht2 WHERE rw_users_groups.group_id = ? AND rw_tier_list.name = ? AND rw_tier_list.ht2 = ?", [$leaderboard_placeholder->ht2, $leaderboard_placeholder->name, $leaderboard_placeholder->ht2])->results();
        $dataht3 = DB::getInstance()->query("SELECT rw_users.id, rw_users_groups.group_id, rw_tier_list.name, rw_tier_list.ht3 FROM rw_users JOIN rw_tier_list LEFT JOIN rw_users_groups ON rw_users.id = rw_users_groups.user_id LEFT JOIN rw_groups ON rw_groups.id = rw_tier_list.ht3 WHERE rw_users_groups.group_id = ? AND rw_tier_list.name = ? AND rw_tier_list.ht3 = ?", [$leaderboard_placeholder->ht3, $leaderboard_placeholder->name, $leaderboard_placeholder->ht3])->results();
        $dataht4 = DB::getInstance()->query("SELECT rw_users.id, rw_users_groups.group_id, rw_tier_list.name, rw_tier_list.ht4 FROM rw_users JOIN rw_tier_list LEFT JOIN rw_users_groups ON rw_users.id = rw_users_groups.user_id LEFT JOIN rw_groups ON rw_groups.id = rw_tier_list.ht4 WHERE rw_users_groups.group_id = ? AND rw_tier_list.name = ? AND rw_tier_list.ht4 = ?", [$leaderboard_placeholder->ht4, $leaderboard_placeholder->name, $leaderboard_placeholder->ht4])->results();
        $dataht5 = DB::getInstance()->query("SELECT rw_users.id, rw_users_groups.group_id, rw_tier_list.name, rw_tier_list.ht5 FROM rw_users JOIN rw_tier_list LEFT JOIN rw_users_groups ON rw_users.id = rw_users_groups.user_id LEFT JOIN rw_groups ON rw_groups.id = rw_tier_list.ht5 WHERE rw_users_groups.group_id = ? AND rw_tier_list.name = ? AND rw_tier_list.ht5 = ?", [$leaderboard_placeholder->ht5, $leaderboard_placeholder->name, $leaderboard_placeholder->ht5])->results();


        // TODO: move this to placeholders class
        foreach ($datalt1 as $rowlt1) {
            $row_datalt1 = new stdClass();
            $tier_user = new User($rowlt1->id);
            $row_datalt1->user_id = $rowlt1->id;
            $row_datalt1->name = $leaderboard_placeholder->name;
            $row_datalt1->username = $tier_user->getDisplayname(true);
            $row_datalt1->avatar = $tier_user->getAvatar(32);
            $row_datalt1->profile_url = $tier_user->getProfileURL();

            $tier_list_lt1_data[] = $row_datalt1;
        }

        foreach ($datalt2 as $rowlt2) {
            $row_datalt2 = new stdClass();
            $tier_user = new User($rowlt2->id);
            $row_datalt2->user_id = $rowlt2->id;
            $row_datalt2->name = $leaderboard_placeholder->name;
            $row_datalt2->username = $tier_user->getDisplayname(true);
            $row_datalt2->avatar = $tier_user->getAvatar(32);
            $row_datalt2->profile_url = $tier_user->getProfileURL();

            $tier_list_lt2_data[] = $row_datalt2;
        }

        foreach ($datalt3 as $rowlt3) {
            $row_datalt3 = new stdClass();
            $tier_user = new User($rowlt3->id);
            $row_datalt3->user_id = $rowlt3->id;
            $row_datalt3->name = $leaderboard_placeholder->name;
            $row_datalt3->username = $tier_user->getDisplayname(true);
            $row_datalt3->avatar = $tier_user->getAvatar(32);
            $row_datalt3->profile_url = $tier_user->getProfileURL();

            $tier_list_lt3_data[] = $row_datalt3;
        }

        foreach ($datalt4 as $rowlt4) {
            $row_datalt4 = new stdClass();
            $tier_user = new User($rowlt4->id);
            $row_datalt4->user_id = $rowlt4->id;
            $row_datalt4->name = $leaderboard_placeholder->name;
            $row_datalt4->username = $tier_user->getDisplayname(true);
            $row_datalt4->avatar = $tier_user->getAvatar(32);
            $row_datalt4->profile_url = $tier_user->getProfileURL();

            $tier_list_lt4_data[] = $row_datalt4;
        }

        foreach ($datalt5 as $rowlt5) {
            $row_datalt5 = new stdClass();
            $tier_user = new User($rowlt5->id);
            $row_datalt5->user_id = $rowlt5->id;
            $row_datalt5->name = $leaderboard_placeholder->name;
            $row_datalt5->username = $tier_user->getDisplayname(true);
            $row_datalt5->avatar = $tier_user->getAvatar(32);
            $row_datalt5->profile_url = $tier_user->getProfileURL();

            $tier_list_lt5_data[] = $row_datalt5;
        }

        foreach ($dataht1 as $rowht1) {
            $row_dataht1 = new stdClass();
            $tier_user = new User($rowht1->id);
            $row_dataht1->user_id = $rowht1->id;
            $row_dataht1->name = $leaderboard_placeholder->name;
            $row_dataht1->username = $tier_user->getDisplayname(true);
            $row_dataht1->avatar = $tier_user->getAvatar(32);
            $row_dataht1->profile_url = $tier_user->getProfileURL();

            $tier_list_ht1_data[] = $row_dataht1;
        }

        foreach ($dataht2 as $rowht2) {
            $row_dataht2 = new stdClass();
            $tier_user = new User($rowht2->id);
            $row_dataht2->user_id = $rowht2->id;
            $row_dataht2->name = $leaderboard_placeholder->name;
            $row_dataht2->username = $tier_user->getDisplayname(true);
            $row_dataht2->avatar = $tier_user->getAvatar(32);
            $row_dataht2->profile_url = $tier_user->getProfileURL();

            $tier_list_ht2_data[] = $row_dataht2;
        }

        foreach ($dataht3 as $rowht3) {
            $row_dataht3 = new stdClass();
            $tier_user = new User($rowht3->id);
            $row_dataht3->user_id = $rowht3->id;
            $row_dataht3->name = $leaderboard_placeholder->name;
            $row_dataht3->username = $tier_user->getDisplayname(true);
            $row_dataht3->avatar = $tier_user->getAvatar(32);
            $row_dataht3->profile_url = $tier_user->getProfileURL();

            $tier_list_ht3_data[] = $row_dataht3;
        }

        foreach ($dataht4 as $rowht4) {
            $row_dataht4 = new stdClass();
            $tier_user = new User($rowht4->id);
            $row_dataht4->user_id = $rowht4->id;
            $row_dataht4->name = $leaderboard_placeholder->name;
            $row_dataht4->username = $tier_user->getDisplayname(true);
            $row_dataht4->avatar = $tier_user->getAvatar(32);
            $row_dataht4->profile_url = $tier_user->getProfileURL();

            $tier_list_ht4_data[] = $row_dataht4;
        }

        foreach ($dataht5 as $rowht5) {
            $row_dataht5 = new stdClass();
            $tier_user = new User($rowht5->id);
            $row_dataht5->user_id = $rowht5->id;
            $row_dataht5->name = $leaderboard_placeholder->name;
            $row_dataht5->username = $tier_user->getDisplayname(true);
            $row_dataht5->avatar = $tier_user->getAvatar(32);
            $row_dataht5->profile_url = $tier_user->getProfileURL();

            $tier_list_ht5_data[] = $row_dataht5;
        }
    }

    $cache->store('tier_list_db', $tier_list_db, 120);
} else {
    $leaderboards_order = $cache->retrieve('tier_list_db');
}

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
