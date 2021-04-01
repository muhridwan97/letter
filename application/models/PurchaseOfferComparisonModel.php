<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PurchaseOfferComparisonModel extends App_Model
{
    protected $table = 'purchase_offer_comparisons';

    /**
     * Get base query of table.
     *
     * @return CI_DB_query_builder
     */
    protected function getBaseQuery()
    {
        return parent::getBaseQuery()
            ->select([
                'purchase_offers.id_vendor',
                'ref_vendors.vendor',
            ])
            ->join('purchase_offers', 'purchase_offers.id = purchase_offer_comparisons.id_purchase_offer')
            ->join('ref_vendors', 'ref_vendors.id = purchase_offers.id_vendor');
    }

    public function getComparisonItem($idPO){
        return parent::getBaseQuery()
            ->group_by('comparison_item')
            ->where('id_purchase_offer',$idPO)
            ->order_by('id','asc')
            ->get()->result_array();
    }
}
