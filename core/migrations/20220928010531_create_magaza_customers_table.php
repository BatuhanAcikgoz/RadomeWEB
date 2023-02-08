<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateMagazaCustomersTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('rw_store_customers');

        $table
            ->addColumn('user_id', 'integer', ['length' => 11, 'null' => true, 'default' => NULL])
            ->addColumn('integration_id', 'integer', ['length' => 11])
            ->addColumn('username', 'string', ['length' => 64, 'null' => true,  'default' => NULL])
            ->addColumn('identifier', 'string', ['length' => 64, 'null' => true, 'default' => NULL])
            ->addColumn('cents', 'integer', ['length' => 20, 'default' => 0]);

        $table->create();
    }
}