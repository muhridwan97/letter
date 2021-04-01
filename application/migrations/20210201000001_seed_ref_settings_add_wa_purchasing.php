<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Seed_ref_settings_add_wa_purchasing
 * @property CI_DB_forge $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_Seed_ref_settings_add_wa_purchasing extends CI_Migration
{
    public function up()
    {
        $this->db->insert_batch('ref_settings', [
            ['key' => 'wa_purchasing', 'value' => ''],
        ]);
        echo 'Migrate Migration_Seed_ref_settings_add_wa_purchasing'. PHP_EOL;
    }

    public function down()
    {
        $this->db->delete('ref_settings', ['key' => 'wa_purchasing']);
        echo 'Rollback Migration_Seed_ref_settings_add_wa_purchasing'. PHP_EOL;
    }
}
