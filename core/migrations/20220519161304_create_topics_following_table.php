<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTopicsFollowingTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('rw_topics_following');

        $table
            ->addColumn('topic_id', 'integer', ['length' => 11])
            ->addColumn('user_id', 'integer', ['length' => 11])
            ->addColumn('existing_alerts', 'boolean', ['default' => false]);

        $table
            ->addForeignKey('topic_id', 'rw_topics', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('user_id', 'rw_users', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE']);

        $table->create();
    }
}
