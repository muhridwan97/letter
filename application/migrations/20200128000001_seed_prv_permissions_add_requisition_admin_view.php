<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Seed_prv_permissions_add_requisition_admin_view extends CI_Migration
{
    public function up()
    {
        $this->db->insert_batch('prv_permissions', [
            ['module' => 'purchasing', 'submodule' => 'requisition', 'permission' => PERMISSION_REQUISITION_ADMIN_VIEW],
        ]);
        echo 'Migrate Migration_Seed_prv_permissions_add_requisition_admin_view'. PHP_EOL;
    }

    public function down()
    {
        $this->db->delete('prv_permissions', ['permission' => PERMISSION_REQUISITION_ADMIN_VIEW]);
        echo 'Rollback Migration_Seed_prv_permissions_add_requisition_admin_view'. PHP_EOL;
    }
}