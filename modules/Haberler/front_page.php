<?php
/*
 *  Made by Samerton
 *  https://github.com/RadomeWEB/Radome/
 *  RadomeWEB version 2.0.0
 *
 *  License: GPL-3.0
 *
 *  Haberler module - front page module
 */

$cache->setCache('news_cache');
if ($cache->isCached('news')) {
    $news = $cache->retrieve('news');
} else {
    $haberler = new Haberler();

    $latest_news = $haberler->getLatestNews(); // Get latest 5 items

    $news = [];

    foreach ($latest_news as $item) {
        $post_user = new User($item['author']);

        $news[] = [
            'id' => $item['haber_id'],
            'url' => URL::build('/haberler/konu/' . urlencode($item['haber_id']) . '-' . $haberler->titleToURL($item['haber_title'])),
            'date' => date(DATE_FORMAT, strtotime($item['haber_date'])),
            'time_ago' => $item['haber_date'],
            'title' => Output::getClean($item['haber_title']),
            'views' => $item['haber_views'],
            'replies' => $item['replies'],
            'author_id' => Output::getClean($item['author']),
            'author_url' => $post_user->getProfileURL(),
            'author_style' => $post_user->getGroupStyle(),
            'author_name' => $post_user->getDisplayname(true),
            'author_nickname' => $post_user->getDisplayname(),
            'author_avatar' => $post_user->getAvatar(64),
            'author_group' => Output::getClean($post_user->getMainGroup()->name),
            'author_group_html' => $post_user->getMainGroup()->group_html,
            'content' => EventHandler::executeEvent('renderPost', ['content' => $item['content']])['content'],
            'label' => $item['label'],
            'labels' => $item['labels']
        ];
    }

    $cache->store('news', $news, 60);
}

$timeago = new TimeAgo(TIMEZONE);
foreach ($news as $key => $item) {
    $news[$key]['time_ago'] = $timeago->inWords($item['time_ago'], $language);
}

$smarty->assign('LATEST_ANNOUNCEMENTS', $haberler_language->get('haberler', 'latest_announcements'));
$smarty->assign('READ_FULL_POST', $haberler_language->get('haberler', 'read_full_post'));
$smarty->assign('NEWS', $news);
$smarty->assign('NO_NEWS', $haberler_language->get('haberler', 'no_news'));
