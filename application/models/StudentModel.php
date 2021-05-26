<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class StudentModel extends App_Model
{
    protected $table = 'ref_students';
    
    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_INACTIVE = 'INACTIVE';
}