<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Alter_ref_vendors_add_id_bill_category
 * @property CI_DB_forge $dbforge
 */
class Migration_Alter_ref_vendors_add_id_bill_category extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_column('ref_vendors', [
			'id_bill_category' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'after' => 'id', 'null' => TRUE],
        ]);

        echo 'Migrate Migration_Alter_ref_vendors_add_id_bill_category' . PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_column('ref_vendors', 'id_bill_category');
        echo 'Rollback Migration_Alter_ref_vendors_add_id_bill_category' . PHP_EOL;
    }
}
