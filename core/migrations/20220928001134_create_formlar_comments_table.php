<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateFormlarCommentsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('rw_forms_comments');

        $table
            ->addColumn('form_id', 'integer', ['length' => 11])
            ->addColumn('user_id', 'integer', ['length' => 11])
            ->addColumn('created', 'integer', ['length' => 11])
            ->addColumn('anonymous', 'smallinteger', ['length' => 1, 'null' => false, 'default' => 0])
            ->addColumn('content', 'text', ['null' => true, 'default' => null]);

        $table->create();
    }
}