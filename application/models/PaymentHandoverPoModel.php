<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PaymentHandoverPoModel extends App_Model
{
    protected $table = 'payment_handover_po';

    /**
     * Get base query of table.
     *
     * @return CI_DB_query_builder
     */
    protected function getBaseQuery()
    {
        return parent::getBaseQuery()
            ->select([
                'requisitions.id AS id_requisition',
                'requisitions.no_requisition',
                'requisitions.request_title',
                'ref_employees.id AS id_employee',
                'ref_employees.name AS employee_name',
                'ref_employees.contact_mobile',
                'ref_categories.category',
                'purchase_offers.id AS id_purchase_offer',
				'purchase_offers.updated_at AS last_updated_offer',
                'ref_vendors.vendor',
                'SUM(purchase_offer_items.quantity_selected) AS total_item_quantity',
                'purchase_orders.id AS id_purchase_order',
                'purchase_orders.no_purchase',
                'purchase_orders.handover_note',
                'purchase_orders.received_date',
                'purchase_orders.received_date_user',
                'purchase_orders.receiving_note',
                'purchase_offers.status',
                'purchase_orders.rating',
                'purchase_orders.rating_user',
                'purchase_orders.created_at',
            ])
            ->join('purchase_orders', 'purchase_orders.id = payment_handover_po.id_purchase_order', 'left')
            ->join('purchase_offers', 'purchase_offers.id = purchase_orders.id_purchase_offer', 'left')
            ->join('requisitions', 'requisitions.id = purchase_offers.id_requisition', 'left')
            ->join(EmployeeModel::$tableEmployee, 'ref_employees.id = requisitions.id_employee', 'left')
            ->join('ref_categories', 'ref_categories.id = requisitions.id_category', 'left')
            ->join('ref_vendors', 'ref_vendors.id = purchase_offers.id_vendor', 'left')
            ->join('purchase_offer_items', 'purchase_offer_items.id_purchase_offer = purchase_offers.id', 'left')
            ->join('requisition_items', 'requisition_items.id = purchase_offer_items.id_requisition_item', 'left')
            ->where_in('purchase_orders.document_status', [
                PurchaseOrderModel::STATUS_PUBLISHED,
            ])
            ->group_by('purchase_orders.id');
    }

    public function getPoNotHandoverYet(){
        return $this->db
            ->select([
                'requisitions.id AS id_requisition',
                'requisitions.no_requisition',
                'requisitions.request_title',
                'ref_employees.id AS id_employee',
                'ref_employees.name AS employee_name',
                'ref_employees.contact_mobile',
                'ref_categories.category',
                'purchase_offers.id AS id_purchase_offer',
                'purchase_offers.updated_at AS last_updated_offer',
                'ref_vendors.vendor',
                'SUM(purchase_offer_items.quantity_selected) AS total_item_quantity',
                'purchase_orders.id AS id_purchase_order',
                'purchase_orders.no_purchase',
                'purchase_orders.handover_note',
                'purchase_orders.received_date',
                'purchase_orders.received_date_user',
                'purchase_orders.receiving_note',
                'purchase_offers.status',
                'purchase_orders.rating',
                'purchase_orders.rating_user',
                'purchase_orders.created_at',
            ])
            ->from('purchase_orders')
            ->join('payment_handover_po', 'purchase_orders.id = payment_handover_po.id_purchase_order', 'left')
            ->join('purchase_offers', 'purchase_offers.id = purchase_orders.id_purchase_offer', 'left')
            ->join('requisitions', 'requisitions.id = purchase_offers.id_requisition', 'left')
            ->join(EmployeeModel::$tableEmployee, 'ref_employees.id = requisitions.id_employee', 'left')
            ->join('ref_categories', 'ref_categories.id = requisitions.id_category', 'left')
            ->join('ref_vendors', 'ref_vendors.id = purchase_offers.id_vendor', 'left')
            ->join('purchase_offer_items', 'purchase_offer_items.id_purchase_offer = purchase_offers.id', 'left')
            ->join('requisition_items', 'requisition_items.id = purchase_offer_items.id_requisition_item', 'left')
            ->where_in('purchase_orders.document_status', [
                PurchaseOrderModel::STATUS_PUBLISHED,
            ])
            ->where('payment_handover_po.id is null')
            ->group_by('purchase_orders.id')
            ->order_by('purchase_orders.id','desc')
            ->get()->result_array();
    }

}
