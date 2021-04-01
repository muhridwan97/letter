<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Create_table_purchase_offers
 * @property CI_DB_forge $dbforge
 */
class Migration_Create_table_purchase_offers extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'auto_increment' => TRUE],
            'id_requisition' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'null' => TRUE],
            'id_vendor' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11],
            'lead_time' => ['type' => 'DATE', 'null' => TRUE],
            'status' => ['type' => 'ENUM("ON REVIEW", "UNSELECTED", "SELECTED", "ORDERED", "COMPLETED")', 'default' => 'ON REVIEW'],
            'document' => ['type' => 'VARCHAR', 'constraint' => '500', 'null' => TRUE],
            'top' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'null' => TRUE],
            'offer_discount_goods' => ['type' => 'DECIMAL(20,2)', 'default' => 0],
            'offer_discount_services' => ['type' => 'DECIMAL(20,2)', 'default' => 0],
            'description' => ['type' => 'TEXT', 'null' => TRUE],
            'is_deleted' => ['type' => 'INT', 'constraint' => 1, 'default' => 0],
            'created_at' => ['type' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'],
            'created_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE],
            'updated_at' => ['type' => 'TIMESTAMP ON UPDATE CURRENT_TIMESTAMP', 'null' => TRUE],
            'updated_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'null' => TRUE],
            'deleted_at' => ['type' => 'TIMESTAMP', 'null' => true],
            'deleted_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'null' => TRUE]
        ])
            ->add_field('CONSTRAINT fk_purchase_offer_requisition FOREIGN KEY (id_requisition) REFERENCES requisitions(id) ON DELETE CASCADE ON UPDATE CASCADE')
            ->add_field('CONSTRAINT fk_purchase_offer_vendor FOREIGN KEY (id_vendor) REFERENCES ref_vendors(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('purchase_offers');
        echo 'Migrate Migration_Create_table_purchase_offers' . PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_table('purchase_offers');
        echo 'Rollback Migration_Create_table_purchase_offers' . PHP_EOL;
    }
}