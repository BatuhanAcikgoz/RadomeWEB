<?php
class DiscordSiteUnlinkPending extends KeyAuthEndpoint {

    public function __construct() {
        $this->_route = 'discord/discord-site-unlink-pending';
        $this->_module = 'Discord Entegrasyonu';
        $this->_description = 'Unlink with users discord account and minecraft account';
        $this->_method = 'GET';
    }

    public function execute(Radome2API $api): void {
        $commands_query = DB::getInstance()->query('SELECT * FROM rw_unlink_pending WHERE status = 0')->results();


        $api->returnArray(['commands' => 'deneme']);
    }
    
    /**
    * @param $uuid string UUID to format
    * @return string Properly formatted UUID (According to UUID v4 Standards xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx WHERE y = 8,9,A,or B and x = random digits.)
    */
}
