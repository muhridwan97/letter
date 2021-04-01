<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Create_table_ref_bill_categories
 * @property CI_DB_forge $dbforge
 */
class Migration_Create_table_ref_bill_categories extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'auto_increment' => TRUE],
            'bill_category' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => TRUE],
            'description' => ['type' => 'TEXT', 'null' => true],
            'is_active' => ['type' => 'INT', 'constraint' => 1, 'default' => 1],
            'is_deleted' => ['type' => 'INT', 'constraint' => 1, 'default' => 0],
            'created_at' => ['type' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'],
            'created_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE],
            'updated_at' => ['type' => 'TIMESTAMP ON UPDATE CURRENT_TIMESTAMP', 'null' => TRUE],
            'updated_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'null' => TRUE],
            'deleted_at' => ['type' => 'TIMESTAMP', 'null' => TRUE],
            'deleted_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'null' => TRUE]
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('ref_bill_categories');
        $this->db->insert_batch('prv_permissions', [
            ['module' => 'master', 'submodule' => 'bill category', 'permission' => PERMISSION_BILL_CATEGORY_VIEW],
            ['module' => 'master', 'submodule' => 'bill category', 'permission' => PERMISSION_BILL_CATEGORY_CREATE],
            ['module' => 'master', 'submodule' => 'bill category', 'permission' => PERMISSION_BILL_CATEGORY_EDIT],
            ['module' => 'master', 'submodule' => 'bill category', 'permission' => PERMISSION_BILL_CATEGORY_DELETE],
        ]);
        echo 'Migrating Migration_Create_table_ref_bill_categories' . PHP_EOL;
    }

    public function down()
    {
        $this->db->delete('prv_permissions', ['module' => 'master', 'submodule' => 'bill category']);
        $this->dbforge->drop_table('ref_bill_categories',TRUE);
        echo 'Rollback Migration_Create_table_ref_bill_categories' . PHP_EOL;
    }
}
