<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Alter_purchase_orders_add_status
 * @property CI_DB_forge $dbforge
 */
class Migration_Alter_purchase_orders_add_status extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_column('purchase_orders', [
			'document_status' => ['type' => 'ENUM("DRAFT", "PUBLISHED", "CANCELED")', 'default' => 'PUBLISHED', 'after' => 'purchasing_supervisor'],
        ]);
        echo 'Migrate Migration_Alter_purchase_orders_add_status' . PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_column('purchase_orders', 'document_status');
        echo 'Rollback Migration_Alter_purchase_orders_add_status' . PHP_EOL;
    }
}
