<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Alter_ref_vendor_contacts_add_position
 * @property CI_DB_forge $dbforge
 */
class Migration_Alter_ref_vendor_contacts_add_position extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_column('ref_vendor_contacts', [
			'position' => ['type' => 'Varchar', 'constraint' => 50, 'after' => 'email', 'null' => TRUE],
        ]);

        echo 'Migrate Migration_Alter_ref_vendor_contacts_add_position' . PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_column('ref_vendor_contacts', 'position');
        echo 'Rollback Migration_Alter_ref_vendor_contacts_add_position' . PHP_EOL;
    }
}
