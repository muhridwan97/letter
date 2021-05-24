<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CollegePermitStudentModel extends App_Model
{
    protected $table = 'college_permit_students';

    /**
     * Get base query of table.
     *
     * @return CI_DB_query_builder
     */
    public function getBaseQuery()
    {
        return parent::getBaseQuery();
    }

}
