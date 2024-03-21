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
$social_media = Settings::get('fb_url');
if ($social_media != null) {
    $social_media_icons[] = [
        'short' => 'fb',
        'long' => 'facebook',
        'link' => Output::getClean($social_media),
        'text' => 'Facebook'
    ];
}

// Twitter
$social_media = Settings::get('twitter_url');
if ($social_media != null) {
    $social_media_icons[] = [
        'short' => 'tw',
        'long' => 'twitter',
        'link' => Output::getClean($social_media),
        'text' => 'Twitter'
    ];
}

// Youtube
$social_media = Settings::get('youtube_url');
if ($social_media != null) {
    $social_media_icons[] = [
        'short' => 'gp',
        'long' => 'youtube',
        'link' => Output::getClean($social_media),
        'text' => 'YouTube'
    ];
}

// Discord
$social_media = Settings::get('discord_url');
if ($social_media != null) {
    $social_media_icons[] = [
        'short' => 'dc',
        'long' => 'discord',
        'link' => Output::getClean($social_media),
        'text' => 'Discord'
    ];
}


//Instagram
$social_media = Settings::get('instagram_url');
if ($social_media != null) {
    $social_media_icons[] = [
        'short' => 'insta',
        'long' => 'instagram',
        'link' => Output::getClean($social_media),
        'text' => 'Instagram'
    ];
}

$youtube_url = Settings::get('youtube_url');
$twitter_url = Settings::get('twitter_url');
$instagram_url = Settings::get('instagram_url');
$discord_url = Settings::get('discord_url');
$twitter_style = Settings::get('twitter_style');
$fb_url = Settings::get('fb_url');

// Smarty template
// Assign to Smarty variables
$smarty->assign([
    'SOCIAL_MEDIA_ICONS' => $social_media_icons,
    'PAGE_LOAD_TIME' => Settings::get('page_loading'),
    'FACEBOOK_URL_VALUE' => Output::getClean($fb_url),
    'INSTAGRAM_URL_VALUE' => Output::getClean($instagram_url),
    'DISCORD_URL_VALUE' => Output::getClean($discord_url),
    'YOUTUBE_URL_VALUE' => Output::getClean($youtube_url),
    'TWITTER_URL_VALUE' => Output::getClean($twitter_url),
    'FOOTER_NAVIGATION' => $navigation->returnNav('footer')
]);

// Terms
$smarty->assign('TERMS_LINK', URL::build('/sartlar'));
$smarty->assign('TERMS_TEXT', $language->get('user', 'terms_and_conditions'));

// Privacy
$smarty->assign('PRIVACY_LINK', URL::build('/gizlilik'));
$smarty->assign('PRIVACY_TEXT', $language->get('general', 'privacy_policy'));
