<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Merge two topics together
 */

const PAGE = 'haberler';
$page_title = $haberler_language->get('haberler', 'merge_topics');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

$haberler = new Haberler();

// User must be logged in to proceed
if (!$user->isLoggedIn()) {
    Redirect::to('/haberler');
}

if (!isset($_GET['tid']) || !is_numeric($_GET['tid'])) {
    Redirect::to(URL::build('/haberler/hata/', 'error=not_exist'));
}

$topic_id = $_GET['tid'];
$id = DB::getInstance()->query('SELECT id FROM rw_topics WHERE id = ?', [$topic_id])->first();
$id = $id->id;

if ($haberler->canModerateHaberler($id, $user->getAllGroupIds())) {
    if (Input::exists()) {
        if (Token::check()) {
            $validation = Validate::check($_POST, [
                'merge' => [
                    Validate::REQUIRED => true
                ]
            ]);

            $haberlers_to_move = DB::getInstance()->get('haberlers', ['topic_id', $topic_id])->results();
            if ($validation->passed()) {

                foreach ($haberlers_to_move as $post_to_move) {
                    DB::getInstance()->update('haberlers', $post_to_move->id, [
                        'topic_id' => Input::get('merge')
                    ]);
                }
                DB::getInstance()->delete('topics', ['id', $topic_id]);
                Log::getInstance()->log(Log::Action('haberlers/merge'));
                // Update latest haberlers in categories
                $haberler->updateHaberlerLatestPosts();
                $haberler->updateTopicLatestPosts();

                Redirect::to(URL::build('/haberler/haber/' . urlencode(Input::get('merge'))));

            } else {
                echo 'Error processing that action. <a href="' . URL::build('/haberler') . '">Haberler index</a>';
            }
            die();
        }
    }
} else {
    Redirect::to(URL::build('/haberler'));
}

$token = Token::get();

// Get topics
$topics = DB::getInstance()->query('SELECT * FROM rw_topics WHERE id = ? AND deleted = 0 AND id <> ? ORDER BY id ASC', [$id, $topic_id])->results();

// Smarty
$smarty->assign([
    'MERGE_TOPICS' => $haberler_language->get('haberler', 'merge_topics'),
    'MERGE_INSTRUCTIONS' => $haberler_language->get('haberler', 'merge_instructions'),
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
    'CANCEL' => $language->get('general', 'cancel'),
    'CONFIRM_CANCEL' => $language->get('general', 'confirm_cancel'),
    'CANCEL_LINK' => URL::build('/haberler/haber/' . urlencode($topic_id)),
    'TOPICS' => $topics
]);

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
$template->displayTemplate('haberler/merge.tpl', $smarty);
