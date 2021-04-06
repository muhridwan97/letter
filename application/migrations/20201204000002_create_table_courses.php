<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Create_table_courses
 * @property CI_DB_forge $dbforge
 */
class Migration_Create_table_courses extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'auto_increment' => TRUE],
            'id_curriculum' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'null' => TRUE],
            'course_title' => ['type' => 'VARCHAR', 'constraint' => '100'],
			'course_order' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'cover_image' => ['type' => 'VARCHAR', 'constraint' => '300', 'null' => TRUE],
            'status' => ['type' => 'ENUM("ACTIVE", "INACTIVE")', 'default' => 'ACTIVE'],
            'description' => ['type' => 'TEXT', 'null' => TRUE],
            'is_deleted' => ['type' => 'INT', 'constraint' => 1, 'default' => 0],
            'created_at' => ['type' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'],
            'created_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE],
            'updated_at' => ['type' => 'TIMESTAMP ON UPDATE CURRENT_TIMESTAMP', 'null' => TRUE],
            'updated_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'null' => TRUE],
            'deleted_at' => ['type' => 'TIMESTAMP', 'null' => true],
            'deleted_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'null' => TRUE]
        ])
			->add_field('CONSTRAINT fk_course_curriculum FOREIGN KEY (id_curriculum) REFERENCES curriculums(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('courses');
        echo 'Migrate Migration_Create_table_courses' . PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_table('courses');
        echo 'Rollback Migration_Create_table_courses' . PHP_EOL;
    }
}
