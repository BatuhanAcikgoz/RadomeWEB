<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateStoreOrdersProductsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_store_orders_products');

        $table
            ->addColumn('order_id', 'integer', ['length' => 11])
            ->addColumn('product_id', 'integer', ['length' => 11])
            ->addColumn('quantity', 'integer', ['length' => 11, 'default' => 1]);

        $table->create();
    }
}