<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PurchaseOrderModel extends App_Model
{
    protected $table = 'purchase_orders';

    const STATUS_DRAFT = 'DRAFT';
    const STATUS_PUBLISHED = 'PUBLISHED';
    const STATUS_CANCELED = 'CANCELED';

    public function __construct()
    {
        $this->addFilteredField([
            'requisitions.no_requisition',
            'requisitions.request_title',
            'requisitions.rate_type',
            'purchase_offers.status',
            'ref_vendors.vendor',
            'ref_employees.name',
        ]);
    }

    /**
     * Get base query of table.
     *
     * @return CI_DB_query_builder
     */
    protected function getBaseQuery()
    {
    	$this->load->model('DepartmentModel');
        return parent::getBaseQuery()
            ->select([
                'requisitions.no_requisition',
                'requisitions.request_title',
                'requisitions.rate_type',
                'ref_vendors.id AS id_vendor',
                'ref_vendors.vendor',
                'prv_users.id AS id_user',
                'prv_users.email AS employee_email',
                'ref_employees.name AS employee_name',
                'ref_departments.department',
                'ref_work_locations.location',
                'purchasing.name AS purchasing_name',
                'purchase_offers.id_requisition',
                'purchase_offers.lead_time',
                'purchase_offers.status',
                'purchase_offers.top',
                'purchase_offers.offer_discount_goods',
                'purchase_offers.offer_discount_services',
                '(purchase_offers.offer_discount_goods + purchase_offers.offer_discount_services) AS offer_discount',
                '(purchase_orders.rounding_goods + purchase_orders.rounding_services) AS rounding_amount',
                'SUM((purchase_offer_items.quantity_selected * purchase_offer_items.price) - (IFNULL(purchase_offer_items.discount, 0) * purchase_offer_items.quantity_selected)) - offer_discount_goods - offer_discount_services AS total',
                'SUM((purchase_offer_items.quantity_selected * purchase_offer_items.price) - (IFNULL(purchase_offer_items.discount, 0) * purchase_offer_items.quantity_selected)) - offer_discount_goods - offer_discount_services
                    + purchase_orders.delivery_cost 
                    + purchase_orders.miscellaneous_expense 
                    + purchase_orders.rounding_goods 
                    + purchase_orders.rounding_services 
                    + purchase_orders.tax 
                    - purchase_orders.tax_basis AS total_price',
                'payment_handovers.document AS payment_handover_doc',
                'payment_handover_po.vendor_invoice',
                'payment_handover_po.invoice_date',
            ])
            ->join('purchase_offers', 'purchase_offers.id = purchase_orders.id_purchase_offer')
            ->join('purchase_offer_items', 'purchase_offer_items.id_purchase_offer = purchase_offers.id')
            ->join('requisitions', 'requisitions.id = purchase_offers.id_requisition')
            ->join(EmployeeModel::$tableEmployee, 'ref_employees.id = requisitions.id_employee', 'left')
            ->join(DepartmentModel::$tableDepartment, 'ref_departments.id = ref_employees.id_department', 'left')
            ->join(env('DB_HR_DATABASE') . '.ref_work_locations', 'ref_work_locations.id = ref_employees.work_location', 'left')
            ->join(UserModel::$tableUser, 'prv_users.id = ref_employees.id_user', 'left')
            ->join(UserModel::$tableUser . ' AS purchasing', 'purchasing.id = purchase_orders.created_by', 'left')
            ->join('ref_vendors', 'ref_vendors.id = purchase_offers.id_vendor')
            ->where([
                'purchase_offers.is_deleted' => false
            ])
            ->join('payment_handover_po','payment_handover_po.id_purchase_order = purchase_orders.id','left')
            ->join('payment_handovers','payment_handover_po.id_handover = payment_handovers.id','left')
            ->group_by('purchase_orders.id, purchase_offers.id');
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

            if ((key_exists('search', $filters) && !empty($filters['search'])) || (key_exists('q', $filters) && !empty($filters['q'])) ) {
                $baseQuery->group_start();
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
                    
                    if (key_exists('q', $filters) && !empty($filters['q'])) {
                        $baseQuery->group_start();
                        foreach ($this->filteredFields as $filteredField) {
                            if ($filteredField == '*') {
                                $fields = $this->db->list_fields($this->table);
                                foreach ($fields as $field) {
                                    $baseQuery->or_like($this->table . '.' . $field, trim($filters['q']));
                                }
                            } else {
                                $baseQuery->or_like($filteredField, trim($filters['q']));
                            }
                        }
                        $baseQuery->group_end();
                    }
                $baseQuery->group_end();
            }

            if (key_exists('document_status', $filters) && !empty($filters['document_status'])) {
				$baseQuery->where($this->table . '.document_status', $filters['document_status']);
            }

            if (key_exists('completed_at_year', $filters) || key_exists('completed_at_week', $filters)) {
				$baseQuery->where('purchase_offers.status', 'COMPLETED');
			}

            if (key_exists('completed_at_year', $filters) && !empty($filters['completed_at_year'])) {
				$baseQuery->where('YEAR(purchase_offers.updated_at)', $filters['completed_at_year']);
            }

            if (key_exists('completed_at_week', $filters) && !empty($filters['completed_at_week'])) {
				$baseQuery->where('WEEK(purchase_offers.updated_at)', $filters['completed_at_week'] - 1);
            }

            if (key_exists('status', $filters) && !empty($filters['status'])) {
				$baseQuery->where('purchase_offers.status', $filters['status']);
            }

            if (key_exists('document_status', $filters) && !empty($filters['document_status'])) {
				$baseQuery->where('purchase_orders.document_status', $filters['document_status']);
            }

            if (key_exists('users', $filters) && !empty($filters['users'])) {
                if ($this->db->field_exists('id_user', $this->table)) {
                    $baseQuery->where_in($this->table . '.id_user', $filters['users']);
                }
            }

            if (key_exists('employees', $filters) && !empty($filters['employees'])) {
                if ($this->db->field_exists('id_employee', $this->table)) {
                    $baseQuery->where_in($this->table . '.id_employee', $filters['employees']);
                }
            }

            if (key_exists('date_from', $filters) && !empty($filters['date_from'])) {
                if ($this->db->field_exists('created_at', $this->table)) {
                    $baseQuery->where($this->table . '.created_at>=', format_date($filters['date_from']));
                }
            }

            if (key_exists('date_to', $filters) && !empty($filters['date_to'])) {
                if ($this->db->field_exists('created_at', $this->table)) {
                    $baseQuery->where($this->table . '.created_at<=', format_date($filters['date_to']));
                }
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
                if ($filters['sort_by'] == 'no') {
                    $baseQuery
                        ->order_by("FIELD(purchase_offers.status, 'ORDERED', 'ASK CONFIRMATION', 'CONFIRMED', 'COMPLETED')")
                        ->order_by($this->table . '.' . $this->id, 'desc');
                } else {
                    if (key_exists('order_method', $filters) && !empty($filters['order_method'])) {
                        $baseQuery->order_by($filters['sort_by'], $filters['order_method']);
                    } else {
                        $baseQuery->order_by($filters['sort_by'], 'asc');
                    }
                }
            } else {
                $baseQuery
                    ->order_by("FIELD(purchase_offers.status, 'ORDERED', 'COMPLETED')")
                    ->order_by($this->table . '.' . $this->id, 'desc');
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

    /**
     * Get requisition number.
     *
     * @return string
     */
    public function getPurchaseNumber()
    {
        $requisitions = $this->db->query("
            SELECT CAST(SUBSTRING_INDEX(no_purchase, '/', 1) AS UNSIGNED) AS order_number
            FROM purchase_orders
            WHERE MONTH(created_at) = MONTH(CURDATE())
                AND YEAR(created_at) = YEAR(CURDATE())
            ORDER BY order_number DESC
            LIMIT 1
        ");
        if ($requisitions->num_rows()) {
            $currentLastNumber = $requisitions->row_array();
            $nextOrder = intval($currentLastNumber['order_number']) + 1;
        } else {
            $nextOrder = 1;
        }
        $monthToRoman = roman_number(intval(date('m')));

        return $nextOrder . '/PURCH-TCI/' . $monthToRoman . '/' . date('Y');
    }

    /**
     * Get incoming request.
     */
    public function getIncomingOrder()
    {
        $incoming = $this->db->select([
            'requisitions.id',
            'requisitions.no_requisition',
            'requisitions.request_title',
            'requisitions.rate_type',
            'purchase_offers.id AS id_purchase_offer',
            'purchase_offers.id_vendor',
            'purchase_offers.status',
            'purchase_offers.offer_discount_goods',
            'purchase_offers.offer_discount_services',
            '(purchase_offers.offer_discount_goods + purchase_offers.offer_discount_services) AS offer_discount',
            'ref_vendors.vendor',
            'ref_vendors.is_pkp',
            'SUM(quantity_selected) AS total_item_quantity',
            'SUM(IF(requisition_items.type = "GOODS", (quantity_selected * price) - (IFNULL(discount, 0) * quantity_selected), 0)) AS total_price_goods',
            'SUM(IF(requisition_items.type = "SERVICES" OR requisition_items.type = "LEASE", (quantity_selected * price) - (IFNULL(discount, 0) * quantity_selected), 0)) AS total_price_services',
            'SUM((quantity_selected * price) - (IFNULL(discount, 0) * quantity_selected)) AS total_price_before_offer_discount',
            'SUM((quantity_selected * price) - (IFNULL(discount, 0) * quantity_selected)) - offer_discount_goods - offer_discount_services AS total_price',
			'purchase_orders.id AS id_purchase_order',
			'purchase_orders.no_purchase',
			'purchase_orders.document_status',
			'purchase_orders.document',
        ])
            ->from('requisitions')
            ->join('purchase_offers', 'purchase_offers.id_requisition = requisitions.id')
            ->join('(
            	SELECT * FROM purchase_orders 
            	WHERE document_status != "CANCELED" 
            	AND is_deleted = 0 
            ) AS purchase_orders', 'purchase_orders.id_purchase_offer = purchase_offers.id', 'left')
            ->join('ref_vendors', 'ref_vendors.id = purchase_offers.id_vendor')
            ->join('purchase_offer_items', 'purchase_offer_items.id_purchase_offer = purchase_offers.id', 'left')
            ->join('requisition_items', 'requisition_items.id = purchase_offer_items.id_requisition_item', 'left')
            ->where_in('purchase_offers.status', [PurchaseOfferModel::STATUS_SELECTED, PurchaseOfferModel::STATUS_IN_PROCESS])
			->or_where(['purchase_orders.document_status' => PurchaseOrderModel::STATUS_DRAFT])
            ->group_by('purchase_offers.id, purchase_orders.id');

        return $incoming->get()->result_array();
    }
}
