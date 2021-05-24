<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Create_table_college_permit_students
 * @property CI_DB_forge $dbforge
 */
class Migration_Create_table_college_permit_students extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'auto_increment' => TRUE],
            'id_college_permit' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'null' => TRUE],
            'name' => ['type' => 'VARCHAR', 'constraint' => '100'],
            'nim' => ['type' => 'VARCHAR', 'constraint' => '50'],
            'description' => ['type' => 'TEXT', 'null' => TRUE],
            'created_at' => ['type' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'],
            'created_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE],
        ])
        ->add_field('CONSTRAINT fk_college_permit_student FOREIGN KEY (id_college_permit) REFERENCES college_permits(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('college_permit_students');
        echo 'Migrate Migration_Create_table_college_permit_students' . PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_table('college_permit_students');
        echo 'Rollback Migration_Create_table_college_permit_students' . PHP_EOL;
    }
}
