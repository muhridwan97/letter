<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Create_table_skripsis
 * @property CI_DB_forge $dbforge
 */
class Migration_Create_table_skripsis extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'auto_increment' => TRUE],
            'id_student' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'null' => TRUE],
            'id_lecturer' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'null' => TRUE],
            'judul' => ['type' => 'VARCHAR', 'constraint' => '200'],
            'status' => ['type' => 'ENUM("PENDING", "ACTIVE", "REJECTED")', 'default' => 'PENDING'],
            'description' => ['type' => 'TEXT', 'null' => TRUE],
            'is_deleted' => ['type' => 'INT', 'constraint' => 1, 'default' => 0],
            'created_at' => ['type' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'],
            'created_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE],
            'updated_at' => ['type' => 'TIMESTAMP ON UPDATE CURRENT_TIMESTAMP', 'null' => TRUE],
            'updated_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'null' => TRUE],
            'deleted_at' => ['type' => 'TIMESTAMP', 'null' => true],
            'deleted_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'null' => TRUE]
        ])
            ->add_field('CONSTRAINT fk_student_skripsi FOREIGN KEY (id_student) REFERENCES ref_students(id) ON DELETE CASCADE ON UPDATE CASCADE')
            ->add_field('CONSTRAINT fk_lecture_skripsi FOREIGN KEY (id_lecturer) REFERENCES ref_lecturers(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('skripsis');
        $this->db->insert_batch('prv_permissions', [

            [
                'module' => 'skripsi', 'submodule' => 'skripsi', 'permission' => PERMISSION_SKRIPSI_VIEW,
                'description' => 'View skripsi data'
            ],
            [
                'module' => 'skripsi', 'submodule' => 'skripsi', 'permission' => PERMISSION_SKRIPSI_CREATE,
                'description' => 'Create new skripsi'
            ],
            [
                'module' => 'skripsi', 'submodule' => 'skripsi', 'permission' => PERMISSION_SKRIPSI_EDIT,
                'description' => 'Edit skripsi'
            ],
            [
                'module' => 'skripsi', 'submodule' => 'skripsi', 'permission' => PERMISSION_SKRIPSI_DELETE,
                'description' => 'Delete skripsi'
            ],
        ]);
        echo 'Migrate Migration_Create_table_skripsis' . PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_table('skripsis');
        $this->db->delete('prv_permissions', ['module' => 'skripsi', 'submodule' => 'skripsi']);
        echo 'Rollback Migration_Create_table_skripsis' . PHP_EOL;
    }
}
