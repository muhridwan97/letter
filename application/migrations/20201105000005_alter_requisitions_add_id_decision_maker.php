<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Alter_requisitions_add_id_decision_maker
 * @property CI_DB_forge $dbforge
 */
class Migration_Alter_requisitions_add_id_decision_maker extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_column('requisitions', [
			'id_decision_maker' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'null' => TRUE, 'after' => 'decision_maker_email'],
        ]);

        echo 'Migrate Migration_Alter_requisitions_add_id_decision_maker' . PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_column('requisitions', 'id_decision_maker');
        echo 'Rollback Migration_Alter_requisitions_add_id_decision_maker' . PHP_EOL;
    }
}
