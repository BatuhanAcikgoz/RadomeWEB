<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateStoreFieldsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_store_fields');

        $table
            ->addColumn('identifier', 'string', ['length' => 32])
            ->addColumn('description', 'string', ['length' => 255])
            ->addColumn('type', 'integer', ['length' => 11])
            ->addColumn('required', 'smallinteger', ['length' => 1, 'null' => false, 'default' => 0])
            ->addColumn('min', 'integer', ['length' => 1, 'null' => false, 'default' => 0])
            ->addColumn('max', 'integer', ['length' => 64, 'null' => false, 'default' => 0])
            ->addColumn('options', 'text', ['null' => true])
            ->addColumn('regex', 'string', ['length' => 64, 'null' => true, 'default' => NULL])
            ->addColumn('default_value', 'string', ['length' => 64, 'null' => false, 'default' => ''])
            ->addColumn('deleted', 'integer', ['length' => 11, 'null' => false, 'default' => 0])
            ->addColumn('order', 'integer', ['length' => 11, 'null' => false, 'default' => 1]);

        $table->create();
    }
}