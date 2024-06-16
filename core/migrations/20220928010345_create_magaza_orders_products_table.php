<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateMagazaOrdersProductsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('rw_store_orders_products');

        $table
            ->addColumn('order_id', 'integer', ['length' => 11])
            ->addColumn('product_id', 'integer', ['length' => 11])
            ->addColumn('quantity', 'integer', ['length' => 11, 'default' => 1])
            ->addColumn('amount_cents', 'integer', ['length' => 11])
            ->addColumn('expire', 'integer', ['length' => 11,'null' => true, 'default' => NULL])
            ->addColumn('task_id', 'integer', ['length' => 11,'null' => true, 'default' => NULL]);

        $table->create();
    }
}