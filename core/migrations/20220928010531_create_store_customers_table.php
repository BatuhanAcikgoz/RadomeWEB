<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateStoreCustomersTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_store_customers');

        $table
            ->addColumn('user_id', 'integer', ['length' => 11, 'default' => null])
            ->addColumn('integration_id', 'integer', ['length' => 11])
            ->addColumn('username', 'string', ['length' => 64, 'default' => null])
            ->addColumn('identifier', 'string', ['length' => 64, 'default' => null])
            ->addColumn('cents', 'integer', ['length' => 11, 'default' => 0]);

        $table->create();
    }
}