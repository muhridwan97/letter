<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Create_table_ref_department_categories
 * @property CI_DB_forge $dbforge
 */
class Migration_Create_table_ref_department_categories extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'auto_increment' => TRUE],
            'id_department' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE],
            'id_category' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE],
            'created_at' => ['type' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'],
        ])
            ->add_field('CONSTRAINT fk_department_category_category FOREIGN KEY (id_category) REFERENCES ref_categories(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('ref_department_categories');
        echo 'Migrate Migration_Create_table_ref_department_categories' . PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_table('ref_department_categories',TRUE);
        echo 'Rollback Migration_Create_table_ref_department_categories' . PHP_EOL;
    }
}