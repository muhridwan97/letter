<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Create_table_logbooks
 * @property CI_DB_forge $dbforge
 */
class Migration_Create_table_logbooks extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'auto_increment' => TRUE],
            'id_skripsi' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'null' => TRUE],
            'tanggal' => ['type' => 'DATE', 'null' => TRUE],
            'konsultasi' => ['type' => 'VARCHAR', 'constraint' => '50'],
            'status' => ['type' => 'ENUM("PENDING", "VALIDATED", "REJECTED")', 'default' => 'PENDING'],
            'description' => ['type' => 'TEXT', 'null' => TRUE],
            'is_deleted' => ['type' => 'INT', 'constraint' => 1, 'default' => 0],
            'created_at' => ['type' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'],
            'created_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE],
            'updated_at' => ['type' => 'TIMESTAMP ON UPDATE CURRENT_TIMESTAMP', 'null' => TRUE],
            'updated_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'null' => TRUE],
            'deleted_at' => ['type' => 'TIMESTAMP', 'null' => true],
            'deleted_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'null' => TRUE]
        ])
            ->add_field('CONSTRAINT fk_skripsi_logbook FOREIGN KEY (id_skripsi) REFERENCES skripsis(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('logbooks');
        $this->db->insert_batch('prv_permissions', [

            [
                'module' => 'skripsi', 'submodule' => 'logbook', 'permission' => PERMISSION_LOGBOOK_VIEW,
                'description' => 'View skripsi data'
            ],
            [
                'module' => 'skripsi', 'submodule' => 'logbook', 'permission' => PERMISSION_LOGBOOK_CREATE,
                'description' => 'Create new skripsi'
            ],
            [
                'module' => 'skripsi', 'submodule' => 'logbook', 'permission' => PERMISSION_LOGBOOK_EDIT,
                'description' => 'Edit skripsi'
            ],
            [
                'module' => 'skripsi', 'submodule' => 'logbook', 'permission' => PERMISSION_LOGBOOK_DELETE,
                'description' => 'Delete skripsi'
            ],
            [
                'module' => 'skripsi', 'submodule' => 'logbook', 'permission' => PERMISSION_LOGBOOK_VALIDATE,
                'description' => 'Validate skripsi'
            ],
        ]);
        echo 'Migrate Migration_Create_table_logbooks' . PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_table('logbooks');
        $this->db->delete('prv_permissions', ['module' => 'skripsi', 'submodule' => 'logbook']);
        echo 'Rollback Migration_Create_table_logbooks' . PHP_EOL;
    }
}
