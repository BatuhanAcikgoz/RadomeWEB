<?php
/*
 *  Made by Reeignn
 *  https://github.com/Verira/RadomeWEB
 *  RadomeWEB v2.1
 *
 *  License: GPL-3.0
 *
 *  Stick/unstick a topic
 */

$forum = new Forum();

// User must be logged in to proceed
if (!$user->isLoggedIn()) {
    Redirect::to(URL::build('/));
}

// Ensure a topic is set via URL parameters
if (isset($_GET['tid'])) {
    if (is_numeric($_GET['tid'])) {
        $topic_id = $_GET['tid'];
    } else {
        Redirect::to(URL::build('/forum/hata/', 'error=not_exist'));
    }
} else {
    Redirect::to(URL::build('/forum/hata/', 'error=not_exist'));
}

// Check topic exists and get forum ID
$topic = DB::getInstance()->get('topics', ['id', $topic_id])->results();

if (!count($topic)) {
    Redirect::to(URL::build('/forum/hata/', 'error=not_exist'));
}

if (!isset($_POST['token']) || !Token::check($_POST['token'])) {
    Session::flash('failure_post', $language->get('general', 'invalid_token'));
    Redirect::to(URL::build('/forum/konu/' . urlencode($topic_id)));
}

$forum_id = $topic[0]->forum_id;

if ($forum->canModerateForum($forum_id, $user->getAllGroupIds())) {
    // Get current status
    if ($topic[0]->sticky == 0) {
        $sticky = 1;
        $status = $forum_language->get('forum', 'topic_stuck');
    } else {
        $sticky = 0;
        $status = $forum_language->get('forum', 'topic_unstuck');
    }

    DB::getInstance()->update('topics', $topic_id, [
        'sticky' => $sticky
    ]);

    Log::getInstance()->log(($sticky == 1) ? Log::Action('forums/topic/stick') : Log::Action('forums/topic/unstick'), $topic[0]->topic_title);

    Session::flash('success_post', $status);
}

Redirect::to(URL::build('/forum/konu/' . urlencode($topic_id)));
