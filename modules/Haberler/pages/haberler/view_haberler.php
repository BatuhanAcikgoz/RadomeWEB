<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  View haberler page
 */

// Always define page name
const PAGE = 'haberler';

$haberler = new Haberler();
$timeago = new TimeAgo(TIMEZONE);

// Get user group ID
$user_groups = $user->getAllGroupIds();

// Get data from the database
$haberler_query = DB::getInstance()->get('haberlers', ['deleted', 0])->results();
$haberler_query = $haberler_query[0];

// Get page
if (isset($_GET['p'])) {
    if (!is_numeric($_GET['p'])) {
        Redirect::to(URL::build('/haberler'));
    }

    if ($_GET['p'] == 1) {
        // Avoid bug in pagination class
        Redirect::to(URL::build('/haberler/goruntule/' . urlencode($fid) . '-' . $haberler->titleToURL($haberler_query->haberler_title)));
    }
    $p = $_GET['p'];
} else {
    $p = 1;
}

$page_metadata = DB::getInstance()->get('page_descriptions', ['page', '/haberler/goruntule'])->results();
if (count($page_metadata)) {

    define('PAGE_DESCRIPTION', str_replace(
        ['{site}', '{haberler_title}', '{page}', '{description}'],
        [Output::getClean(SITE_NAME), Output::getClean($haberler_query->haberler_title), Output::getClean($p), Output::getClean($haberler_query->haberler_description)],
        $page_metadata[0]->description
    ));

    define('PAGE_KEYWORDS', $page_metadata[0]->tags);
}

$page_title = $haberler_language->get('haberler', 'haberler');
$page_title .= ' - ' . $language->get('general', 'page_x', ['page' => $p]);
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

// Redirect haberler?
if ($haberler_query->redirect_haberler == 1) {
    if (!URL::isExternalURL($haberler_query->redirect_url)) {
        Redirect::to(Output::getClean($haberler_query->redirect_url));
    }

    $smarty->assign([
        'CONFIRM_REDIRECT' => $haberler_language->get('haberler', 'haberler_redirect_warning', ['url' => Output::getClean($haberler_query->redirect_url)]),
        'YES' => $language->get('general', 'yes'),
        'NO' => $language->get('general', 'no'),
        'REDIRECT_URL' => Output::getClean($haberler_query->redirect_url),
        'HABERLER_INDEX' => URL::build('/haberler')
    ]);

    // Load modules + template
    Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

    $template->onPageLoad();

    $smarty->assign('WIDGETS_LEFT', $widgets->getWidgets('left'));
    $smarty->assign('WIDGETS_RIGHT', $widgets->getWidgets('right'));

    require(ROOT_PATH . '/core/templates/navbar.php');
    require(ROOT_PATH . '/core/templates/footer.php');

    // Display template
    $template->displayTemplate('haberler/view_haberler_confirm_redirect.tpl', $smarty);
} else {
    // Get all topics
    if ($user->isLoggedIn()) {
        $user_id = $user->data()->id;
    } else {
        $user_id = 0;
    }

    // Search bar
    $smarty->assign([
        'SEARCH_URL' => URL::build('/haberler/search'),
        'SEARCH' => $language->get('general', 'search'),
        'TOKEN' => Token::get()
    ]);

    // Breadcrumbs and search bar - same for latest discussions view + table view
    $parent_category = DB::getInstance()->get('haberlers', ['id', $haberler_query->parent])->results();
    $breadcrumbs = [0 => [
        'id' => $haberler_query->id,
        'haberler_title' => Output::getClean($haberler_query->haberler_title),
        'active' => 1,
        'link' => URL::build('/haberler/goruntule/' . urlencode($haberler_query->id) . '-' . $haberler->titleToURL($haberler_query->haberler_title))
    ]];
    if (!empty($parent_category) && $parent_category[0]->parent == 0) {
        // Category
        $breadcrumbs[] = [
            'id' => $parent_category[0]->id,
            'haberler_title' => Output::getClean($parent_category[0]->haberler_title),
            'link' => URL::build('/haberler/goruntule/' . urlencode($parent_category[0]->id) . '-' . $haberler->titleToURL($parent_category[0]->haberler_title))
        ];
    } else {
        if (!empty($parent_category)) {
            // Parent haberler, get its category
            $breadcrumbs[] = [
                'id' => $parent_category[0]->id,
                'haberler_title' => Output::getClean($parent_category[0]->haber_title),
                'link' => URL::build('/haberler/goruntule/' . urlencode($parent_category[0]->id) . '-' . $haberler->titleToURL($parent_category[0]->haber_title))
            ];
            $parent = false;
            while ($parent == false) {
                $parent_category = DB::getInstance()->get('haberlers', ['id', $parent_category[0]->parent])->results();
                $breadcrumbs[] = [
                    'id' => $parent_category[0]->id,
                    'haberler_title' => Output::getClean($parent_category[0]->haber_title),
                    'link' => URL::build('/haberler/goruntule/' . urlencode($parent_category[0]->id) . '-' . $haberler->titleToURL($parent_category[0]->haber_title))
                ];
                if ($parent_category[0]->parent == 0) {
                    $parent = true;
                }
            }
        }
    }

    $breadcrumbs[] = [
        'id' => 'index',
        'haberler_title' => $haberler_language->get('haberler', 'haberler_index'),
        'link' => URL::build('/haberler')
    ];

    $smarty->assign('BREADCRUMBS', array_reverse($breadcrumbs));

    // Server status module
    $smarty->assign('SERVER_STATUS', '');

    // Assignments
    $smarty->assign('HABERLER_INDEX_LINK', URL::build('/haberler'));

    // Any subhaberlers?
    $haberlers = DB::getInstance()->orderWhere('haberlers', 'deleted = 0', 'id', 'ASC')->results();

    $subhaberler_array = [];

        foreach ($subhaberlers as $subhaberler) {
            // Get number of topics
            if ($haberler->haberlerExist($subhaberler->id, $user_groups)) {
                if ($haberler->canViewOtherTopics($subhaberler->id, $user_groups)) {
                    $latest_post = DB::getInstance()->query('SELECT * FROM rw_topics WHERE id = ? AND deleted = 0 ORDER BY topic_reply_date DESC', [$subhaberler->id])->results();
                } else {
                    $latest_post = DB::getInstance()->query('SELECT * FROM rw_topics WHERE id = ? AND deleted = 0 AND topic_creator = ? ORDER BY topic_reply_date DESC', [$subhaberler->id, $user_id])->results();
                }

                $subhaberler_topics = count($latest_post);
                if (count($latest_post)) {
                    foreach ($latest_post as $item) {
                        if ($item->deleted == 0) {
                            $latest_post = $item;
                            break;
                        }
                    }

            
                    $latest_post_avatar = $latest_post_user->getAvatar();
                    $latest_post_user_displayname = $latest_post_user->getDisplayname();
                    $latest_post_user_link = $latest_post_user->getProfileURL();
                    $latest_post_style = $latest_post_user->getGroupStyle();

                    $latest_post = [
                        'link' => $latest_post_link,
                        'title' => $latest_post_title,
                        'last_user_avatar' => $latest_post_avatar,
                        'last_user' => $latest_post_user_displayname,
                        'last_user_style' => $latest_post_style,
                        'last_user_link' => $latest_post_user_link,
                        'timeago' => $latest_post_date_timeago,
                        'time' => $latest_post_time,
                        'last_user_id' => $latest_post_user_id
                    ];
                } else {
                    $latest_post = [];
                }

                $subhaberler_array[] = [
                    'id' => $subhaberler->id,
                    'title' => Output::getPurified($subhaberler->haberler_title),
                    'description' => Output::getPurified($subhaberler->haberler_description),
                    'topics' => $subhaberler_topics,
                    'link' => URL::build('/haberler/goruntule/' . urlencode($subhaberler->id) . '-' . $haberler->titleToURL($subhaberler->haberler_title)),
                    'latest_post' => $latest_post,
                    'icon' => Output::getPurified($subhaberler->icon),
                    'redirect' => $subhaberler->redirect_haberler
                ];
            }
        }
    }

    // Assign language variables
    $smarty->assign('HABERLERS', $haberler_language->get('haberler', 'haberlers'));
    $smarty->assign('DISCUSSION', $haberler_language->get('haberler', 'discussion'));
    $smarty->assign('TOPIC', $haberler_language->get('haberler', 'topic'));
    $smarty->assign('STATS', $haberler_language->get('haberler', 'stats'));
    $smarty->assign('LAST_REPLY', $haberler_language->get('haberler', 'last_reply'));
    $smarty->assign('BY', $haberler_language->get('haberler', 'by'));
    $smarty->assign('VIEWS', $haberler_language->get('haberler', 'views'));
    $smarty->assign('POSTS', $haberler_language->get('haberler', 'haberlers'));
    $smarty->assign('STATISTICS', $haberler_language->get('haberler', 'stats'));
    $smarty->assign('OVERVIEW', $haberler_language->get('haberler', 'overview'));
    $smarty->assign('LATEST_DISCUSSIONS_TITLE', $haberler_language->get('haberler', 'latest_discussions'));
    $smarty->assign('TOPICS', $haberler_language->get('haberler', 'topics'));
    $smarty->assign('NO_TOPICS', $haberler_language->get('haberler', 'no_topics_short'));
    $smarty->assign('SUBHABERLERS', $subhaberler_array);
    $smarty->assign('SUBHABERLER_LANGUAGE', $haberler_language->get('haberler', 'subhaberlers'));
    $smarty->assign('HABERLER_TITLE', Output::getPurified($haberler_query->haberler_title));
    $smarty->assign('HABERLER_ICON', Output::getPurified($haberler_query->icon));
    $smarty->assign('STICKY_TOPICS', $haberler_language->get('haberler', 'sticky_topics'));

    // Can the user post here?
    if ($user->isLoggedIn() && $user->hasPermission('admincp.haberlers')) {
        $smarty->assign('NEW_TOPIC_BUTTON', URL::build('/haberler/yeni/'));
    } else {
        $smarty->assign('NEW_TOPIC_BUTTON', false);
    }

    $smarty->assign('NEW_TOPIC', $haberler_language->get('haberler', 'new_topic'));

    $latest_news = $haberler->getLatestNews(); // Get latest 5 items

    $news = [];

    foreach ($latest_news as $item) {
        $post_user = new User($item['author']);
        $timeago = new TimeAgo(TIMEZONE);
        $news[] = [
            'id' => $item['id'],
            'url' => URL::build('/haberler/haber/' . urlencode($item['id']) . '-' . $haberler->titleToURL($item['haber_title'])),
            'date' => Output::getClean($item['post_date']),
            'time_ago' => $timeago->inWords($item['created'], $language),
            'title' => Output::getClean($item['haber_title']),
            'views' => $item['post_views'],
            'author_id' => Output::getClean($item['author']),
            'author_url' => $post_user->getProfileURL(),
            'author_style' => $post_user->getGroupStyle(),
            'author_name' => $post_user->getDisplayname(true),
            'author_nickname' => $post_user->getDisplayname(),
            'author_avatar' => $post_user->getAvatar(64),
            'author_group' => Output::getClean($post_user->getMainGroup()->name),
            'author_group_html' => $post_user->getMainGroup()->group_html,
            'content' => EventHandler::executeEvent('renderPost', ['content' => $item['content']])['content']
        ];
    }

    // Load modules + template
    Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

    $template->onPageLoad();

    $smarty->assign('WIDGETS_LEFT', $widgets->getWidgets('left'));
    $smarty->assign('WIDGETS_RIGHT', $widgets->getWidgets('right'));

    require(ROOT_PATH . '/core/templates/navbar.php');
    require(ROOT_PATH . '/core/templates/footer.php');

    // Display template
    if (isset($no_topics_exist)) {
        $template->displayTemplate('haberler/view_haberler_no_discussions.tpl', $smarty);
    } else {
        $template->displayTemplate('haberler/view_haberler.tpl', $smarty);
    }
