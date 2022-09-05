<?php 

/*
 * Wiki module made by reflexLabs 
 * reflexLabs.com
 */
 
if($user->isLoggedIn()){
	if(!$user->canViewStaffCP()){
		// No
		Redirect::to(URL::build('/'));
		die();
	}
	if(!$user->isAdmLoggedIn()){
		// Needs to authenticate
		Redirect::to(URL::build('/panel/auth'));
		die();
	} else {
		if(!$user->hasPermission('admincp.wiki')){
			require_once(ROOT_PATH . '/404.php');
			die();
		}
	}
} else {
	// Not logged in
	Redirect::to(URL::build('/login'));
	die();
}

define('PAGE', 'panel');
define('PANEL_PAGE', 'wiki');
$page_title = $wiki_language->get('wiki', 'wiki');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

require(ROOT_PATH . '/modules/Wiki/classes/Wiki.php');
require(ROOT_PATH . '/modules/Wiki/classes/Page.php');
$queries = new Queries();
$wiki = new Wiki($queries);

$wikipages = $wiki->getPages();
$settings = $wiki->getSettings();

$wiki->initPages(true);

if(!isset($_GET['action'])){
	if(Input::exists()){
		$errors = [];
		if(Token::check(Input::get('token'))){
			$validate = new Validate();
			$validation = $validate->check($_POST, [
				'message' => [
					'required' => true,
					'max' => 8192
				],
				'link_location' => [
					'required' => true
				],
				'icon' => [
					'max' => 64
				]
			]);		
			if($validation->passed()){			
				try {
					if(isset($_POST['link_location'])){
						switch($_POST['link_location']){
							case 1:
							case 2:
							case 3:
							case 4:
								$location = $_POST['link_location'];
								break;
						default:
						$location = 1;
						}
					} else
					$location = 1;
									
					$cache->setCache('nav_location');
					$cache->store('wiki_location', $location);
								
					$cache->setCache('navbar_icons');
					$cache->store('wiki_icon', Input::get('icon'));
								
					$message_id = $queries->getWhere('wiki_settings', ['name', '=', 'home_page']);
					$message_id = $message_id[0]->id;
					$queries->update('wiki_settings', $message_id, [
						'value' => Input::get('message'),
					]);

				} catch(Exception $e){
					$errors[] = $e->getMessage();
				}
			} else {
				$errors[] = $wiki_language->get('wiki', 'wiki_field_maximum');
			}
		} else {
			$errors[] = $language->get('general', 'invalid_token');
		}
	}
	$template_file = 'wiki/wiki.tpl';
} else {
	switch($_GET['action']){
		case 'new':
			if(Input::exists()){
				$errors = [];
				if(Token::check(Input::get('token'))){
					$validate = new Validate();
					$validation = $validate->check($_POST, [
						'wiki_page_title' => [
							'required' => true,
							'min' => 1,
							'max' => 48
						],
						'wiki_page_id' => [
							'required' => true,
							'min' => 1,
							'max' => 48
						],
						'wiki_page_button' => [
							'required' => true,
							'min' => 1,
							'max' => 48
						],
						'wiki_page_icon' => [
							'max' => 96
						],
						'wiki_page_context' => [
							'required' => true,
							'min' => 1
						]
					]);
					if($wiki->isPageExists(htmlspecialchars(Input::get('wiki_page_id')))){
						$errors[] = $wiki_language->get('wiki', 'wiki_id_exists');
					}
					if($validation->passed()){
						try {
							$queries->create('wiki_pages', [
								'title' => htmlspecialchars(Input::get('wiki_page_title')),
								'parent' => $_POST['InputWikiParent'],
								'nameid' => strtolower(Input::get('wiki_page_id')),
								'icon' => htmlspecialchars(Input::get('wiki_page_icon')),
								'button' => htmlspecialchars(Input::get('wiki_page_button')),
								'context' => htmlspecialchars(Input::get('wiki_page_context')),
								'enabled' => $_POST['InputWikiEnabled'],
							]);
							Session::flash('staff_wiki', $wiki_language->get('wiki', 'wiki_created_successfully'));
							Redirect::to(URL::build('/panel/wiki'));
							die();
						} catch(Exception $e){
							$errors[] = $e->getMessage();
						}
					} else {
						foreach($validation->errors() as $item){
							if(strpos($item, 'is required') !== false){
								if(strpos($item, 'wiki_page_title') !== false)
									$errors[] = $wiki_language->get('wiki', 'wiki_title_required');

								else if(strpos($item, 'wiki_page_context') !== false)
									$errors[] = $wiki_language->get('wiki', 'wiki_context_required');

								else if(strpos($item, 'wiki_page_id') !== false)
								$errors[] = $wiki_language->get('wiki', 'wiki_id_required');	

							} else if(strpos($item, 'minimum') !== false){
								if(strpos($item, 'wiki_page_title') !== false)
									$errors[] = $wiki_language->get('wiki', 'wiki_title_minimum');

								else if(strpos($item, 'wiki_page_context') !== false)
									$errors[] = $wiki_language->get('wiki', 'wiki_context_minimum');
									else if(strpos($item, 'wiki_page_id') !== false)
									$errors[] = $wiki_language->get('wiki', 'wiki_id_minimum');		

							} else if(strpos($item, 'maximum') !== false){
								if(strpos($item, 'wiki_page_title') !== false)
									$errors[] = $wiki_language->get('wiki', 'wiki_title_maximum');

								else if(strpos($item, 'wiki_page_icon') !== false)
									$errors[] = $wiki_language->get('wiki', 'wiki_icon_maximum');

								else if(strpos($item, 'wiki_page_id') !== false)
									$errors[] = $wiki_language->get('wiki', 'wiki_id_maximum');
							}
						}
					}
				} else {
					$errors[] = $language->get('general', 'invalid_token');
				}
			}
						
			$smarty->assign([
				'NEW_PAGE' => $wiki_language->get('wiki', 'new_wiki'),
				'BACK' => $language->get('general', 'back'),
				'BACK_LINK' => URL::build('/panel/wiki'),
				'WIKI_PAGE_TITLE' => $wiki_language->get('wiki', 'wiki_page_title'),
				'WIKI_PAGE_ID' => $wiki_language->get('wiki', 'wiki_page_id'),
				'WIKI_PAGE_PARENT' => $wiki_language->get('wiki', 'wiki_page_parent'),
				'WIKI_PAGE_ENABLED' => $wiki_language->get('wiki', 'wiki_page_enabled'),
				'WIKI_PAGE_BUTTON' => $wiki_language->get('wiki', 'wiki_page_button'),
				'WIKI_PAGE_ICON' => $wiki_language->get('wiki', 'wiki_page_icon'),
				'WIKI_PAGE_CONTEXT' => $wiki_language->get('wiki', 'wiki_page_context'),
				'WIKI_PAGE_NOT_EXISTS' => $wiki_language->get('wiki', 'wiki_parent_not_exists')
			]);
			
			$template_file = 'wiki/wiki_new.tpl';
		break;
		case 'edit':
			if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
				Redirect::to(URL::build('/panel/wiki'));
				die();
			}
			$page = $queries->getWhere('wiki_pages', ['id', '=', $_GET['id']]);
			if(!count($page)){
				Redirect::to(URL::build('/panel/wiki'));
				die();
			}
			$page = $page[0];
			if(!isset($found)){
				$thewikis = $wiki->getSubPages($page, true);
				if(isset($thewikis) && !empty($thewikis)){
					$haveSubz = true;
				}
				$found = true;
			}
			if(Input::exists()){
				$errors = [];
				if(Token::check(Input::get('token'))){
					$validate = new Validate();
					$validation = $validate->check($_POST, [
						'wiki_page_title' => [
							'required' => true,
							'min' => 1,
							'max' => 48
						],
						'wiki_page_id' => [
							'required' => true,
							'min' => 1,
							'max' => 48
						],
						'wiki_page_button' => [
							'required' => true,
							'min' => 1,
							'max' => 48
						],
						'wiki_page_icon' => [
							'max' => 96
						],
						'wiki_page_context' => [
							'required' => true,
							'min' => 1
						]
					]);
					$_parent = "";
					if(isset($_POST['InputWikiParent'])){
						$_parent = $_POST['InputWikiParent'];
					} else {
						$_parent = "null";
					}
					$_enabled = "";
					if(isset($_POST['InputWikiEnabled'])){
						$_enabled = $_POST['InputWikiEnabled'];
					} else {
						$_enabled = "1";
					}
					if($validation->passed()){	
						try {
							$queries->update('wiki_pages', $page->id, array(
								'title' => htmlspecialchars(Input::get('wiki_page_title')),
								'parent' => strtolower($_parent),
								'nameid' => strtolower(Input::get('wiki_page_id')),
								'icon' => htmlspecialchars(Input::get('wiki_page_icon')),
								'button' => htmlspecialchars(Input::get('wiki_page_button')),
								'context' => htmlspecialchars(Input::get('wiki_page_context')),
								'enabled' => $_enabled
							));
							Session::flash('staff_wiki', $wiki_language->get('wiki', 'wiki_updated_successfully'));
							Redirect::to(URL::build('/panel/wiki'));
							die();
						} catch(Exception $e){
							$errors[] = $e->getMessage();
						}
					} else {
						foreach($validation->errors() as $item){
							if(strpos($item, 'is required') !== false){
								if(strpos($item, 'wiki_page_title') !== false)
									$errors[] = $wiki_language->get('wiki', 'wiki_title_required');

								else if(strpos($item, 'wiki_page_context') !== false)
									$errors[] = $wiki_language->get('wiki', 'wiki_context_required');
								else if(strpos($item, 'wiki_page_id') !== false)
									$errors[] = $wiki_language->get('wiki', 'wiki_id_required');
							} else if(strpos($item, 'minimum') !== false){
								if(strpos($item, 'wiki_page_title') !== false)
									$errors[] = $wiki_language->get('wiki', 'wiki_title_required');

								else if(strpos($item, 'wiki_page_context') !== false)
									$errors[] = $wiki_language->get('wiki', 'page_wiki_minimum');
									else if(strpos($item, 'wiki_page_id') !== false)
									$errors[] = $wiki_language->get('wiki', 'wiki_id_minimum');	
							} else if(strpos($item, 'maximum') !== false){
								if(strpos($item, 'wiki_page_title') !== false)
									$errors[] = $wiki_language->get('wiki', 'page_title_maximum');

								else if(strpos($item, 'wiki_page_icon') !== false)
									$errors[] = $wiki_language->get('wiki', 'page_icon_maximum');
								else if(strpos($item, 'wiki_page_id') !== false)
									$errors[] = $wiki_language->get('wiki', 'wiki_id_maximum');
							}
						}
					}
				} else {
					$errors[] = $language->get('general', 'invalid_token');
				}
			}
						
			$smarty->assign(array(
				'EDIT_PAGE' => $wiki_language->get('wiki', 'edit_wiki'),
				'BACK' => $language->get('general', 'back'),
				'BACK_LINK' => URL::build('/panel/wiki'),
				'WIKI_PAGE_TITLE' => $wiki_language->get('wiki', 'wiki_page_title'),
				'WIKI_PAGE_TITLE_VALUE' => Output::getClean($page->title),
				'WIKI_PAGE_ID' => $wiki_language->get('wiki', 'wiki_page_id'),
				'WIKI_PAGE_ID_VALUE' => Output::getClean($page->nameid),
				'WIKI_PAGE_PARENT' => $wiki_language->get('wiki', 'wiki_page_parent'),
				'WIKI_PAGE_PARENT_VALUE' => Output::getClean($page->parent),
				'WIKI_PAGE_BUTTON' => $wiki_language->get('wiki', 'wiki_page_button'),
				'WIKI_PAGE_BUTTON_VALUE' => Output::getClean($page->button),
				'WIKI_PAGE_ICON' => $wiki_language->get('wiki', 'wiki_page_icon'),
				'WIKI_PAGE_ICON_VALUE' => Output::getClean($page->icon),
				'WIKI_PAGE_CONTEXT' => $wiki_language->get('wiki', 'wiki_page_context'),
				'WIKI_PAGE_CONTEXT_VALUE' => Output::getClean($page->context),
				'WIKI_PAGE_ENABLED' => $wiki_language->get('wiki', 'wiki_page_enabled'),
				'WIKI_PAGE_ENABLED_VALUE' => Output::getClean($page->enabled),
				'WIKI_PAGE_NOT_EXISTS' => $wiki_language->get('wiki', 'wiki_parent_not_exists'),
				'SUB_PAGED' => $haveSubz,
				'SUB_PAGES' => $thewikis
			));
		
			$template_file = 'wiki/wiki_edit.tpl';
		break;
		case 'delete':
			if(isset($_GET['id']) && is_numeric($_GET['id'])){
				try {
					$queries->delete('wiki_pages', array('id', '=', $_GET['id']));
				} catch(Exception $e){
					die($e->getMessage());
				}

				Session::flash('staff_wiki', $wiki_language->get('wiki', 'wiki_deleted_successfully'));
				Redirect::to(URL::build('/panel/wiki'));
				die();
			}
		break;
		default:
			Redirect::to(URL::build('/panel/wiki'));
			die();
		break;
	}
}
	
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $mod_nav], $widgets, $template);

if(Session::exists('staff_wiki'))
	$success = Session::flash('staff_wiki');

if(isset($success))
	$smarty->assign(array(
		'SUCCESS' => $success,
		'SUCCESS_TITLE' => $language->get('general', 'success')
	));

if(isset($errors) && count($errors))
	$smarty->assign(array(
		'ERRORS' => $errors,
		'ERRORS_TITLE' => $language->get('general', 'error')
	));
	$cache->setCache('navbar_icons');
	$icon = $cache->retrieve('wiki_icon');
	$smarty->assign(array(
		'PAGE' => PANEL_PAGE,
		'DASHBOARD' => $language->get('admin', 'dashboard'),
		'WIKI' => $wiki_language->get('wiki', 'wiki'),
		'WIKI_PAGES' => $wiki->getPages[],
		'MESSAGE_VALUE' => $settings,
		'AMOUNT_PAGES' => $wiki->getPagesAmount(),
		'NO_WIKIS_FOUNDED' => $wiki_language->get('wiki', 'no_wikis_founded'),
		'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
		'CONFIRM_REMOVE_PAGE' => $wiki_language->get('wiki', 'remove_wiki_confirm'),
		'YES' => $language->get('general', 'yes'),
		'NO' => $language->get('general', 'no'),
		'NEW_PAGE' => $wiki_language->get('wiki', 'create_wiki'),
		'EDIT_PAGE' => $wiki_language->get('wiki', 'edit_wiki'),
		'NEW_PAGE_LINK' => URL::build('/panel/wiki/', 'action=new'),
		'NEW_SUB_PAGE_LINK' => URL::build('/panel/wiki/', 'action=newsub'),
		'WIKI_PANEL_LINK' => URL::build('/panel/wiki/'),
		'LINK_LOCATION' => $wiki_language->get('wiki', 'wiki_link_location'),
		'LINK_NAVBAR' => $language->get('admin', 'page_link_navbar'),
		'LINK_MORE' => $language->get('admin', 'page_link_more'),
		'LINK_FOOTER' => $language->get('admin', 'page_link_footer'),
		'LINK_NONE' => $language->get('admin', 'page_link_none'),
		'ICON' => $wiki_language->get('wiki', 'icon'),
		'HOME_PAGE_SECTION_TITLE' => $wiki_language->get('wiki', 'wiki_home_page_section'),
		'ICON_MENU_EXAMPLE' => htmlspecialchars($wiki_language->get('wiki', 'default_menu_icon')),
		'ICON_EXAMPLE' => htmlspecialchars($wiki_language->get('wiki', 'default_wiki_icon')),
		'ICON_TITLE' => $wiki_language->get('wiki', 'wiki_icon_title'),
		'ICON_VALUE' => Output::getClean(htmlspecialchars_decode($icon)),
		'TOKEN' => Token::get(),
		'SUBMIT' => $language->get('general', 'submit')
	));

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

$template->displayTemplate($template_file, $smarty);