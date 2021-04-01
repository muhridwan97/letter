<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ItemModel extends App_Model
{
    protected $table = 'ref_items';

	/**
	 * Get item number.
	 *
	 * @return string
	 */
	public function getItemNumber()
	{
		$query = $this->db->query("
            SELECT CAST(RIGHT(no_item, 4) AS UNSIGNED) AS order_number
            FROM ref_items
            WHERE MONTH(created_at) = MONTH(CURDATE())
                AND YEAR(created_at) = YEAR(CURDATE())
            ORDER BY order_number DESC
            LIMIT 1
        ");
		if ($query->num_rows()) {
			$currentLastNumber = $query->row_array();
			$nextOrder = intval($currentLastNumber['order_number']) + 1;
		} else {
			$nextOrder = 1;
		}

		return 'ITM/' . date('Ym') . '/' . str_pad($nextOrder, 4, '0', STR_PAD_LEFT);
	}
}
