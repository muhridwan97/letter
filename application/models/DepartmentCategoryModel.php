<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DepartmentCategoryModel extends App_Model
{
    protected $table = 'ref_department_categories';

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
            ->join('ref_categories', 'ref_categories.id = ref_department_categories.id_category');
    }
}
