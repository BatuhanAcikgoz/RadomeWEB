<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateStoreCustomersTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_store_customers');

        $table
            ->addColumn('user_id', 'integer', ['length' => 11, 'default' => NULL])
            ->addColumn('integration_id', 'integer', ['length' => 11])
            ->addColumn('username', 'string', ['length' => 64, 'null' => true,  'default' => NULL])
            ->addColumn('identifier', 'string', ['length' => 64, 'null' => true, 'default' => NULL])
            ->addColumn('cents', 'integer', ['length' => 11, 'default' => 0]);

        $table->create();
    }
}