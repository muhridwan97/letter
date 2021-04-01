<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PurchaseOfferModel extends App_Model
{
    protected $table = 'purchase_offers';

    const STATUS_ON_REVIEW = 'ON REVIEW';
    const STATUS_UNSELECTED = 'UNSELECTED';
    const STATUS_SELECTED = 'SELECTED';
    const STATUS_UPLOADED = 'UPLOADED';
    const STATUS_IN_PROCESS = 'IN PROCESS';
    const STATUS_ORDERED = 'ORDERED';
    const STATUS_ASK_CONFIRMATION = 'ASK CONFIRMATION';
    const STATUS_CONFIRMED = 'CONFIRMED';
    const STATUS_UNCONFIRMED = 'UNCONFIRMED';
    const STATUS_COMPLETED = 'COMPLETED';

    public function __construct()
    {
        parent::__construct();

        $this->addFilteredField([
            'requisitions.request_title',
            'ref_vendors.vendor',
        ]);
    }

    /**
     * Get base query of table.
     *
     * @return CI_DB_query_builder
     */
    protected function getBaseQuery()
    {
        $this->addFilteredField([
            'ref_vendors.vendor',
            'requisitions.no_requisition',
            'requisitions.request_title',
            'ref_categories.category',
        ]);

        return parent::getBaseQuery()
            ->select([
                'requisitions.no_requisition',
                'requisitions.request_title',
                'ref_categories.category',
                'ref_vendors.vendor',
                'vendor_contacts.name AS contact_person',
                'vendor_contacts.contact',
                'vendor_contacts.email',
                '(offer_discount_goods + offer_discount_services) AS offer_discount',
                'SUM(purchase_offer_items.quantity) AS total_item_quantity',
                'SUM(purchase_offer_items.quantity * purchase_offer_items.price) AS extended_price',
                'SUM(purchase_offer_items.quantity * IFNULL(purchase_offer_items.discount, 0)) AS extended_discount',
                'SUM((purchase_offer_items.quantity * purchase_offer_items.price) - (IFNULL(purchase_offer_items.discount, 0) * purchase_offer_items.quantity)) AS extended_total',
                'SUM((purchase_offer_items.quantity * purchase_offer_items.price) - (IFNULL(purchase_offer_items.discount, 0) * purchase_offer_items.quantity)) - offer_discount_goods - offer_discount_services AS total_price',
                'SUM(purchase_offer_items.quantity_selected) AS selected_total_item_quantity',
                'SUM(purchase_offer_items.quantity_selected * purchase_offer_items.price) AS selected_extended_price',
                'SUM(purchase_offer_items.quantity_selected * IFNULL(purchase_offer_items.discount, 0)) AS selected_extended_discount',
                'SUM(IF(requisition_items.type = "GOODS", (quantity_selected * price) - (IFNULL(discount, 0) * quantity_selected), 0)) AS selected_total_price_goods',
                'SUM(IF(requisition_items.type = "SERVICES" OR requisition_items.type = "LEASE", (quantity_selected * price) - (IFNULL(discount, 0) * quantity_selected), 0)) AS selected_total_price_services',
                'SUM((purchase_offer_items.quantity_selected * purchase_offer_items.price) - (IFNULL(purchase_offer_items.discount, 0) * purchase_offer_items.quantity_selected)) AS selected_extended_total',
                'SUM((purchase_offer_items.quantity_selected * purchase_offer_items.price) - (IFNULL(purchase_offer_items.discount, 0) * purchase_offer_items.quantity_selected)) - IF(purchase_offers.status != "ON REVIEW" AND purchase_offers.status != "UNSELECTED", offer_discount_goods + offer_discount_services, 0) AS selected_total_price',
                'creators.name AS creator_name',
                'purchase_orders.id AS id_purchase_order',
                'purchase_orders.id AS no_purchase',
                'purchase_orders.document_status',
            ])
            ->distinct()
            ->join('requisitions', 'requisitions.id = purchase_offers.id_requisition', 'left')
            ->join('ref_categories', 'ref_categories.id = requisitions.id_category', 'left')
            ->join('ref_vendors', 'ref_vendors.id = purchase_offers.id_vendor', 'left')
            ->join(UserModel::$tableUser . ' AS creators', 'creators.id = requisitions.created_by', 'left')
            ->join('(
                SELECT DISTINCT id_vendor, name, contact, email 
                FROM ref_vendor_contacts 
                WHERE is_primary = 1
                ) AS vendor_contacts', 'vendor_contacts.id_vendor = ref_vendors.id', 'left')
            ->join('purchase_offer_items', 'purchase_offer_items.id_purchase_offer = purchase_offers.id', 'left')
            ->join('requisition_items', 'requisition_items.id = purchase_offer_items.id_requisition_item', 'left')
            ->join('purchase_orders', 'purchase_orders.id_purchase_offer = purchase_offers.id', 'left')
            ->group_by('purchase_offers.id, vendor_contacts.name, vendor_contacts.contact, vendor_contacts.email, purchase_orders.id');
    }

    /**
     * Get difference offer from requisition and offers.
     *
     * @param $requisitionId
     * @return array
     */
    public function getDifferenceOfferItem($requisitionId)
    {
        $items = $this->db->select([
            'requisition_items.id',
			'IFNULL(ref_items.item_name, requisition_items.item_name) AS item_name',
            'requisition_items.quantity',
            'IFNULL(requisition_offers.quantity, 0) AS quantity_offer',
            'requisition_items.quantity - IFNULL(requisition_offers.quantity, 0)  AS difference_total'
        ])
            ->from('requisitions')
            ->join('requisition_items', 'requisition_items.id_requisition = requisitions.id')
			->join('ref_items', 'ref_items.id = requisition_items.id_item', 'left')
            ->join("(
                SELECT purchase_offer_items.id_requisition_item, SUM(quantity) AS quantity
                FROM purchase_offers
                INNER JOIN purchase_offer_items 
                    ON purchase_offer_items.id_purchase_offer = purchase_offers.id
                WHERE id_requisition = '{$requisitionId}' AND purchase_offers.is_deleted = 0
                GROUP BY id_requisition_item
            ) AS requisition_offers", 'requisition_offers.id_requisition_item = requisition_items.id', 'left')
            ->where('requisitions.id', $requisitionId)
            ->where('requisitions.is_deleted', false);

        return $items->get()->result_array();
    }

    /**
     * Get all data model.
     *
     * @param array $filters
     * @param bool $withTrashed
     * @return mixed
     */
    public function getAll($filters = [], $withTrashed = false)
    {
        $this->db->start_cache();

        $baseQuery = $this->getBaseQuery();

        if (!$withTrashed && $this->db->field_exists('is_deleted', $this->table)) {
            $baseQuery->where($this->table . '.is_deleted', false);
        }

        if (!empty($filters)) {
            if (key_exists('query', $filters) && $filters['query']) {
                return $baseQuery;
            }

            if (key_exists('search', $filters) && !empty($filters['search'])) {
                $baseQuery->group_start();
                foreach ($this->filteredFields as $filteredField) {
                    if ($filteredField == '*') {
                        $fields = $this->db->list_fields($this->table);
                        foreach ($fields as $field) {
                            $baseQuery->or_like($this->table . '.' . $field, trim($filters['search']));
                        }
                    } else {
                        $baseQuery->or_like($filteredField, trim($filters['search']));
                    }
                }
                $baseQuery->group_end();
            }

            if (key_exists('statuses', $filters) && !empty($filters['statuses'])) {
                $baseQuery->where_in($this->table . '.status', $filters['statuses']);
            }

            if (key_exists('vendors', $filters) && !empty($filters['vendors'])) {
                $baseQuery->where_in($this->table . '.id_vendor', $filters['vendors']);
            }

            if (key_exists('employees', $filters) && !empty($filters['employees'])) {
                $baseQuery->where_in('requisitions.id_employee', $filters['employees']);
            }

            if (key_exists('date_from', $filters) && !empty($filters['date_from'])) {
                $baseQuery->where($this->table . '.created_at>=', format_date($filters['date_from']));
            }

            if (key_exists('date_to', $filters) && !empty($filters['date_to'])) {
                $baseQuery->where($this->table . '.created_at<=', format_date($filters['date_to']));
            }
        }
        $this->db->stop_cache();

        if (key_exists('per_page', $filters) && !empty($filters['per_page'])) {
            $perPage = $filters['per_page'];
        } else {
            $perPage = 25;
        }

        if (key_exists('page', $filters) && !empty($filters['page'])) {
            $currentPage = $filters['page'];

            $totalData = $this->db->count_all_results();

            if (key_exists('sort_by', $filters) && !empty($filters['sort_by'])) {
                if (key_exists('order_method', $filters) && !empty($filters['order_method'])) {
                    $baseQuery->order_by($filters['sort_by'], $filters['order_method']);
                } else {
                    $baseQuery->order_by($filters['sort_by'], 'asc');
                }
            } else {
                $baseQuery->order_by($this->table . '.' . $this->id, 'desc');
            }
            $pageData = $baseQuery->limit($perPage, ($currentPage - 1) * $perPage)->get()->result_array();

            $this->db->flush_cache();

            return [
                '_paging' => true,
                'total_data' => $totalData,
                'total_page_data' => count($pageData),
                'total_page' => ceil($totalData / $perPage),
                'per_page' => $perPage,
                'current_page' => $currentPage,
                'data' => $pageData
            ];
        }

        if (key_exists('sort_by', $filters) && !empty($filters['sort_by'])) {
            if (key_exists('order_method', $filters) && !empty($filters['order_method'])) {
                $baseQuery->order_by($filters['sort_by'], $filters['order_method']);
            } else {
                $baseQuery->order_by($filters['sort_by'], 'asc');
            }
        } else {
            $baseQuery->order_by($this->table . '.' . $this->id, 'desc');
        }

        $data = $baseQuery->get()->result_array();

        $this->db->flush_cache();

        return $data;
    }
}
