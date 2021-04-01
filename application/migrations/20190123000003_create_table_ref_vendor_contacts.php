<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Create_table_ref_vendor_contacts
 * @property CI_DB_forge $dbforge
 */
class Migration_Create_table_ref_vendor_contacts extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'auto_increment' => TRUE],
            'id_vendor' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE],
            'type' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => true],
            'title' => ['type' => 'VARCHAR', 'constraint' => '30', 'null' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => '100'],
            'code' => ['type' => 'VARCHAR', 'constraint' => '10', 'null' => true],
            'contact' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => true],
            'email' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => true],
            'is_primary' => ['type' => 'INT', 'constraint' => 1, 'default' => 0],
            'description' => ['type' => 'TEXT', 'null' => TRUE],
            'created_at' => ['type' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'],
        ])->add_field('CONSTRAINT fk_vendor_contact_vendor FOREIGN KEY (id_vendor) REFERENCES ref_vendors(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('ref_vendor_contacts');
        echo 'Migrate Migration_Create_table_ref_vendor_contacts' . PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_table('ref_vendor_contacts',TRUE);
        echo 'Rollback Migration_Create_table_ref_vendor_contacts' . PHP_EOL;
    }
}