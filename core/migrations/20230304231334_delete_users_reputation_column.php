<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class DeleteUsersReputationColumn extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('rw_users');
        $table->removeColumn('reputation');
        $table->update();
    }
}
