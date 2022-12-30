<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr9
 *
 *  License: MIT
 *
 *  Panel haberler labels page
 */

// Can the user view the panel?
if (!$user->handlePanelPageLoad('admincp.haberlers')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'haberler';
const PANEL_PAGE = 'haberler_labels';
$page_title = $haberler_language->get('haberler', 'labels');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

if (!isset($_GET['action'])) {
    $db = DB::getInstance();
    // Topic labels
    $topic_labels = $db->get('haberlers_topic_labels', ['id', '<>', 0])->results();
    $template_array = [];

    if (count($topic_labels)) {
        foreach ($topic_labels as $topic_label) {
            $label_type = $db->get('haberlers_labels', ['id', $topic_label->label])->results();
            if (!count($label_type)) {
                $label_type = 0;
            } else {
                $label_type = $label_type[0];
            }

            // List of haberlers label is enabled in
            $enabled_haberlers = explode(',', $topic_label->fids);
            $haberlers_string = '';
            foreach ($enabled_haberlers as $item) {
                $haberler_name = $db->get('haberlers', ['id', $item])->results();
                if (count($haberler_name)) {
                    $haberlers_string .= Output::getClean($haberler_name[0]->haberler_title) . ', ';
                } else {
                    $haberlers_string .= $haberler_language->get('haberler', 'no_haberlers');
                }
            }
            $haberlers_string = rtrim($haberlers_string, ', ');

            $template_array[] = [
                'name' => str_replace('{x}', Output::getClean($topic_label->name), Output::getPurified($label_type->html)),
                'edit_link' => URL::build('/panel/haberlers/labels/', 'action=edit&lid=' . Output::getClean($topic_label->id)),
                'delete_link' => URL::build('/panel/haberlers/labels/', 'action=delete&lid=' . Output::getClean($topic_label->id)),
                'enabled_haberlers' => $haberlers_string
            ];
        }
    }

    $smarty->assign([
        'LABEL_TYPES' => $haberler_language->get('haberler', 'label_types'),
        'LABEL_TYPES_LINK' => URL::build('/panel/haberlers/labels/', 'action=types'),
        'NEW_LABEL' => $haberler_language->get('haberler', 'new_label'),
        'NEW_LABEL_LINK' => URL::build('/panel/haberlers/labels/', 'action=new'),
        'ALL_LABELS' => $template_array,
        'EDIT' => $language->get('general', 'edit'),
        'DELETE' => $language->get('general', 'delete'),
        'CONFIRM_DELETE' => $language->get('general', 'confirm_deletion'),
        'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
        'YES' => $language->get('general', 'yes'),
        'NO' => $language->get('general', 'no'),
        'NO_LABELS' => $haberler_language->get('haberler', 'no_labels_defined')
    ]);

    $template_file = 'haberler/labels.tpl';

} else {
    switch ($_GET['action']) {
        case 'new':
            // Deal with input
            if (Input::exists()) {
                // Check token
                if (Token::check()) {
                    // Valid token
                    // Validate input
                    $validation = Validate::check($_POST, [
                        'label_name' => [
                            Validate::REQUIRED => true,
                            Validate::MIN => 1,
                            Validate::MAX => 32
                        ],
                        'label_id' => [
                            Validate::REQUIRED => true
                        ]
                    ])->message($haberler_language->get('haberler', 'label_creation_error'));

                    if ($validation->passed()) {
                        // Create string containing selected haberler IDs
                        $haberler_string = '';
                        if (isset($_POST['label_haberlers']) && count($_POST['label_haberlers'])) {
                            // Turn array of inputted haberlers into string of haberlers
                            foreach ($_POST['label_haberlers'] as $item) {
                                $haberler_string .= $item . ',';
                            }
                        }

                        $haberler_string = rtrim($haberler_string, ',');

                        $group_string = '';
                        if (isset($_POST['label_groups']) && count($_POST['label_groups'])) {
                            foreach ($_POST['label_groups'] as $item) {
                                $group_string .= $item . ',';
                            }
                        }

                        $group_string = rtrim($group_string, ',');

                        try {
                            DB::getInstance()->insert('haberlers_topic_labels', [
                                'fids' => $haberler_string,
                                'name' => Output::getClean(Input::get('label_name')),
                                'label' => Input::get('label_id'),
                                'gids' => $group_string
                            ]);

                            Session::flash('haberler_labels', $haberler_language->get('haberler', 'label_creation_success'));
                            Redirect::to(URL::build('/panel/haberlers/labels'));
                        } catch (Exception $e) {
                            $errors = [$e->getMessage()];
                        }

                    } else {
                        // Validation errors
                        $errors = $validation->errors();
                    }

                } else {
                    // Invalid token
                    $errors = [$language->get('general', 'invalid_token')];
                }
            }

            // Get a list of labels
            $labels = DB::getInstance()->get('haberlers_labels', ['id', '<>', 0])->results();
            $template_array = [];

            if (count($labels)) {
                foreach ($labels as $label) {
                    $template_array[] = [
                        'id' => Output::getClean($label->id),
                        'name' => str_replace('{x}', Output::getClean($label->name), Output::getPurified($label->html))
                    ];
                }
            }

            // Get a list of haberlers
            $haberler_list = DB::getInstance()->orderWhere('haberlers', 'parent <> 0', 'haberler_order', 'ASC')->results();
            $template_haberlers = [];

            if (count($haberler_list)) {
                foreach ($haberler_list as $item) {
                    $template_haberlers[] = [
                        'id' => Output::getClean($item->id),
                        'name' => Output::getClean($item->haberler_title)
                    ];
                }
            }

            // Get a list of all groups
            $template_groups = [];

            foreach (Group::all() as $item) {
                $template_groups[] = [
                    'id' => Output::getClean($item->id),
                    'name' => Output::getClean($item->name)
                ];
            }

            $smarty->assign([
                'CREATING_LABEL' => $haberler_language->get('haberler', 'creating_label'),
                'CANCEL' => $language->get('general', 'cancel'),
                'CANCEL_LINK' => URL::build('/panel/haberlers/labels'),
                'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
                'CONFIRM_CANCEL' => $language->get('general', 'confirm_cancel'),
                'YES' => $language->get('general', 'yes'),
                'NO' => $language->get('general', 'no'),
                'LABEL_NAME' => $haberler_language->get('haberler', 'label_name'),
                'LABEL_NAME_VALUE' => Output::getClean(Input::get('label_name')),
                'LABEL_TYPE' => $haberler_language->get('haberler', 'label_type'),
                'LABEL_TYPES' => $template_array,
                'LABEL_HABERLERS' => $haberler_language->get('haberler', 'label_haberlers'),
                'ALL_HABERLERS' => $template_haberlers,
                'LABEL_GROUPS' => $haberler_language->get('haberler', 'label_groups'),
                'ALL_GROUPS' => $template_groups
            ]);

            $template_file = 'haberler/labels_new.tpl';

            break;

        case 'edit':
            // Editing a label
            if (!isset($_GET['lid']) || !is_numeric($_GET['lid'])) {
                // Check the label ID is valid
                Redirect::to(URL::build('/panel/haberlers/labels'));
            }

            // Does the label exist?
            $label = DB::getInstance()->get('haberlers_topic_labels', ['id', $_GET['lid']])->results();
            if (!count($label)) {
                // No, it doesn't exist
                Redirect::to(URL::build('/panel/haberlers/labels'));
            }

            $label = $label[0];

            // Deal with input
            if (Input::exists()) {
                // Check token
                if (Token::check()) {
                    // Valid token
                    // Validate input
                    $validation = Validate::check($_POST, [
                        'label_name' => [
                            Validate::REQUIRED => true,
                            Validate::MIN => 1,
                            Validate::MAX => 32
                        ],
                        'label_id' => [
                            Validate::REQUIRED => true
                        ]
                    ])->message($haberler_language->get('haberler', 'label_creation_error'));

                    if ($validation->passed()) {
                        // Create string containing selected haberler IDs
                        $haberler_string = '';
                        if (isset($_POST['label_haberlers']) && count($_POST['label_haberlers'])) {
                            foreach ($_POST['label_haberlers'] as $item) {
                                // Turn array of inputted haberlers into string of haberlers
                                $haberler_string .= $item . ',';
                            }
                        }

                        $haberler_string = rtrim($haberler_string, ',');

                        $group_string = '';
                        if (isset($_POST['label_groups']) && count($_POST['label_groups'])) {
                            foreach ($_POST['label_groups'] as $item) {
                                $group_string .= $item . ',';
                            }
                        }

                        $group_string = rtrim($group_string, ',');

                        try {
                            DB::getInstance()->update('haberlers_topic_labels', $label->id, [
                                'fids' => $haberler_string,
                                'name' => Output::getClean(Input::get('label_name')),
                                'label' => Input::get('label_id'),
                                'gids' => $group_string
                            ]);

                            Session::flash('haberler_labels', $haberler_language->get('haberler', 'label_edit_success'));
                            Redirect::to(URL::build('/panel/haberlers/labels', 'action=edit&lid=' . Output::getClean($label->id)));
                        } catch (Exception $e) {
                            $errors = [$e->getMessage()];
                        }

                    } else {
                        // Validation errors
                        $errors = $validation->errors();
                    }

                } else {
                    // Invalid token
                    $errors = [$language->get('general', 'invalid_token')];
                }
            }

            // Get a list of labels
            $labels = DB::getInstance()->get('haberlers_labels', ['id', '<>', 0])->results();
            $template_array = [];

            if (count($labels)) {
                foreach ($labels as $item) {
                    $template_array[] = [
                        'id' => Output::getClean($item->id),
                        'name' => str_replace('{x}', Output::getClean($item->name), Output::getPurified($item->html)),
                        'selected' => ($label->label == $item->id)
                    ];
                }
            }

            // Get a list of haberlers
            $haberler_list = DB::getInstance()->orderWhere('haberlers', 'parent <> 0', 'haberler_order', 'ASC')->results();
            $template_haberlers = [];

            // Get a list of haberlers in which the label is enabled
            $enabled_haberlers = explode(',', $label->fids);

            if (count($haberler_list)) {
                foreach ($haberler_list as $item) {
                    $template_haberlers[] = [
                        'id' => Output::getClean($item->id),
                        'name' => Output::getClean($item->haberler_title),
                        'selected' => (in_array($item->id, $enabled_haberlers))
                    ];
                }
            }

            // Get a list of all groups
            $template_groups = [];

            // Get a list of groups which have access to the label
            $groups = explode(',', $label->gids);

            foreach (Group::all() as $item) {
                $template_groups[] = [
                    'id' => Output::getClean($item->id),
                    'name' => Output::getClean($item->name),
                    'selected' => (in_array($item->id, $groups))
                ];
            }

            $smarty->assign([
                'EDITING_LABEL' => $haberler_language->get('haberler', 'editing_label'),
                'CANCEL' => $language->get('general', 'cancel'),
                'CANCEL_LINK' => URL::build('/panel/haberlers/labels'),
                'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
                'CONFIRM_CANCEL' => $language->get('general', 'confirm_cancel'),
                'YES' => $language->get('general', 'yes'),
                'NO' => $language->get('general', 'no'),
                'LABEL_NAME' => $haberler_language->get('haberler', 'label_name'),
                'LABEL_NAME_VALUE' => Output::getClean($label->name),
                'LABEL_TYPE' => $haberler_language->get('haberler', 'label_type'),
                'LABEL_TYPES' => $template_array,
                'LABEL_HABERLERS' => $haberler_language->get('haberler', 'label_haberlers'),
                'ALL_HABERLERS' => $template_haberlers,
                'LABEL_GROUPS' => $haberler_language->get('haberler', 'label_groups'),
                'ALL_GROUPS' => $template_groups
            ]);

            $template_file = 'haberler/labels_edit.tpl';

            break;

        case 'delete':
            // Label deletion
            if (!isset($_GET['lid']) || !is_numeric($_GET['lid'])) {
                // Check the label ID is valid
                Redirect::to(URL::build('/panel/haberlers/labels'));
            }

            if (Token::check($_POST['token'])) {
                // Delete the label
                DB::getInstance()->delete('haberlers_topic_labels', ['id', $_GET['lid']]);
                Session::flash('haberler_labels', $haberler_language->get('haberler', 'label_deleted_successfully'));

            } else {
                Session::flash('haberler_labels_error', $language->get('general', 'invalid_token'));
            }

            Redirect::to(URL::build('/panel/haberlers/labels'));

        case 'types':
            // List label types
            // $labels = DB::getInstance()->get('haberlers_labels', ['id', '<>', 0])->results();
            $labels = DB::getInstance()->query(
                "SELECT `nl2_haberlers_labels`.*, (SELECT COUNT(id) FROM nl2_haberlers_topic_labels WHERE nl2_haberlers_labels.id = nl2_haberlers_topic_labels.id) as count FROM `nl2_haberlers_labels`"
            )->results();
            $template_array = [];

            if (count($labels)) {
                foreach ($labels as $label) {
                    $template_array[] = [
                        'name' => str_replace('{x}', Output::getClean($label->name), Output::getPurified($label->html)),
                        'edit_link' => URL::build('/panel/haberlers/labels/', 'action=edit_type&lid=' . Output::getClean($label->id)),
                        'delete_link' => URL::build('/panel/haberlers/labels/', 'action=delete_type&lid=' . Output::getClean($label->id)),
                        'usages' => (int) $label->count,
                    ];
                }
            }

            $smarty->assign([
                'LABEL_TYPES' => $haberler_language->get('haberler', 'label_types'),
                'LABELS_LINK' => URL::build('/panel/haberlers/labels'),
                'NEW_LABEL_TYPE' => $haberler_language->get('haberler', 'new_label_type'),
                'NEW_LABEL_TYPE_LINK' => URL::build('/panel/haberlers/labels/', 'action=new_type'),
                'ALL_LABEL_TYPES' => $template_array,
                'EDIT' => $language->get('general', 'edit'),
                'DELETE' => $language->get('general', 'delete'),
                'CONFIRM_DELETE' => $language->get('general', 'confirm_deletion'),
                'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
                'YES' => $language->get('general', 'yes'),
                'NO' => $language->get('general', 'no'),
                'NO_LABEL_TYPES' => $haberler_language->get('haberler', 'no_label_types_defined')
            ]);

            $template_file = 'haberler/labels_types.tpl';

            break;

        case 'new_type':
            // Creating a label type
            // Deal with input
            if (Input::exists()) {
                // Check token
                if (Token::check()) {
                    // Valid token
                    // Validate input
                    $validation = Validate::check($_POST, [
                        'label_name' => [
                            Validate::REQUIRED => true,
                            Validate::MIN => 1,
                            Validate::MAX => 32
                        ],
                        'label_html' => [
                            Validate::REQUIRED => true,
                            Validate::MIN => 1,
                            Validate::MAX => 1024
                        ]
                    ])->message($haberler_language->get('haberler', 'label_type_creation_error'));

                    if ($validation->passed()) {
                        try {
                            DB::getInstance()->insert('haberlers_labels', [
                                'name' => Output::getClean(Input::get('label_name')),
                                'html' => Input::get('label_html')
                            ]);

                            Session::flash('haberler_labels', $haberler_language->get('haberler', 'label_type_creation_success'));
                            Redirect::to(URL::build('/panel/haberlers/labels/', 'action=types'));

                        } catch (Exception $e) {
                            $errors = [$e->getMessage()];
                        }

                    } else {
                        // Validation errors
                        $errors = $validation->errors();
                    }

                } else {
                    // Invalid token
                    $errors = [$language->get('general', 'invalid_token')];
                }
            }


            $smarty->assign([
                'LABEL_TYPES' => $haberler_language->get('haberler', 'label_types'),
                'CREATING_LABEL_TYPE' => $haberler_language->get('haberler', 'creating_label_type'),
                'CANCEL' => $language->get('general', 'cancel'),
                'CONFIRM_CANCEL' => $language->get('general', 'confirm_cancel'),
                'CANCEL_LINK' => URL::build('/panel/haberlers/labels/', 'action=types'),
                'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
                'YES' => $language->get('general', 'yes'),
                'NO' => $language->get('general', 'no'),
                'LABEL_TYPE_NAME' => $haberler_language->get('haberler', 'label_type_name'),
                'LABEL_TYPE_NAME_VALUE' => Output::getClean(Input::get('label_type_name')),
                'LABEL_TYPE_HTML' => $haberler_language->get('haberler', 'label_type_html'),
                'INFO' => $language->get('general', 'info'),
                'LABEL_TYPE_HTML_INFO' => $haberler_language->get('haberler', 'label_type_html_help'),
                'LABEL_TYPE_HTML_VALUE' => Output::getClean(Input::get('label_type_html'))
            ]);

            $template_file = 'haberler/labels_types_new.tpl';

            break;

        case 'edit_type':
            // Editing a label type
            if (!isset($_GET['lid']) || !is_numeric($_GET['lid'])) {
                Redirect::to(URL::build('/panel/haberlers/labels/', 'action=types'));
            }

            // Does the label exist?
            $label = DB::getInstance()->get('haberlers_labels', ['id', $_GET['lid']])->results();
            if (!count($label)) {
                // No, it doesn't exist
                Redirect::to(URL::build('/panel/haberlers/labels/', 'action=types'));
            }

            $label = $label[0];

            // Deal with input
            if (Input::exists()) {
                // Check token
                if (Token::check()) {
                    // Valid token
                    // Validate input
                    $validation = Validate::check($_POST, [
                        'label_name' => [
                            Validate::REQUIRED => true,
                            Validate::MIN => 1,
                            Validate::MAX => 32
                        ],
                        'label_html' => [
                            Validate::REQUIRED => true,
                            Validate::MIN => 1,
                            Validate::MAX => 1024
                        ]
                    ])->message($haberler_language->get('haberler', 'label_type_creation_error'));

                    if ($validation->passed()) {
                        try {
                            DB::getInstance()->update('haberlers_labels', $label->id, [
                                'name' => Output::getClean(Input::get('label_name')),
                                'html' => Input::get('label_html')
                            ]);

                            Session::flash('haberler_labels', $haberler_language->get('haberler', 'label_type_edit_success'));
                            Redirect::to(URL::build('/panel/haberlers/labels/', 'action=edit_type&lid=' . Output::getClean($label->id)));
                        } catch (Exception $e) {
                            $errors = [$e->getMessage()];
                        }

                    } else {
                        // Validation errors
                        $errors = $validation->errors();
                    }

                } else {
                    // Invalid token
                    $errors = [$language->get('general', 'invalid_token')];
                }
            }

            $smarty->assign([
                'LABEL_TYPES' => $haberler_language->get('haberler', 'label_types'),
                'EDITING_LABEL_TYPE' => $haberler_language->get('haberler', 'editing_label_type'),
                'CANCEL' => $language->get('general', 'cancel'),
                'CONFIRM_CANCEL' => $language->get('general', 'confirm_cancel'),
                'CANCEL_LINK' => URL::build('/panel/haberlers/labels/', 'action=types'),
                'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
                'YES' => $language->get('general', 'yes'),
                'NO' => $language->get('general', 'no'),
                'LABEL_TYPE_NAME' => $haberler_language->get('haberler', 'label_type_name'),
                'LABEL_TYPE_NAME_VALUE' => Output::getClean($label->name),
                'LABEL_TYPE_HTML' => $haberler_language->get('haberler', 'label_type_html'),
                'INFO' => $language->get('general', 'info'),
                'LABEL_TYPE_HTML_INFO' => $haberler_language->get('haberler', 'label_type_html_help'),
                'LABEL_TYPE_HTML_VALUE' => Output::getClean($label->html)
            ]);

            $template_file = 'haberler/labels_types_edit.tpl';

            break;

        case 'delete_type':
            // Label deletion
            if (!isset($_GET['lid']) || !is_numeric($_GET['lid'])) {
                // Check the label ID is valid
                Redirect::to(URL::build('/panel/haberlers/labels/', 'action=types'));
            }

            if (Token::check($_POST['token'])) {
                // Make sure label type is not in use
                $count = DB::getInstance()->query('SELECT COUNT(id) AS count FROM nl2_haberlers_topic_labels WHERE nl2_haberlers_topic_labels.label = ?', [$_GET['lid']])->first()->count;

                if ($count < 1) {
                    // Delete the label
                    DB::getInstance()->delete('haberlers_labels', ['id', $_GET['lid']]);
                    Session::flash('haberler_labels', $haberler_language->get('haberler', 'label_type_deleted_successfully'));
                } else {
                    Session::flash('haberler_labels_error', $haberler_language->get('haberler', 'label_type_in_use'));
                }

            } else {
                Session::flash('haberler_labels_error', $language->get('general', 'invalid_token'));
            }

            Redirect::to(URL::build('/panel/haberlers/labels/', 'action=types'));

        default:
            Redirect::to(URL::build('/panel/haberlers/labels'));
    }

}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

if (Session::exists('haberler_labels')) {
    $success = Session::flash('haberler_labels');
}

if (Session::exists('haberler_labels_error')) {
    $errors = [Session::flash('haberler_labels_error')];
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
    'LABELS' => $haberler_language->get('haberler', 'labels'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit')
]);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate($template_file, $smarty);
