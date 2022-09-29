<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateStoreCategoriesTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_store_categories');

        $table
            ->addColumn('name', 'string', ['length' => 128])
            ->addColumn('description', 'text')
            ->addColumn('image', 'string', ['length' => 128, 'default' => NULL])
            ->addColumn('only_subcategories', 'smallinteger', ['length' => 1, 'default' => 0])
            ->addColumn('parent_category', 'smallinteger', ['length' => 1, 'null' => false, 'default' => 0])
            ->addColumn('hidden', 'smallinteger', ['length' => 1, 'null' => false, 'default' => 0])
            ->addColumn('disabled', 'smallinteger', ['length' => 1, 'null' => false, 'default' => 0])
            ->addColumn('order', 'integer', ['length' => 11])
            ->addColumn('deleted', 'integer', ['length' => 11, 'null' => false, 'default' => 0]);

        $table->create();
    }
}