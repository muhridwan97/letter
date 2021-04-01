<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Seed_prv_permissions_add_set_check extends CI_Migration
{
    public function up()
    {
        $this->db->insert_batch('prv_permissions', [
            ['module' => 'purchasing', 'submodule' => 'requisition', 'permission' => PERMISSION_REQUISITION_SET_CHECK],
        ]);
        echo 'Migrate Migration_Seed_prv_permissions_add_set_check'. PHP_EOL;
    }

    public function down()
    {
        $this->db->delete('prv_permissions', ['permission' => PERMISSION_REQUISITION_SET_CHECK]);
        echo 'Rollback Migration_Seed_prv_permissions_add_set_check'. PHP_EOL;
    }
}