<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Alter_requisitions_add_rate_type
 * @property CI_DB_forge $dbforge
 */
class Migration_Alter_requisitions_add_rate_type extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_column('requisitions', [
			'rate_type' => ['type' => 'ENUM("FIXED", "ADJUSTABLE")', 'default' => 'FIXED', 'after' => 'status'],
        ]);
        echo 'Migrate Migration_Alter_requisitions_add_rate_type' . PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_column('requisitions', 'rate_type');
        echo 'Rollback Migration_Alter_requisitions_add_rate_type' . PHP_EOL;
    }
}
