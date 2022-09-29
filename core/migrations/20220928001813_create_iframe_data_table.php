<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateIframeDataTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_iframe_data');

        $table
            ->addColumn('name', 'string', ['length' => 255])
            ->addColumn('src', 'string', ['length' => 5000])
            ->addColumn('iframe_size', 'string', ['length' => 255])
            ->addColumn('page_id', 'integer', ['length' => 11])
            ->addColumn('description', 'text', ['null' => true])
            ->addColumn('footer_description', 'text', ['null' => true]);

        $table->create();
    }
}