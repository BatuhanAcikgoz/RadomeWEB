<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTemplatesTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('rw_templates');

        $table
            ->addColumn('name', 'string', ['length' => 64])
            ->addColumn('enabled', 'boolean', ['default' => 0])
            ->addColumn('is_default', 'boolean', ['default' => 0]);

        $table->create();
    }
}
