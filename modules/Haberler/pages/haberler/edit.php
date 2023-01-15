<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Edit post page
 */

// Always define page name
const PAGE = 'haberler';
$page_title = $haberler_language->get('haberler', 'edit_post');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

// User must be logged in to proceed
if (!$user->isLoggedIn()) {
    Redirect::to(URL::build('/haberler'));
}

// Initialise
$haberler = new Haberler();

if (isset($_GET['tid']) && is_numeric($_GET['tid'])) {
    $topic_id = $_GET['tid'];
} else {
    Redirect::to(URL::build('/haberler/hata/', 'error=not_exist'));
}

/*
 *  Is the post the first in the topic? If so, allow the title to be edited.
 */

$post_editing = DB::getInstance()->query('SELECT * FROM rw_haberlers WHERE id = ? ORDER BY id ASC LIMIT 1', [$topic_id])->results();

// Check topic exists
if (!count($post_editing)) {
    Redirect::to(URL::build('/haberler/hata/', 'error=not_exist'));
}

if ($post_editing[0]->id == $topic_id) {
    $edit_title = true;

    /*
     *  Get the title of the topic
     */

    $post_title = DB::getInstance()->get('haberlers', ['id', $topic_id])->results();
    $post_labels = $post_title[0]->labels ? explode(',', $post_title[0]->labels) : [];
    $post_title = Output::getClean($post_title[0]->haber_title);
}

/*
 *  Get the post we're editing
 */

$post_editing = DB::getInstance()->get('haberlers', ['id', $topic_id])->results();

// Check post exists
if (!count($post_editing)) {
    Redirect::to(URL::build('/haberler/hata/', 'error=not_exist'));
}

$id = $post_editing[0]->id;

// Get user group IDs
$user_groups = $user->getAllGroupIds();

// Check permissions before proceeding


if ($user->data()->id != $post_editing[0]->post_creator && !($haberler->canModerateHaberler($id, $user_groups))) {
    Redirect::to(URL::build('/haberler/haber/' . urlencode($topic_id)));
}

// Deal with input
if (Input::exists()) {
    // Check token
    if (Token::check()) {
        // Valid token, check input
        $to_validate = [
            'content' => [
                Validate::REQUIRED => true,
                Validate::MIN => 2,
                Validate::MAX => 50000
            ]
        ];
        // Add title to validation if we need to
        if (isset($edit_title)) {
            $to_validate['title'] = [
                Validate::REQUIRED => true,
                Validate::MIN => 2,
                Validate::MAX => 64
            ];
        }

        $validation = Validate::check($_POST, $to_validate)->messages([
            'content' => [
                Validate::REQUIRED => $haberler_language->get('haberler', 'content_required'),
                Validate::MIN => $haberler_language->get('haberler', 'content_min_2'),
                Validate::MAX => $haberler_language->get('haberler', 'content_max_50000')
            ],
            'title' => [
                Validate::REQUIRED => $haberler_language->get('haberler', 'title_required'),
                Validate::MIN => $haberler_language->get('haberler', 'title_min_2'),
                Validate::MAX => $haberler_language->get('haberler', 'title_max_64')
            ]
        ]);

        if ($validation->passed()) {
            // Valid post content
            $content = EventHandler::executeEvent(isset($edit_title) ? 'preTopicEdit' : 'prePostEdit', [
                'content' => Input::get('content'),
                'id' => $topic_id,
                'user' => $user,
            ])['content'];

            // Update post content
            DB::getInstance()->update('haberlers', $topic_id, [
                'post_content' => $content,
                'last_edited' => date('U')
            ]);

            Log::getInstance()->log(Log::Action('haberlers/post/edit'), $topic_id);

            if (isset($edit_title)) {

                DB::getInstance()->update('topics', $topic_id, [
                    'haber_title' => Input::get('title'),
                    'labels' => implode(',', $post_labels)
                ]);

                Log::getInstance()->log(Log::Action('haberlers/topic/edit'), Input::get('title'));
            }

            // Display success message and redirect
            Session::flash('success_post', $haberler_language->get('haberler', 'post_edited_successfully'));
            Redirect::to(URL::build('/haberler/haber/' . urlencode($topic_id)));
        }

        // Error handling
        $errors = $validation->errors();
    } else {
        // Bad token
        $errors = [$language->get('general', 'invalid_token')];
    }
}

if (isset($errors)) {
    $smarty->assign([
        'ERROR_TITLE' => $language->get('general', 'error'),
        'ERRORS' => $errors
    ]);
}

$smarty->assign('EDITING_POST', $haberler_language->get('haberler', 'edit_post'));

if (isset($edit_title, $post_labels)) {
    $smarty->assign('EDITING_TOPIC', true);

    $smarty->assign('TOPIC_TITLE_VALUE', $post_title);

    // Topic labels
    $smarty->assign('LABELS_TEXT', $haberler_language->get('haberler', 'label'));
    $labels = [];

    $haberler_labels = DB::getInstance()->get('haberlers_topic_labels', ['id', '<>', 0])->results();
    if (count($haberler_labels)) {
        foreach ($haberler_labels as $label) {
            $ids = explode(',', $label->fids);

            if (in_array($id, $ids)) {
                // Check permissions
                $lgroups = explode(',', $label->gids);
                $perms = false;

                foreach ($user_groups as $group) {
                    if (in_array($group, $lgroups)) {
                        $perms = true;
                    }
                }

                if ($perms == false) {
                    continue;
                }

                // Get label HTML
                $label_html = DB::getInstance()->get('haberlers_labels', ['id', $label->label])->results();
                if (!count($label_html)) {
                    continue;
                }

                $label_html = str_replace('{x}', Output::getClean($label->name), Output::getPurified($label_html[0]->html));

                $labels[] = [
                    'id' => $label->id,
                    'active' => in_array($label->id, $post_labels),
                    'html' => $label_html
                ];
            }
        }
    }

    $smarty->assign('LABELS', $labels);
}

// Purify post content
$content = EventHandler::executeEvent('renderPostEdit', [
    'content' => $post_editing[0]->post_content,
    'user' => $user
])['content'];

$smarty->assign([
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
    'CANCEL' => $language->get('general', 'cancel'),
    'CANCEL_LINK' => URL::build('/haberler/haber/' . urlencode($topic_id)),
    'CONFIRM_CANCEL' => $language->get('general', 'confirm_cancel'),
    'CONTENT_LABEL' => $language->get('general', 'content'),
    'TOPIC_TITLE' => $haberler_language->get('haberler', 'topic_title')
]);

$template->assets()->include([
    AssetTree::TINYMCE,
]);

$template->addJSScript(Input::createTinyEditor($language, 'editor', $content, true));

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
$template->displayTemplate('haberler/haberler_edit_post.tpl', $smarty);
