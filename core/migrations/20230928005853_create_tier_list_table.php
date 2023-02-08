<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTierListTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('rw_tier_list');

        $table
            ->addColumn('name', 'string', ['length' => 128])
            ->addColumn('friendly_name', 'string', ['length' => 128])
            ->addColumn('ht1', 'integer', ['length' => 11, 'null' => false, 'default' => 0])
            ->addColumn('lt1', 'integer', ['length' => 11, 'null' => false, 'default' => 0])
            ->addColumn('ht2', 'integer', ['length' => 11, 'null' => false, 'default' => 0])
            ->addColumn('lt2', 'integer', ['length' => 11, 'null' => false, 'default' => 0])
            ->addColumn('ht3', 'integer', ['length' => 11, 'null' => false, 'default' => 0])
            ->addColumn('lt3', 'integer', ['length' => 11, 'null' => false, 'default' => 0])
            ->addColumn('ht4', 'integer', ['length' => 11, 'null' => false, 'default' => 0])
            ->addColumn('lt4', 'integer', ['length' => 11, 'null' => false, 'default' => 0])
            ->addColumn('ht5', 'integer', ['length' => 11, 'null' => false, 'default' => 0])
            ->addColumn('lt5', 'integer', ['length' => 11, 'null' => false, 'default' => 0]);

        $table->create();
    }
}