<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Alter_assignment_letters_add_tujuan extends CI_Migration {

	public function up()
    {
        $this->dbforge->add_column('assignment_letters', [
            'tujuan' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => TRUE, 'after' => 'email'],
            'tanggal_mulai' => ['type' => 'DATE', 'null' => TRUE, 'after' => 'judul'],
            'tanggal_selesai' => ['type' => 'DATE', 'null' => TRUE, 'after' => 'tanggal_mulai'],
            'tempat' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => TRUE, 'after' => 'tanggal_selesai'],
            'penyelenggara' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => TRUE, 'after' => 'tempat'],
        ]);;
        echo 'Migrate Migration_Alter_assignment_letters_add_tujuan'. PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_column('assignment_letters', 'tujuan');
        $this->dbforge->drop_column('assignment_letters', 'tanggal_mulai');
        $this->dbforge->drop_column('assignment_letters', 'tanggal_selesai');
        $this->dbforge->drop_column('assignment_letters', 'penyelenggara');
        $this->dbforge->drop_column('assignment_letters', 'tempat');
        echo 'Rollback Migration_Alter_assignment_letters_add_tujuan'. PHP_EOL;
    }

}