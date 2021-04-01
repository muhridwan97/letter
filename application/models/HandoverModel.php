<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HandoverModel extends App_Model
{
    protected $filteredFields = [
        'requisitions.no_requisition',
        'requisitions.request_title',
        'ref_employees.name',
        'purchase_orders.no_purchase',
        'purchase_orders.handover_note',
        'purchase_orders.received_date',
        'purchase_orders.received_date_user',
        'purchase_offers.status',
    ];

    /**
     * Get base query of table.
     *
     * @return CI_DB_query_builder
     */
    protected function getBaseQuery()
    {
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
            ->distinct()
            ->from('purchase_orders')
            ->join('purchase_offers', 'purchase_offers.id = purchase_orders.id_purchase_offer', 'left')
            ->join('requisitions', 'requisitions.id = purchase_offers.id_requisition', 'left')
            ->join(EmployeeModel::$tableEmployee, 'ref_employees.id = requisitions.id_employee', 'left')
            ->join('ref_categories', 'ref_categories.id = requisitions.id_category', 'left')
            ->join('ref_vendors', 'ref_vendors.id = purchase_offers.id_vendor', 'left')
            ->join('purchase_offer_items', 'purchase_offer_items.id_purchase_offer = purchase_offers.id', 'left')
            ->join('requisition_items', 'requisition_items.id = purchase_offer_items.id_requisition_item', 'left')
            ->where_in('purchase_offers.status', [
                PurchaseOfferModel::STATUS_ASK_CONFIRMATION,
                PurchaseOfferModel::STATUS_CONFIRMED,
                PurchaseOfferModel::STATUS_UNCONFIRMED,
                PurchaseOfferModel::STATUS_COMPLETED,
            ])
            ->group_by('purchase_orders.id');
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

        if (!empty($filters)) {

            if (key_exists('search', $filters) && !empty($filters['search'])) {
                $baseQuery->group_start();
                foreach ($this->filteredFields as $filteredField) {
                    $baseQuery->or_like($filteredField, trim($filters['search']));
                }
                $baseQuery->group_end();
            }

            if (key_exists('statuses', $filters) && !empty($filters['statuses'])) {
                $baseQuery->where_in('purchase_offers.status', $filters['statuses']);
            }

            if (key_exists('employees', $filters) && !empty($filters['employees'])) {
                $baseQuery->where_in('requisitions.id_employee', $filters['employees']);
            }

            if (key_exists('date_from', $filters) && !empty($filters['date_from'])) {
                $baseQuery->where('purchase_orders.received_date>=', format_date($filters['date_from']));
            }

            if (key_exists('date_to', $filters) && !empty($filters['date_to'])) {
                $baseQuery->where('purchase_orders.received_date<=', format_date($filters['date_to']));
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
                $baseQuery->order_by('purchase_offers.status', 'asc');
                $baseQuery->order_by('purchase_orders.id', 'desc');
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
            $baseQuery->order_by('purchase_offers.status', 'asc');
            $baseQuery->order_by('purchase_orders.id', 'desc');
        }

        $data = $baseQuery->get()->result_array();

        $this->db->flush_cache();

        return $data;
    }
}
