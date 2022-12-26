<?php
/*
 *  Made by Reeignn
 *  https://github.com/Verira/RadomeWEB
 *  RadomeWEB v2.1
 *
 *  License: GPL-3.0
 *
 *  Delete haber
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

$haber_id = $_GET['tid'];

// Check haber exists
$haber = DB::getInstance()->get('habers', ['id', $haber_id])->results();

if (!count($haber)) {
    Redirect::to(URL::build('/haberler'));
}

if (!isset($_POST['token']) || !Token::check($_POST['token'])) {
    Session::flash('failure_post', $language->get('general', 'invalid_token'));
    Redirect::to(URL::build('/haberler/konu/' . urlencode($haber_id)));
}

$haber = $haber[0];

if ($haberler->canModerateHaberler($haber->haberler_id, $user->getAllGroupIds())) {

    DB::getInstance()->update('habers', $haber_id, [
        'deleted' => true,
    ]);
    //TODO: TOPIC
    Log::getInstance()->log(Log::Action('haberlers/haber/delete'), $haber_id);

    $posts = DB::getInstance()->get('posts', ['haber_id', $haber_id])->results();

    if (count($posts)) {
        foreach ($posts as $post) {
            DB::getInstance()->update('posts', $post->id, [
                'deleted' => true,
            ]);
        }
    }

    // Update latest posts in haberlers
    $haberler->updateHaberlerLatestPosts();

}
Redirect::to(URL::build('/haberler'));
