<?php

/*
 *  Made by Reeignn
 *  https://github.com/Verira/RadomeWEB
 *  RadomeWEB v2.1
 *
 *  License: GPL-3.0
 *
 *  Recent forms_replies dashboard collection item
 */

class Recentforms_repliesItem extends CollectionItemBase {

    private Smarty $_smarty;
    private Language $_language;
    private int $_forms_replies;

    public function __construct(Smarty $smarty, Language $language, Cache $cache, int $forms_replies) {
        $cache->setCache('dashboard_stats_collection');
        if ($cache->isCached('recent_forms_replies')) {
            $from_cache = $cache->retrieve('recent_forms_replies');
            $order = $from_cache['order'] ?? 3;

            $enabled = $from_cache['enabled'] ?? 1;
        } else {
            $order = 3;
            $enabled = 1;
        }

        parent::__construct($order, $enabled);

        $this->_smarty = $smarty;
        $this->_language = $language;
        $this->_forms_replies = $forms_replies;
    }

    public function getContent(): string {
        $this->_smarty->assign([
            'TITLE' => $this->_language->get('forms', 'submissions'),
            'VALUE' => $this->_forms_replies
        ]);

        return $this->_smarty->fetch('collections/dashboard_stats/recent_forms_replies.tpl');
    }
}
