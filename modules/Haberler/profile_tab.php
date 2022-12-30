<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Haberler module - haberler profile tab
 */

if (!isset($haberler) || (!$haberler instanceof Haberler)) {
    $haberler = new Haberler();
}

// Get latest posts
$latest_posts = DB::getInstance()->orderWhere('posts', 'post_creator = ' . $query->id . ' AND deleted = 0', 'post_date', 'DESC LIMIT 15')->results();
if (!count($latest_posts)) {
    $smarty->assign('NO_POSTS', $haberler_language->get('haberler', 'user_no_posts'));
} else {
    // Check permissions
    $n = 0;

    if (!$user->isLoggedIn()) {
        $groups = [0];
    } else {
        $groups = $user->getAllGroupIds();
    }

    // Array to assign posts to
    $posts = [];

    $permissions = [];
    $topic_titles = [];
    foreach ($latest_posts as $latest_post) {
        if ($n == 5) {
            break;
        }

        // Is the post somewhere the user can view?
        if (!isset($permissions[$latest_post->haberler_id])) {
            $permission = false;
            $haberler_permissions = DB::getInstance()->get('haberlers_permissions', ['haberler_id', $latest_post->haberler_id])->results();
            foreach ($haberler_permissions as $haberler_permission) {
                if (in_array($haberler_permission->group_id, $groups)) {
                    if ($haberler_permission->view == 1 && $haberler_permission->view_other_topics == 1) {
                        $permission = true;
                        break;
                    }
                }
            }
            $permissions[$latest_post->haberler_id] = $permission;
        } else {
            $permission = $permissions[$latest_post->haberler_id];
        }

        if ($permission != true) {
            continue;
        }

        // Check the post isn't deleted
        if ($latest_post->deleted == 1) {
            continue;
        }

        // Get topic title
        if (!isset($topic_titles[$latest_post->topic_id])) {
            $topic_title = DB::getInstance()->get('topics', ['id', $latest_post->topic_id])->results();
            if (!count($topic_title)) {
                continue;
            }
            $topic_title = Output::getClean($topic_title[0]->topic_title);
            $topic_titles[$latest_post->topic_id] = $topic_title;
        } else {
            $topic_title = $topic_titles[$latest_post->topic_id];
        }

        if (is_null($latest_post->created)) {
            $date_friendly = $timeago->inWords($latest_post->post_date, $language);
            $date_full = date(DATE_FORMAT, strtotime($latest_post->post_date));
        } else {
            $date_friendly = $timeago->inWords($latest_post->created, $language);
            $date_full = date(DATE_FORMAT, $latest_post->created);
        }

        $posts[] = [
            'link' => URL::build('/haberler/topic/' . $latest_post->topic_id . '-' . $haberler->titleToURL($topic_title), 'pid=' . $latest_post->id),
            'title' => $topic_title,
            'content' => EventHandler::executeEvent('renderPost', ['content' => $latest_post->post_content])['content'],
            'date_friendly' => $date_friendly,
            'date_full' => $date_full
        ];

        $n++;
    }
}

// Smarty
$smarty->assign([
    'PF_LATEST_POSTS' => (isset($posts)) ? $posts : [],
    'PF_LATEST_POSTS_TITLE' => $haberler_language->get('haberler', 'latest_posts'),
    'HABERLER_TAB_TITLE' => $haberler_language->get('haberler', 'haberler')
]);
