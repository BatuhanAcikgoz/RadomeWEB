<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateHaberlersTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('rw_haberlers');

        $table
            ->addColumn('haber_id', 'integer', ['length' => 11])
            ->addColumn('haber_title', 'string', ['length' => 150])
            ->addColumn('post_creator', 'integer', ['length' => 11])
            ->addColumn('post_content', 'text')
            ->addColumn('post_date', 'datetime')
            ->addColumn('post_views', 'integer', ['length' => 11, 'default' => 0])
            ->addColumn('deleted', 'boolean', ['default' => false])
            ->addColumn('created', 'integer', ['length' => 11]);

        $table
            ->addForeignKey('post_creator', 'rw_users', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE']);

        $table
            ->addIndex('haber_id');

        $table->create();
    }
}
