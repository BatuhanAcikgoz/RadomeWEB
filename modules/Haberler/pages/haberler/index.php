<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Haberler index page
 */

// Always define page name
const PAGE = 'haberler';
$page_title = $haberler_language->get('haberler', 'haberler');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

// Initialise
$haberler = new Haberler();
$timeago = new TimeAgo(TIMEZONE);

// Get user group IDs
$groups = $user->getAllGroupIds();

// Breadcrumbs and search bar - same for latest discussions view + table view
$smarty->assign('BREADCRUMB_URL', URL::build('/haberler'));
$smarty->assign('BREADCRUMB_TEXT', $haberler_language->get('haberler', 'haberler_index'));
// Search bar
$smarty->assign([
    'SEARCH_URL' => URL::build('/haberler/search'),
    'SEARCH' => $language->get('general', 'search'),
    'TOKEN' => Token::get()
]);

// Server status module
$smarty->assign('SERVER_STATUS', '');

// Check session
if (Session::exists('spam_info')) {
    $smarty->assign('SPAM_INFO', Session::flash('spam_info'));
}

// Assign language variables
$smarty->assign('HABERLERS_TITLE', $haberler_language->get('haberler', 'haberlers'));
$smarty->assign('DISCUSSION', $haberler_language->get('haberler', 'discussion'));
$smarty->assign('TOPIC', $haberler_language->get('haberler', 'topic'));
$smarty->assign('STATS', $haberler_language->get('haberler', 'stats'));
$smarty->assign('LAST_REPLY', $haberler_language->get('haberler', 'last_reply'));
$smarty->assign('BY', $haberler_language->get('haberler', 'by'));
$smarty->assign('IN', $haberler_language->get('haberler', 'in'));
$smarty->assign('VIEWS', $haberler_language->get('haberler', 'views'));
$smarty->assign('TOPICS', $haberler_language->get('haberler', 'topics'));
$smarty->assign('POSTS', $haberler_language->get('haberler', 'haberlers'));
$smarty->assign('STATISTICS', $haberler_language->get('haberler', 'statistics'));
$smarty->assign('OVERVIEW', $haberler_language->get('haberler', 'overview'));
$smarty->assign('LATEST_DISCUSSIONS_TITLE', $haberler_language->get('haberler', 'latest_discussions'));
$smarty->assign('NO_TOPICS', $haberler_language->get('haberler', 'no_topics_short'));

// Get haberlers
$cache_name = 'haberler_haberlers_' . rtrim(implode('-', $groups), '-');
$cache->setCache($cache_name);

if ($cache->isCached('haberlers')) {
    $haberlers = $cache->retrieve('haberlers');
} else {
    $haberlers = $haberler->listAllHaberlers($groups, ($user->isLoggedIn() ? $user->data()->id : 0));

    // Loop through to get last poster avatars and to format a date
    if (count($haberlers)) {
        foreach ($haberlers as $key => $item) {
            $haberlers[$key]['link'] = URL::build('/haberler/goruntule/' . urlencode($key) . '-' . $haberler->titleToURL($item['title']));
            if (isset($item['subhaberlers']) && count($item['subhaberlers'])) {
                foreach ($item['subhaberlers'] as $subhaber_id => $subhaberler) {
                    if (isset($subhaberler->last_post)) {
                        $last_post_user = new User($haberlers[$key]['subhaberlers'][$subhaber_id]->last_post->post_creator);

                        $haberlers[$key]['subhaberlers'][$subhaber_id]->last_post->avatar = $last_post_user->getAvatar(64);
                        $haberlers[$key]['subhaberlers'][$subhaber_id]->last_post->user_style = $last_post_user->getGroupStyle();
                        $haberlers[$key]['subhaberlers'][$subhaber_id]->last_post->username = $last_post_user->getDisplayname();
                        $haberlers[$key]['subhaberlers'][$subhaber_id]->last_post->profile = $last_post_user->getProfileURL();

                        if (is_null($haberlers[$key]['subhaberlers'][$subhaber_id]->last_post->created)) {
                            $haberlers[$key]['subhaberlers'][$subhaber_id]->last_post->date_friendly = $timeago->inWords($haberlers[$key]['subhaberlers'][$subhaber_id]->last_post->post_date, $language);
                            $haberlers[$key]['subhaberlers'][$subhaber_id]->last_post->post_date = date(DATE_FORMAT, strtotime($haberlers[$key]['subhaberlers'][$subhaber_id]->last_post->post_date));
                        } else {
                            $haberlers[$key]['subhaberlers'][$subhaber_id]->last_post->date_friendly = $timeago->inWords($haberlers[$key]['subhaberlers'][$subhaber_id]->last_post->created, $language);
                            $haberlers[$key]['subhaberlers'][$subhaber_id]->last_post->post_date = date(DATE_FORMAT, $haberlers[$key]['subhaberlers'][$subhaber_id]->last_post->created);
                        }
                    }

                    if ($haberlers[$key]['subhaberlers'][$subhaber_id]->redirect_haberler == 1 && URL::isExternalURL($haberlers[$key]['subhaberlers'][$subhaber_id]->redirect_url)) {
                        $haberlers[$key]['subhaberlers'][$subhaber_id]->redirect_confirm = $haberler_language->get('haberler', 'haberler_redirect_warning', ['url' => $haberlers[$key]['subhaberlers'][$subhaber_id]->redirect_to]);
                    }
                }
            }
        }
    } else {
        $haberlers = [];
    }

    $cache->store('haberlers', $haberlers, 60);
}

$smarty->assign('HABERLERS', $haberlers);
$smarty->assign('YES', $language->get('general', 'yes'));
$smarty->assign('NO', $language->get('general', 'no'));
$smarty->assign('SUBHABERLERS', $haberler_language->get('haberler', 'subhaberlers'));

$smarty->assign('HABERLER_INDEX_LINK', URL::build('/haberler'));

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$template->onPageLoad();

$smarty->assign('WIDGETS_LEFT', $widgets->getWidgets('left'));
$smarty->assign('WIDGETS_RIGHT', $widgets->getWidgets('right'));

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
$template->displayTemplate('haberler/haberler_index.tpl', $smarty);
