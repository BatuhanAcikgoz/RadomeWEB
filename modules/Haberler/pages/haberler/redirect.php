<?php
/*
 *  Made by Reeignn
 *  https://github.com/Verira/RadomeWEB
 *  RadomeWEB v2.1
 *
 *  License: GPL-3.0
 *
 *  Haberler redirects for old links
 */

if (str_contains($route, 'view_haberler')) {
    // Build new haberler URL
    if (isset($_GET['fid']) && is_numeric($_GET['fid'])) {
        $url = URL::build('/haberler/bakis/' . urlencode($_GET['fid']));
    } else {
        $url = URL::build('/haberler');
    }
} else {
    if (str_contains($route, 'view_haber')) {
        // Build new haber URL
        if (isset($_GET['tid']) && is_numeric($_GET['tid'])) {
            $url = URL::build('/haberler/konu/' . urlencode($_GET['tid']));
        } else {
            $url = URL::build('/haberler');
        }
    } else {
        $url = URL::build('/haberler');
    }
}

header('Location: ' . $url, true, 301);
die();
