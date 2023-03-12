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

        $topics = $db->query('SELECT id, id, topic_title FROM rw_topics WHERE deleted = 0')->results();

        foreach ($topics as $topic) {
            $sitemap->addItem(URL::build('/haberler/haber/' . urlencode($topic->id) . '-' . urlencode($topic->haber_title)), 0.5);
        }

        $topics = null;
    }
}
