<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr13
 *
 *  License: MIT
 *
 *  View topic page
 */

// Set the page name for the active link in navbar
const PAGE = 'haberler';

$haberler = new Haberler();
$timeago = new TimeAgo(TIMEZONE);

// Get topic ID
$tid = explode('/', $route);
$tid = $tid[count($tid) - 1];

if (!strlen($tid)) {
    require_once(ROOT_PATH . '/404.php');
    die();
}

$tid = explode('-', $tid);
if (!is_numeric($tid[0])) {
    require_once(ROOT_PATH . '/404.php');
    die();
}
$tid = $tid[0];

// Does the topic exist, and can the user view it?
$user_groups = $user->getAllGroupIds();

$list = $haberler->topicExist($tid);
if (!$list) {
    require_once(ROOT_PATH . '/404.php');
    die();
}

// Get the topic information
$topic = DB::getInstance()->get('haberlers', ['id', $tid])->results();
$topic = $topic[0];

if ($topic->deleted == 1) {
    require_once(ROOT_PATH . '/404.php');
    die();
}

if ($user->isLoggedIn()) {
    $user_id = $user->data()->id;
} else {
    $user_id = 0;
}


// Get page
if (isset($_GET['p'])) {
    if (!is_numeric($_GET['p'])) {
        Redirect::to(URL::build('/haberler'));
    }

    if ($_GET['p'] <= 1) {
        // Avoid bug in pagination class
        Redirect::to(URL::build('/haberler/haber/' . urlencode($tid) . '-' . $haberler->titleToURL($topic->haber_title)));
    }
    $p = $_GET['p'];
} else {
    $p = 1;
}

// Is the URL pointing to a specific post?
if (isset($_GET['pid'])) {
    $haberlers = DB::getInstance()->query('SELECT * FROM rw_haberlers WHERE id = ? AND deleted = 0', [$tid])->results();
    if (count($haberlers)) {
        $i = 0;
        while ($i < count($haberlers)) {
            if ($haberlers[$i]->id == $_GET['pid']) {
                $output = $i + 1;
                break;
            }
            $i++;
        }
        if (ceil($output / 10) != $p) {
            Redirect::to(URL::build('/haberler/haber/' . urlencode($tid) . '-' . $haberler->titleToURL($topic->haber_title), 'p=' . ceil($output / 10)) . '#post-' . $_GET['pid']);
        } else {
            Redirect::to(URL::build('/haberler/haber/' . urlencode($tid) . '-' . $haberler->titleToURL($topic->haber_title)) . '#post-' . $_GET['pid']);
        }
    } else {
        require_once(ROOT_PATH . '/404.php');
    }
    die();
}

$haberler_parent = DB::getInstance()->get('haberlers', ['id', $topic->id])->results();

$page_metadata = DB::getInstance()->get('page_descriptions', ['page', '/haberler/haber'])->results();
if (count($page_metadata)) {
    $first_post = DB::getInstance()->orderWhere('haberlers', 'topic_id = ' . $topic->id, 'created', 'ASC LIMIT 1')->results();
    $first_post = htmlentities(strip_tags(str_ireplace(['<br />', '<br>', '<br/>', '&nbsp;'], ["\n", "\n", "\n", ' '], $first_post[0]->post_content)), ENT_QUOTES, 'UTF-8', false);

    define('PAGE_DESCRIPTION', str_replace(['{site}', '{title}', '{author}', '{haberler_title}', '{page}', '{post}'], [Output::getClean(SITE_NAME), Output::getClean($topic->haber_title), Output::getClean($user->idToName($topic->topic_creator)), Output::getClean($haberler_parent[0]->haberler_title), Output::getClean($p), substr($first_post, 0, 160) . '...'], $page_metadata[0]->description));
    define('PAGE_KEYWORDS', $page_metadata[0]->tags);
} else {
    $page_metadata = DB::getInstance()->get('page_descriptions', ['page', '/haberler/haberi_goruntule'])->results();

    if (count($page_metadata)) {
        $first_post = DB::getInstance()->orderWhere('haberlers', 'topic_id = ' . $topic->id, 'created', 'ASC LIMIT 1')->results();
        $first_post = htmlentities(strip_tags(str_ireplace(['<br />', '<br>', '<br/>', '&nbsp;'], ["\n", "\n", "\n", ' '], $first_post[0]->post_content)), ENT_QUOTES, 'UTF-8', false);

        define('PAGE_DESCRIPTION', str_replace(['{site}', '{title}', '{author}', '{haberler_title}', '{page}', '{post}'], [Output::getClean(SITE_NAME), Output::getClean($topic->haber_title), Output::getClean($user->idToName($topic->topic_creator)), Output::getClean($haberler_parent[0]->haberler_title), Output::getClean($p), substr($first_post, 0, 160) . '...'], $page_metadata[0]->description));
        define('PAGE_KEYWORDS', $page_metadata[0]->tags);
    }
}

$page_title = ((strlen(Output::getClean($topic->haber_title)) > 20) ? Output::getClean(mb_substr($topic->haber_title, 0, 20)) . '...' : Output::getClean($topic->haber_title)) . ' - ' . $language->get('general', 'page_x', ['page' => $p]);
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

// Assign author + title to Smarty variables
// Get first post
$first_post = DB::getInstance()->query('SELECT * FROM rw_haberlers WHERE id = ? ORDER BY id ASC LIMIT 1', [$tid])->first();

$topic_user = new User($topic->topic_creator);

$smarty->assign([
    'TOPIC_TITLE' => Output::getClean($topic->haber_title),
    'TOPIC_AUTHOR_USERNAME' => $topic_user->getDisplayname(),
    'TOPIC_AUTHOR_MCNAME' => $topic_user->getDisplayname(true),
    'TOPIC_AUTHOR_PROFILE' => $topic_user->getProfileURL(),
    'TOPIC_AUTHOR_STYLE' => $topic_user->getGroupStyle(),
    'TOPIC_ID' => $topic->id,
    'id' => $topic->id,
    'TOPIC_LAST_EDITED' => ($first_post->last_edited ? $timeago->inWords($first_post->last_edited, $language) : null),
    'TOPIC_LAST_EDITED_FULL' => ($first_post->last_edited ? date(DATE_FORMAT, $first_post->last_edited) : null)
]);


$smarty->assign(['TOPIC_LABEL' => $label, 'TOPIC_LABELS' => $labels]);

// Get all haberlers in the topic
$haberlers = $haberler->getPosts($tid);

// Can the user post a reply in this topic?
if ($user->isLoggedIn()) {
    // Topic locked?
        $can_reply = false;
}

// Generate a post token
if ($user->isLoggedIn()) {
    $token = Token::get();
}

// View count
if ($user->isLoggedIn() || (defined('COOKIE_CHECK') && COOKIES_ALLOWED)) {
    if (!Cookie::exists('nl-topic-' . $tid)) {
        DB::getInstance()->increment('haberlers', $tid, 'post_views');
        Cookie::put('nl-topic-' . $tid, 'true', 3600);
    }
} else {
    if (!Session::exists('nl-topic-' . $tid)) {
        DB::getInstance()->increment('haberlers', $tid, 'post_views');
        Session::put('nl-topic-' . $tid, 'true');
    }
}

if ($user->isLoggedIn()) {
    $template->addJSScript('var quotedPosts = [];');
}

// Assign Smarty variables to pass to template
$parent_category = DB::getInstance()->get('haberlers', ['id', $haberler_parent[0]->parent])->results();

$breadcrumbs = [
    0 => [
        'id' => 0,
        'haberler_title' => Output::getClean($topic->haber_title),
        'active' => 1,
        'link' => URL::build('/haberler/haber/' . urlencode($topic->id) . '-' . $haberler->titleToURL($topic->haber_title))
    ],
];

$breadcrumbs[] = [
    'id' => 'index',
    'haberler_title' => $haberler_language->get('haberler', 'haberler_index'),
    'link' => URL::build('/haberler')
];

$smarty->assign('BREADCRUMBS', array_reverse($breadcrumbs));

// Display session messages
if (Session::exists('success_post')) {
    $smarty->assign('SESSION_SUCCESS_POST', Session::flash('success_post'));
}
if (Session::exists('failure_post')) {
    $smarty->assign('SESSION_FAILURE_POST', Session::flash('failure_post'));
}
if (isset($error) && count($error)) {
    $smarty->assign([
        'ERROR_TITLE' => $language->get('general', 'error'),
        'ERRORS' => $error
    ]);
}

// Display "new reply" button and "mod actions" if the user has access to them

// Can the user post a reply?
if ($user->isLoggedIn() && $can_reply) {
    $smarty->assign('CAN_REPLY', true);

    // Is the topic locked?
    if ($topic->locked != 1) { // Not locked
        $smarty->assign('NEW_REPLY', $haberler_language->get('haberler', 'new_reply'));
    } else { // Locked
        if ($user->hasPermission('admincp.haberlers')) {
            // Can post anyway
            $smarty->assign('NEW_REPLY', $haberler_language->get('haberler', 'new_reply'));
        } else {
            // Can't post
            $smarty->assign('NEW_REPLY', $haberler_language->get('haberler', 'topic_locked'));
        }
    }
}

if ($topic->locked == 1) {
    $smarty->assign('LOCKED', true);
}

// Is the user a moderator?
if ($user->isLoggedIn() && $user->hasPermission('admincp.haberlers')) {
    $smarty->assign([
        'CAN_MODERATE' => true,
        'MOD_ACTIONS' => $haberler_language->get('haberler', 'mod_actions'),
        'DELETE_URL' => URL::build('/haberler/delete/', 'tid=' . urlencode($tid)),
        'CONFIRM_DELETE' => $haberler_language->get('haberler', 'confirm_delete_topic'),
        'CONFIRM_DELETE_SHORT' => $language->get('general', 'confirm_delete'),
        'CONFIRM_DELETE_POST' => $haberler_language->get('haberler', 'confirm_delete_post'),
        'DELETE' => $haberler_language->get('haberler', 'delete_topic')
    ]);
}

// Sharing
$smarty->assign([
    'SHARE' => $haberler_language->get('haberler', 'share'),
    'SHARE_TWITTER' => $haberler_language->get('haberler', 'share_twitter'),
    'SHARE_TWITTER_URL' => 'https://twitter.com/intent/tweet?text=' . urlencode(rtrim(URL::getSelfURL(), '/')) . URL::build('/haberler/haber/' . urlencode($tid) . '-' . $haberler->titleToURL($topic->haber_title)),
    'SHARE_FACEBOOK' => $haberler_language->get('haberler', 'share_facebook'),
    'SHARE_FACEBOOK_URL' => 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode(rtrim(URL::getSelfURL(), '/')) . URL::build('/haberler/haber/' . urlencode($tid) . '-' . $haberler->titleToURL($topic->haber_title))
]);

// Pagination
$paginator = new Paginator(
    $template_pagination ?? null,
    $template_pagination_left ?? null,
    $template_pagination_right ?? null
);
$results = $paginator->getLimited($haberlers, 10, $p, count($haberlers));
$pagination = $paginator->generate(7, URL::build('/haberler/haber/' . $tid . '-' . $haberler->titleToURL($topic->haber_title)));

$smarty->assign('PAGINATION', $pagination);

// Replies
$replies = [];
// Display the correct number of posts
foreach ($results->data as $n => $nValue) {
    $post_creator = new User($nValue->post_creator);
    if (!$post_creator->exists()) {
        continue;
    }

    // Get user's group HTML formatting and their signature
    $user_groups_html = $post_creator->getAllGroupHtml();
    $signature = $post_creator->getSignature();

    // Panel heading content
    $url = URL::build('/haberler/haber/' . $tid . '-' . $haberler->titleToURL($topic->haber_title), 'pid=' . $nValue->id);

    if ($n != 0) {
        $heading = $haberler_language->get('haberler', 're') . Output::getClean($topic->haber_title);
    } else {
        $heading = Output::getClean($topic->haber_title);
    }

    // Which buttons do we need to display?
    $buttons = [];

    if ($user->isLoggedIn()) {
        // Assign token
        $smarty->assign('TOKEN', $token);

        // Edit button
        if ($user->hasPermission('admincp.haberlers')) {
            $buttons['edit'] = [
                'URL' => URL::build('/haberler/duzenle/', 'pid=' . $nValue->id . '&amp;tid=' . $tid),
                'TEXT' => $haberler_language->get('haberler', 'edit')
            ];
        } else {
            if ($user->data()->id == $nValue->post_creator && $user->hasPermission('admincp.haberlers')) {
                if ($topic->locked != 1) { // Can't edit if topic is locked
                    $buttons['edit'] = [
                        'URL' => URL::build('/haberler/duzenle/', 'pid=' . $nValue->id . '&amp;tid=' . $tid),
                        'TEXT' => $haberler_language->get('haberler', 'edit')
                    ];
                }
            }
        }

        // Delete button
        if ($moderate || $user->data()->id == $nValue->post_creator) {
            $buttons['delete'] = [
                'URL' => URL::build('/haberler/konuyu_sil/', 'pid=' . $nValue->id . '&amp;tid=' . $tid),
                'TEXT' => $language->get('general', 'delete'),
                'NUMBER' => $p . $n
            ];
        }
    }

    // Profile fields
    $fields = $post_creator->getProfileFields(false, true);

    // User integrations
    $user_integrations = [];
    foreach ($post_creator->getIntegrations() as $key => $integrationUser) {
        if ($integrationUser->data()->username != null && $integrationUser->data()->show_publicly) {
            $fields[] = [
                'name' => Output::getClean($key),
                'value' => Output::getClean($integrationUser->data()->username)
            ];

            $user_integrations[$key] = [
                'username' => Output::getClean($integrationUser->data()->username),
                'identifier' => Output::getClean($integrationUser->data()->identifier)
            ];
        }
    }

    foreach ($haberler_placeholders as $haberler_placeholder) {
        $fields[] = [
            'name' => $haberler_placeholder->friendly_name,
            'value' => $haberler_placeholder->value
        ];
    }

    // Get post reactions
    $post_reactions = [];
    $total_karma = 0;
    if ($reactions_enabled) {
        $post_reactions_query = DB::getInstance()->get('haberlers_reactions', ['post_id', $nValue->id])->results();

        if (count($post_reactions_query)) {
            foreach ($post_reactions_query as $item) {
                if (!isset($post_reactions[$item->reaction_id])) {
                    $post_reactions[$item->reaction_id]['count'] = 1;

                    $reaction = DB::getInstance()->get('reactions', ['id', $item->reaction_id])->results();
                    $post_reactions[$item->reaction_id]['html'] = $reaction[0]->html;
                    $post_reactions[$item->reaction_id]['name'] = $reaction[0]->name;

                    if ($reaction[0]->type == 2) {
                        $total_karma++;
                    } else {
                        if ($reaction[0]->type == 0) {
                            $total_karma--;
                        }
                    }
                } else {
                    $post_reactions[$item->reaction_id]['count']++;
                }

                $reaction_user = new User($item->user_given);
                $post_reactions[$item->reaction_id]['users'][] = [
                    'username' => $reaction_user->getDisplayname(true),
                    'nickname' => $reaction_user->getDisplayname(),
                    'style' => $reaction_user->getGroupStyle(),
                    'avatar' => $reaction_user->getAvatar(),
                    'profile' => $reaction_user->getProfileURL()
                ];
            }
        }
    }

    // Purify post content
    $content = EventHandler::executeEvent('renderPost', ['content' => $nValue->post_content])['content'];

    // Get post date
    if (is_null($nValue->created)) {
        $post_date_rough = $timeago->inWords($nValue->post_date, $language);
        $post_date = date(DATE_FORMAT, strtotime($nValue->post_date));
    } else {
        $post_date_rough = $timeago->inWords($nValue->created, $language);
        $post_date = date(DATE_FORMAT, $nValue->created);
    }

    $replies[] = [
        'url' => $url,
        'heading' => $heading,
        'id' => $nValue->id,
        'user_id' => $post_creator->data()->id,
        'avatar' => $post_creator->getAvatar(),
        'integrations' => $user_integrations,
        'username' => $post_creator->getDisplayname(),
        'mcname' => $post_creator->getDisplayname(true),
        'last_seen' => $language->get('user', 'last_seen_x', ['lastSeenAt' => $timeago->inWords($post_creator->data()->last_online, $language)]),
        'last_seen_full' => date('d M Y', $post_creator->data()->last_online),
        'online_now' => $post_creator->data()->last_online > strtotime('5 minutes ago'),
        'user_title' => Output::getClean($post_creator->data()->user_title),
        'profile' => $post_creator->getProfileURL(),
        'user_style' => $post_creator->getGroupStyle(),
        'user_groups' => $user_groups_html,
        'user_posts_count' => $haberler_language->get('haberler', 'x_posts', ['count' => $haberler->getPostCount($nValue->post_creator)]),
        'user_topics_count' => $haberler_language->get('haberler', 'x_topics', ['count' => $haberler->getTopicCount($nValue->post_creator)]),
        'user_registered' => $haberler_language->get('haberler', 'registered_x', ['registeredAt' => $timeago->inWords($post_creator->data()->joined, $language)]),
        'user_registered_full' => date('d M Y', $post_creator->data()->joined),
        'user_reputation' => $post_creator->data()->reputation,
        'post_date_rough' => $post_date_rough,
        'post_date' => $post_date,
        'buttons' => $buttons,
        'content' => $content,
        'signature' => Output::getPurified(Text::renderEmojis($signature)),
        'fields' => (empty($fields) ? [] : $fields),
        'edited' => is_null($nValue->last_edited)
            ? null
            : $haberler_language->get('haberler', 'last_edited', ['lastEditedAt' => $timeago->inWords($nValue->last_edited, $language)]),
        'edited_full' => (is_null($nValue->last_edited) ? null : date(DATE_FORMAT, $nValue->last_edited)),
        'post_reactions' => $post_reactions,
        'karma' => $total_karma
    ];
}

$smarty->assign('REPLIES', $replies);

// Assign Smarty language variables
$smarty->assign([
    'POSTS' => $haberler_language->get('haberler', 'haberlers'),
    'BY' => ucfirst($haberler_language->get('haberler', 'by')),
    'CANCEL' => $language->get('general', 'cancel'),
    'USER_ID' => (($user->isLoggedIn()) ? $user->data()->id : 0),
    'INSERT_QUOTES' => $haberler_language->get('haberler', 'insert_quotes'),
    'HABERLER_TITLE' => Output::getClean($haberler_parent[0]->haberler_title),
    'STARTED_BY' => $haberler_language->get('haberler', 'started_by_x', [
        'author' => '<a href="' . $topic_user->getProfileURL() . '" style="' . $topic_user->getGroupStyle() . '">' . $topic_user->getDisplayname() . '</a>',
    ]),
    'SUCCESS' => $language->get('general', 'success'),
    'ERROR' => $language->get('general', 'error')
]);

$template->assets()->include([
    AssetTree::TINYMCE,
]);

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
$template->displayTemplate('haberler/view_topic.tpl', $smarty);
