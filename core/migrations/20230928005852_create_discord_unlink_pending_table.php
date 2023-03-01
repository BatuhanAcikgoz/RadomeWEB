<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateDiscordUnlinkPendingTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('rw_unlink_pending');

        $table
            ->addColumn('command', 'string', ['length' => 2048])
            ->addColumn('date', 'integer', ['length' => 11])
            ->addColumn('status', 'smallinteger', ['length' => 1, 'null' => false, 'default' => 0])
            ->addColumn('executed_date', 'integer', ['length' => 11, 'null' => true]);

        $table->create();
    }
}