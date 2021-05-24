<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Create_table_course_elimination_courses
 * @property CI_DB_forge $dbforge
 */
class Migration_Create_table_course_elimination_courses extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'auto_increment' => TRUE],
            'id_course_elimination' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'null' => TRUE],
            'mata_kuliah' => ['type' => 'VARCHAR', 'constraint' => '200'],
            'sks' => ['type' => 'INT', 'constraint' => 11, 'null' => TRUE],
            'nilai' => ['type' => 'VARCHAR', 'constraint' => '50'],
            'description' => ['type' => 'TEXT', 'null' => TRUE],
            'created_at' => ['type' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'],
            'created_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE],
        ])
        ->add_field('CONSTRAINT fk_course_elimination_matkul FOREIGN KEY (id_course_elimination) REFERENCES course_eliminations(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('course_elimination_courses');
        echo 'Migrate Migration_Create_table_course_elimination_courses' . PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_table('course_elimination_courses');
        echo 'Rollback Migration_Create_table_course_elimination_courses' . PHP_EOL;
    }
}
