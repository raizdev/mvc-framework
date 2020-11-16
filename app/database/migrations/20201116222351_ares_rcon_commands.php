<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AresRconCommands extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('ares_rcon_commands');
        $table->addColumn('command', 'string', ['limit' => 100])
            ->addColumn('description', 'string', ['limit' => 100])
            ->addColumn('title', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('permission_id', 'integer', ['limit' => 11])
            ->create();
    }
}
