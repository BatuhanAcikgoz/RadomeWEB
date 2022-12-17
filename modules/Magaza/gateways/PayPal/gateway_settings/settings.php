<?php
/*
 *  Made by Partydragen
 *  https://partydragen.com/resources/resource/5-store-module/
 *  https://partydragen.com/
 *
 *  License: MIT
 *
 *  Magaza module
 */
require_once(ROOT_PATH . '/modules/Magaza/classes/MagazaConfig.php');
require_once(ROOT_PATH . '/modules/Magaza/config.php');

if (isset($store_conf) && is_array($store_conf)) {
    $GLOBALS['store_config'] = $store_conf;
}

if (Input::exists()) {
    if (Token::check()) {

        $settings = ['paypal/email' => $_POST['paypal_email']];
        MagazaConfig::set($settings);
        
        // Is this gateway enabled
        if (isset($_POST['enable']) && $_POST['enable'] == 'on') $enabled = 1;
        else $enabled = 0;
        
        DB::getInstance()->update('store_gateways', $gateway->getId(), [
            'enabled' => $enabled
        ]);

        Session::flash('gateways_success', $language->get('admin', 'successfully_updated'));
    } else
        $errors = [$language->get('general', 'invalid_token')];
}

$smarty->assign([
    'SETTINGS_TEMPLATE' => ROOT_PATH . '/modules/Magaza/gateways/PayPal/gateway_settings/settings.tpl',
    'ENABLE_VALUE' => ((isset($enabled)) ? $enabled : $gateway->isEnabled()),
    'PAYPAL_EMAIL_VALUE' => ((isset($_POST['paypal_email']) && $_POST['paypal_email']) ? Output::getClean(Input::get('paypal_email')) : MagazaConfig::get('paypal/email'))
]);