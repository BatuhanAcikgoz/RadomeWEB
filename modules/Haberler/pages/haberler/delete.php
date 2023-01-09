<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr12
 *
 *  License: MIT
 *
 *  Delete topic
 */

if (!$user->isLoggedIn()) {
    Redirect::to(URL::build('/haberler'));
}

// Always define page name
const PAGE = 'haberler';

$haberler = new Haberler();

// Check params are set
if (!isset($_GET['tid']) || !is_numeric($_GET['tid'])) {
    Redirect::to(URL::build('/haberler'));
}

$topic_id = $_GET['tid'];

// Check topic exists
$topic = DB::getInstance()->get('haberlers', ['id', $topic_id])->results();

if (!count($topic)) {
    Redirect::to(URL::build('/haberler'));
}

if (!isset($_POST['token']) || !Token::check($_POST['token'])) {
    Session::flash('failure_post', $language->get('general', 'invalid_token'));
    Redirect::to(URL::build('/haberler/haber/' . urlencode($topic_id)));
}

$topic = $topic[0];

if ($user->hasPermission('admincp.haberlers')) {

    DB::getInstance()->update('haberlers', $topic_id, [
        'deleted' => true,
    ]);
    //TODO: TOPIC
    Log::getInstance()->log(Log::Action('haberlers/topic/delete'), $topic_id);

    $haberlers = DB::getInstance()->get('haberlers', ['id', $topic_id])->results();

    if (count($haberlers)) {
        foreach ($haberlers as $post) {
            DB::getInstance()->update('haberlers', $post->id, [
                'deleted' => true,
            ]);
        }
    }

    // Update latest haberlers in haberlers
    $haberler->updateHaberlerLatestPosts();

}
Redirect::to(URL::build('/haberler'));
