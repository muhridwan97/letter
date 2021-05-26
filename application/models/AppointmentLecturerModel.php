<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AppointmentLecturerModel extends App_Model
{
    protected $table = 'appointment_lecturers';

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
