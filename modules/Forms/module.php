<?php 
/*
 *  Made by Partydragen
 *  https://github.com/partydragen/Radome-Forms
 *  RadomeWEB version 2.0.1
 *
 *  License: MIT
 *
 *  Forms module file
 */

class Forms_Module extends Module {
    private DB $_db;
    private $_language;
    private $_forms_language;
    private $_cache;

    public function __construct($language, $forms_language, $pages, $user, $navigation, $cache, $endpoints) {
        $this->_db = DB::getInstance();
        $this->_language = $language;
        $this->_forms_language = $forms_language;
        $this->_cache = $cache;

        $name = 'Forms';
        $author = '<a href="https://partydragen.com" target="_blank" rel="nofollow noopener">Partydragen</a>';
        $module_version = '1.9.2';
        $radome_version = '2.1.0';

        parent::__construct($this, $name, $author, $module_version, $radome_version);

        // Define URLs which belong to this module
        $pages->add('Forms', '/panel/form', 'pages/panel/form.php');
        $pages->add('Forms', '/panel/formlar', 'pages/panel/forms.php');
        $pages->add('Forms', '/panel/formlar/durumlar', 'pages/panel/statuses.php');
        $pages->add('Forms', '/panel/formlar/talepler', 'pages/panel/submissions.php');
        $pages->add('Forms', '/kullanici/talepler', 'pages/user/submissions.php');

        // Check if module version changed

        try {
            $forms = $this->_db->query('SELECT id, link_location, url, icon, title, guest FROM rw_forms')->results();
            if (count($forms)) {
                if ($user->isLoggedIn()) {
                    $group_ids = implode(',', $user->getAllGroupIds());
                } else {
                    $group_ids = implode(',', array(0));
                }

                foreach ($forms as $form) {
                    // Register form page
                    $pages->add('Forms', $form->url, 'pages/form.php', 'form-' . $form->id, true);

                    $perm = false;
                    if (!$user->isLoggedIn() && $form->guest == 1) {
                        $perm = true;
                    }

                    if (!$perm) {
                        $hasperm = $this->_db->query('SELECT form_id FROM rw_forms_permissions WHERE form_id = ? AND post = 1 AND group_id IN('.$group_ids.')', array($form->id));
                        if ($hasperm->count()) {
                            $perm = true;
                        }
                    }                 

                    // Add link location to navigation if user have permission
                    if ($perm) {
                        switch ($form->link_location) {
                            case 1:
                                // Navbar
                                // Check cache first
                                $cache->setCache('navbar_order');
                                if (!$cache->isCached('form-' . $form->id . '_order')) {
                                    // Create cache entry now
                                    $form_order = 5;
                                    $cache->store('form-' . $form->id . '_order', 5);
                                } else {
                                    $form_order = $cache->retrieve('form-' . $form->id . '_order');
                                }
                                $navigation->add('form-' . $form->id, Output::getClean($form->title), URL::build(Output::getClean($form->url)), 'top', null, $form_order, $form->icon);
                            break;
                            case 2:
                                // "More" dropdown
                                $navigation->addItemToDropdown('more_dropdown', 'form-' . $form->id, Output::getClean($form->title), URL::build(Output::getClean($form->url)), 'top', null, $form->icon);
                            break;
                            case 3:
                                // Footer
                                $navigation->add('form-' . $form->id, Output::getClean($form->title), URL::build(Output::getClean($form->url)), 'footer', null, 2000, $form->icon);
                            break;
                        }
                    }
                    
                }
            }
        } catch (Exception $e) {
            // Database tables don't exist yet
        }
        
        // Hooks
        EventHandler::registerEvent('newFormSubmission', $forms_language->get('forms', 'new_form_submission'));
        EventHandler::registerEvent('updatedFormSubmission', $forms_language->get('forms', 'updated_form_submission'));
        EventHandler::registerEvent('updatedFormSubmissionStaff', $forms_language->get('forms', 'updated_form_submission_staff'));

        require_once ROOT_PATH . '/modules/Forms/hooks/CloneGroupFormsHook.php';
        EventHandler::registerListener('cloneGroup', 'CloneGroupFormsHook::execute');

        require_once ROOT_PATH . '/modules/Forms/hooks/DeleteUserFormsHook.php';
        EventHandler::registerListener('deleteUser', 'DeleteUserFormsHook::execute');

        $endpoints->loadEndpoints(ROOT_PATH . '/modules/Forms/includes/endpoints');

        Endpoints::registerTransformer('form', 'Forms', static function (Radome2API $api, string $value) {
            if (is_numeric($value)) {
                // Get form by id
                $form = new Form($value);
            } else {
                // Get form by url
                $form = new Form('/' . $value, 'url');
            }

            if ($form->exists()) {
                return $form;
            }

            $api->throwError(FormsApiErrors::ERROR_FORM_NOT_FOUND);
        });

        Endpoints::registerTransformer('submission', 'Forms', static function (Radome2API $api, string $value) {
            $submission = new Submission($value);
            if ($submission->exists()) {
                return $submission;
            }

            $api->throwError(FormsApiErrors::ERROR_SUBMISSION_NOT_FOUND);
        });
    }

    public function onInstall() {
        // Initialise
        $this->initialise();
    }

    public function onUninstall() {
        
    }

    public function onEnable() {
        // Check if we need to initialise again
        $this->initialise();
    }

    public function onDisable() {
        // No actions necessary
    }

    public function onPageLoad($user, $pages, $cache, $smarty, $navs, $widgets, $template) {
        // Permissions
        PermissionHandler::registerPermissions('Forms', array(
            'forms.view-submissions' => $this->_forms_language->get('forms', 'forms_view_submissions'),
            'forms.manage' => $this->_forms_language->get('forms', 'forms_manage'),
            'forms.anonymous' => $this->_language->get('moderator', 'staff_cp')  . ' &raquo; ' .  $this->_forms_language->get('forms', 'forms')  . ' &raquo; ' . $this->_forms_language->get('forms', 'submit_as_anonymous')
        ));

        $navs[1]->add('cc_submissions', $this->_forms_language->get('forms', 'submissions'), URL::build('/kullanici/talepler'));

        if (defined('BACK_END')) {
            if ($user->hasPermission('forms.manage') || $user->hasPermission('forms.view-submissions')) {
                $cache->setCache('panel_sidebar');
                if (!$cache->isCached('forms_order')) {
                    $order = 14;
                    $cache->store('forms_order', 14);
                } else {
                    $order = $cache->retrieve('forms_order');
                }
                $navs[2]->add('forms_divider', mb_strtoupper($this->_forms_language->get('forms', 'forms'), 'UTF-8'), 'divider', 'top', null, $order, '');

                if ($user->hasPermission('forms.manage')) {
                    if (!$cache->isCached('forms_icon')) {
                        $icon = '<i class="nav-icon fas fa-cogs"></i>';
                        $cache->store('forms_icon', $icon);
                    } else {
                        $icon = $cache->retrieve('forms_icon');
                    }
                    $navs[2]->add('forms', $this->_forms_language->get('forms', 'forms'), URL::build('/panel/formlar'), 'top', null, $order + 0.1, $icon);
                }

                if ($user->hasPermission('forms.view-submissions')) {
                    if (!$cache->isCached('forms_submissions_icon')) {
                        $icon = '<i class="nav-icon fas fa-user-circle"></i>';
                        $cache->store('forms_submissions_icon', $icon);
                    } else {
                        $icon = $cache->retrieve('forms_submissions_icon');
                    }
                    $navs[2]->add('submissions', $this->_forms_language->get('forms', 'submissions'), URL::build('/panel/formlar/talepler'), 'top', null, $order + 0.2, $icon);
                }
            }
        }

        // Check for module updates
        if (isset($_GET['route']) && $user->isLoggedIn() && $user->hasPermission('admincp.update')) {
            // Page belong to this module?
            $page = $pages->getActivePage();
            if ($page['module'] == 'Forms') {

                $cache->setCache('forms_module_cache');
                if ($cache->isCached('update_check')) {
                    $update_check = $cache->retrieve('update_check');
                } else {
                    require_once(ROOT_PATH . '/modules/Forms/classes/Forms.php');
                    $update_check = Forms::updateCheck();
                    $cache->store('update_check', $update_check, 3600);
                }

                $update_check = json_decode($update_check);
                if (!isset($update_check->error) && !isset($update_check->no_update) && isset($update_check->new_version)) {  
                    $smarty->assign(array(
                        'NEW_UPDATE' => (isset($update_check->urgent) && $update_check->urgent == 'true') ? $this->_forms_language->get('forms', 'new_urgent_update_available_x', ['module' => $this->getName()]) : $this->_forms_language->get('forms', 'new_update_available_x', ['module' => $this->getName()]),
                        'NEW_UPDATE_URGENT' => (isset($update_check->urgent) && $update_check->urgent == 'true'),
                        'CURRENT_VERSION' => $this->_forms_language->get('forms', 'current_version_x', [
                            'version' => Output::getClean($this->getVersion())
                        ]),
                        'NEW_VERSION' => $this->_forms_language->get('forms', 'new_version_x', [
                            'new_version' => Output::getClean($update_check->new_version)
                        ]),
                        'RADOME_UPDATE' => $this->_forms_language->get('forms', 'view_resource'),
                        'RADOME_UPDATE_LINK' => Output::getClean($update_check->link)
                    ));
                }
            }
        }
    }

    public function getDebugInfo(): array {
        return [];
    }

    private function initialise() {
        // Generate tables
        if (!$this->_db->showTables('forms')) {
            try {
                $this->_db->createTable("forms", " `id` int(11) NOT NULL AUTO_INCREMENT, `url` varchar(32) NOT NULL, `title` varchar(32) NOT NULL, `guest` tinyint(1) NOT NULL DEFAULT '0', `link_location` tinyint(1) NOT NULL DEFAULT '1', `icon` varchar(64) NULL, `can_view` tinyint(1) NOT NULL DEFAULT '0', `captcha` tinyint(1) NOT NULL DEFAULT '0', `content` mediumtext NULL DEFAULT NULL, `comment_status` int(11) NOT NULL DEFAULT '0', `source` varchar(32) NOT NULL DEFAULT 'forms', `forum_id` int(11) NOT NULL DEFAULT '0', PRIMARY KEY (`id`)");

                $this->_db->insert('forms', array(
                    'url' => '/destek',
                    'title' => 'Destek',
                    'guest' => 1,
                    'link_location' => 1,
                    'icon' => '<i class="fas fa-ticket-alt"></i>'                    
                ));
            } catch (Exception $e) {
                // Error
            }
        }

        if (!$this->_db->showTables('forms_permissions')) {
            try {
                $this->_db->createTable("forms_permissions", " `id` int(11) NOT NULL AUTO_INCREMENT, `form_id` int(11) NOT NULL, `group_id` int(11) NOT NULL, `post` tinyint(1) NOT NULL DEFAULT '1', `view_own` tinyint(1) NOT NULL DEFAULT '1', `view` tinyint(1) NOT NULL DEFAULT '0', `can_delete` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`)");

                $groups = $this->_db->query('SELECT id, staff FROM rw_groups')->results();
                $this->_db->insert('forms_permissions', array(
                    'group_id' => 0,
                    'form_id' => 1,
                    'post' => 0,
                    'view_own' => 0,
                    'view' => 0,
                    'can_delete' => 0
                ));

                foreach ($groups as $group) {
                    $this->_db->insert('forms_permissions', array(
                        'group_id' => $group->id,
                        'form_id' => 1,
                        'post' => 1,
                        'view_own' => 1,
                        'view' => ($group->staff == 1 ? 1 : 0),
                        'can_delete' => ($group->staff == 1 ? 1 : 0)
                    ));
                }
            } catch (Exception $e) {
                // Error
            }
        }  

        if (!$this->_db->showTables('forms_comments')) {
            try {
                $this->_db->createTable("forms_comments", " `id` int(11) NOT NULL AUTO_INCREMENT, `form_id` int(11) NOT NULL, `user_id` int(11) NOT NULL, `created` int(11) NOT NULL, `anonymous` tinyint(1) NOT NULL DEFAULT '0', `content` mediumtext NOT NULL, PRIMARY KEY (`id`)");
            } catch (Exception $e) {
                // Error
            }
        }

        if (!$this->_db->showTables('forms_fields')) {
            try {
                $this->_db->createTable("forms_fields", " `id` int(11) NOT NULL AUTO_INCREMENT, `form_id` int(11) NOT NULL, `name` varchar(255) NOT NULL, `type` int(11) NOT NULL, `required` tinyint(1) NOT NULL DEFAULT '0', `min` int(11) NOT NULL DEFAULT '0', `max` int(11) NOT NULL DEFAULT '0', `placeholder` varchar(255) NULL DEFAULT NULL, `options` text NULL, `info` text NULL, `deleted` tinyint(1) NOT NULL DEFAULT '0', `order` int(11) NOT NULL DEFAULT '1', PRIMARY KEY (`id`)");
                
                $this->_db->insert('forms_fields', array(
                    'form_id' => 1,
                    'name' => 'Kategori',
                    'type' => 1,
                    'required' => 1,
                    'order' => 1,
                    'options' => 'Hile / Küfür
                    ,Yetkili Başvuru
                    ,Ödeme Öncesi
                    ,Ödeme Sonrası
                    ,Ceza İtiraf'
                ));
                $this->_db->insert('forms_fields', array(
                    'form_id' => 1,
                    'name' => 'Başlık',
                    'type' => 4,
                    'max' => '30',
                    'min' => '3',
                    'required' => 1,
                    'order' => 2
                ));
                $this->_db->insert('forms_fields', array(
                    'form_id' => 1,
                    'name' => 'Mesaj',
                    'type' => 4,
                    'max' => '30',
                    'min' => '3',
                    'required' => 1,
                    'order' => 3
                ));      
                $this->_db->insert('forms_fields', array(
                    'form_id' => 1,
                    'name' => 'Belge / Görsel',
                    'type' => 10,
                    'required' => 0,
                    'order' => 4
                ));                            
            } catch (Exception $e) {
                // Error
            }
        }

        if (!$this->_db->showTables('forms_replies')) {
            try {
                $this->_db->createTable("forms_replies", " `id` int(11) NOT NULL AUTO_INCREMENT, `form_id` int(11) NOT NULL, `user_id` int(11) NULL, `updated_by` int(11) NULL, `created` int(11) NOT NULL, `updated` int(11) NOT NULL, `content` mediumtext NULL DEFAULT NULL, `status_id` int(11) NOT NULL DEFAULT '1', PRIMARY KEY (`id`)");
            } catch (Exception $e) {
                // Error
            }
        }

        if (!$this->_db->showTables('forms_replies_fields')) {
            try {
                $this->_db->createTable("forms_replies_fields", " `id` int(11) NOT NULL AUTO_INCREMENT, `submission_id` int(11) NOT NULL, `field_id` int(11) NOT NULL, `value` TEXT NOT NULL, PRIMARY KEY (`id`)");
                
                $this->_db->createQuery('ALTER TABLE `rw_forms_replies_fields` ADD INDEX `rw_forms_replies_fields_idx_submission_id` (`submission_id`)');
            } catch (Exception $e) {
                // Error
            }
        }

        if (!$this->_db->showTables('forms_statuses')) {
            try {
                $this->_db->createTable("forms_statuses", " `id` int(11) NOT NULL AUTO_INCREMENT, `html` varchar(1024) NOT NULL, `open` tinyint(1) NOT NULL, `fids` varchar(128) NULL, `gids` varchar(128) NULL, `color` varchar(32) NULL DEFAULT NULL, `deleted` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`)");
                
                $this->_db->insert('forms_statuses', array(
                    'html' => '<span class="badge badge-success">Açık</span>',
                    'open' => 1,
                    'fids' => '1',
                    'gids' => '2,3'
                ));
                $this->_db->insert('forms_statuses', array(
                    'html' => '<span class="badge badge-danger">Kapandı</span>',
                    'open' => 0,
                    'fids' => '1',
                    'gids' => '2,3'
                ));
                $this->_db->insert('forms_statuses', array(
                    'html' => '<span class="badge badge-warning">İşleme Alındı</span>',
                    'open' => 1,
                    'fids' => '1',
                    'gids' => '2,3'
                ));
            } catch (Exception $e) {
                // Error
            }
        }

        try {
            // Update main admin group permissions
            $group = $this->_db->get('groups', array('id', '=', 2))->results();
            $group = $group[0];
            
            $group_permissions = json_decode($group->permissions, TRUE);
            $group_permissions['forms.manage'] = 1;
            $group_permissions['forms.view-submissions'] = 1;
            $group_permissions['forms.manage-submission'] = 1;
            $group_permissions['forms.anonymous'] = 1;
            
            $group_permissions = json_encode($group_permissions);
            $this->_db->update('groups', 2, array('permissions' => $group_permissions));
        } catch (Exception $e) {
            // Error
        }
    }
}
