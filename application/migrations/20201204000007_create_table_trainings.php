<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Create_table_trainings
 * @property CI_DB_forge $dbforge
 */
class Migration_Create_table_trainings extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'auto_increment' => TRUE],
            'id_employee' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'null' => TRUE],
            'id_curriculum' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'null' => TRUE],
			'status' => ['type' => 'VARCHAR', 'constraint' => '50'],
            'description' => ['type' => 'TEXT', 'null' => TRUE],
            'is_deleted' => ['type' => 'INT', 'constraint' => 1, 'default' => 0],
            'created_at' => ['type' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'],
            'created_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE],
            'updated_at' => ['type' => 'TIMESTAMP ON UPDATE CURRENT_TIMESTAMP', 'null' => TRUE],
            'updated_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'null' => TRUE],
            'deleted_at' => ['type' => 'TIMESTAMP', 'null' => true],
            'deleted_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'null' => TRUE]
        ])
			//->add_field('CONSTRAINT fk_training_employee FOREIGN KEY (id_employee) REFERENCES ref_employees(id) ON DELETE CASCADE ON UPDATE CASCADE')
			->add_field('CONSTRAINT fk_training_curriculum FOREIGN KEY (id_curriculum) REFERENCES curriculums(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('trainings');
        echo 'Migrate Migration_Create_table_trainings' . PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_table('trainings');
        echo 'Rollback Migration_Create_table_trainings' . PHP_EOL;
    }
}
