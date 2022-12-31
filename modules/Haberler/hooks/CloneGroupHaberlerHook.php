<?php
/*
 *  Made by Partydragen
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0
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
                $inserts[] = '('.$new_group_id.',' . $permission->haberler_id . ',' . $permission->view . ',' . $permission->create_topic . ',' . $permission->edit_topic . ',' . $permission->create_post . ',' . $permission->view_other_topics . ',' . $permission->moderate . ')';
            }

            $query = 'INSERT INTO rw_haberlers_permissions (group_id, haberler_id, view, create_topic, edit_topic, create_post, view_other_topics, moderate) VALUES ';
            $query .= implode(',', $inserts);

            DB::getInstance()->query($query);
        }
    }
}
