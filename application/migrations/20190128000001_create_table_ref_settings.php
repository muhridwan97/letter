<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Create_table_ref_settings
 * @property CI_DB_forge $dbforge
 */
class Migration_Create_table_ref_settings extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(array(
            'key' => ['type' => 'VARCHAR', 'constraint' => '50'],
            'value' => ['type' => 'TEXT'],
            'description' => ['type' => 'VARCHAR', 'constraint' => '500', 'null' => true],
            'created_at' => ['type' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'],
            'created_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE],
            'updated_at' => ['type' => 'TIMESTAMP ON UPDATE CURRENT_TIMESTAMP', 'null' => true],
            'updated_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'null' => true],
        ));
        $this->dbforge->add_key('key', TRUE);
        $this->dbforge->create_table('ref_settings');
        echo 'Migrate Migration_Create_table_ref_settings' . PHP_EOL;

        $this->db->insert_batch('ref_settings', [
            [
                'key' => 'app_name',
                'value' => 'Purchasing',
            ],
            [
                'key' => 'meta_url',
                'value' => 'https://purchasing.transcon-indonesia.com',
            ],
            [
                'key' => 'meta_keywords',
                'value' => 'purchasing, employee, buy, product, goods, services',
            ],
            [
                'key' => 'meta_description',
                'value' => 'Purchasing management system for transcon\'s employee',
            ],
            [
                'key' => 'meta_author',
                'value' => 'Transcon Indonesia',
            ],
            [
                'key' => 'email_bug_report',
                'value' => 'bug@transcon-indonesia.com',
            ],
            [
                'key' => 'email_support',
                'value' => 'purchasing@transcon-indonesia.com',
            ],
            [
                'key' => 'company_name',
                'value' => 'Transcon Indonesia',
            ],
            [
                'key' => 'company_address',
                'value' => 'Jl. Denpasar Blok II No.1 dan 16 Kbn Marunda. Clincing Jakarta Utara',
            ],
            [
                'key' => 'company_contact',
                'value' => 'Telp: 021-44850578 Fax: 021-44850403',
            ],
            [
                'key' => 'purchasing_admin',
                'value' => '',
            ],
            [
                'key' => 'purchasing_supervisor',
                'value' => '',
            ],
        ]);
        echo '--Seeding Migration_Create_table_ref_settings' . PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_table('ref_settings');
        echo 'Rollback Migration_Create_table_ref_settings' . PHP_EOL;
    }
}