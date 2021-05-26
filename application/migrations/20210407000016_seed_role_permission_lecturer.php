<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Seed_role_permission_lecturer
 * @property CI_DB_query_builder $db
 */
class Migration_Seed_role_permission_lecturer extends CI_Migration
{
    public function up()
    {
        $this->db->insert_batch('prv_permissions', [

            [
                'module' => 'master', 'submodule' => 'lecturer', 'permission' => PERMISSION_LECTURER_VIEW,
                'description' => 'View lecturer data'
            ],
            [
                'module' => 'master', 'submodule' => 'lecturer', 'permission' => PERMISSION_LECTURER_CREATE,
                'description' => 'Create new lecturer'
            ],
            [
                'module' => 'master', 'submodule' => 'lecturer', 'permission' => PERMISSION_LECTURER_EDIT,
                'description' => 'Edit lecturer'
            ],
            [
                'module' => 'master', 'submodule' => 'lecturer', 'permission' => PERMISSION_LECTURER_DELETE,
                'description' => 'Delete lecturer'
            ],
        ]);

        echo '--Seeding Migration_Seed_role_permission_lecturer' . PHP_EOL;
    }

    public function down()
    {
        $this->db->delete('prv_permissions', ['module' => 'master', 'submodule' => 'lecturer']);

        echo 'Rollback Migration_Seed_role_permission_lecturer' . PHP_EOL;
    }
}
