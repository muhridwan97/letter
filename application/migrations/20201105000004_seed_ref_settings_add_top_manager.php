<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Seed_ref_settings_add_top_manager
 * @property CI_DB_forge $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_Seed_ref_settings_add_top_manager extends CI_Migration
{
    public function up()
    {
        $this->db->insert_batch('ref_settings', [
            ['key' => 'top_manager', 'value' => ''],
        ]);
        echo 'Migrate Migration_Seed_ref_settings_add_top_manager'. PHP_EOL;
    }

    public function down()
    {
        $this->db->delete('ref_settings', ['key' => 'top_manager']);
        echo 'Rollback Migration_Seed_ref_settings_add_top_manager'. PHP_EOL;
    }
}
