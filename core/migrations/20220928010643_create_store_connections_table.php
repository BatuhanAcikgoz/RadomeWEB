<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateStoreConnectionsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_store_connections');

        $table
            ->addColumn('service_id', 'integer', ['length' => 11])
            ->addColumn('name', 'string', ['length' => 64])
            ->addColumn('data', 'text', ['default' => null])
            ->addColumn('last_fetch', 'integer', ['length' => 11, 'null' => false, 'default' => 0]);

        $table->create();
    }
}