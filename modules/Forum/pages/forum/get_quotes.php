<?php
/*
 *  Made by Reeignn
 *  https://github.com/Verira/RadomeWEB
 *  RadomeWEB v2.1
 *
 *  License: GPL-3.0
 *
 *  Get a list of quotes
 */

if (!$user->isLoggedIn()) {
    die(json_encode(['error' => 'Not logged in']));
}

// Always define page name
const PAGE = 'forum';

// Initialise
$forum = new Forum();

// Get the post data
if (empty($_POST)) {
    die(json_encode(['error' => 'No post data']));
}

$posts = [];

foreach ($_POST['posts'] as $item) {
    $post = $forum->getIndividualPost($item);

    $content = $post['content'];
    $content = preg_replace('~<blockquote(.*?)>(.*)</blockquote>~si', '', $content);

    if ($post['topic_id'] == $_POST['topic']) {
        $post_author = new User($post['creator']);
        $posts[] = [
            'content' => Output::getPurified($content),
            'author_username' => $post_author->getDisplayname(),
            'author_nickname' => $post_author->getDisplayname(true),
            'link' => URL::build('/forum/konu/' . urlencode($post['topic_id']), 'pid=' . urlencode($item))
        ];
    }
}


die(json_encode($posts));
