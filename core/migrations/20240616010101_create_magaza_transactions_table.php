<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateMagazaTransactionsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('rw_store_transactions');

        $table
            ->addColumn('customer_id', 'integer', ['length' => 11, 'null' => false])
            ->addColumn('received_by', 'integer', ['length' => 11, 'null' => false])
            ->addColumn('action', 'string', ['length' => 64, 'null' => false])
            ->addColumn('cents', 'integer', ['length' => 11, 'null' => false])
            ->addColumn('time', 'integer', ['length' => 11, 'null' => false])
            ->addColumn('info', 'text', ['length' => 11, 'null' => false]);

        $table->create();
    }
}