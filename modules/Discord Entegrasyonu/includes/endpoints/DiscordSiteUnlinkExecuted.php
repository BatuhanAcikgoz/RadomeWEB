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
            $api->throwError('store:no_commands_provided');
        }
        
        $ids = '(';
        foreach ($commands as $id) {
            if (is_numeric($id)) {
                $ids .= ((int) $id) . ',';
            }
        }
        $ids = rtrim($ids, ',') . ')';

        // Ensure the user exists
        $user = $api->getDb()->query('UPDATE `rw_store_pending_actions` SET `status`=1 WHERE id IN ' . $ids);
        
        $api->returnArray(['success' => true]);
    }
}