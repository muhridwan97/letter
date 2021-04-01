<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Create_table_payment_handovers
 * @property CI_DB_forge $dbforge
 */
class Migration_Create_table_payment_handovers extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'auto_increment' => TRUE],
            'no_handover' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => true],
            'handover_date' => ['type' => 'DATE', 'null' => TRUE],
            'document' => ['type' => 'VARCHAR', 'constraint' => '500', 'null' => TRUE],
            'description' => ['type' => 'TEXT', 'null' => TRUE],
            'is_deleted' => ['type' => 'INT', 'constraint' => 1, 'default' => 0],
            'created_at' => ['type' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'],
            'created_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE],
            'updated_at' => ['type' => 'TIMESTAMP ON UPDATE CURRENT_TIMESTAMP', 'null' => TRUE],
            'updated_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'null' => TRUE],
            'deleted_at' => ['type' => 'TIMESTAMP', 'null' => true],
            'deleted_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'null' => TRUE]
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('payment_handovers');
        $this->db->insert_batch('prv_permissions', [
            ['module' => 'purchasing', 'submodule' => 'payment handover', 'permission' => PERMISSION_PAYMENT_HANDOVER_VIEW],
            ['module' => 'purchasing', 'submodule' => 'payment handover', 'permission' => PERMISSION_PAYMENT_HANDOVER_CREATE],
            ['module' => 'purchasing', 'submodule' => 'payment handover', 'permission' => PERMISSION_PAYMENT_HANDOVER_DELETE],
            ['module' => 'purchasing', 'submodule' => 'payment handover', 'permission' => PERMISSION_PAYMENT_HANDOVER_EDIT],
        ]);
        echo 'Migrate Migration_Create_table_payment_handovers' . PHP_EOL;
    }

    public function down()
    {
        $this->db->delete('prv_permissions', ['module' => 'purchasing', 'submodule' => 'payment handover']);
        $this->dbforge->drop_table('payment_handovers');
        echo 'Rollback Migration_Create_table_payment_handovers' . PHP_EOL;
    }
}