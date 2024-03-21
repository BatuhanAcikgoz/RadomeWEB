<?php
/*
 *
 *
 *  License: MIT
 *
 *  Magaza module - Index Page
 */

// Always define page name
define('PAGE', 'store');
$page_title = $store_language->get('general', 'store');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');
require_once(ROOT_PATH . '/modules/Magaza/core/frontend_init.php');

$content = Settings::get('store_content', '', 'Magaza');
$content = Output::getDecoded($content);
$content = Output::getPurified($content);
$categories_list = [];
$categories = DB::getInstance()->query('SELECT id, image FROM rw_store_categories WHERE deleted = 0')->results();
foreach ($categories as $category) {
    $categories_list[] = [
        'id' => Output::getClean($category->id),
        'image' => Output::getClean($category->image),
    ];
}

$smarty->assign([
    'STORE' => $store_language->get('general', 'store'),
    'STORE_URL' => URL::build($store->getMagazaURL()),
    'CATEGORIES' => $store->getNavbarMenu('Home'),
    'CATEGORY_IMAGE_VALUE' => Output::getClean($category->image),
    'CONTENT' => $content,
    'TOKEN' => Token::get(),
]);

$template->assets()->include([
    DARK_MODE
        ? AssetTree::PRISM_DARK
        : AssetTree::PRISM_LIGHT,
    AssetTree::TINYMCE_SPOILER,
]);

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

if (Session::exists('store_error')) {
    $errors[] = Session::flash('store_error');
}

if (isset($success))
    $smarty->assign([
        'SUCCESS' => $success,
        'SUCCESS_TITLE' => $language->get('general', 'success')
    ]);

if (isset($errors) && count($errors))
    $smarty->assign([
        'ERRORS' => $errors,
        'ERRORS_TITLE' => $language->get('general', 'error')
    ]);

$template->onPageLoad();

$smarty->assign('WIDGETS_LEFT', $widgets->getWidgets('left'));
$smarty->assign('WIDGETS_RIGHT', $widgets->getWidgets('right'));

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
$template->displayTemplate('store/index.tpl', $smarty);
