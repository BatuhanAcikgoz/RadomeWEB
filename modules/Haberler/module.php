<?php
/*
 *  Made by Samerton
 *  https://github.com/RadomeWEB/Radome/
 *  RadomeWEB version 2.0.0
 *
 *  License: MIT
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
        $author = '<a href="https://batuhanacikgoz.com.tr" target="_blank" rel="nofollow noopener">Reeignn</a>';
        $module_version = '2.0.2';
        $radome_version = '2.0.2';

        parent::__construct($this, $name, $author, $module_version, $radome_version);

        // Define URLs which belong to this module
        $pages->add('Haberler', '/panel/haberlers/settings', 'pages/panel/settings.php');

        $pages->add('Haberler', '/haberler', 'pages/haberler/view_haberler.php', 'haberler', true);
        $pages->add('Haberler', '/haberler/hata', 'pages/haberler/error.php');
        $pages->add('Haberler', '/haberler/goruntule', 'pages/haberler/view_haberler.php');
        $pages->add('Haberler', '/haberler/haber', 'pages/haberler/view_topic.php');
        $pages->add('Haberler', '/haberler/yeni', 'pages/haberler/new_topic.php');
        $pages->add('Haberler', '/haberler/spam', 'pages/haberler/spam.php');
        $pages->add('Haberler', '/haberler/delete_post', 'pages/haberler/delete_post.php');
        $pages->add('Haberler', '/haberler/delete', 'pages/haberler/delete.php');
        $pages->add('Haberler', '/haberler/duzenle', 'pages/haberler/edit.php');
        $pages->add('Haberler', '/haberler/stick', 'pages/haberler/stick.php');
        $pages->add('Haberler', '/haberler/search', 'pages/haberler/search.php');

        // Redirects
        $pages->add('Haberler', '/haberler/haberi_goruntule', 'pages/haberler/redirect.php');
        $pages->add('Haberler', '/haberler/haber_goruntule', 'pages/haberler/redirect.php');

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

        EventHandler::registerListener('renderHaber', [ContentHook::class, 'purify']);
        EventHandler::registerListener('renderHaber', [ContentHook::class, 'codeTransform'], 15);
        EventHandler::registerListener('renderHaber', [ContentHook::class, 'decode'], 20);
        EventHandler::registerListener('renderHaber', [ContentHook::class, 'renderEmojis'], 10);
        EventHandler::registerListener('renderHaber', [ContentHook::class, 'replaceAnchors'], 15);
        EventHandler::registerListener('renderHaber', [MentionsHook::class, 'parsePost'], 5);
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
            $icon = '<i class="fas fa-newspaper"></i>';
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

        // Front end or back end?
        if (defined('FRONT_END')) {
            // Global variables if user is logged in
            if ($user->isLoggedIn()) {
                // Basic user variables
                $topic_count = DB::getInstance()->get('topics', ['topic_creator', $user->data()->id])->results();
                $topic_count = count($topic_count);
                $post_count = DB::getInstance()->get('haberlers', ['post_creator', $user->data()->id])->results();
                $post_count = count($post_count);
                $smarty->assign('LOGGED_IN_USER_HABERLER', [
                    'topic_count' => $topic_count,
                    'post_count' => $post_count
                ]);
            }

            if (defined('PAGE') && PAGE == 'user_query') {
                $user_id = $smarty->getTemplateVars('USER_ID');

                if ($user_id) {
                    $haberler = new Haberler();

                    $smarty->assign('TOPICS', $this->_haberler_language->get('haberler', 'x_topics', ['count' => $haberler->getTopicCount($user_id)]));
                    $smarty->assign('POSTS', $this->_haberler_language->get('haberler', 'x_haberlers', ['count' => $haberler->getPostCount($user_id)]));
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
                    $navs[2]->add('haberler_settings', $this->_language->get('admin', 'settings'), URL::build('/panel/haberlers/settings'), 'top', null, $order + 0.1, $icon);

                }

            }
        }
    }

    public function getDebugInfo(): array {
        return [];
    }
}
