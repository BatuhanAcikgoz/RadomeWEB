<?php

/**
 * @param string $url New Discord bot URL
 * @param string $id New Discord Guild/server ID
 *
 * @return string JSON Array
 */
class DiscordLinkEndpoint extends KeyAuthEndpoint {

    public function __construct() {
        $this->_route = 'discord/dc-link-account';
        $this->_module = 'Discord Entegrasyonu';
        $this->_description = 'Link with users discord account and minecraft account';
        $this->_method = 'POST';
    }

    public function execute(Radome2API $api): void {
        if (isset($_POST['player_name']) && isset($_POST['discord_identifier']) && isset($_POST['discord_username'])) {
            $user = DB::getInstance()->get('users', ['username', '=', $_POST['player_name']])->results();
            $user_id = $user[0]->id;
            
            DB::getInstance()->insert('users_integrations', [
                'user_id' => $user_id,
                'integration_id' => 2,
                'identifier' => $_POST['discord_identifier'],
                'username' => $_POST['discord_username'],
                'verified' => 1,
                'date' => date('U')
            ]);

            $api->returnArray(['message' => Discord::getLanguageTerm('discord_settings_updated')]);
            }
            else {
                $api->returnArray(['message' => Discord::getLanguageTerm('discord_unknown_error')]);
            }
    }
}
