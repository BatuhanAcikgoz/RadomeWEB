<?php
/*
 *  Made by Reeignn
 *  https://github.com/Verira/RadomeWEB
 *  RadomeWEB v2.1
 *
 *  License: GPL-3.0
 *
 *  Mark a post as spam
 */

if (!$user->isLoggedIn()) {
    Redirect::to(URL::build('/haberler'));
}

// Always define page name
const PAGE = 'haberler';

// Initialise
$haberler = new Haberler();

// Get the post
if (!isset($_POST['post']) || !is_numeric($_POST['post'])) {
    Redirect::to(URL::build('/haberler'));
}

$post = DB::getInstance()->get('posts', ['id', $_POST['post']])->results();
if (!count($post)) {
    // Doesn't exist
    Redirect::to(URL::build('/haberler'));
}
$post = $post[0];

// Check the user can moderate the haberler
if ($haberler->canModerateHaberler($post->haberler_id, $user->getAllGroupIds())) {
    // Check token
    if (Token::check()) {
        // Valid token, go ahead and mark the user as spam

        // Get user
        $banned_user = new User($post->post_creator);

        $is_admin = $banned_user->canViewStaffCP();

        // Ensure user is not admin
        if ($is_admin) {
            Session::flash('failure_post', $language->get('moderator', 'cant_ban_admin'));
            Redirect::to(URL::build('/haberler/konu/' . urlencode($post->haber_id), 'pid=' . urlencode($post->id)));
        }

        // Delete all posts from the user
        DB::getInstance()->delete('posts', ['post_creator', $post->post_creator]);

        // Delete all habers from the user
        DB::getInstance()->delete('habers', ['haber_creator', $post->post_creator]);

        // Log user out
        $banned_user_ip = $banned_user->data()->lastip;

        // Ban IP
        DB::getInstance()->insert('ip_bans', [
            'ip' => $banned_user_ip,
            'banned_by' => $user->data()->id,
            'banned_at' => date('U'),
            'reason' => 'Spam'
        ]);

        // Ban user
        DB::getInstance()->update('users', $post->post_creator, [
            'isbanned' => true,
        ]);

        // Redirect
        Session::flash('spam_info', $language->get('moderator', 'user_marked_as_spam'));
        Redirect::to(URL::build('/haberler'));
    } else {
        // Invalid token
        Redirect::to(URL::build('/haberler/konu/' . urlencode($post->haber_id), 'pid=' . urlencode($post->id)));
    }
} else {
    // Can't moderate haberler
    Redirect::to(URL::build('/haberler'));
}
