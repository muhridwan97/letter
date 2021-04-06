<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Create_table_exercises
 * @property CI_DB_forge $dbforge
 */
class Migration_Create_table_exercises extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'auto_increment' => TRUE],
			'type' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => TRUE],
			'id_reference' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'null' => TRUE],
            'exercise_title' => ['type' => 'VARCHAR', 'constraint' => '100'],
			'category' => ['type' => 'ENUM("CHOICES", "ESSAY", "PRACTICE")'],
			'question_sequence' => ['type' => 'ENUM("RANDOM", "IN ORDER")'],
			'duration' => ['type' => 'TIME', 'null' => TRUE],
            'description' => ['type' => 'TEXT', 'null' => TRUE],
            'is_deleted' => ['type' => 'INT', 'constraint' => 1, 'default' => 0],
            'created_at' => ['type' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'],
            'created_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE],
            'updated_at' => ['type' => 'TIMESTAMP ON UPDATE CURRENT_TIMESTAMP', 'null' => TRUE],
            'updated_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'null' => TRUE],
            'deleted_at' => ['type' => 'TIMESTAMP', 'null' => true],
            'deleted_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'null' => TRUE]
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('exercises');
        echo 'Migrate Migration_Create_table_exercises' . PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_table('exercises');
        echo 'Rollback Migration_Create_table_exercises' . PHP_EOL;
    }
}
