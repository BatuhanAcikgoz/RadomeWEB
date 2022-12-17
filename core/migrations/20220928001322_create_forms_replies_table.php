<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateFormlarRepliesTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('rw_forms_replies');

        $table
            ->addColumn('form_id', 'integer', ['length' => 11])
            ->addColumn('user_id', 'integer', ['length' => 11])
            ->addColumn('updated_by', 'integer', ['length' => 11])
            ->addColumn('created', 'integer', ['length' => 11])
            ->addColumn('updated', 'integer', ['length' => 11])
            ->addColumn('content', 'text', ['null' => true, 'default' => null])
            ->addColumn('status_id', 'integer', ['length' => 11, 'null' => false, 'default' => 1]);

        $table->create();
    }
}