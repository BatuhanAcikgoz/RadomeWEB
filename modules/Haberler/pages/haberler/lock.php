<?php
/*
 *  Made by Reeignn
 *  https://github.com/Verira/RadomeWEB
 *  RadomeWEB v2.1
 *
 *  License: GPL-3.0
 *
 *  Lock/unlock a haber
 */

$haberler = new Haberler();

if ($user->isLoggedIn()) {
    if (!isset($_GET['tid']) || !is_numeric($_GET['tid'])) {
        Redirect::to(URL::build('/haberler/hata/', 'error=not_exist'));
    }

    $haber_id = $_GET['tid'];

    // Check haber exists and get haberler ID
    $haber = DB::getInstance()->get('habers', ['id', $haber_id])->results();

    if (!count($haber)) {
        Redirect::to(URL::build('/haberler/hata/', 'error=not_exist'));
    }

    if (!isset($_POST['token']) || !Token::check($_POST['token'])) {
        Session::flash('failure_post', $language->get('general', 'invalid_token'));
        Redirect::to(URL::build('/haberler/konu/' . urlencode($haber_id)));
    }

    $haberler_id = $haber[0]->haberler_id;

    if ($haberler->canModerateHaberler($haberler_id, $user->getAllGroupIds())) {
        $locked_status = $haber[0]->locked;

        if ($locked_status == 1) {
            $locked_status = 0;
        } else {
            $locked_status = 1;
        }

        DB::getInstance()->update('habers', $haber_id, [
            'locked' => $locked_status
        ]);
        Log::getInstance()->log(Log::Action('haberlers/haber/lock'), ($locked_status == 1) ? $language->get('log', 'info_haberlers_lock') : $language->get('log', 'info_haberlers_unlock'));

        Redirect::to(URL::build('/haberler/konu/' . urlencode($haber_id)));

    } else {
        Redirect::to(URL::build('/haberler'));
    }
} else {
    Redirect::to(URL::build('/haberler'));
}
