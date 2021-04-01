<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Create_table_purchase_offer_items
 * @property CI_DB_forge $dbforge
 */
class Migration_Create_table_purchase_offer_items extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'auto_increment' => TRUE],
            'id_purchase_offer' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'null' => TRUE],
            'id_requisition_item' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11],
            'quantity' => ['type' => 'DOUBLE', 'default' => '1'],
            'quantity_selected' => ['type' => 'DOUBLE', 'null' => TRUE],
            'price' => ['type' => 'DECIMAL(20,2)', 'default' => 0],
            'discount' => ['type' => 'DECIMAL(20,2)', 'default' => 0],
            'description' => ['type' => 'TEXT', 'null' => TRUE],
            'created_at' => ['type' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'],
        ])
            ->add_field('CONSTRAINT fk_purchase_offer_item_requisition FOREIGN KEY (id_purchase_offer) REFERENCES purchase_offers(id) ON DELETE CASCADE ON UPDATE CASCADE')
            ->add_field('CONSTRAINT fk_purchase_offer_item_vendor FOREIGN KEY (id_requisition_item) REFERENCES requisition_items(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('purchase_offer_items');
        echo 'Migrate Migration_Create_table_purchase_offer_items' . PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_table('purchase_offer_items');
        echo 'Rollback Migration_Create_table_purchase_offer_items' . PHP_EOL;
    }
}