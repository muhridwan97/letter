<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Create_table_course_eliminations
 * @property CI_DB_forge $dbforge
 */
class Migration_Create_table_course_eliminations extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'auto_increment' => TRUE],
            'id_kaprodi' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'null' => TRUE],
            'id_pembimbing' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'null' => TRUE],
            'email' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => TRUE],
            'name' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => TRUE],
            'nim' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => TRUE],
            'sks' => ['type' => 'INT', 'constraint' => 5, 'null' => TRUE],
            'sks_pilihan' => ['type' => 'INT', 'constraint' => 5, 'null' => TRUE],
            'date' => ['type' => 'DATE', 'null' => TRUE],
            'description' => ['type' => 'TEXT', 'null' => TRUE],
            'is_deleted' => ['type' => 'INT', 'constraint' => 1, 'default' => 0],
            'created_at' => ['type' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'],
            'created_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE],
            'updated_at' => ['type' => 'TIMESTAMP ON UPDATE CURRENT_TIMESTAMP', 'null' => TRUE],
            'updated_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'null' => TRUE],
            'deleted_at' => ['type' => 'TIMESTAMP', 'null' => true],
            'deleted_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'null' => TRUE]
        ])
        ->add_field('CONSTRAINT fk_kaprodi_ce FOREIGN KEY (id_kaprodi) REFERENCES ref_lecturer(id) ON DELETE CASCADE ON UPDATE CASCADE')
        ->add_field('CONSTRAINT fk_pembimbing_ce FOREIGN KEY (id_pembimbing) REFERENCES ref_lecturer(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('course_eliminations');
        echo 'Migrate Migration_Create_table_course_eliminations' . PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_table('course_eliminations');
        echo 'Rollback Migration_Create_table_course_eliminations' . PHP_EOL;
    }
}
