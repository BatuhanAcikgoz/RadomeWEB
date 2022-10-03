<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateAlertsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('rw_alerts');

        $table
            ->addColumn('user_id', 'integer', ['length' => 11])
            ->addColumn('type', 'string', ['length' => 64])
            ->addColumn('url', 'string', ['length' => 255])
            ->addColumn('content_short', 'string', ['length' => 128])
            ->addColumn('content', 'string', ['length' => 512])
            ->addColumn('created', 'integer', ['length' => 11])
            ->addColumn('read', 'boolean', ['default' => false]);

        $table
            ->addForeignKey('user_id', 'rw_users', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE']);

        $table->create();
    }
}
