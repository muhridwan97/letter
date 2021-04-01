<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Alter_requisitions_add_check_note
 * @property CI_DB_forge $dbforge
 */
class Migration_Alter_requisitions_add_check_note extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_column('requisitions', [
			'check_note' => ['type' => 'TEXT', 'null' => TRUE, 'after' => 'decision_maker_email'],
            'is_checked' => ['type' => 'INT', 'constraint' => 1, 'default' => 0,'after' => 'check_note'],
            'checked_at' => ['type' => 'TIMESTAMP ON UPDATE CURRENT_TIMESTAMP','after' => 'is_checked', 'null' => TRUE],
            'checked_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE,'after' => 'checked_at', 'null' => TRUE],
        ]);
        $requisitions = $this->db->select('*')
                ->from('requisitions')
                ->where('requisitions.approved_type','INTERNAL')
                ->or_where('requisitions.approved_type','');
        $requisitions = $requisitions->get()->result_array();
        $data = [];
        foreach ($requisitions as $requisition) {
            $data[] = ['id' => $requisition['id'], 'is_checked' => 1, 'check_note'=>'migration database'];
        }
        $this->db->update_batch('requisitions', $data,'id'); 
        echo 'Migrate Migration_Alter_requisitions_add_check_note' . PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_column('requisitions', 'check_note');
        $this->dbforge->drop_column('requisitions', 'is_checked');
        $this->dbforge->drop_column('requisitions', 'checked_at');
        $this->dbforge->drop_column('requisitions', 'checked_by');
        echo 'Rollback Migration_Alter_requisitions_add_check_note' . PHP_EOL;
    }
}
