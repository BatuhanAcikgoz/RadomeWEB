<?php

/**
 * Most purchases member list provider
 *
 * @package Modules\Magaza
 * @author Partydragen
 * @version 2.1.2
 * @license MIT
 */
class MostPurchasesMemberListProvider extends MemberListProvider {

    public function __construct(Language $language) {
        $this->_name = 'most_purchases';
        $this->_friendly_name = $language->get('general', 'most_purchases');
        $this->_module = 'Magaza';
        $this->_icon = 'shopping cart icon';
    }

    protected function generator(): array {
        return [
            'SELECT rw_store_customers.user_id, COUNT(*) AS `count` FROM rw_store_payments LEFT JOIN rw_store_orders ON order_id=rw_store_orders.id LEFT JOIN rw_store_customers ON to_customer_id=rw_store_customers.id INNER JOIN rw_users ON rw_store_customers.user_id=rw_users.id WHERE status_id = 1 GROUP BY user_id ORDER BY `count` DESC',
            'user_id',
            'count'
        ];
    }
}