<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateFormlarFieldsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('rw_forms_fields');

        $table
            ->addColumn('form_id', 'integer', ['length' => 11])
            ->addColumn('name', 'string', ['length' => 255])
            ->addColumn('type', 'integer', ['length' => 11])
            ->addColumn('required', 'smallinteger', ['length' => 1, 'null' => false, 'default' => 0])
            ->addColumn('min', 'integer', ['length' => 11, 'null' => false, 'default' => 0])
            ->addColumn('max', 'integer', ['length' => 11, 'null' => false, 'default' => 0])
            ->addColumn('placeholder', 'string', ['length' => 255, 'null' => true, 'default' => NULL])
            ->addColumn('options', 'text', ['null' => true, 'default' => null])
            ->addColumn('info', 'text', ['null' => true, 'default' => null])
            ->addColumn('regex', 'string', ['length' => 65, 'null' => true, 'default' => NULL])
            ->addColumn('default_value', 'string', ['length' => 65, 'null' => false, 'default' => ""])
            ->addColumn('deleted', 'smallinteger', ['length' => 1, 'null' => false, 'default' => 0])
            ->addColumn('order', 'integer', ['length' => 11, 'null' => false, 'default' => 1]);

        $table->create();
    }
}