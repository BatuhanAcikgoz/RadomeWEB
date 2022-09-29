<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateIframeTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_iframe_pages');

        $table
            ->addColumn('name', 'string', ['length' => 255])
            ->addColumn('url', 'string', ['length' => 255]);

        $table->create();
    }
}