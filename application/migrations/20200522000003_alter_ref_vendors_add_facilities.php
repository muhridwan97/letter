<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Alter_ref_vendors_add_facilities
 * @property CI_DB_forge $dbforge
 */
class Migration_Alter_ref_vendors_add_facilities extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_column('ref_vendors', [
			'facilities' => ['type' => 'Varchar', 'constraint' => 200, 'after' => 'is_owned', 'null' => TRUE],
        ]);

        echo 'Migrate Migration_Alter_ref_vendors_add_facilities' . PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_column('ref_vendors', 'facilities');
        echo 'Rollback Migration_Alter_ref_vendors_add_facilities' . PHP_EOL;
    }
}
