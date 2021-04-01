<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Create_table_purchase_offer_comparisons
 * @property CI_DB_forge $dbforge
 */
class Migration_Create_table_purchase_offer_comparisons extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'auto_increment' => TRUE],
            'id_requisition' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'null' => TRUE],
            'id_purchase_offer' => ['type' => 'INT', 'unsigned' => TRUE, 'constraint' => 11, 'null' => TRUE],
            'comparison_item' => ['type' => 'VARCHAR', 'constraint' => '100'],
            'description' => ['type' => 'TEXT', 'null' => TRUE],
            'created_at' => ['type' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'],
        ])
            ->add_field('CONSTRAINT fk_purchase_offer_comparison_requisition FOREIGN KEY (id_purchase_offer) REFERENCES purchase_offers(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('purchase_offer_comparisons');
        echo 'Migrate Migration_Create_table_purchase_offer_comparisons' . PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_table('purchase_offer_comparisons');
        echo 'Rollback Migration_Create_table_purchase_offer_comparisons' . PHP_EOL;
    }
}