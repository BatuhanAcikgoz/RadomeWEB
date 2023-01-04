<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr13
 *
 *  License: MIT
 *
 *  New topic page
 */

// Always define page name
const PAGE = 'haberler';
$page_title = $haberler_language->get('haberler', 'new_topic');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

// User must be logged in to proceed
if (!$user->isLoggedIn()) {
    Redirect::to(URL::build('/haberler'));
}

if ($user->hasPermission('admincp.haberlers')) {
    
} else {
    Redirect::to(URL::build('/haberler/hata/', 'error=not_exist'));
}

$haberler = new Haberler();

// Get user group ID
$user_groups = $user->getAllGroupIds();


$current_haberler = DB::getInstance()->query('SELECT * FROM rw_haberlers WHERE id = ?', [$topic_id])->first();
$haberler_title = Output::getClean($current_haberler->haberler_title);

// Topic labels
$smarty->assign('LABELS_TEXT', $haberler_language->get('haberler', 'label'));
$labels = [];

$default_labels = $current_haberler->default_labels ? explode(',', $current_haberler->default_labels) : [];
$selected_labels = ((isset($_POST['topic_label']) && is_array($_POST['topic_label'])) ? Input::get('topic_label') : $default_labels);

// Deal with any inputted data
if (Input::exists()) {
    if (Token::check()) {
        // Check post limits
        $last_post = DB::getInstance()->orderWhere('haberlers', 'post_creator = ' . $user->data()->id, 'post_date', 'DESC LIMIT 1')->results();
        if (count($last_post)) {
            if ($last_post[0]->created > strtotime('-30 seconds')) {
                $spam_check = true;
            }
        }

        if (!isset($spam_check)) {
            // Spam check passed
            $validate = Validate::check($_POST, [
                'title' => [
                    Validate::REQUIRED => true,
                    Validate::MIN => 2,
                    Validate::MAX => 64
                ],
                'content' => [
                    Validate::REQUIRED => true,
                    Validate::MIN => 2,
                    Validate::MAX => 50000
                ]
            ])->messages([
                'title' => [
                    Validate::REQUIRED => $haberler_language->get('haberler', 'title_required'),
                    Validate::MIN => $haberler_language->get('haberler', 'title_min_2'),
                    Validate::MAX => $haberler_language->get('haberler', 'title_max_64')
                ],
                'content' => [
                    Validate::REQUIRED => $haberler_language->get('haberler', 'content_required'),
                    Validate::MIN => $haberler_language->get('haberler', 'content_min_2'),
                    Validate::MAX => $haberler_language->get('haberler', 'content_max_50000')
                ]
            ]);

            if ($validate->passed()) {
                $post_labels = [];

                if (isset($_POST['topic_label']) && !empty($_POST['topic_label']) && is_array($_POST['topic_label'])) {
                    foreach ($_POST['topic_label'] as $topic_label) {
                        $label = DB::getInstance()->get('haberlers_topic_labels', ['id', $topic_label])->results();
                        if (count($label)) {
                            $lgroups = explode(',', $label[0]->gids);

                            $hasperm = false;
                            foreach ($user_groups as $group_id) {
                                if (in_array($group_id, $lgroups)) {
                                    $hasperm = true;
                                    break;
                                }
                            }

                            if ($hasperm) {
                                $post_labels[] = $label[0]->id;
                            }
                        }
                    }
                } else {
                    if (count($default_labels)) {
                        $post_labels = $default_labels;
                    }
                }

                DB::getInstance()->insert('haberlers', [
                    'haber_id' => DB::getInstance()->lastId(),
                    'haber_title' => Input::get('title'),
                    'post_creator' => $user->data()->id,
                    'post_content' => Input::get('post_content'),
                    'post_date' => date('U'),
                    'post_date' => date('Y-m-d H:i:s')
                ]);


                // Get last post ID
                $last_post_id = DB::getInstance()->lastId();
                $content = EventHandler::executeEvent('preTopicCreate', [
                    'alert_full' => ['path' => ROOT_PATH . '/modules/Haberler/language', 'file' => 'haberler', 'term' => 'user_tag_info', 'replace' => '{{author}}', 'replace_with' => $user->getDisplayname()],
                    'alert_short' => ['path' => ROOT_PATH . '/modules/Haberler/language', 'file' => 'haberler', 'term' => 'user_tag'],
                    'alert_url' => URL::build('/haberler/haber/' . urlencode($topic_id), 'pid=' . urlencode($last_post_id)),
                    'content' => $content,
                    'user' => $user,
                ])['content'];

                DB::getInstance()->update('haberlers', $last_post_id, [
                    'post_content' => $content
                ]);


                // Execute hooks and pass $available_hooks
                $default_haberler_language = new Language(ROOT_PATH . '/modules/Haberler/language', DEFAULT_LANGUAGE);
                $available_hooks = DB::getInstance()->get('haberlers', ['id', $topic_id])->results();
                $available_hooks = json_decode($available_hooks[0]->hooks);
                EventHandler::executeEvent('newTopic', [
                    'user_id' => Output::getClean($user->data()->id),
                    'username' => $user->getDisplayname(true),
                    'nickname' => $user->getDisplayname(),
                    'content' => $default_haberler_language->get('haberler', 'new_topic_text', [
                        'haberler' => $haberler_title,
                        'author' => $user->getDisplayname(),
                    ]),
                    'content_full' => strip_tags(str_ireplace(['<br />', '<br>', '<br/>'], "\r\n", Input::get('content'))),
                    'avatar_url' => $user->getAvatar(128, true),
                    'title' => Input::get('title'),
                    'url' => URL::getSelfURL() . ltrim(URL::build('/haberler/haber/' . urlencode($topic_id) . '-' . $haberler->titleToURL(Input::get('title'))), '/'),
                    'available_hooks' => $available_hooks === null ? [] : $available_hooks
                ]);

                Session::flash('success_post', $haberler_language->get('haberler', 'post_successful'));

                Redirect::to(URL::build('/haberler/haber/' . urlencode($topic_id) . '-' . $haberler->titleToURL(Input::get('title'))));
            } else {
                $error = $validate->errors();
            }
        } else {
            $error = [$haberler_language->get('haberler', 'spam_wait', ['count' => (strtotime($last_post[0]->post_date) - strtotime('-30 seconds'))])];
        }
    } else {
        $error = [$language->get('general', 'invalid_token')];
    }
}

// Generate a token
$token = Token::get();

// Generate content for template
if (isset($error)) {
    $smarty->assign('ERROR', $error);
}

$creating_topic_in = $haberler_language->get('haberler', 'creating_topic_in_x', ['haberler' => $haberler_title]);
$smarty->assign('CREATING_TOPIC_IN', $creating_topic_in);

// Get info about haberler
$haberler_query = DB::getInstance()->get('haberlers', ['id', $topic_id])->results();
$haberler_query = $haberler_query[0];

// Placeholder?
if ($haberler_query->topic_placeholder) {
    $placeholder = Output::getPurified($haberler_query->topic_placeholder);
}

// Smarty variables
$smarty->assign([
    'LABELS' => $labels,
    'TOPIC_TITLE' => $haberler_language->get('haberler', 'topic_title'),
    'TOPIC_VALUE' => ((isset($_POST['title']) && $_POST['title']) ? Output::getClean(Input::get('title')) : ''),
    'LABEL' => $haberler_language->get('haberler', 'label'),
    'SUBMIT' => $language->get('general', 'submit'),
    'CANCEL' => $language->get('general', 'cancel'),
    'CLOSE' => $language->get('general', 'close'),
    'CONFIRM_CANCEL' => $language->get('general', 'confirm_cancel'),
    'YES' => $language->get('general', 'yes'),
    'NO' => $language->get('general', 'no'),
    'TOKEN' => '<input type="hidden" name="token" value="' . $token . '">',
    'HABERLER_LINK' => URL::build('/haberler'),
    'CONTENT_LABEL' => $language->get('general', 'content'),
    'HABERLER_TITLE' => Output::getClean($haberler_title),
    'HABERLER_DESCRIPTION' => Output::getPurified($haberler_query->haberler_description),
    'NEWS_HABERLER' => $haberler_query->news
]);

$content = $_POST['content'] ?? $haberler_query->topic_placeholder ?? null;
if ($content) {
    // Purify post content
    $content = EventHandler::executeEvent('renderPostEdit', ['content' => $content])['content'];
}

$template->assets()->include([
    AssetTree::TINYMCE,
]);

$template->addJSScript(Input::createTinyEditor($language, 'reply', $content, true));

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
$template->displayTemplate('haberler/new_topic.tpl', $smarty);
