<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreatePrivateMessagesRepliesTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('rw_private_messages_replies');

        $table
            ->addColumn('pm_id', 'integer', ['length' => 11])
            ->addColumn('author_id', 'integer', ['length' => 11])
            ->addColumn('created', 'integer', ['length' => 11])
            ->addColumn('content', 'text');

        $table
            ->addForeignKey('pm_id', 'rw_private_messages', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('author_id', 'rw_users', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE']);

        $table->create();
    }
}
