<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateStoreOrdersTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_store_orders');

        $table
            ->addColumn('user_id', 'integer', ['length' => 11])
            ->addColumn('from_customer_id', 'integer', ['length' => 11])
            ->addColumn('to_customer_id', 'integer', ['length' => 11])
            ->addColumn('created', 'integer', ['length' => 11])
            ->addColumn('ip', 'string', ['length' => 128]);

        $table->create();
    }
}