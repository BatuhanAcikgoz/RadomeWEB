<?php
/*
 *  Made by Reeignn
 *  https://github.com/Verira/RadomeWEB
 *  RadomeWEB v2.1
 *
 *  License: GPL-3.0
 *
 *  Move a haber
 */

const PAGE = 'haberler';
$page_title = $haberler_language->get('haberler', 'move_haber');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

$haberler = new Haberler();

if (!isset($_GET['tid']) || !is_numeric($_GET['tid'])) {
    Redirect::to(URL::build('/haberler/hata/', 'error=not_exist'));
}

$haber_id = $_GET['tid'];
$haber = DB::getInstance()->get('habers', ['id', $haber_id])->results();
if (!count($haber)) {
    Redirect::to(URL::build('/haberler/hata/', 'error=not_exist'));
}
$haberler_id = $haber[0]->haberler_id;
$haber = $haber[0];

if ($haberler->canModerateHaberler($haberler_id, $user->getAllGroupIds())) {
    if (Input::exists()) {
        if (Token::check()) {
            $validation = Validate::check($_POST, [
                'haberler' => [
                    Validate::REQUIRED => true
                ]
            ]);

            // Ensure haberler we're moving to exists
            $haberler_moving_to = DB::getInstance()->get('haberlers', ['id', Input::get('haberler')])->results();
            if (!count($haberler_moving_to)) {
                Redirect::to(URL::build('/haberler'));
            }

            $posts_to_move = DB::getInstance()->get('posts', ['haber_id', $haber_id])->results();
            if ($validation->passed()) {

                DB::getInstance()->update('habers', $haber->id, [
                    'haberler_id' => Input::get('haberler')
                ]);
                foreach ($posts_to_move as $post_to_move) {
                    DB::getInstance()->update('posts', $post_to_move->id, [
                        'haberler_id' => Input::get('haberler')
                    ]);
                }

                //TODO: Topic name & and Haberlers name
                Log::getInstance()->log(Log::Action('haberlers/move'), Output::getClean($haber_id) . ' => ' . Output::getClean(Input::get('haberler')));

                // Update latest posts in categories
                $haberler->updateHaberlerLatestPosts();
                $haberler->updateTopicLatestPosts();

                Redirect::to(URL::build('/haberler/konu/' . $haber_id));

            } else {
                echo 'Error processing that action. <a href="' . URL::build('/haberler') . '">Haberler index</a>';
            }
            die();
        }
    }
} else {
    Redirect::to(URL::build('/haberler'));
}

// Generate navbar and footer
require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Get a list of all haberlers
$template_haberlers = [];

$categories = DB::getInstance()->orderWhere('haberlers', 'parent = 0', 'haberler_order', 'ASC')->results();
foreach ($categories as $category) {
    if (!$haberler->haberlerExist($category->id, $user->getAllGroupIds())) {
        continue;
    }

    $to_add = new stdClass();
    $to_add->id = Output::getClean($category->id);
    $to_add->haberler_title = Output::getClean($category->haberler_title);
    $to_add->category = true;
    $template_haberlers[] = $to_add;


    $haberlers = DB::getInstance()->query('SELECT * FROM rw_haberlers WHERE parent = ? ORDER BY haberler_order ASC', [$category->id]);

    if ($haberlers->count()) {
        $haberlers = $haberlers->results();
        foreach ($haberlers as $item) {
            if (!$haberler->haberlerExist($item->id, $user->getAllGroupIds())) {
                continue;
            }

            if ($item->id !== $haberler_id) {
                $to_add = new stdClass();
                $to_add->id = Output::getClean($item->id);
                $to_add->haberler_title = Output::getClean($item->haberler_title);
                $to_add->category = false;
                $template_haberlers[] = $to_add;
            }

            // Subhaberlers
            $subhaberlers = $haberler->getAnySubhaberlers($item->id, $user->getAllGroupIds());

            if (count($subhaberlers)) {
                foreach ($subhaberlers as $subhaberler) {
                    $template_haberlers[] = $subhaberler;
                }
            }
        }
    }
}

// Assign Smarty variables
$smarty->assign([
    'MOVE_TOPIC' => $haberler_language->get('haberler', 'move_haber'),
    'MOVE_TO' => $haberler_language->get('haberler', 'move_haber_to'),
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
    'CANCEL' => $language->get('general', 'cancel'),
    'CONFIRM_CANCEL' => $language->get('general', 'confirm_cancel'),
    'CANCEL_LINK' => URL::build('/haberler/konu/' . urlencode($haber->id)),
    'FORUMS' => $template_haberlers
]);

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
$template->displayTemplate('haberler/move.tpl', $smarty);
