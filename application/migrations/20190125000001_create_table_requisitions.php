<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Create_table_requisitions
 * @property CI_DB_forge $dbforge
 */
class Migration_Create_table_requisitions extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'auto_increment' => TRUE],
            'id_category' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'null' => TRUE],
            'id_employee' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11],
            'no_requisition' => ['type' => 'VARCHAR', 'constraint' => '50'],
            'request_title' => ['type' => 'VARCHAR', 'constraint' => '100'],
            'deadline' => ['type' => 'DATE', 'null' => TRUE],
            'deadline_remark' => ['type' => 'VARCHAR', 'constraint' => '200', 'null' => TRUE],
            'urgency' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => TRUE],
            'status' => ['type' => 'ENUM("PENDING", "REJECTED", "CANCELLED", "APPROVED", "LISTED", "ASK SELECTION", "READY", "SELECTED", "IN PROCESS", "DONE")', 'default' => 'PENDING'],
            'document' => ['type' => 'VARCHAR', 'constraint' => '500', 'null' => TRUE],
            'purchasing_note' => ['type' => 'TEXT', 'null' => TRUE],
            'selection_note' => ['type' => 'TEXT', 'null' => TRUE],
            'decision_maker_email' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => TRUE],
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
        $this->dbforge->create_table('requisitions');
        echo 'Migrate Migration_Create_table_requisitions' . PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_table('requisitions');
        echo 'Rollback Migration_Create_table_requisitions' . PHP_EOL;
    }
}