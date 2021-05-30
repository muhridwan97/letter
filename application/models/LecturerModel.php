<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LecturerModel extends App_Model
{
    protected $table = 'ref_lecturers';
    public static $tableLecturer = 'ref_lecturers';
    
    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_INACTIVE = 'INACTIVE';
}