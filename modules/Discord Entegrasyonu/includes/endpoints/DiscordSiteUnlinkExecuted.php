<?php
class DiscordSiteUnlinkExecuted extends KeyAuthEndpoint {

    public function __construct() {
        $this->_route = 'discord/discord-site-unlink-executed';
        $this->_module = 'Discord Entegrasyonu';
        $this->_description = 'Mark commands as complete';
        $this->_method = 'POST';
    }

    public function execute(Radome2API $api): void {
        $commands = $_POST['commands'];
        if (!is_array($commands) || !count($commands)) {
            $api->throwError(Radome2API::ERROR_INVALID_POST_CONTENTS, 'Herhangi bir komut bulunamadı!');
        }
        
        $ids = [];
        foreach ($commands as $id) {
            $commands[] = [
                'id' => $id->id,
            ];
        }

        // Ensure the user exists
        $api->getDb()->query('UPDATE `rw_unlink_pending` SET `status`=1 WHERE id IN ' . $ids);
        
        $api->returnArray(['success' => true]);
    }
}