<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class VendorOfferModel extends App_Model
{
    protected $table = 'ref_vendor_offers';

    /**
     * Get base query of table.
     *
     * @return CI_DB_query_builder
     */
    protected function getBaseQuery()
    {
        $this->addFilteredField([
            'ref_vendors.vendor'
        ]);

        return parent::getBaseQuery()
            ->select([
                'ref_vendors.vendor',
            ])
            ->join('ref_vendors', 'ref_vendors.id = ref_vendor_offers.id_vendor', 'left');
    }
}