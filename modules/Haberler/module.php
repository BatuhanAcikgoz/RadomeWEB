<?php
/*
 *  Made by Samerton
 *  https://github.com/RadomeWEB/Radome/
 *  RadomeWEB version 2.0.0
 *
 *  License: GPL-3.0
 *
 *  Haberler module file
 */

class Haberler_Module extends Module {

    private Language $_language;
    private Language $_haberler_language;

    public function __construct(Language $language, Language $haberler_language, Pages $pages) {
        $this->_language = $language;
        $this->_haberler_language = $haberler_language;

        $name = 'Haberler';
        $author = '<a href="https://samerton.me" target="_blank" rel="nofollow noopener">Reeignn</a>';
        $module_version = '2.0.2';
        $radome_version = '2.0.2';

        parent::__construct($this, $name, $author, $module_version, $radome_version);

        // Define URLs which belong to this module
        $pages->add('Haberler', '/panel/haberlerlar', 'pages/panel/settings.php');

        $pages->add('Haberler', '/haberler', 'pages/haberler/index.php', 'haberler', true);
        $pages->add('Haberler', '/haberler/hata', 'pages/haberler/error.php');
        $pages->add('Haberler', '/haberler/bakis', 'pages/haberler/view_haberler.php');
        $pages->add('Haberler', '/haberler/konu', 'pages/haberler/view_haber.php');
        $pages->add('Haberler', '/haberler/yeni', 'pages/haberler/new_haber.php');
        $pages->add('Haberler', '/haberler/kaldir', 'pages/haberler/delete.php');
        $pages->add('Haberler', '/haberler/duzenle', 'pages/haberler/edit.php');
        $pages->add('Haberler', '/haberler/kitle', 'pages/haberler/lock.php');
        $pages->add('Haberler', '/haberler/sabitle', 'pages/haberler/stick.php');
        $pages->add('Haberler', '/haberler/arama', 'pages/haberler/search.php');

        // UserCP
        $pages->add('Haberler', '/kullanici/takip_edilen_konular', 'pages/user/following_posts.php');

        // Redirects
        $pages->add('Haberler', '/haberler/konuyu_goruntule', 'pages/haberler/redirect.php');
        $pages->add('Haberler', '/haberler/haberler_goruntule', 'pages/haberler/redirect.php');

        // Hooks
        EventHandler::registerEvent('newTopic',
            $this->_haberler_language->get('haberler', 'new_topic_hook_info'),
            [
                'user_id' => $this->_language->get('admin', 'user_id'),
                'username' => $this->_language->get('user', 'username'),
                'nickname' => $this->_language->get('user', 'nickname'),
                'content' => $this->_language->get('general', 'content'),
                'content_full' => $this->_language->get('general', 'full_content'),
                'avatar_url' => $this->_language->get('user', 'avatar'),
                'title' => $this->_haberler_language->get('haberler', 'topic_title'),
                'url' => $this->_language->get('general', 'url'),
                'available_hooks' => $this->_haberler_language->get('haberler', 'available_hooks')
            ]
        );

        EventHandler::registerEvent('prePostCreate',
            $this->_haberler_language->get('haberler', 'pre_post_create_hook_info'),
            [
                'content' => $this->_language->get('general', 'content'),
                'post_id' => $this->_haberler_language->get('haberler', 'post_id'),
                'topic_id' => $this->_haberler_language->get('haberler', 'topic_id'),
                'user' => $this->_haberler_language->get('haberler', 'user_object')
            ],
            true,
            true
        );

        EventHandler::registerEvent('prePostEdit',
            $this->_haberler_language->get('haberler', 'pre_post_edit_hook_info'),
            [
                'content' => $this->_language->get('general', 'content'),
                'post_id' => $this->_haberler_language->get('haberler', 'post_id'),
                'topic_id' => $this->_haberler_language->get('haberler', 'topic_id'),
                'user' => $this->_haberler_language->get('haberler', 'user_object')
            ],
            true,
            true
        );

        EventHandler::registerEvent('preTopicCreate',
            $this->_haberler_language->get('haberler', 'pre_topic_create_hook_info'),
            [
                'content' => $this->_language->get('general', 'content'),
                'post_id' => $this->_haberler_language->get('haberler', 'post_id'),
                'topic_id' => $this->_haberler_language->get('haberler', 'topic_id'),
                'user' => $this->_haberler_language->get('haberler', 'user_object')
            ],
            true,
            true
        );

        EventHandler::registerEvent('preTopicEdit',
            $this->_haberler_language->get('haberler', 'pre_topic_edit_hook_info'),
            [
                'content' => $this->_language->get('general', 'content'),
                'post_id' => $this->_haberler_language->get('haberler', 'post_id'),
                'topic_id' => $this->_haberler_language->get('haberler', 'topic_id'),
                'topic_title' => $this->_haberler_language->get('haberler', 'topic_title'),
                'user' => $this->_haberler_language->get('haberler', 'user_object')
            ],
            true,
            true
        );

        EventHandler::registerEvent('renderPost',
            $this->_haberler_language->get('haberler', 'render_post'),
            [
                'content' => $this->_language->get('general', 'content')
            ],
            true,
            true
        );

        EventHandler::registerEvent('renderPostEdit',
            $this->_haberler_language->get('haberler', 'render_post_edit'),
            [
                'content' => $this->_language->get('general', 'content')
            ],
            true,
            true
        );

        EventHandler::registerEvent('topicReply',
            $this->_haberler_language->get('haberler', 'topic_reply'),
            [
                'user_id' => $this->_language->get('admin', 'user_id'),
                'username' => $this->_language->get('user', 'username'),
                'nickname' => $this->_language->get('user', 'nickname'),
                'content' => $this->_language->get('general', 'content'),
                'content_full' => $this->_language->get('general', 'full_content'),
                'avatar_url' => $this->_language->get('user', 'avatar'),
                'title' => $this->_haberler_language->get('haberler', 'topic_title'),
                'url' => $this->_language->get('general', 'url'),
                'topic_author_user_id' => $this->_haberler_language->get('haberler', 'topic_author_uuid'),
                'topic_author_username' => $this->_haberler_language->get('haberler', 'topic_author_username'),
                'topic_author_nickname' => $this->_haberler_language->get('haberler', 'topic_author_nickname'),
                'topic_id' => $this->_haberler_language->get('haberler', 'topic_id'),
                'post_id' => $this->_haberler_language->get('haberler', 'post_id'),
            ]
        );

        EventHandler::registerListener('prePostCreate', 'MentionsHook::preCreate');
        EventHandler::registerListener('prePostEdit', 'MentionsHook::preEdit');
        EventHandler::registerListener('preTopicCreate', 'MentionsHook::preCreate');
        EventHandler::registerListener('preTopicEdit', 'MentionsHook::preEdit');

        EventHandler::registerListener('renderPost', 'ContentHook::purify');
        EventHandler::registerListener('renderPost', 'ContentHook::codeTransform', 15);
        EventHandler::registerListener('renderPost', 'ContentHook::decode', 20);
        EventHandler::registerListener('renderPost', 'ContentHook::renderEmojis', 10);
        EventHandler::registerListener('renderPost', 'ContentHook::replaceAnchors', 15);
        EventHandler::registerListener('renderPost', 'MentionsHook::parsePost', 5);

        EventHandler::registerListener('renderPostEdit', 'ContentHook::purify');
        EventHandler::registerListener('renderPostEdit', 'ContentHook::codeTransform', 15);
        EventHandler::registerListener('renderPostEdit', 'ContentHook::decode', 20);
        EventHandler::registerListener('renderPostEdit', 'ContentHook::replaceAnchors', 15);

        EventHandler::registerListener('cloneGroup', 'CloneGroupHaberlerHook::execute');
    }

    public function onInstall() {
        // Not necessary for Haberler
    }

    public function onUninstall() {

    }

    public function onEnable() {
        // No actions necessary
    }

    public function onDisable() {
        // No actions necessary
    }

    public function onPageLoad($user, $pages, $cache, $smarty, $navs, $widgets, $template) {
        // AdminCP
        PermissionHandler::registerPermissions('Haberler', [
            'admincp.haberlers' => $this->_language->get('moderator', 'staff_cp') . ' &raquo; ' . $this->_haberler_language->get('haberler', 'haberler')
        ]);

        // Sitemap
        $pages->registerSitemapMethod([Haberler_Sitemap::class, 'generateSitemap']);

        // Add link to navbar
        $cache->setCache('nav_location');
        if (!$cache->isCached('haberler_location')) {
            $link_location = 1;
            $cache->store('haberler_location', 1);
        } else {
            $link_location = $cache->retrieve('haberler_location');
        }

        $cache->setCache('navbar_order');
        if (!$cache->isCached('haberler_order')) {
            $haberler_order = 2;
            $cache->store('haberler_order', 2);
        } else {
            $haberler_order = $cache->retrieve('haberler_order');
        }

        $cache->setCache('navbar_icons');
        if (!$cache->isCached('haberler_icon')) {
            $icon = '';
        } else {
            $icon = $cache->retrieve('haberler_icon');
        }

        switch ($link_location) {
            case 1:
                // Navbar
                $navs[0]->add('haberler', $this->_haberler_language->get('haberler', 'haberler'), URL::build('/haberler'), 'top', null, $haberler_order, $icon);
                break;
            case 2:
                // "More" dropdown
                $navs[0]->addItemToDropdown('more_dropdown', 'haberler', $this->_haberler_language->get('haberler', 'haberler'), URL::build('/haberler'), 'top', null, $icon, $haberler_order);
                break;
            case 3:
                // Footer
                $navs[0]->add('haberler', $this->_haberler_language->get('haberler', 'haberler'), URL::build('/haberler'), 'footer', null, $haberler_order, $icon);
                break;
        }

        // Widgets
        if (defined('FRONT_END') || (defined('PANEL_PAGE') && str_contains(PANEL_PAGE, 'widget'))) {
            // Latest posts
            $widgets->add(new LatestPostsWidget($this->_haberler_language->get('haberler', 'latest_posts'), $this->_haberler_language->get('haberler', 'by'), $smarty, $cache, $user, $this->_language));
        }

        // Front end or back end?
        if (defined('FRONT_END')) {
            // Global variables if user is logged in
            if ($user->isLoggedIn()) {
                // Basic user variables
                $haber_count = DB::getInstance()->get('posts', ['haber_creator', $user->data()->id])->results();
                $haber_count = count($haber_count);
                $smarty->assign('LOGGED_IN_USER_FORUM', [
                    'haber_count' => $haber_count,
                ]);
            }

            if (defined('PAGE') && PAGE == 'user_query') {
                $user_id = $smarty->getTemplateVars('USER_ID');

                if ($user_id) {
                    $haberler = new Haberler();

                    $smarty->assign('TOPICS', $this->_haberler_language->get('haberler', 'x_posts', ['count' => $haberler->getTopicCount($user_id)]));
                }
            }

        } else {
            if (defined('BACK_END')) {
                if ($user->hasPermission('admincp.haberlers')) {
                    $cache->setCache('panel_sidebar');
                    if (!$cache->isCached('haberler_order')) {
                        $order = 12;
                        $cache->store('haberler_order', 12);
                    } else {
                        $order = $cache->retrieve('haberler_order');
                    }

                    if (!$cache->isCached('haberler_settings_icon')) {
                        $icon = '<i class="nav-icon fas fa-cogs"></i>';
                        $cache->store('haberler_settings_icon', $icon);
                    } else {
                        $icon = $cache->retrieve('haberler_settings_icon');
                    }

                    $navs[2]->add('haberler_divider', mb_strtoupper($this->_haberler_language->get('haberler', 'haberler'), 'UTF-8'), 'divider', 'top', null, $order, '');
                    $navs[2]->add('haberler_settings', $this->_language->get('admin', 'settings'), URL::build('/panel/haberlerlar/ayarlar'), 'top', null, $order + 0.1, $icon);

                    if (!$cache->isCached('haberler_icon')) {
                        $icon = '<i class="nav-icon fas fa-comments"></i>';
                        $cache->store('haberler_icon', $icon);
                    } else {
                        $icon = $cache->retrieve('haberler_icon');
                    }

                    $navs[2]->add('haberlers', $this->_haberler_language->get('haberler', 'haberlers'), URL::build('/panel/haberlerlar'), 'top', null, $order + 0.2, $icon);

                    if (!$cache->isCached('haberler_label_icon')) {
                        $icon = '<i class="nav-icon fas fa-tags"></i>';
                        $cache->store('haberler_label_icon', $icon);
                    } else {
                        $icon = $cache->retrieve('haberler_label_icon');
                    }

                    $navs[2]->add('haberler_labels', $this->_haberler_language->get('haberler', 'labels'), URL::build('/panel/haberlerlar/etiketler'), 'top', null, $order + 0.3, $icon);
                }
            }
        }
    }

    public function getDebugInfo(): array {
        return [];
    }
}
