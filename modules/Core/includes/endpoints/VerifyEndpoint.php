<?php

/**
 * @param int $id The RadomeWEB user's ID
 * @param string $code The RadomeWEB user's reset code, used to verify they own the account
 *
 * @return string JSON Array
 */
class VerifyEndpoint extends KeyAuthEndpoint {

    public function __construct() {
        $this->_route = 'users/{user}/verify';
        $this->_module = 'Core';
        $this->_description = 'Validate/Activate a RadomeWEB account by confirming their reset code';
        $this->_method = 'POST';
    }

    public function execute(Radome2API $api, User $user): void {
        $api->validateParams($_POST, ['code']);

        if ($user->data()->active || $user->data()->reset_code == '') {
            $api->throwError(CoreApiErrors::ERROR_USER_ALREADY_ACTIVE);
        }

        if ($user->data()->reset_code != $_POST['code']) {
            $api->throwError(CoreApiErrors::ERROR_INVALID_CODE);
        }

        $user->update([
            'active' => true,
            'reset_code' => ''
        ]);
        $default_language = new Language('core', DEFAULT_LANGUAGE);
        EventHandler::executeEvent(new UserValidatedEvent(
            $user,
        ));

        $api->returnArray(['message' => $api->getLanguage()->get('api', 'account_validated')]);
    }
}
