<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Alter_requisition_items_add_id_item
 * @property CI_DB_forge $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_Alter_requisition_items_add_id_item extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_column('requisition_items', [
			'id_item' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'null' => TRUE, 'after' => 'id_requisition'],
        ]);
		$this->db->query('ALTER TABLE requisition_items ADD CONSTRAINT fk_requisition_item_item FOREIGN KEY (id_item) REFERENCES ref_items(id) ON DELETE CASCADE ON UPDATE CASCADE');

        echo 'Migrate Migration_Alter_requisition_items_add_id_item' . PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_column('requisition_items', 'id_item');
        echo 'Rollback Migration_Alter_requisition_items_add_id_item' . PHP_EOL;
    }
}
