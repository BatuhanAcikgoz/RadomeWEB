<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreatePrivateMessagesUsersTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('rw_private_messages_users');

        $table
            ->addColumn('pm_id', 'integer', ['length' => 11])
            ->addColumn('user_id', 'integer', ['length' => 11])
            ->addColumn('read', 'boolean', ['default' => false]);

        $table
            ->addForeignKey('pm_id', 'rw_private_messages', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('user_id', 'rw_users', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE']);

        $table->create();
    }
}
