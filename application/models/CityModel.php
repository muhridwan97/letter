<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CityModel extends App_Model
{
    protected $table = 'ref_cities';

    protected function getBaseQuery()
    {
        return parent::getBaseQuery();
    }

    public function getProvince(){
        $province = $this->db->select([
                            "ref_cities.province",
                        ])
                        ->from($this->table)
                        ->group_by('ref_cities.province');
        return $province->get()->result_array();
    }
}