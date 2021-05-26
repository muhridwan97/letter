<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Create_table_assignment_letters
 * @property CI_DB_forge $dbforge
 */
class Migration_Create_table_assignment_letters extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'auto_increment' => TRUE],
            'id_kaprodi' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'null' => TRUE],
            'id_letter_number' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'null' => TRUE],
            'email' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => TRUE],
            'date' => ['type' => 'DATE', 'null' => TRUE],
            'judul' => ['type' => 'TEXT', 'null' => TRUE],
            'description' => ['type' => 'TEXT', 'null' => TRUE],
            'is_deleted' => ['type' => 'INT', 'constraint' => 1, 'default' => 0],
            'created_at' => ['type' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'],
            'created_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE],
            'updated_at' => ['type' => 'TIMESTAMP ON UPDATE CURRENT_TIMESTAMP', 'null' => TRUE],
            'updated_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'null' => TRUE],
            'deleted_at' => ['type' => 'TIMESTAMP', 'null' => true],
            'deleted_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'null' => TRUE]
        ])
        ->add_field('CONSTRAINT fk_kaprodi_al FOREIGN KEY (id_kaprodi) REFERENCES ref_lecturers(id) ON DELETE CASCADE ON UPDATE CASCADE')
        ->add_field('CONSTRAINT fk_letter_number_al FOREIGN KEY (id_letter_number) REFERENCES letter_numbers(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('assignment_letters');
        echo 'Migrate Migration_Create_table_assignment_letters' . PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_table('assignment_letters');
        echo 'Rollback Migration_Create_table_assignment_letters' . PHP_EOL;
    }
}
