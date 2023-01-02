<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Get a list of quotes
 */

if (!$user->isLoggedIn()) {
    die(json_encode(['error' => 'Not logged in']));
}

// Always define page name
const PAGE = 'haberler';

// Initialise
$haberler = new Haberler();

// Get the post data
if (empty($_POST)) {
    die(json_encode(['error' => 'No post data']));
}

$haberlers = [];

foreach ($_POST['haberlers'] as $item) {
    $post = $haberler->getIndividualPost($item);

    $content = $post['content'];
    $content = preg_replace('~<blockquote(.*?)>(.*)</blockquote>~si', '', $content);

    if ($post['topic_id'] == $_POST['topic']) {
        $post_author = new User($post['creator']);
        $haberlers[] = [
            'content' => Output::getPurified($content),
            'author_username' => $post_author->getDisplayname(),
            'author_nickname' => $post_author->getDisplayname(true),
            'link' => URL::build('/haberler/haber/' . urlencode($post['topic_id']), 'pid=' . urlencode($item))
        ];
    }
}


die(json_encode($haberlers));
