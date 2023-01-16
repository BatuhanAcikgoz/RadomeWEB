<?php

/**
 * @param string $url New Discord bot URL
 * @param string $id New Discord Guild/server ID
 *
 * @return string JSON Array
 */
class UpdateDiscordBotSettingsEndpoint extends KeyAuthEndpoint {

    public function __construct() {
        $this->_route = 'discord/update-bot-settings';
        $this->_module = 'Discord Entegrasyonu';
        $this->_description = 'Updates the Discord Bot URL and/or Guild ID setting';
        $this->_method = 'POST';
    }

    public function execute(Radome2API $api): void {
        if (isset($_POST['guild_id'])) {
            Util::setSetting('discord_integration', 1);
            Util::setSetting('discord', $_POST['guild_id']);
            Util::setSetting('discord_bot_username', $_POST['guild_id']);
            Util::setSetting('discord_bot_url', $_POST['guild_id']);
            $api->returnArray(['message' => Discord::getLanguageTerm('discord_settings_updated')]);
            }
    }
}
