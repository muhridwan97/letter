<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Create_table_ref_vendor_locations
 * @property CI_DB_forge $dbforge
 */
class Migration_Create_table_ref_vendor_locations extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'auto_increment' => TRUE],
            'id_vendor' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE],
            'city' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => true],
            'address' => ['type' => 'VARCHAR', 'constraint' => '200', 'null' => true],
            'office_phone' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => true],
            'is_primary' => ['type' => 'INT', 'constraint' => 1, 'default' => 0],
            'description' => ['type' => 'TEXT', 'null' => TRUE],
            'created_at' => ['type' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'],
        ])->add_field('CONSTRAINT fk_vendor_location_vendor FOREIGN KEY (id_vendor) REFERENCES ref_vendors(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('ref_vendor_locations');
        echo 'Migrate Migration_Create_table_ref_vendor_locations' . PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_table('ref_vendor_locations',TRUE);
        echo 'Rollback Migration_Create_table_ref_vendor_locations' . PHP_EOL;
    }
}