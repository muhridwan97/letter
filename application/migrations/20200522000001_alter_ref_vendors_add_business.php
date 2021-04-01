<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Alter_ref_vendors_add_business
 * @property CI_DB_forge $dbforge
 */
class Migration_Alter_ref_vendors_add_business extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_column('ref_vendors', [
			'business' => ['type' => 'Varchar', 'constraint' => 50, 'after' => 'is_pkp', 'null' => TRUE],
        ]);

        echo 'Migrate Migration_Alter_ref_vendors_add_business' . PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_column('ref_vendors', 'business');
        echo 'Rollback Migration_Alter_ref_vendors_add_business' . PHP_EOL;
    }
}
