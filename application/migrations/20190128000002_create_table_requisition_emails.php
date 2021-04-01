<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Create_table_requisition_emails
 * @property CI_DB_forge $dbforge
 */
class Migration_Create_table_requisition_emails extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'auto_increment' => TRUE],
            'id_requisition' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11],
            'id_vendor' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11],
            'email' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => TRUE],
            'sent_at' => ['type' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'],
            'sent_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE],
        ])
            ->add_field('CONSTRAINT fk_requisition_email_requisition FOREIGN KEY (id_requisition) REFERENCES requisitions(id) ON DELETE CASCADE ON UPDATE CASCADE')
            ->add_field('CONSTRAINT fk_requisition_email_vendor FOREIGN KEY (id_vendor) REFERENCES ref_vendors(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('requisition_emails');
        echo 'Migrate Migration_Create_table_requisition_emails' . PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_table('requisition_emails');
        echo 'Rollback Migration_Create_table_requisition_emails' . PHP_EOL;
    }
}
