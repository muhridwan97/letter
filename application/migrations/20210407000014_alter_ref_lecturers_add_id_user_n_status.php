<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Alter_ref_lecturers_add_id_user_n_status extends CI_Migration {

	public function up()
    {
        $this->dbforge->add_column('ref_lecturers', [
            'id_user' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'null' => TRUE, 'after' => 'id'],
            'status' => ['type' => 'ENUM("ACTIVE", "INACTIVE")', 'default' => 'ACTIVE', 'after' => 'position'],
        ]);
        $this->dbforge->add_field('CONSTRAINT fk_lecturer_user FOREIGN KEY (id_user) REFERENCES prv_users(id) ON DELETE CASCADE ON UPDATE CASCADE');
        echo 'Migrate Migration_Alter_ref_lecturers_add__add_id_user_n_status'. PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_column('ref_lecturers', 'id_user');
        $this->dbforge->drop_column('ref_lecturers', 'status');
        echo 'Rollback Migration_Alter_ref_lecturers_add__add_id_user_n_status'. PHP_EOL;
    }

}