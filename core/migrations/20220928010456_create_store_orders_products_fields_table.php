<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateStoreOrdersProductsFieldsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('rw_store_orders_products_fields');

        $table
            ->addColumn('order_id', 'integer', ['length' => 11])
            ->addColumn('product_id', 'integer', ['length' => 11])
            ->addColumn('field_id', 'integer', ['length' => 11])
            ->addColumn('value', 'text');

        $table->create();
    }
}