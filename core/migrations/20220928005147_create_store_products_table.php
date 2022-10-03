<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateStoreProductsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('rw_store_products');

        $table
            ->addColumn('category_id', 'integer', ['length' => 11])
            ->addColumn('name', 'string', ['length' => 128])
            ->addColumn('price', 'string', ['length' => 8])
            ->addColumn('description', 'text', ['null' => true])
            ->addColumn('image', 'string', ['length' => 128, 'default' => NULL])
            ->addColumn('global_limit', 'string', ['length' => 128,  'default' => NULL])
            ->addColumn('user_limit', 'string', ['length' => 128, 'default' => NULL])
            ->addColumn('required_products', 'string', ['length' => 128, 'default' => NULL])
            ->addColumn('required_groups', 'string', ['length' => 128, 'default' => NULL])
            ->addColumn('required_integrations', 'string', ['length' => 128, 'default' => NULL])
            ->addColumn('payment_type', 'smallinteger', ['length' => 1, 'null' => false, 'default' => 1])
            ->addColumn('hidden', 'smallinteger', ['length' => 1, 'null' => false, 'default' => 0])
            ->addColumn('disabled', 'smallinteger', ['length' => 1, 'null' => false, 'default' => 0])
            ->addColumn('order', 'integer', ['length' => 11])
            ->addColumn('deleted', 'smallinteger', ['length' => 1, 'null' => false, 'default' => 0]);

        $table->create();
    }
}