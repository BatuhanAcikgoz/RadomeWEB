<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Haberler redirects for old links
 */

if (str_contains($route, 'view_haberler')) {
    // Build new haberler URL
    if (isset($_GET['fid']) && is_numeric($_GET['fid'])) {
        $url = URL::build('/haberler/goruntule/' . urlencode($_GET['fid']));
    } else {
        $url = URL::build('/haberler');
    }
} else {
    if (str_contains($route, 'view_topic')) {
        // Build new topic URL
        if (isset($_GET['tid']) && is_numeric($_GET['tid'])) {
            $url = URL::build('/haberler/topic/' . urlencode($_GET['tid']));
        } else {
            $url = URL::build('/haberler');
        }
    } else {
        $url = URL::build('/haberler');
    }
}

header('Location: ' . $url, true, 301);
die();
