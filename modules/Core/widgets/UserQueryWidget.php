<?php
/*
 *  Made by Reeignn
 *  https://github.com/Verira/RadomeWEB
 *  RadomeWEB v2.1
 *
 *  License: GPL-3.0
 *
 *  Statistics Widget // By Xemah // https://xemah.me
 */

class UserQueryWidget extends WidgetBase {

    private Cache $_cache;
    private Language $_language;

    public function __construct(Smarty $smarty, Language $language, Cache $cache) {
        $this->_cache = $cache;
        $this->_smarty = $smarty;
        $this->_language = $language;

        // Get widget
        $widget_query = self::getData('User Query Widget');

        parent::__construct(self::parsePages($widget_query));

        // Set widget variables
        $this->_module = 'Core';
        $this->_name = 'User Query Widget';
        $this->_location = $widget_query->location;
        $this->_description = 'User query tool widget.';
        $this->_order = $widget_query->order;
    }

    public function initialise(): void {
        $search_value = $_GET["user_search"];
        if(isset($search_value)){
            $sResults= Redirect::to(URL::build("/profil/$search_value"));
        if(!empty($sResults)){

        } else {
	    // no results
        }
        } 
        $this->_smarty->assign(
            [
                'USER_QUERY_TITLE' => $this->_language->get('general', 'user_query_title'),
                'ONLINE' => $this->_language->get('general', 'online'),
                'OFFLINE' => $this->_language->get('general', 'offline'),
                'SEARCH_RESULT' => $search_value,
            ]
        );

        $this->_content = $this->_smarty->fetch('widgets/user_query.tpl');
    }

}