<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Create_table_exam_exercises
 * @property CI_DB_forge $dbforge
 */
class Migration_Create_table_exam_exercises extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'auto_increment' => TRUE],
            'id_exam' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'null' => TRUE],
            'id_exercise' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'null' => TRUE],
			'title' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => TRUE],
			'started_at' => ['type' => 'DATETIME', 'null' => TRUE],
			'finished_at' => ['type' => 'DATETIME', 'null' => TRUE],
			'status' => ['type' => 'VARCHAR', 'constraint' => '50', 'default' => 'PENDING'],
            'description' => ['type' => 'TEXT', 'null' => TRUE],
            'is_deleted' => ['type' => 'INT', 'constraint' => 1, 'default' => 0],
            'created_at' => ['type' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'],
            'created_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE],
            'updated_at' => ['type' => 'TIMESTAMP ON UPDATE CURRENT_TIMESTAMP', 'null' => TRUE],
            'updated_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'null' => TRUE],
            'deleted_at' => ['type' => 'TIMESTAMP', 'null' => true],
            'deleted_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'null' => TRUE]
        ])
			->add_field('CONSTRAINT fk_exam_exercise_exam FOREIGN KEY (id_exam) REFERENCES exams(id) ON DELETE CASCADE ON UPDATE CASCADE')
			->add_field('CONSTRAINT fk_exam_exercises_exercise FOREIGN KEY (id_exercise) REFERENCES exercises(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('exam_exercises');
        echo 'Migrate Migration_Create_table_exam_exercises' . PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_table('exam_exercises');
        echo 'Rollback Migration_Create_table_exam_exercises' . PHP_EOL;
    }
}
