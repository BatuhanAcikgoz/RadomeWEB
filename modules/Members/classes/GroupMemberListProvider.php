<?php

class GroupMemberListProvider extends MemberListProvider {

    private int $_group_id;

    public function __construct(int $group_id) {
        $this->_group_id = $group_id;
    }

    protected function generator(): array {
        return [
            "SELECT rw_users.id AS id, rw_users.username FROM rw_users LEFT JOIN rw_users_groups ON rw_users.id = rw_users_groups.user_id WHERE rw_users_groups.group_id = {$this->_group_id} ORDER BY rw_users.username",
            'id',
        ];
    }
}
