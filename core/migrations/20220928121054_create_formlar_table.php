<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateFormlarTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('rw_forms');

        $table
            ->addColumn('url', 'string', ['length' => 32])
            ->addColumn('title', 'string', ['length' => 32])
            ->addColumn('guest', 'smallinteger', ['length' => 1, 'null' => false, 'default' => 0])
            ->addColumn('link_location', 'smallinteger', ['length' => 1, 'null' => false, 'default' => 1])
            ->addColumn('icon', 'string', ['length' => 64])
            ->addColumn('can_view', 'smallinteger', ['length' => 1, 'null' => false, 'default' => 0])
            ->addColumn('captcha', 'smallinteger', ['length' => 1, 'null' => false, 'default' => 0])
            ->addColumn('content', 'text', ['null' => true, 'default' => null])
            ->addColumn('comment_status', 'integer', ['length' => 11, 'null' => false, 'default' => 0])
            ->addColumn('source', 'string', ['length' => 32, 'null' => false, 'default' => 'forum'])
            ->addColumn('forum_id', 'integer', ['length' => 11, 'null' => false, 'default' => 0]);

        $table->create();
    }
}