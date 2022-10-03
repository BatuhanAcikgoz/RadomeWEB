<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateWikiSettingsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('rw_wiki_settings');

        $table
            ->addColumn('name', 'string', ['length' => 20])
            ->addColumn('value', 'string', ['length' => 8192]);

        $table->create();
    }
}