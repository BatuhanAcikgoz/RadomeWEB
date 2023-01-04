<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr9
 *
 *  License: MIT
 *
 *  Panel haberlers page
 */

// Can the user view the panel?
if (!$user->handlePanelPageLoad('admincp.haberlers')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'haberler';
const PANEL_PAGE = 'haberlers';
$page_title = $haberler_language->get('haberler', 'haberlers');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

if (!isset($_GET['action']) && !isset($_GET['haberler'])) {
    $haberlers = DB::getInstance()->orderAll('haberlers', 'haberler_order', 'ASC')->results();
    $template_array = [];

    if (count($haberlers)) {
        $i = 1;
        $count = count($haberlers);
        foreach ($haberlers as $item) {
            if ($item->parent > 0) {
                $parent_haberler_query = DB::getInstance()->get('haberlers', ['id', $item->parent])->results();
                if (count($parent_haberler_query)) {
                    $parent_haberler_count = 1;
                    $parent_haberler = $haberler_language->get('haberler', 'parent_haberler_x', ['haberler' => Output::getClean($parent_haberler_query[0]->haberler_title)]);
                    $id = $parent_haberler_query[0]->parent;

                    while ($parent_haberler_count < 100 && $id > 0) {
                        $parent_haberler_query = DB::getInstance()->get('haberlers', ['id', $parent_haberler_query[0]->parent])->results();
                        $id = $parent_haberler_query[0]->parent;
                        $parent_haberler_count++;
                    }
                } else {
                    $parent_haberler = null;
                    $parent_haberler_count = 0;
                }
            } else {
                $parent_haberler_count = 0;
            }

            $template_array[] = [
                'edit_link' => URL::build('/panel/haberlers/', 'haberler=' . Output::getClean($item->id)),
                'delete_link' => URL::build('/panel/haberlers/', 'action=delete&fid=' . Output::getClean($item->id)),
                'up_link' => ($i > 1 ? URL::build('/panel/haberlers/', 'action=order&dir=up&fid=' . Output::getClean($item->id)) : null),
                'down_link' => ($i < $count ? URL::build('/panel/haberlers/', 'action=order&dir=down&fid=' . Output::getClean($item->id)) : null),
                'title' => Output::getClean($item->haberler_title),
                'description' => Output::getPurified($item->haberler_description),
                'id' => Output::getClean($item->id),
                'parent_haberler' => (($item->parent > 0) ? $parent_haberler : null),
                'parent_haberler_count' => $parent_haberler_count
            ];
            $i++;
        }
    }

    $haberler_reactions = Util::getSetting('haberler_reactions');

    $smarty->assign([
        'NEW_HABERLER' => $haberler_language->get('haberler', 'new_haberler'),
        'NEW_HABERLER_LINK' => URL::build('/panel/haberlers/', 'action=new'),
        'HABERLERS_ARRAY' => $template_array,
        'NO_HABERLERS' => $haberler_language->get('haberler', 'no_haberlers'),
        'REORDER_DRAG_URL' => URL::build('/panel/haberlers')
    ]);

    $template_file = 'haberler/haberlers.tpl';
} else {
    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'new':
                if (!isset($_GET['step'])) {
                    // Step 1
                    if (Input::exists()) {
                        $errors = [];

                        if (Token::check()) {
                            // Validate input
                            $validation = Validate::check($_POST, [
                                'haberlername' => [
                                    Validate::REQUIRED => true,
                                    Validate::MIN => 2,
                                    Validate::MAX => 150
                                ],
                                'haberlerdesc' => [
                                    Validate::MAX => 255
                                ],
                                'haberler_icon' => [
                                    Validate::MAX => 256
                                ]
                            ])->messages([
                                'haberlername' => [
                                    Validate::REQUIRED => $haberler_language->get('haberler', 'input_haberler_title'),
                                    Validate::MIN => $haberler_language->get('haberler', 'haberler_name_minimum'),
                                    Validate::MAX => $haberler_language->get('haberler', 'haberler_name_maximum')
                                ],
                                'haberlerdesc' => $haberler_language->get('haberler', 'haberler_description_maximum'),
                                'haberler_icon' => $haberler_language->get('haberler', 'haberler_icon_maximum')
                            ]);

                            if ($validation->passed()) {
                                // Create the haberler
                                try {
                                    $description = Input::get('haberlerdesc');

                                    $last_haberler_order = DB::getInstance()->orderAll('haberlers', 'haberler_order', 'DESC')->results();
                                    if (count($last_haberler_order)) {
                                        $last_haberler_order = $last_haberler_order[0]->haberler_order;
                                    } else {
                                        $last_haberler_order = 0;
                                    }

                                    DB::getInstance()->insert('haberlers', [
                                        'haberler_title' => Input::get('haberlername'),
                                        'haberler_description' => $description,
                                        'haberler_order' => $last_haberler_order + 1,
                                        'haberler_type' => Input::get('haberler_type'),
                                        'icon' => Input::get('haberler_icon')
                                    ]);

                                    $id = DB::getInstance()->lastId();

                                    Redirect::to(URL::build('/panel/haberlers/', 'action=new&step=2&haberler=' . $id));
                                } catch (Exception $e) {
                                    $errors[] = $e->getMessage();
                                }
                            } else {
                                $errors = $validation->errors();
                            }
                        } else {
                            // Invalid token
                            $errors[] = $language->get('general', 'invalid_token');
                        }
                    }

                    $smarty->assign([
                        'HABERLER_TYPE' => $haberler_language->get('haberler', 'haberler_type'),
                        'HABERLER_TYPE_HABERLER' => $haberler_language->get('haberler', 'haberler_type_haberler'),
                        'HABERLER_TYPE_CATEGORY' => $haberler_language->get('haberler', 'haberler_type_category'),
                        'HABERLER_NAME' => $haberler_language->get('haberler', 'haberler_name'),
                        'HABERLER_NAME_VALUE' => Output::getClean(Input::get('haberlername')),
                        'HABERLER_DESCRIPTION' => $haberler_language->get('haberler', 'haberler_description'),
                        'HABERLER_DESCRIPTION_VALUE' => Output::getClean(Input::get('haberlerdesc')),
                        'HABERLER_ICON' => $haberler_language->get('haberler', 'haberler_icon'),
                        'HABERLER_ICON_VALUE' => Output::getClean(Input::get('haberler_icon'))
                    ]);

                    $template_file = 'haberler/haberlers_new_step_1.tpl';
                } else {
                    // Parent category, for type haberler only
                    if (!isset($_GET['haberler']) || !is_numeric($_GET['haberler'])) {
                        Redirect::to(URL::build('/panel/haberlers'));
                    }

                    // Get haberler from database
                    $haberler = DB::getInstance()->get('haberlers', ['id', $_GET['haberler']])->results();
                    if (!count($haberler)) {
                        Redirect::to(URL::build('/panel/haberlers'));
                    }

                    $haberler = $haberler[0];

                    // Haberlers only
                    if ($haberler->haberler_type == 'category') {
                        Redirect::to(URL::build('/panel/haberlers/', 'haberler=' . $haberler->id));
                    }

                    // Deal with input

                    // Get a list of haberlers
                    $haberlers = DB::getInstance()->get('haberlers', ['id', '<>', $haberler->id])->results();
                    $template_array = [];

                    if (count($haberlers)) {
                        foreach ($haberlers as $item) {
                            $template_array[] = [
                                'id' => Output::getClean($item->id),
                                'name' => Output::getClean($item->haberler_title)
                            ];
                        }
                    }
                    $hooks_query = DB::getInstance()->orderAll('hooks', 'id', 'ASC')->results();
                    $hooks_array = [];
                    if (count($hooks_query)) {
                        foreach ($hooks_query as $hook) {
                            if (in_array('newTopic', json_decode($hook->events))) {
                                $hooks_array[] = [
                                    'id' => $hook->id,
                                    'name' => Output::getClean($hook->name),
                                ];
                            }
                        }
                    }
                    $smarty->assign([
                        'SELECT_PARENT_HABERLER' => $haberler_language->get('haberler', 'select_a_parent_haberler'),
                        'PARENT_HABERLERS' => $template_array,
                        'DISPLAY_TOPICS_AS_NEWS' => $haberler_language->get('haberler', 'display_topics_as_news'),
                        'REDIRECT_HABERLER' => $haberler_language->get('haberler', 'redirect_haberler'),
                        'REDIRECT_URL' => $haberler_language->get('haberler', 'redirect_url'),
                        'REDIRECT_URL_VALUE' => Output::getClean(Input::get('redirect_url')),
                        'INCLUDE_IN_HOOK' => $haberler_language->get('haberler', 'include_in_hook'),
                        'HOOKS_ARRAY' => $hooks_array,
                        'INFO' => $language->get('general', 'info'),
                        'HOOK_SELECT_INFO' => $language->get('admin', 'hook_select_info')
                    ]);

                    $template_file = 'haberler/haberlers_new_step_2.tpl';
                }

                $smarty->assign([
                    'CREATING_HABERLER' => $haberler_language->get('haberler', 'creating_haberler'),
                    'CANCEL' => $language->get('general', 'cancel'),
                    'CANCEL_LINK' => URL::build('/panel/haberlers'),
                    'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
                    'YES' => $language->get('general', 'yes'),
                    'NO' => $language->get('general', 'no'),
                    'CONFIRM_CANCEL' => $language->get('general', 'confirm_cancel')
                ]);

                break;

            case 'order':
                if (!isset($_GET['dir'])) {
                    echo $haberler_language->get('haberler', 'invalid_action') . ' - <a href="' . URL::build('/panel/haberlers') . '">' . $language->get('general', 'back') . '</a>';
                    die();
                }
                if ($_GET['dir'] == 'up' || $_GET['dir'] == 'down') {
                    if (!isset($_GET['fid']) || !is_numeric($_GET['fid'])) {
                        echo $haberler_language->get('haberler', 'invalid_action') . ' - <a href="' . URL::build('/panel/haberlers') . '">' . $language->get('general', 'back') . '</a>';
                        die();
                    }

                    if (!Token::check($_POST['token'])) {
                        Session::flash('admin_haberlers_error', $language->get('general', 'invalid_token'));
                        Redirect::to('/panel/haberlers');
                    }

                    $dir = $_GET['dir'];

                    $id = DB::getInstance()->get('haberlers', ['id', $_GET['fid']])->results();
                    $id = $id[0]->id;

                    $haberler_order = DB::getInstance()->get('haberlers', ['id', $_GET['fid']])->results();
                    $haberler_order = $haberler_order[0]->haberler_order;

                    $previous_haberlers = DB::getInstance()->orderAll('haberlers', 'haberler_order', 'ASC')->results();

                    if ($dir == 'up') {
                        $n = 0;
                        foreach ($previous_haberlers as $previous_haberler) {
                            if ($previous_haberler->id == $_GET['fid']) {
                                $previous_fid = $previous_haberlers[$n - 1]->id;
                                $previous_f_order = $previous_haberlers[$n - 1]->haberler_order;
                                break;
                            }
                            $n++;
                        }

                        try {
                            if (isset($previous_fid, $previous_f_order)) {
                                DB::getInstance()->update('haberlers', $id, [
                                    'haberler_order' => $previous_f_order
                                ]);
                                DB::getInstance()->update('haberlers', $previous_fid, [
                                    'haberler_order' => $previous_f_order + 1
                                ]);
                            }
                        } catch (Exception $e) {
                            $errors = [$e->getMessage()];
                        }

                        Redirect::to(URL::build('/panel/haberlers'));
                    }

                    if ($dir == 'down') {
                        $n = 0;
                        foreach ($previous_haberlers as $previous_haberler) {
                            if ($previous_haberler->id == $_GET['fid']) {
                                $previous_fid = $previous_haberlers[$n + 1]->id;
                                $previous_f_order = $previous_haberlers[$n + 1]->haberler_order;
                                break;
                            }
                            $n++;
                        }
                        try {
                            if (isset($previous_fid, $previous_f_order)) {
                                DB::getInstance()->update('haberlers', $id, [
                                    'haberler_order' => $previous_f_order
                                ]);
                                DB::getInstance()->update('haberlers', $previous_fid, [
                                    'haberler_order' => $previous_f_order - 1
                                ]);
                            }
                        } catch (Exception $e) {
                            $errors = [$e->getMessage()];
                        }

                        Redirect::to(URL::build('/panel/haberlers'));
                    }
                } else {
                    if ($_GET['dir'] == 'drag') {
                        // Get haberlers
                        if (isset($_GET['haberlers'])) {
                            $haberlers = json_decode($_GET['haberlers'])->haberlers;

                            $i = 0;
                            foreach ($haberlers as $item) {
                                DB::getInstance()->update('haberlers', $item, [
                                    'haberler_order' => $i
                                ]);

                                $i++;
                            }
                        }

                        die('Complete');
                    }

                    echo $haberler_language->get('haberler', 'invalid_action') . ' - <a href="' . URL::build('/panel/haberlers') . '">' . $language->get('general', 'back') . '</a>';
                    die();
                }
                break;

            case 'delete':
                if (!isset($_GET['fid']) || !is_numeric($_GET['fid'])) {
                    Redirect::to(URL::build('/panel/haberlers'));
                }

                // Ensure haberler exists
                $haberler = DB::getInstance()->get('haberlers', ['id', $_GET['fid']])->results();
                if (!count($haberler)) {
                    Redirect::to(URL::build('/panel/haberlers'));
                }
                $haberler = $haberler[0];

                if (Input::exists()) {
                    if (Token::check()) {
                        if (Input::get('confirm') === 'true') {
                            $haberler_perms = DB::getInstance()->get('haberlers_permissions', ['id', $_GET['fid']])->results(); // Get permissions to be deleted
                            if (Input::get('move_haberler') === 'none') {
                                $haberlers = DB::getInstance()->get('haberlers', ['id', $_GET['fid']])->results();
                                $topics = DB::getInstance()->get('topics', ['id', $_GET['fid']])->results();

                                foreach ($haberlers as $post) {
                                    DB::getInstance()->delete('haberlers', ['id', $post->id]);
                                }
                                foreach ($topics as $topic) {
                                    DB::getInstance()->delete('topics', ['id', $topic->id]);
                                }

                                // Haberler perm deletion

                            } else {
                                $new_haberler = Input::get('move_haberler');
                                $haberlers = DB::getInstance()->get('haberlers', ['id', $_GET['fid']])->results();
                                $topics = DB::getInstance()->get('topics', ['id', $_GET['fid']])->results();

                                foreach ($haberlers as $post) {
                                    DB::getInstance()->update('haberlers', $post->id, [
                                        'id' => $new_haberler
                                    ]);
                                }
                                foreach ($topics as $topic) {
                                    DB::getInstance()->update('topics', $topic->id, [
                                        'id' => $new_haberler
                                    ]);
                                }

                                // Haberler perm deletion

                            }
                            DB::getInstance()->delete('haberlers', ['id', $_GET['fid']]);
                            foreach ($haberler_perms as $perm) {
                                DB::getInstance()->delete('haberlers_permissions', ['id', $perm->id]);
                            }
                            Session::flash('admin_haberlers', $haberler_language->get('haberler', 'haberler_deleted_successfully'));
                            Redirect::to(URL::build('/panel/haberlers'));
                        }
                    } else {
                        $errors = [$language->get('general', 'invalid_token')];
                    }
                }

                $other_haberlers = DB::getInstance()->orderWhere('haberlers', 'parent > 0', 'haberler_order', 'ASC')->results();
                $template_array = [];
                foreach ($other_haberlers as $item) {
                    if ($item->id == $haberler->id) {
                        continue;
                    }

                    $template_array[] = [
                        'id' => Output::getClean($item->id),
                        'name' => Output::getClean($item->haberler_title)
                    ];
                }

                $smarty->assign([
                    'DELETE_HABERLER' => $haberler_language->get('haberler', 'delete_haberler'),
                    'MOVE_TOPICS_AND_POSTS_TO' => $haberler_language->get('haberler', 'move_topics_and_haberlers_to'),
                    'DELETE_TOPICS_AND_POSTS' => $haberler_language->get('haberler', 'delete_topics_and_haberlers'),
                    'OTHER_HABERLERS' => $template_array
                ]);

                $template_file = 'haberler/haberlers_delete.tpl';

                break;

            default:
                Redirect::to(URL::build('/panel/haberlers'));
        }
    } else {
        if (isset($_GET['haberler'])) {
            // Editing haberler
            if (!is_numeric($_GET['haberler'])) {
                die();
            }

            $haberler = DB::getInstance()->get('haberlers', ['id', $_GET['haberler']])->results();

            if (!count($haberler)) {
                Redirect::to(URL::build('/panel/haberlers'));
            }

            $available_haberlers = DB::getInstance()->orderWhere('haberlers', 'id > 0', 'haberler_order', 'ASC')->results(); // Get a list of all haberlers which can be chosen as a parent

            if (Input::exists()) {
                $errors = [];

                if (Token::check()) {
                    if (Input::get('action') == 'update') {
                        $validation = Validate::check($_POST, [
                            'title' => [
                                Validate::REQUIRED => true,
                                Validate::MIN => 2,
                                Validate::MAX => 150
                            ],
                            'description' => [
                                Validate::MAX => 255
                            ],
                            'icon' => [
                                Validate::MAX => 256
                            ]
                        ])->messages([
                            'title' => [
                                Validate::REQUIRED => $haberler_language->get('haberler', 'input_haberler_title'),
                                Validate::MIN => $haberler_language->get('haberler', 'haberler_name_minimum'),
                                Validate::MAX => $haberler_language->get('haberler', 'haberler_name_maximum')
                            ],
                            'description' => $haberler_language->get('haberler', 'haberler_description_maximum'),
                            'icon' => $haberler_language->get('haberler', 'haberler_icon_maximum')
                        ]);

                        if ($validation->passed()) {
                            try {
                                if (isset($_POST['redirect']) && $_POST['redirect'] == 1) {
                                    $redirect = 1;
                                    if (isset($_POST['redirect_url']) && strlen($_POST['redirect_url']) > 0 && strlen($_POST['redirect_url']) <= 512) {
                                        $redirect_url = Output::getClean($_POST['redirect_url']);
                                    } else {
                                        $redirect = 0;
                                        $redirect_url = null;
                                        $redirect_error = true;
                                    }
                                } else {
                                    $redirect = 0;
                                    $redirect_url = null;
                                }

                                $parent = $_POST['parent_haberler'] ?? 0;

                                if (isset($_POST['hooks']) && count($_POST['hooks'])) {
                                    $hooks = json_encode($_POST['hooks']);
                                } else {
                                    $hooks = null;
                                }

                                if (isset($_POST['default_labels']) && count($_POST['default_labels'])) {
                                    $default_labels = implode(',', $_POST['default_labels']);
                                } else {
                                    $default_labels = null;
                                }

                                // Update the haberler
                                $to_update = [
                                    'haber_title' => Output::getClean(Input::get('title')),
                                ];

                                if (!isset($redirect_error)) {
                                    $to_update['redirect_url'] = $redirect_url;
                                }

                                DB::getInstance()->update('haberlers', $_GET['haberler'], $to_update);
                            } catch (Exception $e) {
                                $errors[] = $e->getMessage();
                            }

                            // Guest haberler permissions
                            $view = Input::get('perm-view-0');
                            $create = 0;
                            $edit = 0;
                            $post = 0;
                            $view_others = Input::get('perm-view_others-0');
                            $moderate = 0;

                            if (!($view)) {
                                $view = 0;
                            }

                            $haberler_perm_exists = 0;

                            $haberler_perm_query = DB::getInstance()->get('haberlers_permissions', ['id', $_GET['haberler']])->results();
                            if (count($haberler_perm_query)) {
                                foreach ($haberler_perm_query as $query) {
                                    if ($query->group_id == 0) {
                                        $haberler_perm_exists = 1;
                                        $update_id = $query->id;
                                        break;
                                    }
                                }
                            }

                            try {
                                if ($haberler_perm_exists != 0) { // Permission already exists, update
                                    // Update the haberler
                                    DB::getInstance()->update('haberlers_permissions', $update_id, [
                                        'view' => $view,
                                        'create_topic' => $create,
                                        'edit_topic' => $edit,
                                        'create_post' => $post,
                                        'view_other_topics' => $view_others,
                                        'moderate' => $moderate
                                    ]);
                                } else { // Permission doesn't exist, create
                                    DB::getInstance()->insert('haberlers_permissions', [
                                        'group_id' => 0,
                                        'id' => $_GET['haberler'],
                                        'view' => $view,
                                        'create_topic' => $create,
                                        'edit_topic' => $edit,
                                        'create_post' => $post,
                                        'view_other_topics' => $view_others,
                                        'moderate' => $moderate
                                    ]);
                                }
                            } catch (Exception $e) {
                                $errors[] = $e->getMessage();
                            }

                            // Group haberler permissions
                            foreach (Group::all() as $group) {
                                $view = Input::get('perm-view-' . $group->id);
                                $create = Input::get('perm-topic-' . $group->id);
                                $edit = Input::get('perm-edit_topic-' . $group->id);
                                $post = Input::get('perm-post-' . $group->id);
                                $view_others = Input::get('perm-view_others-' . $group->id);
                                $moderate = Input::get('perm-moderate-' . $group->id);

                                if (!($view)) {
                                    $view = 0;
                                }
                                if (!($create)) {
                                    $create = 0;
                                }
                                if (!($edit)) {
                                    $edit = 0;
                                }
                                if (!($post)) {
                                    $post = 0;
                                }
                                if (!($view_others)) {
                                    $view_others = 0;
                                }
                                if (!($moderate)) {
                                    $moderate = 0;
                                }

                                $haberler_perm_exists = 0;

                                if (count($haberler_perm_query)) {
                                    foreach ($haberler_perm_query as $query) {
                                        if ($query->group_id == $group->id) {
                                            $haberler_perm_exists = 1;
                                            $update_id = $query->id;
                                            break;
                                        }
                                    }
                                }

                                try {
                                    if ($haberler_perm_exists != 0) { // Permission already exists, update
                                        // Update the haberler
                                        DB::getInstance()->update('haberlers_permissions', $update_id, [
                                            'view' => $view,
                                            'create_topic' => $create,
                                            'edit_topic' => $edit,
                                            'create_post' => $post,
                                            'view_other_topics' => $view_others,
                                            'moderate' => $moderate
                                        ]);
                                    } else { // Permission doesn't exist, create
                                        DB::getInstance()->insert('haberlers_permissions', [
                                            'group_id' => $group->id,
                                            'id' => $_GET['haberler'],
                                            'view' => $view,
                                            'create_topic' => $create,
                                            'edit_topic' => $edit,
                                            'create_post' => $post,
                                            'view_other_topics' => $view_others,
                                            'moderate' => $moderate
                                        ]);
                                    }
                                } catch (Exception $e) {
                                    $errors[] = $e->getMessage();
                                }
                            }

                            Session::flash('admin_haberlers', $haberler_language->get('haberler', 'haberler_updated_successfully'));
                            Redirect::to(URL::build('/panel/haberlers'));
                        }

                        $errors = $validation->errors();
                    }
                } else {
                    $errors[] = $language->get('general', 'invalid_token');
                }
            }

            $hooks_query = DB::getInstance()->orderAll('hooks', 'id', 'ASC')->results();
            $hooks_array = [];
            if (count($hooks_query)) {
                foreach ($hooks_query as $hook) {
                    if (in_array('newTopic', json_decode($hook->events))) {
                        $hooks_array[] = [
                            'id' => $hook->id,
                            'name' => Output::getClean($hook->name),
                        ];
                    }
                }
            }

            $haberler_hooks = $haberler[0]->hooks ?: '[]';

            $template_haberlers_array = [];
            if (count($available_haberlers)) {
                foreach ($available_haberlers as $item) {
                    if ($item->id == $haberler[0]->id) {
                        continue;
                    }
                    $template_haberlers_array[] = [
                        'id' => $item->id,
                        'title' => Output::getClean($item->haberler_title)
                    ];
                }
            }

            // Get all haberler permissions
            $guest_query = DB::getInstance()->query('SELECT 0 AS id, `view`, view_other_topics FROM rw_haberlers_permissions WHERE group_id = 0 AND id = ?', [$haberler[0]->id])->results();
            $group_query = DB::getInstance()->query('SELECT id, name, `view`, create_topic, edit_topic, create_post, view_other_topics, moderate FROM rw_groups A LEFT JOIN (SELECT group_id, `view`, create_topic, edit_topic, create_post, view_other_topics, moderate FROM rw_haberlers_permissions WHERE id = ?) B ON A.id = B.group_id ORDER BY `order` ASC', [$haberler[0]->id])->results();

            // Get default labels
            $enabled_labels = $haberler[0]->default_labels ? explode(',', $haberler[0]->default_labels) : [];
            $haberler_labels = DB::getInstance()->get('haberlers_topic_labels', ['id', '<>', 0])->results();
            $available_labels = [];
            if (count($haberler_labels)) {
                foreach ($haberler_labels as $label) {
                    $ids = explode(',', $label->fids);

                    if (in_array($haberler[0]->id, $ids)) {
                        $available_labels[] = [
                            'id' => Output::getClean($label->id),
                            'name' => Output::getClean($label->name),
                            'is_enabled' => in_array($label->id, $enabled_labels)
                        ];
                    }
                }
            }

            $smarty->assign([
                'CANCEL' => $language->get('general', 'cancel'),
                'CANCEL_LINK' => URL::build('/panel/haberlers'),
                'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
                'YES' => $language->get('general', 'yes'),
                'NO' => $language->get('general', 'no'),
                'CONFIRM_CANCEL' => $language->get('general', 'confirm_cancel'),
                'HABERLER_TYPE' => $haberler_language->get('haberler', 'haberler_type'),
                'HABERLER_TYPE_HABERLER' => $haberler_language->get('haberler', 'haberler_type_haberler'),
                'HABERLER_TYPE_CATEGORY' => $haberler_language->get('haberler', 'haberler_type_category'),
                'HABERLER_TYPE_VALUE' => ($haberler[0]->haberler_type == 'category') ? 'category' : 'haberler',
                'HABERLER_TITLE' => $haberler_language->get('haberler', 'haberler_name'),
                'HABERLER_TITLE_VALUE' => Output::getClean($haberler[0]->haberler_title),
                'HABERLER_DESCRIPTION' => $haberler_language->get('haberler', 'haberler_description'),
                'HABERLER_DESCRIPTION_VALUE' => Output::getClean($haberler[0]->haberler_description),
                'HABERLER_ICON' => $haberler_language->get('haberler', 'haberler_icon'),
                'HABERLER_ICON_VALUE' => Output::getClean($haberler[0]->icon),
                'PARENT_HABERLER' => $haberler_language->get('haberler', 'parent_haberler'),
                'PARENT_HABERLER_VALUE' => $haberler[0]->parent,
                'NO_PARENT' => $haberler_language->get('haberler', 'has_no_parent'),
                'PARENT_HABERLER_LIST' => $template_haberlers_array,
                'DISPLAY_TOPICS_AS_NEWS' => $haberler_language->get('haberler', 'display_topics_as_news'),
                'DISPLAY_TOPICS_AS_NEWS_VALUE' => ($haberler[0]->news == 1),
                'REDIRECT_HABERLER' => $haberler_language->get('haberler', 'redirect_haberler'),
                'REDIRECT_HABERLER_VALUE' => ($haberler[0]->redirect_haberler == 1),
                'REDIRECT_URL' => $haberler_language->get('haberler', 'redirect_url'),
                'REDIRECT_URL_VALUE' => Output::getClean($haberler[0]->redirect_url),
                'INCLUDE_IN_HOOK' => $haberler_language->get('haberler', 'include_in_hook'),
                'HOOKS_ARRAY' => $hooks_array,
                'HABERLER_HOOKS' => json_decode($haberler_hooks),
                'INFO' => $language->get('general', 'info'),
                'HOOK_SELECT_INFO' => $language->get('admin', 'hook_select_info'),
                'HABERLER_PERMISSIONS' => $haberler_language->get('haberler', 'haberler_permissions'),
                'GUESTS' => $language->get('user', 'guests'),
                'GUEST_PERMISSIONS' => (count($guest_query) ? $guest_query[0] : []),
                'GROUP_PERMISSIONS' => $group_query,
                'GROUP' => $haberler_language->get('haberler', 'group'),
                'CAN_VIEW_HABERLER' => $haberler_language->get('haberler', 'can_view_haberler'),
                'CAN_CREATE_TOPIC' => $haberler_language->get('haberler', 'can_create_topic'),
                'CAN_EDIT_TOPIC' => $haberler_language->get('haberler', 'can_edit_topic'),
                'CAN_POST_REPLY' => $haberler_language->get('haberler', 'can_post_reply'),
                'CAN_VIEW_OTHER_TOPICS' => $haberler_language->get('haberler', 'can_view_other_topics'),
                'CAN_MODERATE_HABERLER' => $haberler_language->get('haberler', 'can_moderate_haberler'),
                'TOPIC_PLACEHOLDER' => $haberler_language->get('haberler', 'topic_placeholder'),
                'TOPIC_PLACEHOLDER_VALUE' => Output::getPurified($haberler[0]->topic_placeholder),
                'DEFAULT_LABELS' => $haberler_language->get('haberler', 'default_labels'),
                'DEFAULT_LABELS_INFO' => $haberler_language->get('haberler', 'default_labels_info'),
                'AVAILABLE_DEFAULT_LABELS' => $available_labels
            ]);

            $template_file = 'haberler/haberlers_edit.tpl';
        }
    }
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

if (Session::exists('admin_haberlers')) {
    $success = Session::flash('admin_haberlers');
}

if (Session::exists('admin_haberlers_error')) {
    $errors = [Session::flash('admin_haberlers_error')];
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
    'HABERLER' => $haberler_language->get('haberler', 'haberler'),
    'HABERLERS' => $haberler_language->get('haberler', 'haberlers'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
    'NO_ITEM_SELECTED' => $language->get('admin', 'no_item_selected'),
]);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate($template_file, $smarty);
