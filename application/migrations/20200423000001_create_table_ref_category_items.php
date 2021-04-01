<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Create_table_ref_category_items
 * @property CI_DB_forge $dbforge
 */
class Migration_Create_table_ref_category_items extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'auto_increment' => TRUE],
            'id_category' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE],
            'item_code' => ['type' => 'VARCHAR', 'constraint' => '50'],
            'item_name' => ['type' => 'VARCHAR', 'constraint' => '100'],
            'description' => ['type' => 'TEXT', 'null' => true],
            'is_deleted' => ['type' => 'INT', 'constraint' => 1, 'default' => 0],
            'created_at' => ['type' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'],
            'created_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'updated_at' => ['type' => 'TIMESTAMP ON UPDATE CURRENT_TIMESTAMP', 'null' => true],
            'updated_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'deleted_at' => ['type' => 'TIMESTAMP', 'null' => true],
            'deleted_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true]
        ])->add_field('CONSTRAINT fk_category_items FOREIGN KEY (id_category) REFERENCES ref_categories(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('ref_item_categories');
        $this->db->insert_batch('prv_permissions', [
            ['module' => 'master', 'submodule' => 'item category', 'permission' => PERMISSION_ITEM_CATEGORY_VIEW],
            ['module' => 'master', 'submodule' => 'item category', 'permission' => PERMISSION_ITEM_CATEGORY_CREATE],
            ['module' => 'master', 'submodule' => 'item category', 'permission' => PERMISSION_ITEM_CATEGORY_EDIT],
            ['module' => 'master', 'submodule' => 'item category', 'permission' => PERMISSION_ITEM_CATEGORY_DELETE],
        ]);
        echo 'Migrate Migration_Create_table_ref_category_items' . PHP_EOL;
    }

    public function down()
    {
        $this->db->delete('prv_permissions', ['module' => 'master', 'submodule' => 'item category']);
        $this->dbforge->drop_table('ref_item_categories',TRUE);
        echo 'Rollback Migration_Create_table_ref_category_items' . PHP_EOL;
    }
}