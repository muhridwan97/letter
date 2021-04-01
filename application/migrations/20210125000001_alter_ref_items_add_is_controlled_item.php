<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Alter_ref_items_add_is_controlled_item
 * @property CI_DB_forge $dbforge
 */
class Migration_Alter_ref_items_add_is_controlled_item extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_column('ref_items', [
			'is_controlled_item' => ['type' => 'INT', 'constraint' => 1, 'default' => 1, 'after' => 'specification'],
        ]);

        echo 'Migrate Migration_Alter_ref_items_add_is_controlled_item' . PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_column('ref_items', 'is_controlled_item');
        echo 'Rollback Migration_Alter_ref_items_add_is_controlled_item' . PHP_EOL;
    }
}
