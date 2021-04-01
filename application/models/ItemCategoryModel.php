<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ItemCategoryModel extends App_Model
{
    protected $table = 'ref_item_categories';
	protected $filteredFields = ['*', 'ref_categories.category'];

    protected function getBaseQuery()
    {
        return parent::getBaseQuery()
            ->select([
                'ref_categories.category AS category_name',
                'COUNT(DISTINCT ref_vendor_item_categories.id_vendor) AS total_vendor'
            ])
            ->join('ref_categories', 'ref_item_categories.id_category = ref_categories.id', 'left')
            ->join('ref_vendor_item_categories', 'ref_vendor_item_categories.id_item_category = ref_item_categories.id', 'left')
            ->group_by('ref_item_categories.id');
    }

    /**
     * Get item code.
     *
     * @return string
     */
    public function getItemCode($type = 'TM')
    {
        $orderData = $this->db->query("
            SELECT CAST(RIGHT(item_code, 6) AS UNSIGNED) + 1 AS order_number 
            FROM ref_item_categories
            WHERE SUBSTRING(item_code, 1, 2) = '$type'
            ORDER BY CAST(RIGHT(item_code, 6) AS UNSIGNED) DESC LIMIT 1
            ");
        $orderPad = '000001';
        if ($orderData->num_rows()) {
            $lastOrder = $orderData->row_array();
            $orderPad = str_pad($lastOrder['order_number'], 6, '0', STR_PAD_LEFT);
        }
        return $type . $orderPad;
    }
}
