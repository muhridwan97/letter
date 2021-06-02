<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RepositoryModel extends App_Model
{
    protected $table = 'repositories';
    
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