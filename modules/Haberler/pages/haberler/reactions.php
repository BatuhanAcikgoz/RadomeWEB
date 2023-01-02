<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  React to a post
 */

$haberler = new Haberler();

// User must be logged in to proceed
if (!$user->isLoggedIn()) {
    Redirect::to(URL::build('/haberler'));
}

// Are reactions enabled?
if (Util::getSetting('haberler_reactions') !== '1') {
    Redirect::to(URL::build('/haberler'));
}

// Deal with input
if (Input::exists()) {
    // Validate form input
    if (!isset($_POST['post'], $_POST['reaction']) || !is_numeric($_POST['post']) || !is_numeric($_POST['reaction'])) {
        Redirect::to(URL::build('/haberler'));
    }

    // Get post information
    $post = DB::getInstance()->get('haberlers', ['id', $_POST['post']])->results();

    if (!count($post)) {
        Redirect::to(URL::build('/haberler'));
    }

    $post = $post[0];
    $topic_id = $post->topic_id;

    // Check user can actually view the post
    if (!($haberler->haberlerExist($post->haber_id, $user->getAllGroupIds()))) {
        Redirect::to(URL::build('/haberler/hata/', 'error=not_exist'));
    }

    if (Token::check()) {
        // Check if the user has already reacted to this post
        $user_reacted = DB::getInstance()->get('haberlers_reactions', ['post_id', $post->id])->results();
        if (count($user_reacted)) {
            foreach ($user_reacted as $reaction) {
                if ($reaction->user_given == $user->data()->id) {
                    if ($reaction->reaction_id == $_POST['reaction']) {
                        // Undo reaction
                        DB::getInstance()->delete('haberlers_reactions', ['id', $reaction->id]);
                    } else {
                        // Change reaction
                        DB::getInstance()->update('haberlers_reactions', $reaction->id, [
                            'reaction_id' => $_POST['reaction'],
                            'time' => date('U')
                        ]);
                    }

                    $changed = true;
                    break;
                }
            }
        }

        if (!isset($changed)) {
            // Input new reaction
            DB::getInstance()->insert('haberlers_reactions', [
                'post_id' => $post->id,
                'user_received' => $post->post_creator,
                'user_given' => $user->data()->id,
                'reaction_id' => $_POST['reaction'],
                'time' => date('U')
            ]);

            Log::getInstance()->log(Log::Action('haberlers/react'), $_POST['reaction']);
        }

        // Redirect
    }
    Redirect::to(URL::build('/haberler/topic/' . urlencode($topic_id), 'pid=' . urlencode($post->id)));
} else {
    Redirect::to(URL::build('/haberler'));
}
