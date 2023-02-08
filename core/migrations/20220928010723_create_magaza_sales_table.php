<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateMagazaSalesTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('rw_store_sales');

        $table
            ->addColumn('name', 'string', ['length' => 64, 'null' => false])
            ->addColumn('effective_on', 'string', ['length' => 256, 'null' => false])
            ->addColumn('discount_type', 'integer', ['length' => 11, 'null' => false])
            ->addColumn('discount_amount', 'integer', ['length' => 11, 'null' => false])
            ->addColumn('start_date', 'integer', ['length' => 11, 'null' => false])
            ->addColumn('expire_date', 'integer', ['length' => 11, 'null' => false]);

        $table->create();
    }
}