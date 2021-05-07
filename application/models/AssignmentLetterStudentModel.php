<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AssignmentLetterStudentModel extends App_Model
{
    protected $table = 'assignment_letter_students';

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
