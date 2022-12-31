<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Report a post
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

$post = DB::getInstance()->get('haberlers', ['id', $_POST['post']])->results();
if (!count($post)) {
    // Doesn't exist
    Redirect::to(URL::build('/haberler'));
}
$post = $post[0];

// Check token
if (Token::check()) {
    // Valid token
    // Ensure user hasn't already submitted a report for this post
    $reports = DB::getInstance()->get('reports', ['reported_post', $_POST['post']])->results();

    if (count($reports)) {
        foreach ($reports as $report) {
            if ($report->reporter_id == $user->data()->id && $report->status == 0) {
                // User already has an open report
                Session::flash('failure_post', $haberler_language->get('haberler', 'post_already_reported'));
                Redirect::to(URL::build('/haberler/topic/' . urlencode($_POST['topic'])));
            }
        }
    }

    $validation = Validate::check($_POST, [
        'reason' => [
            Validate::REQUIRED => true,
            Validate::MIN => 2,
            Validate::MAX => 1024
        ]
    ]);

    if ($validation->passed()) {
        Report::create($language, $user, new User($post->post_creator), [
            'type' => Report::ORIGIN_WEBSITE,
            'reporter_id' => $user->data()->id,
            'reported_id' => $post->post_creator,
            'report_reason' => Output::getClean($_POST['reason']),
            'updated_by' => $user->data()->id,
            'reported_post' => $post->id,
            'link' => URL::build('/haberler/topic/' . urlencode($_POST['topic']), 'pid=' . urlencode($_POST['post']))
        ]);

        Log::getInstance()->log(Log::Action('misc/report'), $post->post_creator);
        Session::flash('success_post', $language->get('user', 'report_created'));
    } else {
        // Invalid report content
        Session::flash('failure_post', $language->get('user', 'invalid_report_content'));
    }
} else {
    // Invalid token
    Session::flash('failure_post', $language->get('general', 'invalid_token'));
}
Redirect::to(URL::build('/haberler/topic/' . urlencode($_POST['topic'])));
