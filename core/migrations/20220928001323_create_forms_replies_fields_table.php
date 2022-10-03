<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateFormsRepliesFieldsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('rw_forms_replies_fields');

        $table
            ->addColumn('submission_id', 'integer', ['length' => 11])
            ->addColumn('field_id', 'integer', ['length' => 11])
            ->addColumn('value', 'text', ['null' => false]);

        $table->create();
    }
}