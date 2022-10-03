<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateStoreSettingsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('rw_store_settings');

        $table
            ->addColumn('name', 'string', ['length' => 64])
            ->addColumn('value', 'text');
            
        $table->create();
    }
}