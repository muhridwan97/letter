<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LogbookModel extends App_Model
{
    protected $table = 'ref_logbooks';
    
    const STATUS_PENDING = 'PENDING';
    const STATUS_VALIDATE = 'VALIDATE';
    const STATUS_REJECTED = 'REJECTED';
}