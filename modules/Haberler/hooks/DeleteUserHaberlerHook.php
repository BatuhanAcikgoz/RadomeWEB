<?php
/*
 *  Made by Samerton
 *  https://github.com/RadomeWEB/Radome/
 *  RadomeWEB version 2.0.0
 *
 *  Delete user event listener for Haberler module
 */

class DeleteUserHaberlerHook {

    public static function execute(array $params = []): void {
        if (isset($params['user_id']) && $params['user_id'] > 1) {
            $db = DB::getInstance();

            // Delete the user's posts
            $db->delete('posts', ['post_creator', $params['user_id']]);

            // Delete the user's habers
            $db->delete('habers', ['haber_creator', $params['user_id']]);

            // Haberler reactions
            $db->delete('haberlers_reactions', ['user_received', $params['user_id']]);
            $db->delete('haberlers_reactions', ['user_given', $params['user_id']]);

            // Topics following
            $db->delete('habers_following', ['user_id', $params['user_id']]);
        }
    }
}
