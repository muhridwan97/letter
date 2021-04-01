<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Create_table_ref_vendors
 * @property CI_DB_forge $dbforge
 */
class Migration_Create_table_ref_vendors extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'auto_increment' => TRUE],
            'vendor' => ['type' => 'VARCHAR', 'constraint' => '50'],
            'address' => ['type' => 'VARCHAR', 'constraint' => '200', 'null' => true],
            'city' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => true],
            'has_tax_number' => ['type' => 'INT', 'constraint' => 1, 'default' => 0],
            'tax_number' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => true],
            'tax_file' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => true],
            'is_pkp' => ['type' => 'INT', 'constraint' => 1, 'default' => 0],
            'description' => ['type' => 'TEXT', 'null' => true],
            'is_deleted' => ['type' => 'INT', 'constraint' => 1, 'default' => 0],
            'created_at' => ['type' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'],
            'created_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'updated_at' => ['type' => 'TIMESTAMP ON UPDATE CURRENT_TIMESTAMP', 'null' => true],
            'updated_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'deleted_at' => ['type' => 'TIMESTAMP', 'null' => true],
            'deleted_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true]
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('ref_vendors');
        echo 'Migrate Migration_Create_table_ref_vendors' . PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_table('ref_vendors',TRUE);
        echo 'Rollback Migration_Create_table_ref_vendors' . PHP_EOL;
    }
}