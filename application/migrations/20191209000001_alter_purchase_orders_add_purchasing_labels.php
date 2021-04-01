<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Alter_purchase_orders_add_purchasing_labels
 * @property CI_DB_forge $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_Alter_purchase_orders_add_purchasing_labels extends CI_Migration
{
	public function up()
	{
		$this->dbforge->add_column('purchase_orders', [
			'purchasing_admin' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => TRUE, 'after' => 'remark'],
			'purchasing_supervisor' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => TRUE, 'after' => 'purchasing_admin'],
		]);

		$admin = get_setting('purchasing_admin');
		$supervisor = get_setting('purchasing_supervisor');
		$this->db->update('purchase_orders', [
			'purchasing_admin' => $admin,
			'purchasing_supervisor' => $supervisor,
		]);

		echo 'Migrate Migration_Alter_purchase_orders_add_purchasing_labels' . PHP_EOL;
	}

	public function down()
	{
		$this->dbforge->drop_column('purchase_orders', 'purchasing_admin');
		$this->dbforge->drop_column('purchase_orders', 'purchasing_supervisor');
		echo 'Rollback Migration_Alter_purchase_orders_add_purchasing_labels' . PHP_EOL;
	}
}
