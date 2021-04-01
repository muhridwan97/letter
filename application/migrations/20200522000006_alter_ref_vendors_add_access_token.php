<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Alter_ref_vendors_add_access_token
 * @property CI_DB_forge $dbforge
 */
class Migration_Alter_ref_vendors_add_access_token extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_column('ref_vendors', [
			'access_token' => ['type' => 'VARCHAR', 'constraint' => 200, 'null' => TRUE, 'after' => 'id_bill_category'],
			'expired_token' => ['type' => 'TIMESTAMP', 'null' => TRUE, 'after' => 'description'],
        ]);

        echo 'Migrate Migration_Alter_ref_vendors_add_access_token' . PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_column('ref_vendors', 'access_token');
        $this->dbforge->drop_column('ref_vendors', 'expired_token');
        echo 'Rollback Migration_Alter_ref_vendors_add_access_token' . PHP_EOL;
    }
}
