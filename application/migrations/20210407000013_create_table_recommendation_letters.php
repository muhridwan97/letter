<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Create_table_recommendation_letters
 * @property CI_DB_forge $dbforge
 */
class Migration_Create_table_recommendation_letters extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'auto_increment' => TRUE],
            'id_kaprodi' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'null' => TRUE],
            'id_letter_number' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'null' => TRUE],
            'email' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => TRUE],
            'nama_dosen' => ['type' => 'VARCHAR', 'constraint' => '100'],
            'jabatan' => ['type' => 'VARCHAR', 'constraint' => '100'],
            'prodi' => ['type' => 'VARCHAR', 'constraint' => '100'],
            'nama_mahasiswa' => ['type' => 'VARCHAR', 'constraint' => '100'],
            'nim' => ['type' => 'VARCHAR', 'constraint' => '50'],
            'rekomendasi' => ['type' => 'TEXT', 'null' => TRUE],
            'date' => ['type' => 'DATE', 'null' => TRUE],
            'description' => ['type' => 'TEXT', 'null' => TRUE],
            'is_deleted' => ['type' => 'INT', 'constraint' => 1, 'default' => 0],
            'created_at' => ['type' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'],
            'created_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE],
            'updated_at' => ['type' => 'TIMESTAMP ON UPDATE CURRENT_TIMESTAMP', 'null' => TRUE],
            'updated_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'null' => TRUE],
            'deleted_at' => ['type' => 'TIMESTAMP', 'null' => true],
            'deleted_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'null' => TRUE]
        ])
        ->add_field('CONSTRAINT fk_kaprodi_rl FOREIGN KEY (id_kaprodi) REFERENCES ref_lecturers(id) ON DELETE CASCADE ON UPDATE CASCADE')
        ->add_field('CONSTRAINT fk_letter_number_rl FOREIGN KEY (id_letter_number) REFERENCES letter_numbers(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('recommendation_letters');
        echo 'Migrate Migration_Create_table_recommendation_letters' . PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_table('recommendation_letters');
        echo 'Rollback Migration_Create_table_recommendation_letters' . PHP_EOL;
    }
}
