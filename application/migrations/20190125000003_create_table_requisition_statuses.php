<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Create_table_requisition_requisitions
 * @property CI_DB_forge $dbforge
 */
class Migration_Create_table_requisition_statuses extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'auto_increment' => TRUE],
            'id_requisition' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11],
            'status' => ['type' => 'VARCHAR', 'constraint' => '200'],
            'data' => ['type' => 'text', 'null' => TRUE],
            'description' => ['type' => 'TEXT', 'null' => TRUE],
            'created_at' => ['type' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'],
            'created_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE],
        ])
            ->add_field('CONSTRAINT fk_requisition_status_requisition FOREIGN KEY (id_requisition) REFERENCES requisitions(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('requisition_statuses');
        echo 'Migrate Migration_Create_table_requisition_statuses' . PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_table('requisition_statuses');
        echo 'Rollback Migration_Create_table_requisition_statuses' . PHP_EOL;
    }
}
