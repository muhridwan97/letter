<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Alter_ref_students_add_id_pembimbing extends CI_Migration {

	public function up()
    {
        $this->dbforge->add_column('ref_students', [
            'id_pembimbing' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'null' => TRUE, 'after' => 'id_user'],
        ]);
        
        $this->dbforge->add_field('CONSTRAINT fk_pembimbing_student FOREIGN KEY (id_pembimbing) REFERENCES ref_lecturers(id) ON DELETE CASCADE ON UPDATE CASCADE');
        echo 'Migrate Migration_Alter_ref_students_add_id_pembimbing'. PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_column('ref_students', 'id_pembimbing');
        echo 'Rollback Migration_Alter_ref_students_add_id_pembimbing'. PHP_EOL;
    }

}