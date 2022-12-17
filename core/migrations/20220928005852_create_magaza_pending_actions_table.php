<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateMagazaPendingActionsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('rw_store_pending_actions');

        $table
            ->addColumn('order_id', 'string', ['length' => 11])
            ->addColumn('action_id', 'string', ['length' => 11])
            ->addColumn('product_id', 'string', ['length' => 11])
            ->addColumn('customer_id', 'string', ['length' => 11, 'null' => true, 'default' => NULL])
            ->addColumn('connection_id', 'string', ['length' => 11])
            ->addColumn('type', 'string', ['length' => 11, 'default' => 1])
            ->addColumn('command', 'string', ['length' => 2048])
            ->addColumn('require_online', 'smallinteger', ['length' => 1, 'null' => false, 'default' => 1])
            ->addColumn('status', 'smallinteger', ['length' => 1, 'null' => false, 'default' => 0])
            ->addColumn('order', 'integer', ['length' => 11]);

        $table->create();
    }
}