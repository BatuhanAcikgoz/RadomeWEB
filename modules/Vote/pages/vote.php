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
$mcmp_top_voters ="https://minecraft-mp.com/api/?object=servers&element=voters&key=rWYd7YHBqFyu6ZRfFrAMvoJBGLeIBIHBGhS&month=current&format=json&limit=5";
$vote_mcmp_top_voters = json_decode(file_get_contents($mcmp_top_voters));
$top_vote = $vote_mcmp_top_voters->top_voters;


// Get sites from database
$sites = DB::getInstance()->get("vote_sites", ["id", "<>", 0])->results();


$top_voters_array = [];
foreach ($top_vote as $top_voters) {
	$top_voters_array[] = [
		'nickname' => Output::getClean($top_voters->nickname),
		'votes' => Output::getClean($top_voters->votes),
	];
}

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
	'MESSAGE_ENABLED' => $message_enabled,
	'MESSAGE' => Output::getClean($vote_message),
	'SITES' => $sites_array,
	'VOTERS' => $voters_array,
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