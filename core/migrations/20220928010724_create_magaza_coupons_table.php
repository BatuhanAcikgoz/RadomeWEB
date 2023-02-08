<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateMagazaCouponsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('rw_store_coupons');

        $table
            ->addColumn('code', 'string', ['length' => 64, 'null' => false])
            ->addColumn('effective_on', 'string', ['length' => 256, 'null' => false])
            ->addColumn('discount_type', 'integer', ['length' => 11, 'null' => false])
            ->addColumn('discount_amount', 'integer', ['length' => 11, 'null' => false])
            ->addColumn('start_date', 'integer', ['length' => 11, 'null' => false])
            ->addColumn('expire_date', 'integer', ['length' => 11, 'null' => false])
            ->addColumn('redeem_limit', 'integer', ['length' => 11, 'null' => false, 'default' => 0])
            ->addColumn('customer_limit', 'integer', ['length' => 11, 'null' => false, 'default' => 0])
            ->addColumn('min_basket', 'integer', ['length' => 11, 'null' => false, 'default' => 0]);

        $table->create();
    }
}