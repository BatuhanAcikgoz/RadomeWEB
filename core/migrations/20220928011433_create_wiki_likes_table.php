<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateWikiLikesTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_wiki_likes');

        $table
            ->addColumn('username', 'string', ['length' => 20])
            ->addColumn('pageid', 'string', ['length' => 48]);

        $table->create();
    }
}