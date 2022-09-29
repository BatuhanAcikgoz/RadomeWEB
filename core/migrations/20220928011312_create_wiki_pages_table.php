<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateWikiPagesTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_wiki_pages');

        $table
            ->addColumn('parent', 'string', ['length' => 48])
            ->addColumn('nameid', 'string', ['length' => 48])
            ->addColumn('title', 'string', ['length' => 48])
            ->addColumn('button', 'string', ['length' => 48])
            ->addColumn('icon', 'string', ['length' => 96])
            ->addColumn('guest', 'smallinteger', ['length' => 1, 'null' => false, 'default' => 0])
            ->addColumn('link_location', 'smallinteger', ['length' => 1, 'null' => false, 'default' => 1])
            ->addColumn('can_view', 'smallinteger', ['length' => 1, 'null' => false, 'default' => 0])
            ->addColumn('captcha', 'smallinteger', ['length' => 1, 'null' => false, 'default' => 0])
            ->addColumn('context', 'text')
            ->addColumn('views', 'integer', ['length' => 11, 'null' => false, 'default' => 0])
            ->addColumn('likes', 'integer', ['length' => 11])
            ->addColumn('likeable', 'integer', ['length' => 11, 'null' => false, 'default' => 1])
            ->addColumn('enabled', 'integer', ['length' => 11, 'null' => false, 'default' => 1]);

        $table->create();
    }
}