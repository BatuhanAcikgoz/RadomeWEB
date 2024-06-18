<?php

/*
 *  Made by Samerton
 *  https://github.com/RadomeWEB/Radome/
 *  RadomeWEB version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Twitter Widget
 */

class TwitterWidget extends WidgetBase {

    private string $_twitter_url;
    private string $_theme;

    public function __construct(Smarty $smarty, ?string $twitter = '', ?string $theme = '') {
        $this->_cache = $cache;
        $this->_smarty = $smarty;
        $this->_language = $language;

        // Get widget
        $widget_query = self::getData('Statistics');

        parent::__construct(self::parsePages($widget_query));

        // Set widget variables
        $this->_module = 'Core';
        $this->_name = 'Statistics';
        $this->_location = $widget_query->location;
        $this->_description = 'Displays the basic statistics of your website.';
        $this->_order = $widget_query->order;
    }

    public function initialise(): void {
        $this->_content = '
            <a class="twitter-timeline" ' . (($this->_theme == 'dark') ? 'data-theme="dark" ' : '') . ' data-height="600" href="' . Output::getClean($this->_twitter_url) . '">
                Tweets
            </a>
            <script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
            <br>
        ';
    }
}
