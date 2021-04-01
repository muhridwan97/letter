<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class VendorItemCategoryModel extends App_Model
{
    protected $table = 'ref_vendor_item_categories';

    /**
     * Get base query of table.
     *
     * @return CI_DB_query_builder
     */
    protected function getBaseQuery()
    {
        return parent::getBaseQuery()
            ->select([
                'ref_item_categories.item_name',
                'ref_item_categories.description',
            ])
            ->join('ref_item_categories', 'ref_item_categories.id = ref_vendor_item_categories.id_item_category');
    }
}