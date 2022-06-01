<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Alter_ref_lecturers_add_profile extends CI_Migration {

	public function up()
    {
        $this->dbforge->add_column('ref_lecturers', [
            'avatar' => ['type' => 'VARCHAR', 'constraint' => '300', 'null' => TRUE, 'after' => 'position'],
            'profile' => ['type' => 'TEXT', 'null' => TRUE, 'after' => 'avatar'],

        ]);
        echo 'Migrate Migration_Alter_ref_lecturers_add_profile'. PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_column('ref_lecturers', 'avatar');
        $this->dbforge->drop_column('ref_lecturers', 'profile');
        echo 'Rollback Migration_Alter_ref_lecturers_add_profile'. PHP_EOL;
    }

}