<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr12
 *
 *  License: MIT
 *
 *  Stick/unstick a topic
 */

$haberler = new Haberler();

// User must be logged in to proceed
if (!$user->isLoggedIn()) {
    Redirect::to(URL::build('/haberler'));
}

// Ensure a topic is set via URL parameters
if (isset($_GET['tid'])) {
    if (is_numeric($_GET['tid'])) {
        $topic_id = $_GET['tid'];
    } else {
        Redirect::to(URL::build('/haberler/hata/', 'error=not_exist'));
    }
} else {
    Redirect::to(URL::build('/haberler/hata/', 'error=not_exist'));
}

// Check topic exists and get haberler ID
$topic = DB::getInstance()->get('topics', ['id', $topic_id])->results();

if (!count($topic)) {
    Redirect::to(URL::build('/haberler/hata/', 'error=not_exist'));
}

if (!isset($_POST['token']) || !Token::check($_POST['token'])) {
    Session::flash('failure_post', $language->get('general', 'invalid_token'));
    Redirect::to(URL::build('/haberler/topic/' . urlencode($topic_id)));
}

$haber_id = $topic[0]->haber_id;

if ($haberler->canModerateHaberler($haber_id, $user->getAllGroupIds())) {
    // Get current status
    if ($topic[0]->sticky == 0) {
        $sticky = 1;
        $status = $haberler_language->get('haberler', 'topic_stuck');
    } else {
        $sticky = 0;
        $status = $haberler_language->get('haberler', 'topic_unstuck');
    }

    DB::getInstance()->update('topics', $topic_id, [
        'sticky' => $sticky
    ]);

    Log::getInstance()->log(($sticky == 1) ? Log::Action('haberlers/topic/stick') : Log::Action('haberlers/topic/unstick'), $topic[0]->topic_title);

    Session::flash('success_post', $status);
}

Redirect::to(URL::build('/haberler/topic/' . urlencode($topic_id)));
