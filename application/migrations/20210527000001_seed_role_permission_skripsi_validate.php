<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Seed_role_permission_skripsi_validate
 * @property CI_DB_query_builder $db
 */
class Migration_Seed_role_permission_skripsi_validate extends CI_Migration
{
    public function up()
    {
        $this->db->insert_batch('prv_permissions', [

            [
                'module' => 'skripsi', 'submodule' => 'skripsi', 'permission' => PERMISSION_SKRIPSI_VALIDATE,
                'description' => 'validate skripsi'
            ],
        ]);

        echo '--Seeding Migration_Seed_role_permission_skripsi_validate' . PHP_EOL;
    }

    public function down()
    {
        $this->db->delete('prv_permissions', ['module' => 'skripsi', 'submodule' => 'skripsi', 'permission'=> PERMISSION_SKRIPSI_VALIDATE]);

        echo 'Rollback Migration_Seed_role_permission_skripsi_validate' . PHP_EOL;
    }
}
