<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Alter_requisitions_add_request_type
 * @property CI_DB_forge $dbforge
 */
class Migration_Alter_requisitions_add_request_type extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_column('requisitions', [
			'request_type' => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'ROUTINE', 'after' => 'rate_type'],
        ]);

        echo 'Migrate Migration_Alter_requisitions_add_request_type' . PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_column('requisitions', 'request_type');
        echo 'Rollback Migration_Alter_requisitions_add_request_type' . PHP_EOL;
    }
}
