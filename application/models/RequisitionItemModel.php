<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RequisitionItemModel extends App_Model
{
    protected $table = 'requisition_items';

    protected function getBaseQuery()
    {
        return parent::getBaseQuery()
            ->select([
				'IFNULL(ref_items.item_name, requisition_items.item_name) AS item_name',
				'IFNULL(ref_items.brand, requisition_items.brand) AS brand',
				'IFNULL(requisition_items.unit, ref_items.unit) AS unit',
                'ref_items.specification',
                'IFNULL(SUM(purchase_offer_items.quantity_selected), 0) AS total_selected'
            ])
            ->join('purchase_offer_items', 'purchase_offer_items.id_requisition_item = requisition_items.id', 'left')
            ->join('ref_items', 'ref_items.id = requisition_items.id_item', 'left')
            ->join('(SELECT * FROM purchase_offers WHERE is_deleted = false) AS purchase_offers', 'purchase_offers.id = purchase_offer_items.id_purchase_offer', 'left')
            ->group_by('requisition_items.id');
    }
}
