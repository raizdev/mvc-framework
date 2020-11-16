<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

/**
 * Class AresRoles
 */
final class AresRoles extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('ares_roles');
        $table->addColumn('name', 'string', ['limit' => 100])
            ->addColumn('description', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('status', 'integer', ['limit' => 11])
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->create();
    }
}
