<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Alter_requisitions_add_id_item_category
 * @property CI_DB_forge $dbforge
 */
class Migration_Alter_requisitions_add_id_item_category extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_column('requisitions', [
            'id_item_category' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'after' => 'id_category'],
        ]);

        echo 'Migrate Migration_Alter_requisitions_add_id_item_category' . PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_column('requisitions', 'id_item_category');
        echo 'Rollback Migration_Alter_requisitions_add_id_item_category' . PHP_EOL;
    }
}
