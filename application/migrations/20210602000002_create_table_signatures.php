<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Create_table_signatures
 * @property CI_DB_forge $dbforge
 */
class Migration_Create_table_signatures extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'auto_increment' => TRUE],
            'id_reference' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11],
            'id_lecturer' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11],
            'type' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => TRUE],
            'code' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => TRUE],
            'created_at' => ['type' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'],
            'created_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE]
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('signatures');
        echo 'Migrate Migration_Create_table_signatures' . PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_table('signatures');
        echo 'Rollback Migration_Create_table_signatures' . PHP_EOL;
    }
}
