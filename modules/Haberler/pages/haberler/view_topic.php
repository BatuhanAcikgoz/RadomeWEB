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
$topic = DB::getInstance()->get('topics', ['id', $tid])->results();
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

// Quick reply
if (Input::exists()) {
    if (!$user->isLoggedIn() || !$can_reply) {
        Redirect::to(URL::build('/haberler'));
    }
    if (Token::check()) {
        $validate = Validate::check($_POST, [
            'content' => [
                Validate::REQUIRED => true,
                Validate::MIN => 2,
                Validate::MAX => 50000
            ]
        ])->messages([
            'content' => [
                Validate::REQUIRED => $haberler_language->get('haberler', 'content_required'),
                Validate::MIN => $haberler_language->get('haberler', 'content_min_2'),
                Validate::MAX => $haberler_language->get('haberler', 'content_max_50000')
            ]
        ]);

        if ($validate->passed()) {
            $content = Input::get('content');

            DB::getInstance()->insert('haberlers', [
                'id' => $topic->id,
                'topic_id' => $tid,
                'post_creator' => $user->data()->id,
                'post_content' => $content,
                'post_date' => date('Y-m-d H:i:s'),
                'created' => date('U')
            ]);

            // Get last post ID
            $last_post_id = DB::getInstance()->lastId();
            $content = EventHandler::executeEvent('prePostCreate', [
                'alert_full' => ['path' => ROOT_PATH . '/modules/Haberler/language', 'file' => 'haberler', 'term' => 'user_tag_info', 'replace' => '{{author}}', 'replace_with' => $user->getDisplayname()],
                'alert_short' => ['path' => ROOT_PATH . '/modules/Haberler/language', 'file' => 'haberler', 'term' => 'user_tag'],
                'alert_url' => URL::build('/haberler/haber/' . urlencode($tid), 'pid=' . urlencode($last_post_id)),
                'content' => $content,
                'user' => $user,
            ])['content'];

            DB::getInstance()->update('haberlers', $last_post_id, [
                'post_content' => $content
            ]);

            DB::getInstance()->update('haberlers', $topic->id, [
                'last_topic_posted' => $tid,
                'last_user_posted' => $user->data()->id,
                'last_post_date' => date('U')
            ]);
            DB::getInstance()->update('topics', $tid, [
                'topic_last_user' => $user->data()->id,
                'topic_reply_date' => date('U')
            ]);

            // Execute hooks and pass $available_hooks
            // TODO: This gets hooks only for this specific haberler, not any of its parents...
            $default_haberler_language = new Language(ROOT_PATH . '/modules/Haberler/language', DEFAULT_LANGUAGE);
            $available_hooks = DB::getInstance()->get('haberlers', ['id', $topic->id])->results();
            $available_hooks = json_decode($available_hooks[0]->hooks);
            EventHandler::executeEvent('topicReply', [
                'user_id' => $user->data()->id,
                'username' => $user->data()->username,
                'nickname' => $user->data()->nickname,
                'content' => $default_haberler_language->get('haberler', 'new_reply_in_topic', [
                    'topic' => $topic->haber_title,
                    'author' => $user->getDisplayname(),
                ]),
                'content_full' => strip_tags(str_ireplace(['<br />', '<br>', '<br/>'], "\r\n", $content)),
                'avatar_url' => $user->getAvatar(128, true),
                'title' => $topic->haber_title,
                'url' => URL::getSelfURL() . ltrim(URL::build('/haberler/haber/' . urlencode($topic->id) . '-' . $haberler->titleToURL($topic->haber_title)), '/'),
                'topic_author_user_id' => $topic_user->data()->id,
                'topic_author_username' => $topic_user->data()->username,
                'topic_id' => $tid,
                'post_id' => $last_post_id,
                'available_hooks' => $available_hooks === null ? [] : $available_hooks
            ]);

            // Alerts + Emails
            $users_following = DB::getInstance()->get('topics_following', ['topic_id', $tid])->results();
            if (count($users_following)) {
                $users_following_info = [];
                foreach ($users_following as $user_following) {
                    if ($user_following->user_id != $user->data()->id) {
                        if ($user_following->existing_alerts == 0) {
                            Alert::create(
                                $user_following->user_id,
                                'new_reply',
                                ['path' => ROOT_PATH . '/modules/Haberler/language', 'file' => 'haberler', 'term' => 'new_reply_in_topic', 'replace' => ['{{author}}', '{{topic}}'], 'replace_with' => [Output::getClean($user->data()->nickname), Output::getClean($topic->haber_title)]],
                                ['path' => ROOT_PATH . '/modules/Haberler/language', 'file' => 'haberler', 'term' => 'new_reply_in_topic', 'replace' => ['{{author}}', '{{topic}}'], 'replace_with' => [Output::getClean($user->data()->nickname), Output::getClean($topic->haber_title)]],
                                URL::build('/haberler/haber/' . urlencode($tid) . '-' . $haberler->titleToURL($topic->haber_title), 'pid=' . $last_post_id)
                            );
                            DB::getInstance()->update('topics_following', $user_following->id, [
                                'existing_alerts' => 1
                            ]);
                        }
                        $user_info = DB::getInstance()->get('users', ['id', $user_following->user_id])->results();
                        if ($user_info[0]->topic_updates) {
                            $users_following_info[] = ['email' => $user_info[0]->email, 'username' => $user_info[0]->username];
                        }
                    }
                }
                $path = implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'custom', 'templates', TEMPLATE, 'email', 'haberler_topic_reply.html']);
                $html = file_get_contents($path);

                $message = str_replace(
                    ['[Sitename]', '[TopicReply]', '[Greeting]', '[Message]', '[Link]', '[Thanks]'],
                    [
                        Output::getClean(SITE_NAME),
                        $language->get('emails', 'haberler_topic_reply_subject', ['author' => $user->data()->username, 'topic' => $topic->haber_title]),
                        $language->get('emails', 'greeting'),
                        $language->get('emails', 'haberler_topic_reply_message', ['author' => $user->data()->username, 'content' => html_entity_decode($content)]),
                        rtrim(URL::getSelfURL(), '/') . URL::build('/haberler/haber/' . urlencode($tid) . '-' . $haberler->titleToURL($topic->haber_title), 'pid=' . $last_post_id),
                        $language->get('emails', 'thanks')
                    ],
                    $html
                );
                $subject = Output::getClean(SITE_NAME) . ' - ' . $language->get('emails', 'haberler_topic_reply_subject', ['author' => $user->data()->username, 'topic' => $topic->haber_title]);

                $reply_to = Email::getReplyTo();
                foreach ($users_following_info as $user_info) {
                    $sent = Email::send(
                        ['email' => $user_info['email'], 'name' => $user_info['username']],
                        $subject,
                        $message,
                        $reply_to
                    );

                    if (isset($sent['error'])) {
                        DB::getInstance()->insert('email_errors', [
                            'type' => Email::FORUM_TOPIC_REPLY,
                            'content' => $sent['error'],
                            'at' => date('U'),
                            'user_id' => ($user->data()->id)
                        ]);
                    }
                }
            }
            Session::flash('success_post', $haberler_language->get('haberler', 'post_successful'));
            Redirect::to(URL::build('/haberler/haber/' . urlencode($tid) . '-' . $haberler->titleToURL($topic->haber_title), 'pid=' . $last_post_id));
        } else {
            $error = $validate->errors();
        }
    } else {
        $error = [$language->get('general', 'invalid_token')];
    }
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

// Are reactions enabled?
$reactions_enabled = Util::getSetting('haberler_reactions') === '1';

// Assign Smarty variables to pass to template
$parent_category = DB::getInstance()->get('haberlers', ['id', $haberler_parent[0]->parent])->results();

$breadcrumbs = [
    0 => [
        'id' => 0,
        'haberler_title' => Output::getClean($topic->haber_title),
        'active' => 1,
        'link' => URL::build('/haberler/haber/' . urlencode($topic->id) . '-' . $haberler->titleToURL($topic->haber_title))
    ],
    1 => [
        'id' => $haberler_parent[0]->id,
        'haberler_title' => Output::getClean($haberler_parent[0]->haberler_title),
        'link' => URL::build('/haberler/goruntule/' . urlencode($haberler_parent[0]->id) . '-' . $haberler->titleToURL($haberler_parent[0]->haberler_title))
    ]
];
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
            'haberler_title' => Output::getClean($parent_category[0]->haberler_title),
            'link' => URL::build('/haberler/goruntule/' . urlencode($parent_category[0]->id) . '-' . $haberler->titleToURL($parent_category[0]->haberler_title))
        ];
        $parent = false;
        while ($parent == false) {
            $parent_category = DB::getInstance()->get('haberlers', ['id', $parent_category[0]->parent])->results();
            $breadcrumbs[] = [
                'id' => $parent_category[0]->id,
                'haberler_title' => Output::getClean($parent_category[0]->haberler_title),
                'link' => URL::build('/haberler/goruntule/' . urlencode($parent_category[0]->id) . '-' . $haberler->titleToURL($parent_category[0]->haberler_title))
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
        if ($haberler->canModerateHaberler($haberler_parent[0]->id, $user_groups)) {
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
if ($user->isLoggedIn() && $haberler->canModerateHaberler($haberler_parent[0]->id, $user_groups)) {
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
