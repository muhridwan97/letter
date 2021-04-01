<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Create_table_purchase_orders
 * @property CI_DB_forge $dbforge
 */
class Migration_Create_table_purchase_orders extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'auto_increment' => TRUE],
            'id_purchase_offer' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'null' => TRUE],
            'no_purchase' => ['type' => 'VARCHAR', 'constraint' => '50'],
            'date' => ['type' => 'DATE', 'null' => TRUE],
            'approve_date' => ['type' => 'DATE', 'null' => TRUE],
            'rounding_goods' => ['type' => 'DECIMAL(20,2)', 'default' => 0],
            'rounding_services' => ['type' => 'DECIMAL(20,2)', 'default' => 0],
            'tax' => ['type' => 'DECIMAL(20,2)', 'default' => 0],
            'tax_percentage' => ['type' => 'DECIMAL(10,2)', 'default' => 0],
            'tax_basis' => ['type' => 'DECIMAL(20,2)', 'default' => 0],
            'tax_basis_percentage' => ['type' => 'DECIMAL(10,2)', 'default' => 0],
            'tax_basis_label' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => TRUE],
            'delivery_cost' => ['type' => 'DECIMAL(20,2)', 'default' => 0],
            'miscellaneous_expense' => ['type' => 'DECIMAL(20,2)', 'default' => 0],
            'miscellaneous_label' => ['type' => 'VARCHAR', 'constraint' => '50'],
            'rating' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'null' => TRUE],
            'document' => ['type' => 'VARCHAR', 'constraint' => '500', 'null' => TRUE],
            'description' => ['type' => 'TEXT', 'null' => TRUE],
            'remark' => ['type' => 'TEXT', 'null' => TRUE],
            'is_deleted' => ['type' => 'INT', 'constraint' => 1, 'default' => 0],
            'created_at' => ['type' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'],
            'created_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE],
            'updated_at' => ['type' => 'TIMESTAMP ON UPDATE CURRENT_TIMESTAMP', 'null' => TRUE],
            'updated_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'null' => TRUE],
            'deleted_at' => ['type' => 'TIMESTAMP', 'null' => true],
            'deleted_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'null' => TRUE]
        ])
            ->add_field('CONSTRAINT fk_purchase_order_offer FOREIGN KEY (id_purchase_offer) REFERENCES purchase_offers(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('purchase_orders');
        echo 'Migrate Migration_Create_table_purchase_orders' . PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_table('purchase_orders');
        echo 'Rollback Migration_Create_table_purchase_offers' . PHP_EOL;
    }
}