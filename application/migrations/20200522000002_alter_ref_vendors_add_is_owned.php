<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Alter_ref_vendors_add_is_owned
 * @property CI_DB_forge $dbforge
 */
class Migration_Alter_ref_vendors_add_is_owned extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_column('ref_vendors', [
			'is_owned' => ['type' => 'INT', 'constraint' => 1, 'unsigned' => TRUE, 'after' => 'business', 'null' => TRUE],
        ]);

        echo 'Migrate Migration_Alter_ref_vendors_add_is_owned' . PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_column('ref_vendors', 'is_owned');
        echo 'Rollback Migration_Alter_ref_vendors_add_is_owned' . PHP_EOL;
    }
}
