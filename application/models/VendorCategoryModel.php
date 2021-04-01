<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class VendorCategoryModel extends App_Model
{
    protected $table = 'ref_vendor_categories';

    /**
     * Get base query of table.
     *
     * @return CI_DB_query_builder
     */
    protected function getBaseQuery()
    {
        return parent::getBaseQuery()
            ->select([
                'ref_categories.category',
                'ref_categories.description',
            ])
            ->join('ref_categories', 'ref_categories.id = ref_vendor_categories.id_category');
    }
}