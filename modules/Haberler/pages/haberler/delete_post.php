<?php
/*
 *	Made by Samerton
 *  https://github.com/RadomeWEB/Radome/
 *  RadomeWEB version 2.0.0-pr8
 *
 *  License: GPL-3.0
 *
 *  Delete post page
 */

if (!$user->isLoggedIn()) {
    Redirect::to(URL::build('/haberler'));
}

// Always define page name
const PAGE = 'haberler';

$haberler = new Haberler();

// Check params are set
if (!isset($_GET['pid']) || !is_numeric($_GET['pid'])) {
    Redirect::to(URL::build('/haberler'));
}

// Get post and haberler ID
$post = DB::getInstance()->get('posts', ['id', $_GET['pid']])->results();
if (!count($post)) {
    Redirect::to(URL::build('/haberler'));
}
$post = $post[0];

$haberler_id = $post->haberler_id;

if ($haberler->canModerateHaberler($haberler_id, $user->getAllGroupIds())) {
    if (Input::exists()) {
        if (Token::check()) {
            if (isset($_POST['tid'])) {
                // Is it the OP?
                if (isset($_POST['number']) && Input::get('number') == 10) {

                    DB::getInstance()->update('habers', Input::get('tid'), [
                        'deleted' => true,
                    ]);

                    Log::getInstance()->log(Log::Action('haberlers/post/delete'), Input::get('tid'));
                    $opening_post = 1;

                    $redirect = URL::build('/haberler'); // Create a redirect string
                } else {
                    $redirect = URL::build('/haberler/konu/' . urlencode(Input::get('tid')));
                }
            } else {
                $redirect = URL::build('/haberler/arama/', 'p=1&s=' . urlencode($_POST['search_string']));
            }

            DB::getInstance()->update('posts', Input::get('pid'), [
                'deleted' => true,
            ]);

            if (isset($opening_post)) {
                $posts = DB::getInstance()->get('posts', ['haber_id', $_POST['tid']])->results();

                if (count($posts)) {
                    foreach ($posts as $post) {
                        DB::getInstance()->update('posts', $post->id, [
                            'deleted' => true,
                        ]);
                        Log::getInstance()->log(Log::Action('haberlers/post/delete'), $post->id);
                    }
                }
            }

            // Update latest posts in categories
            $haberler->updateHaberlerLatestPosts();
            $haberler->updateTopicLatestPosts();

            Redirect::to($redirect);

        } else {
            Redirect::to(URL::build('/haberler/konu/' . urlencode(Input::get('tid'))));
        }
    } else {
        echo 'No post selected';
    }
} else {
    Redirect::to(URL::build('/haberler'));
}
die();
