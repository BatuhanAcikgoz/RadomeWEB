<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateMagazaProductsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('rw_store_products');

        $table
            ->addColumn('category_id', 'integer', ['length' => 11])
            ->addColumn('name', 'string', ['length' => 128])
            ->addColumn('price_cents', 'integer', ['length' => 11])
            ->addColumn('description', 'text', ['null' => true])
            ->addColumn('image', 'string', ['length' => 128, 'null' => true, 'default' => NULL])
            ->addColumn('global_limit', 'string', ['length' => 128, 'null' => true, 'default' => NULL])
            ->addColumn('user_limit', 'string', ['length' => 128, 'null' => true, 'default' => NULL])
            ->addColumn('required_products', 'string', ['length' => 128, 'null' => true, 'default' => NULL])
            ->addColumn('required_groups', 'string', ['length' => 128, 'null' => true, 'default' => NULL])
            ->addColumn('required_integrations', 'string', ['length' => 128, 'null' => true, 'default' => NULL])
            ->addColumn('allowed_gateways', 'string', ['length' => 128, 'null' => true, 'default' => NULL])
            ->addColumn('payment_type', 'smallinteger', ['length' => 1, 'null' => false, 'default' => 1])
            ->addColumn('hidden', 'smallinteger', ['length' => 1, 'null' => false, 'default' => 0])
            ->addColumn('disabled', 'smallinteger', ['length' => 1, 'null' => false, 'default' => 0])
            ->addColumn('order', 'integer', ['length' => 11])
            ->addColumn('deleted', 'smallinteger', ['length' => 1, 'null' => false, 'default' => 0]);

        $table->create();
    }
}