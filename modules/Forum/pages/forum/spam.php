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
    Redirect::to(URL::build('/'));
}

// Always define page name
const PAGE = 'forum';

// Initialise
$forum = new Forum();

// Get the post
if (!isset($_POST['post']) || !is_numeric($_POST['post'])) {
    Redirect::to(URL::build('/'));
}

$post = DB::getInstance()->get('posts', ['id', $_POST['post']])->results();
if (!count($post)) {
    // Doesn't exist
    Redirect::to(URL::build('/'));
}
$post = $post[0];

// Check the user can moderate the forum
if ($forum->canModerateForum($post->forum_id, $user->getAllGroupIds())) {
    // Check token
    if (Token::check()) {
        // Valid token, go ahead and mark the user as spam

        // Get user
        $banned_user = new User($post->post_creator);

        $is_admin = $banned_user->canViewStaffCP();

        // Ensure user is not admin
        if ($is_admin) {
            Session::flash('failure_post', $language->get('moderator', 'cant_ban_admin'));
            Redirect::to(URL::build('/forum/konu/' . urlencode($post->topic_id), 'pid=' . urlencode($post->id)));
        }

        // Delete all posts from the user
        DB::getInstance()->query('UPDATE rw_posts SET `deleted` = 1 WHERE `post_creator` = ?', [$post->post_creator]);

        // Delete all topics from the user
        DB::getInstance()->query(
            'UPDATE rw_posts SET `deleted` = 1 WHERE `topic_id` IN (SELECT `id` FROM rw_topics WHERE `topic_creator` = ?)',
            [$post->post_creator]
        );
        DB::getInstance()->query('UPDATE rw_topics SET `deleted` = 1 WHERE `topic_creator` = ?', [$post->post_creator]);

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
        Redirect::to(URL::build('/'));
    } else {
        // Invalid token
        Redirect::to(URL::build('/forum/konu/' . urlencode($post->topic_id), 'pid=' . urlencode($post->id)));
    }
} else {
    // Can't moderate forum
    Redirect::to(URL::build('/'));
}
