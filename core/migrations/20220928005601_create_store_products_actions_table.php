<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateStoreProductsActionsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('rw_store_products_actions');

        $table
            ->addColumn('product_id', 'integer', ['length' => 11])
            ->addColumn('field_id', 'integer', ['length' => 11])
            ->addColumn('type', 'integer', ['length' => 11, 'default' => 1])
            ->addColumn('service_id', 'integer', ['length' => 11])
            ->addColumn('command', 'string', ['length' => 2048])
            ->addColumn('require_online', 'smallinteger', ['length' => 1, 'null' => false, 'default' => 1])
            ->addColumn('own_connections', 'smallinteger', ['length' => 1, 'null' => false, 'default' => 0])
            ->addColumn('order', 'integer', ['length' => 11]);

        $table->create();
    }
}