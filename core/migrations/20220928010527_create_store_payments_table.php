<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateStorePaymentsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_store_payments');

        $table
            ->addColumn('order_id', 'integer', ['length' => 11])
            ->addColumn('gateway_id', 'integer', ['length' => 11])         
            ->addColumn('payment_id', 'string', ['length' => 64, 'default' => NULL])
            ->addColumn('agreement_id', 'string', ['length' => 64, 'default' => NULL])
            ->addColumn('transaction', 'string', ['length' => 32, 'default' => NULL])
            ->addColumn('amount', 'string', ['length' => 11, 'default' => NULL])
            ->addColumn('currency', 'string', ['length' => 11, 'default' => NULL])
            ->addColumn('fee', 'string', ['length' => 11, 'default' => NULL])
            ->addColumn('status_id', 'integer', ['length' => 11, 'default' => 0])
            ->addColumn('created', 'integer', ['length' => 11])
            ->addColumn('last_updated', 'integer', ['length' => 11]);

        $table->create();
    }
}