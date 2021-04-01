<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class VendorLocationModel extends App_Model
{
    protected $table = 'ref_vendor_locations';

    /**
     * Get base query of table.
     *
     * @return CI_DB_query_builder
     */
    protected function getBaseQuery()
    {
        return parent::getBaseQuery()
            ->select([
                'ref_cities.province',
            ])
            ->join('ref_cities', 'ref_cities.city = ref_vendor_locations.city','left');
    }
}