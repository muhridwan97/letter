<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Alter_requisitions_add_approved_type
 * @property CI_DB_forge $dbforge
 */
class Migration_Alter_requisitions_add_approved_type extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_column('requisitions', [
            'id_customer' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true, 'after' => 'id_item_category'],
            'approved_type' => ['type' => 'ENUM("INTERNAL", "EXTERNAL", "")', 'default' => "", 'after' => 'description'],
        ]);

        echo 'Migrate Migration_Alter_requisitions_add_approved_type' . PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_column('requisitions', 'id_customer');
        $this->dbforge->drop_column('requisitions', 'approved_type');
        echo 'Rollback Migration_Alter_requisitions_add_approved_type' . PHP_EOL;
    }
}
