<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AresSettings extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('ares_settings');
        $table->addColumn('key', 'string', ['limit' => 50])
            ->addColumn('value', 'string', ['limit' => 70])
            ->create();
    }
}
