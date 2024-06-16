<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateMagazaPaymentsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('rw_store_payments');

        $table
            ->addColumn('order_id', 'integer', ['length' => 11])
            ->addColumn('gateway_id', 'integer', ['length' => 11])         
            ->addColumn('payment_id', 'string', ['length' => 64, 'null' => true, 'default' => NULL])
            ->addColumn('subscription_id', 'integer', ['length' => 11, 'null' => true, 'default' => NULL])
            ->addColumn('transaction', 'string', ['length' => 32, 'null' => true, 'default' => NULL])
            ->addColumn('amount_cents', 'integer', ['length' => 11, 'null' => true, 'default' => NULL])
            ->addColumn('currency', 'string', ['length' => 11, 'null' => true, 'default' => NULL])
            ->addColumn('fee_cents', 'integer', ['length' => 11, 'null' => true, 'default' => NULL])
            ->addColumn('status_id', 'integer', ['length' => 11, 'default' => 0])
            ->addColumn('created', 'integer', ['length' => 11])
            ->addColumn('last_updated', 'integer', ['length' => 11]);

        $table->create();
    }
}