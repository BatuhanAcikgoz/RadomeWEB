<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateFormsFieldsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_forms_fields');

        $table
            ->addColumn('form_id', 'integer', ['length' => 11])
            ->addColumn('name', 'string', ['length' => 255])
            ->addColumn('type', 'integer', ['length' => 11])
            ->addColumn('required', 'smallinteger', ['length' => 1, 'null' => false, 'default' => 0])
            ->addColumn('min', 'integer', ['length' => 11, 'null' => false, 'default' => 0])
            ->addColumn('max', 'integer', ['length' => 11, 'null' => false, 'default' => 0])
            ->addColumn('placeholder', 'string', ['length' => 255])
            ->addColumn('options', 'text', ['null' => true, 'default' => null])
            ->addColumn('info', 'text', ['null' => true, 'default' => null])
            ->addColumn('deleted', 'smallinteger', ['length' => 1, 'null' => false, 'default' => 0])
            ->addColumn('order', 'integer', ['length' => 11, 'null' => false, 'default' => 1]);

        $table->create();
    }
}