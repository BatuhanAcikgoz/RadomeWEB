<?php

use SitemapPHP\Sitemap;

/**
 * Haberler sitemap class
 *
 * @package Modules\Haberler
 * @author Samerton
 * @version 2.0.0-pr8
 * @license MIT
 */
class Haberler_Sitemap {

    /**
     * Generate sitemap for the Haberler.
     *
     * @param Sitemap $sitemap Instance of sitemap generator.
     */
    public static function generateSitemap(Sitemap $sitemap): void {

        // Haberler
        $sitemap->addItem(URL::build('/haberler'), 0.9);

        $db = DB::getInstance();

        $haberlers = $db->query('SELECT id, haberler_title, last_post_date FROM rw_haberlers WHERE id IN (SELECT haber_id FROM rw_haberlers_permissions WHERE group_id = 0 AND `view` = 1)')->results();

        foreach ($haberlers as $haberler) {
            $sitemap->addItem(URL::build('/haberler/view/' . urlencode($haberler->id) . '-' . Text::urlSafe($haberler->haberler_title)), 0.5, 'daily', date('Y-m-d', $haberler->last_post_date));
        }

        $haberlers = null;

        $topics = $db->query('SELECT id, haber_id, topic_title FROM rw_topics WHERE deleted = 0 AND haber_id IN (SELECT haber_id FROM rw_haberlers_permissions WHERE group_id = 0 AND `view` = 1)')->results();

        foreach ($topics as $topic) {
            $sitemap->addItem(URL::build('/haberler/topic/' . urlencode($topic->id) . '-' . Text::urlSafe($topic->topic_title)), 0.5);
        }

        $topics = null;
    }
}
