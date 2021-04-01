<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Alter_ref_vendors_add_is_active
 * @property CI_DB_forge $dbforge
 */
class Migration_Alter_ref_vendors_add_is_active extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_column('ref_vendors', [
			'is_active' => ['type' => 'INT', 'constraint' => 1, 'default' => 1, 'after' => 'description'],
        ]);

        echo 'Migrate Migration_Alter_ref_vendors_add_is_active' . PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_column('ref_vendors', 'is_active');
        echo 'Rollback Migration_Alter_ref_vendors_add_is_active' . PHP_EOL;
    }
}
