<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateFormlarStatusesTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('rw_forms_statuses');

        $table
            ->addColumn('html', 'string', ['length' => 1024])
            ->addColumn('open', 'smallinteger', ['length' => 1, 'null' => false])
            ->addColumn('fids', 'string', ['length' => 128, 'null' => true])
            ->addColumn('gids', 'string', ['length' => 128, 'null' => true])
            ->addColumn('color', 'string', ['length' => 32, 'null' => true, 'default' => NULL])
            ->addColumn('deleted', 'smallinteger', ['length' => 1, 'null' => false, 'default' => 0]);

        $table->create();
    }
}