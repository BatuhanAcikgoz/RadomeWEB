<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateMagazaSubscriptionsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('rw_store_subscriptions');

        $table
            ->addColumn('order_id', 'integer', ['length' => 11, 'null' => false])
            ->addColumn('gateway_id', 'integer', ['length' => 11, 'null' => false])
            ->addColumn('customer_id', 'integer', ['length' => 11, 'null' => false])
            ->addColumn('agreement_id', 'string', ['length' => 64, 'null' => false])
            ->addColumn('status_id', 'integer', ['length' => 11, 'null' => false, 'default' => 0])
            ->addColumn('amount_cents', 'integer', ['length' => 11, 'null' => false])
            ->addColumn('currency', 'string', ['length' => 16, 'null' => false])
            ->addColumn('frequency', 'string', ['length' => 16, 'null' => false])
            ->addColumn('frequency_interval', 'integer', ['length' => 11, 'null' => false])
            ->addColumn('email', 'string', ['length' => 128, 'null' => true, 'default' => NULL])
            ->addColumn('verified', 'integer', ['length' => 1, 'null' => false, 'default' => 0])
            ->addColumn('payer_id', 'string', ['length' => 64, 'null' => true, 'default' => NULL])
            ->addColumn('last_payment_date', 'integer', ['length' => 11, 'null' => false])
            ->addColumn('next_billing_date', 'integer', ['length' => 11, 'null' => false])
            ->addColumn('failed_attempts', 'integer', ['length' => 11, 'null' => false, 'default' => 0])
            ->addColumn('created', 'integer', ['length' => 11, 'null' => false])
            ->addColumn('updated', 'integer', ['length' => 11, 'null' => false])
            ->addColumn('expired', 'integer', ['length' => 1, 'null' => false, 'default' => 0]);

        $table->create();
    }
}