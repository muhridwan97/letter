<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Create_table_exam_exercise_answers
 * @property CI_DB_forge $dbforge
 */
class Migration_Create_table_exam_exercise_answers extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'auto_increment' => TRUE],
            'id_exam_exercise' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'null' => TRUE],
            'id_question' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'null' => TRUE],
			'question' => ['type' => 'TEXT'],
			'answer' => ['type' => 'TEXT', 'null' => TRUE],
			'attachment' => ['type' => 'VARCHAR', 'constraint' => '300', 'null' => TRUE],
			'score' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'null' => TRUE],
			'assessment_note' => ['type' => 'TEXT', 'null' => TRUE],
			'description' => ['type' => 'TEXT', 'null' => TRUE],
            'created_at' => ['type' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'],
        ])
			->add_field('CONSTRAINT fk_exam_exercise_answer_exam_exercise FOREIGN KEY (id_exam_exercise) REFERENCES exam_exercises(id) ON DELETE CASCADE ON UPDATE CASCADE')
			->add_field('CONSTRAINT fk_exam_exercise_answer_question FOREIGN KEY (id_question) REFERENCES questions(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('exam_exercise_answers');
        echo 'Migrate Migration_Create_table_exam_exercise_answers' . PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_table('exam_exercise_answers');
        echo 'Rollback Migration_Create_table_exam_exercise_answers' . PHP_EOL;
    }
}
