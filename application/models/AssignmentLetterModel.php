<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AssignmentLetterModel extends App_Model
{
    protected $table = 'assignment_letters';

    /**
     * Get base query of table.
     *
     * @return CI_DB_query_builder
     */
    public function getBaseQuery()
    {
        return parent::getBaseQuery()
                ->select('letter_numbers.no_letter')
                ->join('letter_numbers','letter_numbers.id = assignment_letters.id_letter_number','left');
    }

}
