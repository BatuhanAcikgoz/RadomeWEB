<?php
/*
 *  Made by Reeignn
 *  https://github.com/Verira/RadomeWEB
 *  RadomeWEB v2.1
 *
 *  License: GPL-3.0
 *
 *  Generate footer
 */

// Get social media icons if enabled

$social_media_icons = [];

// Facebook
$social_media = Util::getSetting('fb_url');
if ($social_media != null) {
    $social_media_icons[] = [
        'short' => 'fb',
        'long' => 'facebook',
        'link' => Output::getClean($social_media),
        'text' => 'Facebook'
    ];
}

// Twitter
$social_media = Util::getSetting('twitter_url');
if ($social_media != null) {
    $social_media_icons[] = [
        'short' => 'tw',
        'long' => 'twitter',
        'link' => Output::getClean($social_media),
        'text' => 'Twitter'
    ];
}

// Youtube
$social_media = Util::getSetting('youtube_url');
if ($social_media != null) {
    $social_media_icons[] = [
        'short' => 'gp',
        'long' => 'youtube',
        'link' => Output::getClean($social_media),
        'text' => 'YouTube'
    ];
}

// Discord
$social_media = Util::getSetting('discord_url');
if ($social_media != null) {
    $social_media_icons[] = [
        'short' => 'dc',
        'long' => 'discord',
        'link' => Output::getClean($social_media),
        'text' => 'Discord'
    ];
}


//Instagram
$social_media = Util::getSetting('instagram_url');
if ($social_media != null) {
    $social_media_icons[] = [
        'short' => 'insta',
        'long' => 'instagram',
        'link' => Output::getClean($social_media),
        'text' => 'Instagram'
    ];
}

// Smarty template
// Assign to Smarty variables
$smarty->assign([
    'SOCIAL_MEDIA_ICONS' => $social_media_icons,
    'PAGE_LOAD_TIME' => Util::getSetting('page_loading'),
    'FOOTER_NAVIGATION' => $navigation->returnNav('footer')
]);

// Terms
$smarty->assign('TERMS_LINK', URL::build('/sartlar'));
$smarty->assign('TERMS_TEXT', $language->get('user', 'terms_and_conditions'));

// Privacy
$smarty->assign('PRIVACY_LINK', URL::build('/gizlilik'));
$smarty->assign('PRIVACY_TEXT', $language->get('general', 'privacy_policy'));
