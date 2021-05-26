<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Alter_assignment_letter_students_add_nip extends CI_Migration {

	public function up()
    {
        $this->dbforge->add_column('assignment_letter_students', [
            'nip' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => TRUE, 'after' => 'name'],
        ]);;
        echo 'Migrate Migration_Alter_assignment_letter_students_add_nip'. PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_column('assignment_letter_students', 'nip');
        echo 'Rollback Migration_Alter_assignment_letter_students_add_nip'. PHP_EOL;
    }

}