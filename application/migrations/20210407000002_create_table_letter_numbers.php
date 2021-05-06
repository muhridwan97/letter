<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Create_table_letter_numbers
 * @property CI_DB_forge $dbforge
 */
class Migration_Create_table_letter_numbers extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'auto_increment' => TRUE],
            'no_letter' => ['type' => 'VARCHAR', 'constraint' => '100', 'unique' => TRUE],
            'created_at' => ['type' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'],
            'created_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('letter_numbers');
        echo 'Migrate Migration_Create_table_letter_numbers' . PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_table('letter_numbers');
        echo 'Rollback Migration_Create_table_letter_numbers' . PHP_EOL;
    }
}
