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

        $haberlers = $db->query('SELECT id, haberler_title, last_post_date FROM rw_haberlers WHERE id IN (SELECT haberler_id FROM rw_haberlers_permissions WHERE group_id = 0 AND `view` = 1)')->results();

        foreach ($haberlers as $haberler) {
            $sitemap->addItem(URL::build('/haberler/bakis/' . urlencode($haberler->id) . '-' . Text::urlSafe($haberler->haberler_title)), 0.5, 'daily', date('Y-m-d', $haberler->last_post_date));
        }

        $haberlers = null;

        $habers = $db->query('SELECT id, haberler_id, haber_title FROM rw_habers WHERE deleted = 0 AND haberler_id IN (SELECT haberler_id FROM rw_haberlers_permissions WHERE group_id = 0 AND `view` = 1)')->results();

        foreach ($habers as $haber) {
            $sitemap->addItem(URL::build('/haberler/konu/' . urlencode($haber->id) . '-' . Text::urlSafe($haber->haber_title)), 0.5);
        }

        $habers = null;
    }
}
