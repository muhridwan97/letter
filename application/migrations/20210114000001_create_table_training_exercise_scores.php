<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Create_table_training_exercise_scores
 * @property CI_DB_forge $dbforge
 */
class Migration_Create_table_training_exercise_scores extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'auto_increment' => TRUE],
            'id_training' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'null' => TRUE],
            'id_exercise' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'null' => TRUE],
			'score' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'null' => TRUE],
			'correct' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'null' => TRUE],
			'total_question' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'null' => TRUE],
            'created_at' => ['type' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'],
            'created_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE],
        ])
			->add_field('CONSTRAINT fk_training_exercise_score_training FOREIGN KEY (id_training) REFERENCES trainings(id) ON DELETE CASCADE ON UPDATE CASCADE')
			->add_field('CONSTRAINT fk_training_exercise_score_exercise FOREIGN KEY (id_exercise) REFERENCES exercises(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('training_exercise_scores');
        echo 'Migrate Migration_Create_table_training_exercise_scores' . PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_table('training_exercise_scores');
        echo 'Rollback Migration_Create_table_training_exercise_scores' . PHP_EOL;
    }
}
