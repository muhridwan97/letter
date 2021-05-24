<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ApplicationLetterModel extends App_Model
{
    protected $table = 'application_letters';

    /**
     * Get base query of table.
     *
     * @return CI_DB_query_builder
     */
    public function getBaseQuery()
    {
        return parent::getBaseQuery()
                ->select('letter_numbers.no_letter')
                ->join('letter_numbers','letter_numbers.id = application_letters.id_letter_number','left');
    }

}
