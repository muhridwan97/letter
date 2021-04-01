<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Create_table_ref_vendor_banks
 * @property CI_DB_forge $dbforge
 */
class Migration_Create_table_ref_vendor_banks extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'auto_increment' => TRUE],
            'id_vendor' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE],
            'bank' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => true],
            'account_name' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
            'account_number' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
            'is_primary' => ['type' => 'INT', 'constraint' => 1, 'default' => 0],
            'description' => ['type' => 'TEXT', 'null' => TRUE],
            'created_at' => ['type' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'],
        ])->add_field('CONSTRAINT fk_vendor_bank_vendor FOREIGN KEY (id_vendor) REFERENCES ref_vendors(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('ref_vendor_banks');
        echo 'Migrate Migration_Create_table_ref_vendor_banks' . PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_table('ref_vendor_banks',TRUE);
        echo 'Rollback Migration_Create_table_ref_vendor_banks' . PHP_EOL;
    }
}