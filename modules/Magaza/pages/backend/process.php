<?php
/*
 *  Made by Partydragen
 *  https://partydragen.com/resources/resource/5-store-module/
 *  https://partydragen.com/
 *
 *  License: MIT
 *
 *  Magaza module - Gateway Processer
 */

if (!isset($_GET['gateway'])) {
    die('Invalid');
}

require_once(ROOT_PATH . '/modules/Magaza/core/frontend_init.php');
$gateways = Gateways::getInstance();

// Handle return from gateway
$gateway = $gateways->get($_GET['gateway']);
if ($gateway) {
    if ($gateway->handleReturn()) {
        // Success 
        $shopping_cart->clear();
        Redirect::to(URL::build($store->getMagazaURL() . '/checkout/', 'do=complete'));
    } else {
        // Canceled or failed
        Redirect::to(URL::build($store->getMagazaURL() . '/checkout/', 'do=cancel'));
    }
} else {
    die('Invalid gateway');
}