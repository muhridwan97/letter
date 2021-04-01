<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Alter_purchase_offers_add_status_ask_confirmation
 * @property CI_DB_forge $dbforge
 */
class Migration_Alter_purchase_offers_add_status_ask_confirmation extends CI_Migration
{
    public function up()
    {
        $this->dbforge->modify_column('purchase_offers', [
            'status' => ['type' => 'ENUM("ON REVIEW", "UNSELECTED", "SELECTED", "ORDERED", "ASK CONFIRMATION", "CONFIRMED", "UNCONFIRMED", "COMPLETED")', 'default' => 'ON REVIEW']
        ]);

        echo 'Migrate Migration_Alter_purchase_offers_add_status_ask_confirmation' . PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->modify_column('purchase_offers', [
            'status' => ['type' => 'ENUM("ON REVIEW", "UNSELECTED", "SELECTED", "ORDERED", "COMPLETED")', 'default' => 'ON REVIEW']
        ]);
        echo 'Rollback Migration_Alter_purchase_offers_add_status_ask_confirmation' . PHP_EOL;
    }
}