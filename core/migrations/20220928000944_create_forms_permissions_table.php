<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateFormsPermissionsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_forms_permissions');

        $table
            ->addColumn('form_id', 'integer', ['length' => 11])
            ->addColumn('group_id', 'integer', ['length' => 11])
            ->addColumn('title', 'string', ['length' => 32])
            ->addColumn('post', 'smallinteger', ['length' => 1, 'null' => false, 'default' => 1])
            ->addColumn('view_own', 'smallinteger', ['length' => 1, 'null' => false, 'default' => 1])
            ->addColumn('view', 'smallinteger', ['length' => 1, 'null' => false, 'default' => 0])
            ->addColumn('can_delete', 'smallinteger', ['length' => 1, 'null' => false, 'default' => 1]);

        $table->create();
    }
}