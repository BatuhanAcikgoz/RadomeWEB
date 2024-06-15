<?php
/**
 * Most credits member list provider
 *
 * @package Modules\Magaza
 * @author Partydragen
 * @version 2.1.2
 * @license MIT
 */
class MostCreditsMemberListProvider extends MemberListProvider {

    public function __construct(Language $language) {
        $this->_name = 'most_credits';
        $this->_friendly_name = $language->get('general', 'most_credits');
        $this->_module = 'Magaza';
        $this->_icon = 'money bill alternate icon';
    }

    protected function generator(): array {
        return [
            'SELECT user_id, SUM(TRUNCATE(cents / 100, 2)) AS `count` FROM `rw_store_customers` INNER JOIN rw_users ON user_id=rw_users.id GROUP BY `user_id` ORDER BY count DESC',
            'user_id',
            'count'
        ];
    }
}