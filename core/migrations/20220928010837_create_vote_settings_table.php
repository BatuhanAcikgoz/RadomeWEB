<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateVoteSettingsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_vote_settings');

        $table
            ->addColumn('name', 'string', ['length' => 20])
            ->addColumn('value', 'string', ['length' => 2048]);

        $table->create();
    }
}