<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PurchaseOfferItemModel extends App_Model
{
    protected $table = 'purchase_offer_items';

    /**
     * Get base query of table.
     *
     * @return CI_DB_query_builder
     */
    protected function getBaseQuery()
    {
        return parent::getBaseQuery()
            ->select([
                '(purchase_offer_items.quantity * purchase_offer_items.price) AS extended_price',
                '(purchase_offer_items.quantity * IFNULL(purchase_offer_items.discount, 0)) AS extended_discount',
                '(purchase_offer_items.quantity * purchase_offer_items.price) - (IFNULL(purchase_offer_items.discount, 0) * purchase_offer_items.quantity) AS total',
                '(purchase_offer_items.quantity_selected * purchase_offer_items.price) AS selected_extended_price',
                '(purchase_offer_items.quantity_selected * IFNULL(purchase_offer_items.discount, 0)) AS selected_extended_discount',
                '(purchase_offer_items.quantity_selected * purchase_offer_items.price) - (IFNULL(purchase_offer_items.discount, 0) * purchase_offer_items.quantity_selected) AS selected_total',
				'IFNULL(ref_items.item_name, requisition_items.item_name) AS item_name',
				'IFNULL(ref_items.brand, requisition_items.brand) AS brand',
				'IFNULL(requisition_items.unit, ref_items.unit) AS unit',
				'ref_items.specification',
				'requisition_items.type',
                'requisition_items.quantity AS quantity_needed',
                'requisition_items.description AS item_description',
                'purchase_offers.id_vendor',
                'ref_vendors.vendor',
            ])
            ->join('purchase_offers', 'purchase_offers.id = purchase_offer_items.id_purchase_offer')
            ->join('ref_vendors', 'ref_vendors.id = purchase_offers.id_vendor')
            ->join('requisition_items', 'requisition_items.id = purchase_offer_items.id_requisition_item')
			->join('ref_items', 'ref_items.id = requisition_items.id_item', 'left');
    }
}
