<?php

header('Content-type: application/json;charset=utf-8');

if (!$user->isLoggedIn()) {
    die();
}

$users = DB::getInstance()->query(
    'SELECT u.id, u.username, u.gravatar, u.email, u.has_avatar, u.avatar_updated, IFNULL(rw_users_integrations.identifier, \'none\') as uuid FROM rw_users u LEFT JOIN rw_users_integrations ON user_id=u.id AND integration_id=1 WHERE u.username LIKE ?',
)->results();

$users_json = [];
foreach ($users as $user) {
    $users_json[] = [
        'avatar_url' => AvatarSource::getAvatarFromUserData($user, false, 20, true)
    ];
}

die(json_encode($users_json));
