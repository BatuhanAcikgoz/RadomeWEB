<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateMagazaOrdersTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('rw_store_orders');

        $table
            ->addColumn('user_id', 'integer', ['length' => 11, 'null' => true, 'default' => NULL])
            ->addColumn('from_customer_id', 'integer', ['length' => 11])
            ->addColumn('to_customer_id', 'integer', ['length' => 11])
            ->addColumn('created', 'integer', ['length' => 11])
            ->addColumn('ip', 'string', ['length' => 128, 'null' => true, 'default' => NULL])
            ->addColumn('coupon_id', 'integer', ['length' => 11, 'null' => true, 'default' => NULL]);

        $table->create();
    }
}