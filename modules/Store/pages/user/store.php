<?php
/*
 *  Made by Partydragen
 *  https://partydragen.com/resources/resource/5-store-module/
 *  https://partydragen.com/
 *
 *  License: MIT
 *
 *  UserCP store page
 */

// Must be logged in
if(!$user->isLoggedIn()){
    Redirect::to(URL::build('/'));
}

// Always define page name for navbar
const PAGE = 'cc_store';
$page_title = $language->get('user', 'user_cp');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

$timeago = new TimeAgo(TIMEZONE);
$customer = new Customer($user);

$configuration = new Configuration('store');
$currency = Output::getClean($configuration->get('currency'));
$currency_symbol = Output::getClean($configuration->get('currency_symbol'));

$transactions_list = [];
$transactions = DB::getInstance()->query('SELECT rw_store_payments.* FROM rw_store_payments INNER JOIN rw_store_orders ON order_id=rw_store_orders.id WHERE from_customer_id = ? ORDER BY rw_store_payments.created DESC', [$customer->data()->id]);
if ($transactions->count()) {
    foreach ($transactions->results() as $transaction) {
        $transactions_list[] = [
            'gateway' => Output::getClean($transaction->gateway_id),
            'transaction' => Output::getClean($transaction->transaction),
            'amount' => Output::getClean($transaction->amount),
            'currency' => Output::getClean($transaction->currency),
            'currency_symbol' => $currency_symbol,
            'fee' => Output::getClean($transaction->fee),
            'date_full' => date(DATE_FORMAT, $transaction->created),
            'date_friendly' => $timeago->inWords($transaction->created, $language)
        ];
    }
}
$purchase_list = [];
$purchases = DB::getInstance()->query('SELECT rw_store_payments.*, rw_store_products.name FROM rw_store_payments INNER JOIN rw_store_orders ON order_id=rw_store_orders.id LEFT JOIN rw_store_orders_products on rw_store_orders.id=rw_store_orders_products.order_id LEFT JOIN rw_store_products on rw_store_orders_products.product_id=rw_store_products.id WHERE from_customer_id = ?  ORDER BY rw_store_payments.created DESC', [$customer->data()->id]);if ($transactions->count()) {
    foreach ($purchases->results() as $purchase) {
        $purchase_list[] = [
            'gateway' => Output::getClean($purchase->gateway_id),
            'transaction' => Output::getClean($purchase->transaction),
            'amount' => Output::getClean($purchase->amount),
            'name' => Output::getClean($purchase->name),
            'currency' => Output::getClean($purchase->currency),
            'currency_symbol' => $currency_symbol,
            'fee' => Output::getClean($purchase->fee),
            'date_full' => date(DATE_FORMAT, $purchase->created),
            'date_friendly' => $timeago->inWords($purchase->created, $language)
        ];
    }
}

$smarty->assign([
    'STORE' => $store_language->get('general', 'store'),
    'CREDITS' => $store_language->get('general', 'credits'),
    'CREDITS_VALUE' => $customer->getCredits(),
    'MY_TRANSACTIONS' => $store_language->get('general', 'my_transactions'),
    'NO_TRANSACTIONS' => $store_language->get('general', 'no_transactions'),
    'TRANSACTION' => $store_language->get('admin', 'transaction'),
    'AMOUNT' => $store_language->get('admin', 'amount'),
    'DATE' => $store_language->get('admin', 'date'),
    'TRANSACTIONS_LIST' => $transactions_list,
    'PURCHASES_LIST' => $purchase_list,
    'CURRENCY' => $currency,
    'CURRENCY_SYMBOL' => $currency_symbol
]);

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

require(ROOT_PATH . '/core/templates/cc_navbar.php');

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
$template->displayTemplate('store/user/store.tpl', $smarty);