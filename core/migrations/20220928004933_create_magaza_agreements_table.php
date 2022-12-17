<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateMagazaAgreementsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('rw_store_agreements');

        $table
            ->addColumn('user_id', 'integer', ['length' => 11])
            ->addColumn('player_id', 'integer', ['length' => 11])
            ->addColumn('agreement_id', 'string', ['length' => 32])
            ->addColumn('status_id', 'integer', ['length' => 11, 'null' => false, 'default' => 0])
            ->addColumn('email', 'string', ['length' => 128])
            ->addColumn('payment_method', 'integer', ['length' => 11])
            ->addColumn('verified', 'smallinteger', ['length' => 1])
            ->addColumn('payer_id', 'string', ['length' => 64])
            ->addColumn('last_payment_date', 'integer', ['length' => 11])
            ->addColumn('next_billing_date', 'integer', ['length' => 11])
            ->addColumn('created', 'integer', ['length' => 11])
            ->addColumn('updated', 'integer', ['length' => 11]);

        $table->create();
    }
}