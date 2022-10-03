<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateForumsTopicLabelsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('rw_forums_topic_labels');

        $table
            ->addColumn('fids', 'string', ['length' => 128])
            ->addColumn('name', 'string', ['length' => 32])
            ->addColumn('label', 'string', ['length' => 20])
            ->addColumn('gids', 'string', ['length' => 256, 'null' => true, 'default' => null]);

        $table->create();
    }
}
