<?php
/*
 *  Made by Partydragen
 *  https://github.com/RadomeWEB/Radome/
 *  RadomeWEB version 2.0.0
 *
 *  Clone group event listener handler class
 */

class CloneGroupHaberlerHook {

    public static function execute(array $params = []): void {

        // Clone group permissions for haberlers
        $new_group_id = $params['group_id'];
        $permissions = DB::getInstance()->query('SELECT * FROM rw_haberlers_permissions WHERE group_id = ?', [$params['cloned_group_id']]);
        if ($permissions->count()) {
            $permissions = $permissions->results();

            $inserts = [];
            foreach ($permissions as $permission) {
                $inserts[] = '('.$new_group_id.',' . $permission->haberler_id . ',' . $permission->view . ',' . $permission->create_haber . ',' . $permission->edit_haber . ',' . $permission->create_post . ',' . $permission->view_other_habers . ',' . $permission->moderate . ')';
            }

            $query = 'INSERT INTO rw_haberlers_permissions (group_id, haberler_id, view, create_haber, edit_haber, create_post, view_other_habers, moderate) VALUES ';
            $query .= implode(',', $inserts);

            DB::getInstance()->query($query);
        }
    }
}
