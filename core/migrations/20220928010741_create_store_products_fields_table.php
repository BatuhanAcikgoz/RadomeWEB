<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateStoreProductsFieldsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('rw_store_products_fields');

        $table
            ->addColumn('product_id', 'integer', ['length' => 11])
            ->addColumn('field_id', 'integer', ['length' => 11]);

        $table->create();
    }
}