<?php
/*
 *  Made by Partydragen
 *  https://github.com/partydragen/Radome-Formlar
 *  https://partydragen.com/
 *  RadomeWEB version 2.0.0-pr13
 *
 *  License: MIT
 */

class Formlar {
    private DB $_db;

    /**
     * @var Language Instance of Language class for translations
     */
    private static Language $_forms_language;

    // Constructor, connect to database
    public function __construct() {
        $this->_db = DB::getInstance();
    }
    
    // Can the user post a submission in the given form?
    public function canPostSubmission($group_ids, $form_id): bool {
        if (is_array($group_ids)) {
            $group_ids = implode(',', $group_ids);
        }
        
        return $this->_db->query('SELECT `post` FROM rw_forms_permissions WHERE form_id = ? AND `post` = 1 AND group_id IN (' . $group_ids . ')', array($form_id))->count() ? true : false;
    }
    
    // Can the user view a submission in the given form?
    public function canViewOwnSubmission($group_ids, $form_id): bool {
        if (is_array($group_ids)) {
            $group_ids = implode(',', $group_ids);
        }
        
        return $this->_db->query('SELECT `view_own` FROM rw_forms_permissions WHERE form_id = ? AND `view_own` = 1 AND group_id IN (' . $group_ids . ')', array($form_id))->count() ? true : false;
    }
    
    // Can the user view a submission in the given form?
    public function canViewSubmission($group_ids, $form_id): bool {
        if (is_array($group_ids)) {
            $group_ids = implode(',', $group_ids);
        }
        
        return $this->_db->query('SELECT `view` FROM rw_forms_permissions WHERE form_id = ? AND `view` = 1 AND group_id IN (' . $group_ids . ')', array($form_id))->count() ? true : false;
    }
    
    // Can the user view a submission in the given form?
    public function canDeleteSubmission($group_ids, $form_id): bool {
        if (is_array($group_ids)) {
            $group_ids = implode(',', $group_ids);
        }
        
        return $this->_db->query('SELECT `can_delete` FROM rw_forms_permissions WHERE form_id = ? AND `can_delete` = 1 AND group_id IN (' . $group_ids . ')', array($form_id))->count() ? true : false;
    }

    /**
     * @return Language The current language instance for translations
     */
    public static function getLanguage(): Language {
        if (!isset(self::$_forms_language)) {
            self::$_forms_language = new Language(ROOT_PATH . '/modules/Formlar/language');
        }

        return self::$_forms_language;
    }
}