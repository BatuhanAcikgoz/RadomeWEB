<?php
/*
 *	Made by Partydragen
 *  https://github.com/partydragen/Vote-Module
 *  https://partydragen.com
 *  RadomeWEB version 2.0.0-pr13
 *
 *  License: MIT
 */

// Always define page name
define('PAGE', 'vote');
$page_title = $vote_language->get('vote', 'vote');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

// Get message
$vote_message = DB::getInstance()->get("vote_settings", ["name", "=", "vote_message"])->results();
$vote_message = $vote_message[0]->value;

// Is vote message empty?
if (!empty($vote_message)) {
	$message_enabled = true;
}
$mcmp_key = DB::getInstance()->get('vote_settings', ['name', '=', "mcmp_key"])->results();
$mcmp_key = $mcmp_key[0]->value;
$minecraftmp_top_voters = 'https://minecraft-mp.com/api/?object=servers&element=voters&key='.$mcmp_key.'&month=current&format=json&limit=5';
$mcmp_vote1 = json_decode(file_get_contents($minecraftmp_top_voters));
$top_voters = $mcmp_vote1->voters;

$voters_array = [];
foreach ($top_voters as $top_voters) {
	$voters_array[] = [
		'nickname' => Output::getClean($top_voters->nickname),
		'votes' => Output::getClean($top_voters->votes),
	];
}

$minecraftmp_votes = 'https://minecraft-mp.com/api/?object=servers&element=votes&key='.$mcmp_key.'&format=json';
$mcmp_vote2 = json_decode(file_get_contents($minecraftmp_votes));
$votes_mcmp = $mcmp_vote2->votes;
$timeago = new TimeAgo(TIMEZONE);
$votes_array = [];
foreach ($votes_mcmp as $mcmp_votes) {
	$votes_array[] = [
		'nickname' => Output::getClean($mcmp_votes->nickname),
		'date_friendly' => $timeago->inWords($mcmp_votes->timestamp, $language),
	];
}

$search_value = $_GET["search"];

    $sResults2 = 'https://minecraft-mp.com/api/?object=votes&element=claim&key='.$mcmp_key.'&username='.$search_value;
	$sResults = json_decode(file_get_contents($sResults2));


// Get sites from database
$sites = DB::getInstance()->get("vote_sites", ["id", "<>", 0])->results();


$sites_array = [];
foreach ($sites as $site) {
    $sites_array[] = [
        'name' => Output::getClean($site->name),
        'url' => Output::getClean($site->site),
    ];
}

// Assign Smarty variables
$smarty->assign([
	'VOTE_TITLE' => $vote_language->get('vote', 'vote'),
	'USERNAME' => $language->get('user', 'username'),
	'VOTES' => $vote_language->get('vote', 'votes'),
	'TOP_VOTERS' => $vote_language->get('vote', 'top_voters_header'),
	'LAST_VOTERS' => $vote_language->get('vote', 'last_voters'),
	'SEARCH_RESULT' => $search_value,
    'SEARCH_RESULTS' => $sResults,
	'DATE' => $vote_language->get('vote', 'date'),
	'MESSAGE_ENABLED' => $message_enabled,
	'MESSAGE' => Output::getClean($vote_message),
	'SITES' => $sites_array,
	'MCMP_TOP_VOTERS' => $voters_array,
	'MCMP_VOTES' => $votes_array,
]);

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$template->onPageLoad();

$smarty->assign('WIDGETS_LEFT', $widgets->getWidgets('left'));
$smarty->assign('WIDGETS_RIGHT', $widgets->getWidgets('right'));

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
$template->displayTemplate('vote.tpl', $smarty);