<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddPurifyBypassColumToAlertsTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('rw_alerts')
            ->addColumn('bypass_purify', 'boolean', ['default' => false])
            ->update();$page_title = $language->get('user', 'user_cp');
    }
}