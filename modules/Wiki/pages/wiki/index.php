<?php

/*
 * Wiki module made by reflexLabs 
 * reflexLabs.com
 */
 
define('PAGE', 'wiki');
$page_title = $wiki_language->get('wiki', 'wiki');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');
require(ROOT_PATH . '/modules/Wiki/classes/Wiki.php');
require(ROOT_PATH . '/modules/Wiki/classes/Page.php');
$queries = new Queries();
$wiki = new Wiki($queries);

$wikipages = $wiki->getPages();
$settings = $wiki->getSettings();

$wiki->initPages(false);

$_route = explode('/', $route);
$wpage_id = $_route[count($_route) - 1];
$wcat_id = $_route[2];

$pageResult = 0;
$pageType = false;
$parentName = null;
$parentID = null;
$search_value = $_GET["search"];

if(isset($search_value)){
    $sRequest = '%'.$search_value.'%';
    $sResults = DB::getInstance()->query('SELECT * FROM rw_wiki_pages WHERE title like ? OR nameid like ? OR context like ?',array($sRequest,$sRequest,$sRequest)); 
    $pageResult = 3;
    $sResults = $sResults->results();
    if(!empty($sResults)){

    } else {
        // no results
    }
} else {
    if($wpage_id == "wiki"){
        $pageResult = 0;
    } else {
        if($wpage_id != $wcat_id){
            $wpage = DB::getInstance()->query('SELECT * FROM rw_wiki_pages WHERE parent = ? AND nameid = ?',array($wcat_id,$wpage_id));
        } else {
            $wpage = DB::getInstance()->query('SELECT * FROM rw_wiki_pages WHERE nameid = ?', array($wpage_id));
        }
        $wpage = $wpage->first();
        if(!empty($wpage)){
            if($wpage->enabled == "1"){
                if($wpage->parent != "null"){
                    $pageType = true;
                    $parentName = $wiki->getPageTitle($wpage->parent);
                    $parentID = $wpage->id;
                }
                $pageResult = 1;
                $sessionName = 'visited_'.$wpage->nameid;
                if(!Session::exists($sessionName)){
                    Session::put($sessionName, $wpage->id);
                    $wiki->updateViews($wpage->nameid);
                } else {
                    if(Session::get($sessionName) != $wpage->id){
                        Session::put($sessionName, $wpage->id);
                        $wiki->updateViews($wpage->nameid);
                    }
                }
            } else {
                $pageResult = 2;
            }
        } else {
            $pageResult = 2;
        }
    }
}

$smarty->assign(array(
    'WP_TYPE' => $pageType,
    'WP_ID' => Output::getClean($wpage->id),
	'WP_TITLE' => Output::getClean($wpage->title),
	'WP_NAMEID' => Output::getClean($wpage->nameid),
    'WP_CATID' => Output::getClean($wiki->getPageCategory($wpage->nameid)),
    'WP_PARENT' => Output::getClean($parentName),
    'WP_PARENT_ID' => Output::getClean($parentID),
    'WP_VIEWS' => Output::getClean($wpage->views+1),
    'WP_LIKES' => Output::getClean($wpage->likes),
    'WP_ENABLED' => Output::getClean($wpage->enabled),
    'LIKED' => $wiki->isPageLikedByUserAsString($user->data()->username, $wpage->nameid),
    'LIKEABLE' => $wiki->isPageLikeable($wpage->nameid),
    'LIKES' => $wiki->getLikesByPage($wpage->nameid),
    'LOGGED' => $user->isLoggedIn(),
    'WHO_LIKED' => $wiki_language->get('wiki', 'who_liked'),
    'ALL_LIKES' => $wiki_language->get('wiki', 'all_likes'),
	'WP_BUTTON' => (($wepage->all_html == 0) ? Output::getPurified(htmlspecialchars_decode($wpage->button)) : htmlspecialchars_decode($wpage->button)),
	'WP_CONTEXT' => (($wepage->all_html == 0) ? Output::getPurified(htmlspecialchars_decode($wpage->context)) : htmlspecialchars_decode($wpage->context)),
	'PAGE_RESULT' => $pageResult,
    'SEARCH_RESULT' => $search_value,
    'SEARCH_RESULTS' => $sResults,
    'SEARCH_PLACEHOLDER' => $wiki_language->get('wiki', 'search_placeholder'),
    'SEARCH_RESULTS_LANG' => $wiki_language->get('wiki', 'search_results'),
    'SEARCH_NO_RESULTS' => $wiki_language->get('wiki', 'search_no_results'),
    'WIKI' => $wiki_language->get('wiki', 'wiki'),
    'PAGES_TITLE' => $wiki_language->get('wiki', 'wiki_pages_title'),
    'WIKI_PAGES' => $wiki->getPagesArray(),
    'HOME_PAGE_CONTEXT' => $settings,
    'AMOUNT_PAGES' => $wiki->getPagesAmount(),
    'WIKI_HOME_LINK' => URL::build('/wiki/'),
    'WP_404_MESSAGE' => $wiki_language->get('wiki', 'wiki_404_message'),
    'WP_EDIT_MESSAGE' => $wiki_language->get('wiki', 'edit_wiki'),
    'NO_LIKES_MESSAGE' => $wiki_language->get('wiki', 'no_like_yet'),
    'AJAX_ACTION_URL', URL::build('/queries/like')

    //'WIKI_HOME_LINK' => URL::build('/vote/', 'order=all')
));

$template->addCSSFiles(array(
	(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/ckeditor/plugins/spoiler/css/spoiler.css' => array(),
	(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/emoji/css/emojione.min.css' => array()
));

$template->addJSFiles(array(
	(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/ckeditor/plugins/spoiler/js/spoiler.js' => array()
));

Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets, $template);

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

$smarty->assign('WIDGETS_LEFT', $widgets->getWidgets('left'));
$smarty->assign('WIDGETS_RIGHT', $widgets->getWidgets('right'));

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');
	
if($pageResult == 3){
    $template->displayTemplate('wiki/search.tpl', $smarty);
} else {
    $template->displayTemplate('wiki/index.tpl', $smarty);
}
