<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateStoreCategoriesTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('rw_store_categories');

        $table
            ->addColumn('name', 'string', ['length' => 128])
            ->addColumn('description', 'text', ['null' => false])
            ->addColumn('image', 'string', ['length' => 512, 'null' => true, 'default' => NULL])
            ->addColumn('only_subcategories', 'smallinteger', ['length' => 1, 'default' => 0])
            ->addColumn('parent_category', 'smallinteger', ['length' => 1, 'null' => true, 'default' => NULL])
            ->addColumn('hidden', 'smallinteger', ['length' => 1, 'default' => 0])
            ->addColumn('disabled', 'smallinteger', ['length' => 1, 'default' => 0])
            ->addColumn('order', 'integer', ['length' => 11])
            ->addColumn('deleted', 'integer', ['length' => 11, 'default' => 0]);

        $table->create();
    }
}