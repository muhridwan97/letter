<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Alter_ref_item_categories_add_is_reserved
 * @property CI_DB_forge $dbforge
 */
class Migration_Alter_ref_item_categories_add_is_reserved extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_column('ref_item_categories', [
			'is_reserved' => ['type' => 'INT', 'constraint' => 1, 'default' => 0, 'after' => 'description'],
        ]);

        echo 'Migrate Migration_Alter_ref_item_categories_add_is_reserved' . PHP_EOL;
    }

    public function down()
    {
        $this->dbforge->drop_column('ref_item_categories', 'is_reserved');
        echo 'Rollback Migration_Alter_ref_item_categories_add_is_reserved' . PHP_EOL;
    }
}
