<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateStoreProductsConnectionsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('rw_store_products_connections');

        $table
            ->addColumn('product_id', 'integer', ['length' => 11])
            ->addColumn('action_id', 'integer', ['length' => 11, 'default' => NULL])
            ->addColumn('connection_id', 'integer', ['length' => 11]);

        $table->create();
    }
}