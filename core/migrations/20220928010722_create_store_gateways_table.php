<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateStoreGatewaysTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('rw_store_gateways');

        $table
            ->addColumn('name', 'string', ['length' => 64])
            ->addColumn('displayname', 'string', ['length' => 64])
            ->addColumn('enabled', 'smallinteger', ['length' => 1, 'null' => false, 'default' => 0]);

        $table->create();
    }
}