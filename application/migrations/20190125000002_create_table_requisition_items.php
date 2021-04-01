<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Create_table_requisition_items
 * @property CI_DB_forge $dbforge
 */
class Migration_Create_table_requisition_items extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'auto_increment' => TRUE],
            'id_requisition' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11],
            'item_name' => ['type' => 'VARCHAR', 'constraint' => '200'],
            'type' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => TRUE],
            'brand' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => TRUE],
            'quantity' => ['type' => 'DOUBLE', 'default' => '1'],
            'unit' => ['type' => 'VARCHAR', 'constraint' => '50'],
            'description' => ['type' => 'TEXT', 'null' => TRUE],
            'created_at' => ['type' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'],
        ])
            ->add_field('CONSTRAINT fk_requisition_item_requisition FOREIGN KEY (id_requisition) REFERENCES requisitions(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('requisition_items');
        echo 'Migrate Migration_Create_table_requisition_items' . PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_table('requisition_items');
        echo 'Rollback Migration_Create_table_requisition_items' . PHP_EOL;
    }
}
