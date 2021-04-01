<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Create_table_payment_handover_po
 * @property CI_DB_forge $dbforge
 */
class Migration_Create_table_payment_handover_po extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'auto_increment' => TRUE],
            'id_handover' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'null' => TRUE],
            'id_purchase_order' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'null' => TRUE],
            'vendor_invoice' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => true],
            'invoice_date' => ['type' => 'DATE', 'null' => TRUE],
            'description' => ['type' => 'TEXT', 'null' => TRUE],
            'created_at' => ['type' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'],
        ])
            ->add_field('CONSTRAINT fk_payment_handover FOREIGN KEY (id_handover) REFERENCES payment_handovers(id) ON DELETE CASCADE ON UPDATE CASCADE')
            ->add_field('CONSTRAINT fk_purchase_order_payment FOREIGN KEY (id_purchase_order) REFERENCES purchase_orders(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('payment_handover_po');
        echo 'Migrate Migration_Create_table_payment_handover_po' . PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_table('payment_handover_po');
        echo 'Rollback Migration_Create_table_payment_handover_po' . PHP_EOL;
    }
}