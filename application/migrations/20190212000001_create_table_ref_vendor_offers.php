<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Create_table_ref_vendor_offers
 * @property CI_DB_forge $dbforge
 */
class Migration_Create_table_ref_vendor_offers extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'auto_increment' => TRUE],
            'id_vendor' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'null' => TRUE],
            'item_name' => ['type' => 'VARCHAR', 'constraint' => '200'],
            'brand' => ['type' => 'VARCHAR', 'constraint' => '100'],
            'price_date' => ['type' => 'DATE', 'null' => TRUE],
            'price_until' => ['type' => 'DATE', 'null' => TRUE],
            'price' => ['type' => 'DECIMAL(20,2)', 'default' => 0],
            'discount' => ['type' => 'DECIMAL(20,2)', 'default' => 0],
            'description' => ['type' => 'TEXT', 'null' => TRUE],
            'created_at' => ['type' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'],
            'created_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE],
            'updated_at' => ['type' => 'TIMESTAMP ON UPDATE CURRENT_TIMESTAMP', 'null' => TRUE],
            'updated_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'null' => TRUE],
        ])
            ->add_field('CONSTRAINT fk_vendor_offer_vendor FOREIGN KEY (id_vendor) REFERENCES ref_vendors(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('ref_vendor_offers');
        echo 'Migrate Migration_Create_table_ref_vendor_offers' . PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_table('ref_vendor_offers');
        echo 'Rollback Migration_Create_table_ref_vendor_offers' . PHP_EOL;
    }
}