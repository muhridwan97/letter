<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Create_table_repositories
 * @property CI_DB_forge $dbforge
 */
class Migration_Create_table_repositories extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'auto_increment' => TRUE],
            'name' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => TRUE],
            'file' => ['type' => 'VARCHAR', 'constraint' => '500', 'null' => TRUE],
            'src' => ['type' => 'VARCHAR', 'constraint' => '500'],
            'url' => ['type' => 'VARCHAR', 'constraint' => '2083', null],
            'description' => ['type' => 'VARCHAR', 'constraint' => '500', 'null' => TRUE],
            'created_at' => ['type' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'],
            'created_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE]
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('repositories');
        $this->db->insert_batch('prv_permissions', [

            [
                'module' => 'repository', 'submodule' => 'repository', 'permission' => PERMISSION_REPOSITORY_VIEW,
                'description' => 'View repository data'
            ],
            [
                'module' => 'repository', 'submodule' => 'repository', 'permission' => PERMISSION_REPOSITORY_CREATE,
                'description' => 'Create new repository'
            ],
            [
                'module' => 'repository', 'submodule' => 'repository', 'permission' => PERMISSION_REPOSITORY_EDIT,
                'description' => 'Edit repository'
            ],
            [
                'module' => 'repository', 'submodule' => 'repository', 'permission' => PERMISSION_REPOSITORY_DELETE,
                'description' => 'Delete repository'
            ],
        ]);
        echo 'Migrate Migration_Create_table_repositories' . PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_table('repositories');
        $this->db->delete('prv_permissions', ['module' => 'skripsi', 'submodule' => 'repository']);
        echo 'Rollback Migration_Create_table_repositories' . PHP_EOL;
    }
}
