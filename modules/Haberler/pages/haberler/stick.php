<?php
/*
 *  Made by Reeignn
 *  https://github.com/Verira/RadomeWEB
 *  RadomeWEB v2.1
 *
 *  License: GPL-3.0
 *
 *  Stick/unstick a haber
 */

$haberler = new Haberler();

// User must be logged in to proceed
if (!$user->isLoggedIn()) {
    Redirect::to(URL::build('/haberler'));
}

// Ensure a haber is set via URL parameters
if (isset($_GET['tid'])) {
    if (is_numeric($_GET['tid'])) {
        $haber_id = $_GET['tid'];
    } else {
        Redirect::to(URL::build('/haberler/hata/', 'error=not_exist'));
    }
} else {
    Redirect::to(URL::build('/haberler/hata/', 'error=not_exist'));
}

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
    // Get current status
    if ($haber[0]->sticky == 0) {
        $sticky = 1;
        $status = $haberler_language->get('haberler', 'haber_stuck');
    } else {
        $sticky = 0;
        $status = $haberler_language->get('haberler', 'haber_unstuck');
    }

    DB::getInstance()->update('habers', $haber_id, [
        'sticky' => $sticky
    ]);

    Log::getInstance()->log(($sticky == 1) ? Log::Action('haberlers/haber/stick') : Log::Action('haberlers/haber/unstick'), $haber[0]->haber_title);

    Session::flash('success_post', $status);
}

Redirect::to(URL::build('/haberler/konu/' . urlencode($haber_id)));
