<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Seed_table_ref_vendor_locations
 * @property CI_DB_forge $dbforge
 */
class Migration_Seed_table_ref_vendor_locations extends CI_Migration
{
    public function up()
    {
        $vendors = $this->db->select('*')
                    ->from('ref_vendors');
        $vendors = $vendors->get()->result_array();
        $data = [];
        foreach ($vendors as $vendor) {
            $data[] = ['id_vendor' => $vendor['id'], 'city' => $vendor['city'], 'address' => $vendor['address']];
        }
        $this->db->insert_batch('ref_vendor_locations', $data);        
        echo 'Migrate Migration_Seed_table_ref_vendor_locations' . PHP_EOL;
    }

    public function down()
    {
        echo 'Rollback Migration_Seed_table_ref_vendor_locations' . PHP_EOL;
    }
}