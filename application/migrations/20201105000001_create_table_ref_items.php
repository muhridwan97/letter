<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Create_table_ref_items
 * @property CI_DB_forge $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_Create_table_ref_items extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'auto_increment' => TRUE],
            'no_item' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => true],
            'item_name' => ['type' => 'VARCHAR', 'constraint' => '200', 'null' => true],
            'unit' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => true],
            'brand' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
			'specification' => ['type' => 'TEXT', 'null' => TRUE],
            'description' => ['type' => 'TEXT', 'null' => TRUE],
			'is_deleted' => ['type' => 'INT', 'constraint' => 1, 'default' => 0],
            'created_at' => ['type' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'],
            'created_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE],
            'updated_at' => ['type' => 'TIMESTAMP ON UPDATE CURRENT_TIMESTAMP', 'null' => TRUE],
            'updated_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'null' => TRUE],
			'deleted_at' => ['type' => 'TIMESTAMP', 'null' => true],
			'deleted_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true]
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('ref_items');

        $this->db->insert_batch('prv_permissions', [
			['module' => 'master', 'submodule' => 'item', 'permission' => PERMISSION_ITEM_VIEW],
			['module' => 'master', 'submodule' => 'item', 'permission' => PERMISSION_ITEM_CREATE],
			['module' => 'master', 'submodule' => 'item', 'permission' => PERMISSION_ITEM_EDIT],
			['module' => 'master', 'submodule' => 'item', 'permission' => PERMISSION_ITEM_DELETE],
        ]);
        echo 'Migrate Migration_Create_table_ref_items' . PHP_EOL;
    }

    public function down()
    {
        $this->db->delete('prv_permissions', ['module' => 'master', 'submodule' => 'item']);
        $this->dbforge->drop_table('ref_items',TRUE);
        echo 'Rollback Migration_Create_table_ref_items' . PHP_EOL;
    }
}
