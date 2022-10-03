<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateVoteSitesTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('rw_vote_sites');

        $table
            ->addColumn('site', 'string', ['length' => 512])
            ->addColumn('name', 'string', ['length' => 64]);

        $table->create();
    }
}