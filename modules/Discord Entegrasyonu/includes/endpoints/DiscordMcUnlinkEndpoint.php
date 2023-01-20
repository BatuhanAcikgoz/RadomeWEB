<?php

/**
 * @param string $url New Discord bot URL
 * @param string $id New Discord Guild/server ID
 *
 * @return string JSON Array
 */
class DiscordMcUnlinkEndpoint extends KeyAuthEndpoint {

    public function __construct() {
        $this->_route = 'discord/mc-srv-unlink-account';
        $this->_module = 'Discord Entegrasyonu';
        $this->_description = 'Unlink with users discord account and minecraft account';
        $this->_method = 'POST';
    }

    public function execute(Radome2API $api): void {
        if (isset($_POST['player_name'])) {
            $user = DB::getInstance()->get('users', ['username', '=', $_POST['player_name']])->results();
            $user_id = $user[0]->id;
            
            DB::getInstance()->query('DELETE * FROM rw_users_integrations WHERE integration_id = 2 and user_id = ?', [$user_id]);

            $api->returnArray(['message' => Discord::getLanguageTerm('discord_settings_updated')]);
            }
        else {
            $api->returnArray(['message' => Discord::getLanguageTerm('discord_unknown_error')]);
        }
    }
}
