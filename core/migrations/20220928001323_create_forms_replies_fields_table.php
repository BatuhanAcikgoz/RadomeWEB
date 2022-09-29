<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateFormsRepliesFieldsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_forms_replies_fields');

        $table
            ->addColumn('submission_id', 'integer', ['length' => 11])
            ->addColumn('field_id', 'integer', ['length' => 11])
            ->addColumn('value', 'text', ['null' => false]);

        $table = $this->query('ALTER TABLE `nl2_forms_replies_fields` ADD INDEX `nl2_forms_replies_fields_idx_submission_id` (`submission_id`)');

        $table->create();
    }
}