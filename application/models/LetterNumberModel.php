<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LetterNumberModel extends App_Model
{
    protected $table = 'letter_numbers';

    /**
     * Get base query of table.
     *
     * @return CI_DB_query_builder
     */
    public function getBaseQuery()
    {
        return parent::getBaseQuery();
    }

    /**
     * Get requisition number.
     *
     * @return string
     */
    public function getLetterNumber()
    {
        $letter_number = $this->db->query("
            SELECT CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(no_letter, '/', 1),'-',-1) AS UNSIGNED) AS order_number
            FROM letter_numbers
            WHERE MONTH(created_at) = MONTH(CURDATE())
                AND YEAR(created_at) = YEAR(CURDATE())
            ORDER BY order_number DESC
            LIMIT 1
        ");
        
        if ($letter_number->num_rows()) {
            $currentLastNumber = $letter_number->row_array();
            $nextOrder = intval($currentLastNumber['order_number']) + 1;
        } else {
            $nextOrder = 1;
        }

        return 'B-'.$nextOrder . '/Un.02/K.P.Fis/PP.01/' . date('m') . '/' . date('Y');
    }
}
