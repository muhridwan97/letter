<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CategoryModel extends App_Model
{
    protected $table = 'ref_categories';

    protected function getBaseQuery()
    {
        return parent::getBaseQuery()
            ->select([
                'COUNT(DISTINCT ref_vendor_categories.id_vendor) AS total_vendor'
            ])
            ->join('ref_vendor_categories', 'ref_vendor_categories.id_category = ref_categories.id', 'left')
            ->group_by('ref_categories.id');
    }
}