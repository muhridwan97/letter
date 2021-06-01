<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LecturerModel extends App_Model
{
    protected $table = 'ref_lecturers';
    public static $tableLecturer = 'ref_lecturers';
    
    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_INACTIVE = 'INACTIVE';

    /**
     * Get base query of table.
     *
     * @return CI_DB_query_builder
     */
    public function getBaseQuery()
    {
        return parent::getBaseQuery()
                ->select([
                    'prv_users.email',
                    ])
                ->join('prv_users','prv_users.id = ref_lecturers.id_user');
    }
}