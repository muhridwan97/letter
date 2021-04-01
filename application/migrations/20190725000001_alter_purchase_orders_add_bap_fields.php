<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Alter_purchase_orders_add_bap_fields
 * @property CI_DB_forge $dbforge
 */
class Migration_Alter_purchase_orders_add_bap_fields extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_column('purchase_orders', [
            'rating_user' => ['type' => 'INT', 'constraint' => 1, 'null' => TRUE, 'after' => 'rating'],
            'rating_user_reason' => ['type' => 'INT', 'constraint' => 1, 'null' => TRUE, 'after' => 'rating_user'],
            'handover_note' => ['type' => 'TEXT', 'null' => TRUE, 'after' => 'rating_user_reason'],
            'handover_date' => ['type' => 'DATE', 'null' => TRUE, 'after' => 'handover_note'],
            'receiving_note' => ['type' => 'TEXT', 'null' => TRUE, 'after' => 'handover_date'],
            'received_date' => ['type' => 'DATE', 'null' => TRUE, 'after' => 'receiving_note'],
            'received_date_user' => ['type' => 'DATE', 'null' => TRUE, 'after' => 'received_date'],
        ]);

        echo 'Migrate Migration_Alter_purchase_orders_add_bap_fields' . PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_column('purchase_orders', 'rating_user');
        $this->dbforge->drop_column('purchase_orders', 'rating_user_reason');
        $this->dbforge->drop_column('purchase_orders', 'received_date');
        $this->dbforge->drop_column('purchase_orders', 'handover_note');
        $this->dbforge->drop_column('purchase_orders', 'receiving_note');
        $this->dbforge->drop_column('purchase_orders', 'received_date_user');
        echo 'Rollback Migration_Alter_purchase_orders_add_bap_fields' . PHP_EOL;
    }
}