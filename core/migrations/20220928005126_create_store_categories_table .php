<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateStoreCategoriesTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_store_categories');

        $table
            ->addColumn('name', 'string', ['length' => 128]);

        $table->create();
    }
}