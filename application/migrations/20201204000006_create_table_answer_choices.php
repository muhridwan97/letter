<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Create_table_answer_choices
 * @property CI_DB_forge $dbforge
 */
class Migration_Create_table_answer_choices extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'auto_increment' => TRUE],
			'id_question' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11],
			'answer' => ['type' => 'TEXT'],
            'is_correct_answer' => ['type' => 'INT', 'constraint' => 1, 'default' => 0],
            'created_at' => ['type' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'],
            'updated_at' => ['type' => 'TIMESTAMP ON UPDATE CURRENT_TIMESTAMP', 'null' => TRUE],
        ])
			->add_field('CONSTRAINT fk_answer_choices FOREIGN KEY (id_question) REFERENCES questions(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('answer_choices');
        echo 'Migrate Migration_Create_table_answer_choices' . PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_table('answer_choices');
        echo 'Rollback Migration_Create_table_answer_choices' . PHP_EOL;
    }
}
