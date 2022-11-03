<?php
/*
 *  Made by Reeignn
 *  https://github.com/Verira/RadomeWEB
 *  RadomeWEB v2.1
 *
 *  License: GPL-3.0
 *
 *  Panel templates page
 */

if (!$user->handlePanelPageLoad('admincp.styles.templates')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'layout';
const PANEL_PAGE = 'template';
$page_title = $language->get('admin', 'templates');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

if (!isset($_GET['action'])) {
    // Get all templates
    $templates = DB::getInstance()->get('templates', ['id', '<>', 0])->results();

    // Get all active templates
    $active_templates = DB::getInstance()->get('templates', ['enabled', true])->results();

    $current_template = $template;

    $templates_template = [];

    $loaded_templates = [];

    foreach ($templates as $item) {
        // Prevent the white screen error and delete template with duplicate name
        if (in_array($item->name, $loaded_templates)) {
            DB::getInstance()->delete('templates', ['id', $item->id]);
            continue;
        }

        $loaded_templates[] = $item->name;

        $template_path = implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'custom', 'templates', Output::getClean($item->name), 'template.php']);

        if (file_exists($template_path)) {
            require($template_path);
        } else {
            DB::getInstance()->delete('templates', ['id', $item->id]);
            continue;
        }

        $templates_template[] = [
            'name' => Output::getClean($item->name),
            'version' => Output::getClean($template->getVersion()),
            'author' => $template->getAuthor(),
            'author_x' => $language->get('admin', 'author_x', ['author' => $template->getAuthor()]),
            'version_mismatch' => !Util::isCompatible($template->getRadomeVersion(), RADOME_VERSION) ? $language->get('admin', 'template_outdated', [
                'intendedVersion' => Text::bold(Output::getClean($template->getRadomeVersion())),
                'actualVersion' => Text::bold(RADOME_VERSION)
            ]) : false,
            'enabled' => $item->enabled,
            'default_warning' => (Output::getClean($item->name) == 'Default') ? $language->get('admin', 'template_not_supported') : null,
            'activate_link' => (($item->enabled) ? null : URL::build('/panel/temalar/', 'action=activate&template=' . urlencode($item->id))),
            'delete_link' => ((!$user->hasPermission('admincp.styles.templates.edit') || $item->id == 1 || $item->enabled) ? null : URL::build('/panel/temalar/', 'action=delete&template=' . urlencode($item->id))),
            'default' => $item->is_default,
            'deactivate_link' => (($item->enabled && count($active_templates) > 1 && !$item->is_default) ? URL::build('/panel/temalar/', 'action=deactivate&template=' . urlencode($item->id)) : null),
            'default_link' => (($item->enabled && !$item->is_default) ? URL::build('/panel/temalar/', 'action=make_default&template=' . urlencode($item->id)) : null),
            'edit_link' => ($user->hasPermission('admincp.styles.templates.edit') ? URL::build('/panel/temalar/', 'action=edit&template=' . urlencode($item->id)) : null),
            'settings_link' => ($template->getSettings() && $user->hasPermission('admincp.styles.templates.edit') ? URL::build('/panel/temalar/', 'action=settings&template=' . urlencode($item->id)) : null)
        ];
    }

    $template = $current_template;

    $smarty->assign([
        'WARNING' => $language->get('admin', 'warning'),
        'ACTIVATE' => $language->get('admin', 'activate'),
        'DEACTIVATE' => $language->get('admin', 'deactivate'),
        'DELETE' => $language->get('admin', 'delete'),
        'CONFIRM_DELETE_TEMPLATE' => $language->get('admin', 'confirm_delete_template'),
        'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
        'YES' => $language->get('general', 'yes'),
        'NO' => $language->get('general', 'no'),
        'ACTIVE' => $language->get('admin', 'active'),
        'DEFAULT' => $language->get('admin', 'default'),
        'MAKE_DEFAULT' => $language->get('admin', 'make_default'),
        'EDIT' => $language->get('general', 'edit'),
        'SETTINGS' => $language->get('admin', 'settings'),
        'TEMPLATE_LIST' => $templates_template,
        'INSTALL_TEMPLATE' => $language->get('admin', 'install'),
        'INSTALL_TEMPLATE_LINK' => URL::build('/panel/temalar/', 'action=install'),
        'FIND_TEMPLATES' => $language->get('admin', 'find_templates'),
        'WEBSITE_TEMPLATES' => $all_templates,
        'VIEW_ALL_TEMPLATES' => $language->get('admin', 'view_all_templates'),
        'UNABLE_TO_RETRIEVE_TEMPLATES' => $language->get('admin', 'unable_to_retrieve_templates'),
        'VIEW' => $language->get('general', 'view'),
        'TEMPLATE' => $language->get('admin', 'template'),
        'STATS' => $language->get('admin', 'stats'),
        'ACTIONS' => $language->get('general', 'actions')
    ]);

    $template_file = 'core/templates.tpl';
} else {
    switch ($_GET['action']) {
        case 'install':
            if (Token::check()) {
                // Install new template
                // Scan template directory for new templates
                $directories = glob(ROOT_PATH . DIRECTORY_SEPARATOR . 'custom' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . '*', GLOB_ONLYDIR);
                foreach ($directories as $directory) {
                    $folders = explode(DIRECTORY_SEPARATOR, $directory);

                    // Is it already in the database?
                    $exists = DB::getInstance()->get('templates', ['name', $folders[count($folders) - 1]])->results();
                    if (!count($exists) && file_exists(ROOT_PATH . DIRECTORY_SEPARATOR . 'custom' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . str_replace(['../', '/', '..'], '', $folders[count($folders) - 1]) . DIRECTORY_SEPARATOR . 'template.php')) {
                        $template = null;
                        require_once(ROOT_PATH . DIRECTORY_SEPARATOR . 'custom' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . str_replace(['../', '/', '..'], '', $folders[count($folders) - 1]) . DIRECTORY_SEPARATOR . 'template.php');

                        /** @phpstan-ignore-next-line */
                        if ($template instanceof TemplateBase) {
                            // No, add it now
                            DB::getInstance()->insert('templates', [
                                'name' => $folders[count($folders) - 1]
                            ]);
                        }
                    }
                }

                Session::flash('admin_templates', $language->get('admin', 'templates_installed_successfully'));
            } else {
                Session::flash('admin_templates_error', $language->get('general', 'invalid_token'));
            }

            Redirect::to(URL::build('/panel/temalar'));

        case 'activate':
            if (Token::check()) {
                // Activate a template
                // Ensure it exists
                $template = DB::getInstance()->get('templates', ['id', $_GET['template']])->results();
                if (!count($template)) {
                    // Doesn't exist
                    Redirect::to(URL::build('/panel/temalar/'));
                }
                $name = str_replace(['../', '/', '..'], '', $template[0]->name);

                if (file_exists(ROOT_PATH . DIRECTORY_SEPARATOR . 'custom' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR . 'template.php')) {
                    $id = $template[0]->id;
                    $template = null;
                    require_once(ROOT_PATH . DIRECTORY_SEPARATOR . 'custom' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR . 'template.php');

                    /** @phpstan-ignore-next-line */
                    if ($template instanceof TemplateBase) {
                        // Activate the template
                        DB::getInstance()->update('templates', $id, [
                            'enabled' => true,
                        ]);

                        // Session
                        Session::flash('admin_templates', $language->get('admin', 'template_activated'));
                    } else {
                        // Session
                        Session::flash('admin_templates_error', $language->get('admin', 'unable_to_enable_template'));
                    }
                }
            } else {
                Session::flash('admin_templates_error', $language->get('general', 'invalid_token'));
            }

            Redirect::to(URL::build('/panel/temalar/'));

        case 'deactivate':
            if (Token::check()) {
                // Deactivate a template
                // Ensure it exists
                $template = DB::getInstance()->get('templates', ['id', $_GET['template']])->results();
                if (!count($template)) {
                    // Doesn't exist
                    Redirect::to(URL::build('/panel/temalar/'));
                }

                $template = $template[0]->id;

                // Deactivate the template
                DB::getInstance()->update('templates', $template, [
                    'enabled' => false,
                ]);

                // Session
                Session::flash('admin_templates', $language->get('admin', 'template_deactivated'));
            } else {
                Session::flash('admin_templates_error', $language->get('general', 'invalid_token'));
            }

            Redirect::to(URL::build('/panel/temalar'));

        case 'delete':
            if (!isset($_GET['template'])) {
                Redirect::to('/panel/temalar');
            }

            if (Token::check()) {
                $item = $_GET['template'];

                try {
                    // Ensure template is not default or active
                    $template = DB::getInstance()->get('templates', ['id', $item])->results();
                    if (count($template)) {
                        $template = $template[0];
                        if ($template->name == 'RadomeWEB' || $template->id == 1 || $template->enabled == 1 || $template->is_default == 1) {
                            Redirect::to(URL::build('/panel/temalar'));
                        }

                        $item = $template->name;
                    } else {
                        Redirect::to(URL::build('/panel/temalar'));
                    }

                    if (!Util::recursiveRemoveDirectory(ROOT_PATH . '/custom/templates/' . $item)) {
                        Session::flash('admin_templates_error', $language->get('admin', 'unable_to_delete_template'));
                    } else {
                        Session::flash('admin_templates', $language->get('admin', 'template_deleted_successfully'));
                    }

                    // Delete from database
                    DB::getInstance()->delete('templates', ['name', $item]);
                } catch (Exception $e) {
                    Session::flash('admin_templates_error', $e->getMessage());
                }
            } else {
                Session::flash('admin_templates_error', $language->get('general', 'invalid_token'));
            }

            Redirect::to(URL::build('/panel/temalar'));

        case 'make_default':
            if (Token::check()) {
                // Make a template default
                // Ensure it exists
                $new_default = DB::getInstance()->get('templates', ['id', $_GET['template']])->results();
                if (!count($new_default)) {
                    // Doesn't exist
                    Redirect::to(URL::build('/panel/temalar/'));
                }

                $new_default_template = $new_default[0]->name;
                $new_default = $new_default[0]->id;

                // Get current default template
                $current_default = DB::getInstance()->get('templates', ['is_default', true])->results();
                if (count($current_default)) {
                    $current_default = $current_default[0]->id;
                    // No longer default
                    DB::getInstance()->update('templates', $current_default, [
                        'is_default' => false,
                    ]);
                }

                // Make selected template default
                DB::getInstance()->update('templates', $new_default, [
                    'is_default' => true,
                ]);

                // Cache
                $cache->setCache('templatecache');
                $cache->store('default', $new_default_template);

                // Session
                Session::flash('admin_templates', $language->get('admin', 'default_template_set', ['template' => Output::getClean($new_default_template)]));
            } else {
                Session::flash('admin_templates_error', $language->get('general', 'invalid_token'));
            }

            Redirect::to(URL::build('/panel/temalar/'));

        case 'settings':
            // Editing template settings
            if (!$user->hasPermission('admincp.styles.templates.edit')) {
                Redirect::to(URL::build('/panel/temalar'));
            }

            $current_template = $template;

            // Get the template
            $template_query = DB::getInstance()->get('templates', ['id', $_GET['template']])->results();
            if (count($template_query)) {
                $template_query = $template_query[0];
            } else {
                Redirect::to(URL::build('/panel/temalar'));
            }

            require_once(ROOT_PATH . DIRECTORY_SEPARATOR . 'custom' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . str_replace(['../', '/', '..'], '', $template_query->name) . DIRECTORY_SEPARATOR . 'template.php');

            if ($template instanceof TemplateBase) {
                if ($template->getSettings()) {
                    require_once($template->getSettings());

                    $smarty->assign([
                        'EDITING_TEMPLATE' => $language->get('admin', 'editing_template_x', [
                            'template' => Text::bold(Output::getClean($template_query->name))
                        ]),
                        'BACK' => $language->get('general', 'back'),
                        'BACK_LINK' => URL::build('/panel/temalar'),
                        'PERMISSIONS' => $language->get('admin', 'permissions'),
                        'PERMISSIONS_LINK' => $user->hasPermission('admincp.groups') ? URL::build('/panel/temalar/', 'template=' . urlencode($template_query->id) . '&action=permissions') : null,
                    ]);

                    $template_file = 'core/template_settings.tpl';
                } else {
                    Redirect::to(URL::build('/panel/temalar'));
                }
            } else {
                Redirect::to(URL::build('/panel/temalar'));
            }

            $template = $current_template;

            break;

        case 'permissions':
            // Template permissions
            if (!$user->hasPermission('admincp.groups')) {
                Redirect::to(URL::build('/panel/temalar'));
            }

            // Get the template
            $template_query = DB::getInstance()->get('templates', ['id', $_GET['template']])->results();
            if (count($template_query)) {
                $template_query = $template_query[0];
            } else {
                Redirect::to(URL::build('/panel/temalar'));
            }

            // Handle input
            if (Input::exists()) {
                if (Token::check()) {
                    // Guest template permissions
                    $can_use_template = Input::get('perm-use-0');

                    if (!($can_use_template)) {
                        $can_use_template = 0;
                    }

                    $perm_exists = 0;

                    $perm_query = DB::getInstance()->get('groups_templates', ['template_id', $template_query->id])->results();
                    if (count($perm_query)) {
                        foreach ($perm_query as $query) {
                            if ($query->group_id == 0) {
                                $perm_exists = 1;
                                $update_id = $query->id;
                                break;
                            }
                        }
                    }

                    try {
                        if ($perm_exists != 0) { // Permission already exists, update
                            // Update the permission
                            DB::getInstance()->update('groups_templates', $update_id, [
                                'can_use_template' => $can_use_template
                            ]);
                        } else { // Permission doesn't exist, create
                            DB::getInstance()->insert('groups_templates', [
                                'group_id' => 0,
                                'template_id' => $template_query->id,
                                'can_use_template' => $can_use_template,
                            ]);
                        }
                    } catch (Exception $e) {
                        $errors[] = $e->getMessage();
                    }

                    // Group template permissions
                    foreach (Group::all() as $group) {
                        $can_use_template = Input::get('perm-use-' . $group->id);

                        if (!($can_use_template)) {
                            $can_use_template = 0;
                        }

                        $perm_exists = 0;

                        if (count($perm_query)) {
                            foreach ($perm_query as $query) {
                                if ($query->group_id == $group->id) {
                                    $perm_exists = 1;
                                    $update_id = $query->id;
                                    break;
                                }
                            }
                        }

                        try {
                            if ($perm_exists != 0) { // Permission already exists, update
                                // Update the permission
                                DB::getInstance()->update('groups_templates', $update_id, [
                                    'can_use_template' => $can_use_template,
                                ]);
                            } else { // Permission doesn't exist, create
                                DB::getInstance()->insert('groups_templates', [
                                    'group_id' => $group->id,
                                    'template_id' => $template_query->id,
                                    'can_use_template' => $can_use_template,
                                ]);
                            }
                        } catch (Exception $e) {
                            $errors[] = $e->getMessage();
                        }
                    }

                    $success = $language->get('admin', 'successfully_updated');
                } else {
                    $errors = [$language->get('general', 'invalid_token')];
                }
            }

            // Get permissions
            $guest_query = DB::getInstance()->query('SELECT 0 AS id, can_use_template FROM rw_groups_templates WHERE group_id = 0 AND template_id = ?', [$template_query->id])->results();
            $group_query = DB::getInstance()->query('SELECT id, `name`, can_use_template FROM rw_groups A LEFT JOIN (SELECT group_id, can_use_template FROM rw_groups_templates WHERE template_id = ?) B ON A.id = B.group_id ORDER BY `order` ASC', [$template_query->id])->results();

            $smarty->assign([
                'EDITING_TEMPLATE' => $language->get('admin', 'editing_template_x', [
                    'template' => Text::bold(Output::getClean($template_query->name))
                ]),
                'BACK' => $language->get('general', 'back'),
                'BACK_LINK' => URL::build('/panel/temalar'),
                'PERMISSIONS' => $language->get('admin', 'permissions'),
                'GUESTS' => $language->get('user', 'guests'),
                'GUEST_PERMISSIONS' => (count($guest_query) ? $guest_query[0] : []),
                'GROUP_PERMISSIONS' => $group_query,
                'GROUP' => $language->get('admin', 'group'),
                'CAN_USE_TEMPLATE' => $language->get('admin', 'can_use_template'),
                'SELECT_ALL' => $language->get('admin', 'select_all'),
                'DESELECT_ALL' => $language->get('admin', 'deselect_all')
            ]);

            $template_file = 'core/template_permissions.tpl';

            break;

        case 'edit':
                Redirect::to(URL::build('/panel'));

            break;

        default:
            Redirect::to(URL::build('/panel/temalar'));
    }
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

if (Session::exists('admin_templates')) {
    $success = Session::flash('admin_templates');
}

if (Session::exists('admin_templates_error')) {
    $errors = [Session::flash('admin_templates_error')];
}

if (isset($success)) {
    $smarty->assign([
        'SUCCESS' => $success,
        'SUCCESS_TITLE' => $language->get('general', 'success')
    ]);
}

if (isset($errors) && count($errors)) {
    $smarty->assign([
        'ERRORS' => $errors,
        'ERRORS_TITLE' => $language->get('general', 'error')
    ]);
}

$smarty->assign([
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'LAYOUT' => $language->get('admin', 'layout'),
    'TEMPLATES' => $language->get('admin', 'templates'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit')
]);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate($template_file, $smarty);
