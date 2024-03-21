<?php

/**
 * No params
 *
 * @return string JSON Array of RadomeWEB information
 */
class InfoEndpoint extends KeyAuthEndpoint {

    public function __construct() {
        $this->_route = 'info';
        $this->_module = 'Core';
        $this->_description = 'Return info about the Radome installation';
        $this->_method = 'GET';
    }

    public function execute(Radome2API $api): void {

        $site_id = Settings::get('unique_id');

        if ($site_id === null) {
            $api->throwError(Radome2API::ERROR_NO_SITE_UID);
        }

        $ret = [];

        $ret['radome_version'] = Settings::get('radome_version');

        if (Settings::get('version_update') === 'urgent' || Settings::get('version_update') === 'true') {
            $ret['version_update'] = [
                'update' => true,
                'version' => Settings::get('new_version'),
                'urgent' => Settings::get('version_update') === 'urgent',
            ];
        }

        // Return default language
        $ret['locale'] = LANGUAGE;

        $modules_query = $api->getDb()->get('modules', ['enabled', true]);
        $ret_modules = [];
        if ($modules_query->count()) {
            $modules_query = $modules_query->results();
            foreach ($modules_query as $module) {
                $ret_modules[] = $module->name;
            }
        }
        $ret['modules'] = $ret_modules;

        $api->returnArray($ret);
    }
}
