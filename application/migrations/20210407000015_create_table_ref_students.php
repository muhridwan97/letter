<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Create_table_ref_students
 * @property CI_DB_forge $dbforge
 */
class Migration_Create_table_ref_students extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'auto_increment' => TRUE],
            'id_user' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'null' => TRUE],
            'no_student' => ['type' => 'VARCHAR', 'constraint' => '50', 'unique' => TRUE],
            'name' => ['type' => 'VARCHAR', 'constraint' => '50'],
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
            ->add_field('CONSTRAINT fk_student_user FOREIGN KEY (id_user) REFERENCES prv_users(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('ref_students');
        $this->db->insert_batch('prv_permissions', [

            [
                'module' => 'master', 'submodule' => 'student', 'permission' => PERMISSION_STUDENT_VIEW,
                'description' => 'View student data'
            ],
            [
                'module' => 'master', 'submodule' => 'student', 'permission' => PERMISSION_STUDENT_CREATE,
                'description' => 'Create new student'
            ],
            [
                'module' => 'master', 'submodule' => 'student', 'permission' => PERMISSION_STUDENT_EDIT,
                'description' => 'Edit student'
            ],
            [
                'module' => 'master', 'submodule' => 'student', 'permission' => PERMISSION_STUDENT_DELETE,
                'description' => 'Delete student'
            ],
        ]);
        echo 'Migrate Migration_Create_table_ref_students' . PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_table('ref_students');
        $this->db->delete('prv_permissions', ['module' => 'master', 'submodule' => 'student']);
        echo 'Rollback Migration_Create_table_ref_students' . PHP_EOL;
    }
}
