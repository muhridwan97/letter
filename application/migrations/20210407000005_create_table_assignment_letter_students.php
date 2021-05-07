<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Create_table_assignment_letter_students
 * @property CI_DB_forge $dbforge
 */
class Migration_Create_table_assignment_letter_students extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'auto_increment' => TRUE],
            'id_assignment_letter' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'null' => TRUE],
            'name' => ['type' => 'VARCHAR', 'constraint' => '100'],
            'jabatan' => ['type' => 'VARCHAR', 'constraint' => '50'],
            'description' => ['type' => 'TEXT', 'null' => TRUE],
            'created_at' => ['type' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'],
            'created_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE],
        ])
        ->add_field('CONSTRAINT fk_assignment_letter_student FOREIGN KEY (id_assignment_letter) REFERENCES assignment_letters(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('assignment_letter_students');
        echo 'Migrate Migration_Create_table_assignment_letter_students' . PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_table('assignment_letter_students');
        echo 'Rollback Migration_Create_table_assignment_letter_students' . PHP_EOL;
    }
}
