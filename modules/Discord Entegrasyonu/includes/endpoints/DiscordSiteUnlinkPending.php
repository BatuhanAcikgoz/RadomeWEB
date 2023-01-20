<?php
class DiscordSiteUnlinkPending extends KeyAuthEndpoint {

    public function __construct() {
        $this->_route = 'discord/discord-site-unlink-pending';
        $this->_module = 'Discord Entegrasyonu';
        $this->_description = 'Unlink with users discord account and minecraft account';
        $this->_method = 'GET';
    }

    public function execute(Radome2API $api): void {
        $query = 'SELECT * FROM rw_unlink_pending';
        $where = ' WHERE status = 0';
        $order = ' ORDER BY `id` ASC';

        $commands_query = $api->getDb()->query($query . $where . $order)->results();
        $commands = [];
        foreach ($commands_query as $commands_2) {
                $commands[] = [
                    'command' => $commands_2->command,
                ];
        }
        $return['command'] = $commands;
        $api->returnArray($return);
    }
}
