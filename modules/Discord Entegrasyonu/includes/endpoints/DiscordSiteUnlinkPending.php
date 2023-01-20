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
        $params = [];

        $commands_query = $api->getDb()->query($query . $where . $order, $params)->results();
        $commands = [];
        foreach ($commands_query as $commands) {
                $commands[] = [
                    'command' => $commands->command,
                ];
        }

        $api->returnArray(['commands' => $commands]);
    }
    
    /**
    * @param $uuid string UUID to format
    * @return string Properly formatted UUID (According to UUID v4 Standards xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx WHERE y = 8,9,A,or B and x = random digits.)
    */
}
