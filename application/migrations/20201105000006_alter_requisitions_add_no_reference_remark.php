<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Alter_requisitions_add_no_reference_remark
 * @property CI_DB_forge $dbforge
 */
class Migration_Alter_requisitions_add_no_reference_remark extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_column('requisitions', [
			'no_reference_remark' => ['type' => 'TEXT', 'null' => TRUE, 'after' => 'urgency'],
        ]);

        echo 'Migrate Migration_Alter_requisitions_add_no_reference_remark' . PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_column('requisitions', 'no_reference_remark');
        echo 'Rollback Migration_Alter_requisitions_add_no_reference_remark' . PHP_EOL;
    }
}
