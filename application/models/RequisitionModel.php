<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RequisitionModel extends App_Model
{
    protected $table = 'requisitions';
    protected $table_heep = 'heavy_equipment_entry_permits';

    const STATUS_PENDING = 'PENDING';
    const STATUS_REJECTED = 'REJECTED';
    const STATUS_CANCELLED = 'CANCELLED';
    const STATUS_APPROVED = 'APPROVED';
    const STATUS_LISTED = 'LISTED';
    const STATUS_ASK_SELECTION = 'ASK SELECTION';
    const STATUS_READY = 'READY';
    const STATUS_SELECTED = 'SELECTED';
    const STATUS_IN_PROCESS = 'IN PROCESS';
    const STATUS_DONE = 'DONE';

    const RATE_TYPE_FIXED = 'FIXED';
    const RATE_TYPE_ADJUSTABLE = 'ADJUSTABLE';

    const REQUEST_TYPE_ROUTINE = 'ROUTINE';
    const REQUEST_TYPE_URGENT = 'URGENT';
    const REQUEST_TYPE_PROJECT = 'PROJECT';

    public function __construct()
    {
    	parent::__construct();

        if ($this->config->item('sso_enable')) {
            $this->table_heep = env('DB_WAREHOUSE_DATABASE') . '.heavy_equipment_entry_permits';
        }
    }
    /**
     * Get base query of table.
     *
     * @return CI_DB_query_builder
     */
    protected function getBaseQuery()
    {
        $this->addFilteredField([
            'ref_categories.category',
            'ref_employees.name'
        ]);

        return parent::getBaseQuery()
            ->select([
                'ref_employees.name AS employee_name',
                'prv_users.id AS id_user',
                'prv_users.email AS employee_email',
                'supervisors.id AS id_supervisor',
                'supervisors.name AS supervisor_name',
                'supervisor_users.id AS id_user_supervisor',
                'supervisor_users.email AS supervisor_email',
                'ref_categories.category',
                'creators.name AS creator_name',
                'COUNT(DISTINCT purchase_offers.id) AS total_offer',
                'SUM(IF(purchase_offers.status = "SELECTED", 1, 0)) AS total_selected',
                'checkers.name AS checker_name',
                'heep.heep_code',
                'heep.id AS id_heep',
                'IF(heep.checked_in_at is null, DATE_FORMAT(heep.created_at,"01 %M %Y"),DATE_FORMAT(heep.checked_in_at,"%d %M %Y")) AS heep_in',
                'IF(heep.checked_out_at is null, IF(heep.checked_in_at is null, DATE_FORMAT(LAST_DAY(heep.created_at),"%d %M %Y"),DATE_FORMAT(LAST_DAY(heep.checked_in_at),"%d %M %Y")), DATE_FORMAT(heep.checked_out_at,"%d %M %Y")) AS heep_out',
                'ref_item_categories.item_name',
            ])
            ->distinct()
            ->join(EmployeeModel::$tableEmployee, 'ref_employees.id = requisitions.id_employee', 'left')
            ->join(UserModel::$tableUser, 'prv_users.id = ref_employees.id_user', 'left')
            ->join(EmployeeModel::$tableEmployee . ' AS supervisors', 'ref_employees.id_employee = supervisors.id', 'left')
            ->join(UserModel::$tableUser . ' AS supervisor_users', 'supervisor_users.id = supervisors.id_user', 'left')
            ->join('ref_categories', 'ref_categories.id = requisitions.id_category', 'left')
            ->join(UserModel::$tableUser . ' AS creators', 'creators.id = requisitions.created_by', 'left')
            ->join(UserModel::$tableUser . ' AS checkers', 'checkers.id = requisitions.checked_by', 'left')
            ->join('(SELECT * FROM purchase_offers WHERE is_deleted = 0) AS purchase_offers', 'purchase_offers.id_requisition = requisitions.id', 'left')
            ->join('(
            	SELECT ' . $this->table_heep . '.* FROM ' . $this->table_heep . '
            	INNER JOIN (
            		SELECT id_requisition, MAX(id) AS latest_id 
            		FROM ' . $this->table_heep . '
            		GROUP BY id_requisition
            	) AS latest_heep ON latest_heep.latest_id = ' . $this->table_heep . '.id
            ) AS heep', 'heep.id_requisition = requisitions.id', 'left')
            ->join('ref_item_categories','ref_item_categories.id=requisitions.id_item_category','left')
            ->group_by('requisitions.id, heep.id');
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

        if (!$withTrashed) {
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
					if (key_exists('with_offer_status', $filters) && $filters['with_offer_status']) {
						$baseQuery->or_like('purchase_offer_statuses.status', trim($filters['search']));
					}
                }
                $baseQuery->group_end();
            }

            if (key_exists('with_offer_status', $filters) && $filters['with_offer_status']) {
            	$baseQuery
					->select('GROUP_CONCAT(DISTINCT purchase_offer_statuses.status) AS selected_offer_status')
					->join("(
						SELECT * FROM purchase_offers WHERE STATUS NOT IN('ON REVIEW', 'UNSELECTED')
					) AS purchase_offer_statuses", 'purchase_offer_statuses.id_requisition = requisitions.id', 'left');
			}

            if (key_exists('check_type', $filters) && !empty($filters['check_type'])) {
                if ($filters['check_type'] == 'checked') {
                    $baseQuery->where_in($this->table . '.is_checked', 1);
                }else if ($filters['check_type'] == 'unchecked'){
                    $baseQuery->where_in($this->table . '.is_checked', 0);
                }
            }

            if (key_exists('status', $filters) && !empty($filters['status'])) {
                $baseQuery->where_in($this->table . '.status', $filters['status']);
            }

            if (key_exists('employees', $filters) && !empty($filters['employees'])) {
                $baseQuery->where_in($this->table . '.id_employee', $filters['employees']);
            }

            if (key_exists('date_from', $filters) && !empty($filters['date_from'])) {
                $baseQuery->where($this->table . '.created_at>=', format_date($filters['date_from']));
            }

            if (key_exists('date_to', $filters) && !empty($filters['date_to'])) {
                $baseQuery->where($this->table . '.created_at<=', format_date($filters['date_to']));
            }

			if (key_exists('approved_at_year_week', $filters) && !empty($filters['approved_at_year_week'])) {
				$baseQuery->join("(
					SELECT id_requisition, MIN(created_at) AS approved_at 
					FROM requisition_statuses WHERE STATUS LIKE 'APPROVED%'
					GROUP BY id_requisition
				) AS approved_requisitions", "approved_requisitions.id_requisition = requisitions.id");

				$baseQuery->where('YEARWEEK(approved_requisitions.approved_at, 2)=', $filters['approved_at_year_week']);
            }

			$filterStaffProceedYearWeek = key_exists('staff_proceed_at_year_week', $filters) && !empty($filters['staff_proceed_at_year_week']);
			if ($filterStaffProceedYearWeek) {
				$baseQuery->join("(
					SELECT 
						id_requisition, 
						MIN(CASE WHEN STATUS = 'APPROVED' THEN created_at END) AS approved_at,
						MIN(CASE WHEN STATUS = 'IN PROCESS' THEN created_at END) AS ordered_at,
						MIN(CASE WHEN STATUS = 'CONFIRMED' THEN created_at END) AS confirmed_at,
						MAX(CASE WHEN STATUS = 'DONE' THEN created_at END) AS done_at
					FROM requisition_statuses
					GROUP BY id_requisition
				) AS request_staff_processes", 'request_staff_processes.id_requisition = requisitions.id')
					->where('request_staff_processes.approved_at IS NOT NULL')
					//->where('YEAR(request_staff_processes.approved_at)<=', $filters['staff_proceed_at_year'])
					//->where('WEEK(request_staff_processes.approved_at)<=', $filters['staff_proceed_at_week'] - 1);
					->where('YEARWEEK(request_staff_processes.approved_at, 2)<=', $filters['staff_proceed_at_year_week']);
				$baseQuery
					->group_start()
					//->where('YEAR(request_staff_processes.ordered_at)>=', $filters['staff_proceed_at_year'])
					//->where('WEEK(request_staff_processes.ordered_at)>', $filters['staff_proceed_at_week'] - 1)
					->where('YEARWEEK(request_staff_processes.ordered_at)>', $filters['staff_proceed_at_year_week'])
					->or_where('request_staff_processes.ordered_at IS NULL')
					->group_end();
				$baseQuery
					->or_where('YEARWEEK(request_staff_processes.confirmed_at, 2)<=', $filters['staff_proceed_at_year_week'])
					->group_start()
					->where('YEARWEEK(request_staff_processes.done_at)>', $filters['staff_proceed_at_year_week'])
					->or_where('request_staff_processes.done_at IS NULL')
					->group_end();


			}

			$filterVendorProceedYearWeek = key_exists('vendor_proceed_at_year_week', $filters) && !empty($filters['vendor_proceed_at_year_week']);
			if ($filterVendorProceedYearWeek) {
				$baseQuery->join("(
					SELECT 
						id_requisition, 
						MIN(CASE WHEN STATUS = 'IN PROCESS' THEN created_at END) AS ordered_at,
						IFNULL(
							MIN(CASE WHEN STATUS = 'ASK CONFIRMATION' THEN created_at END),
							MIN(CASE WHEN STATUS = 'DONE' THEN created_at END)
						)AS ask_confirmation_at
					FROM requisition_statuses
					GROUP BY id_requisition
				) AS request_vendor_processes", 'request_vendor_processes.id_requisition = requisitions.id')
					->where('request_vendor_processes.ordered_at IS NOT NULL')
					//->where('YEAR(request_vendor_processes.ordered_at)<=', $filters['vendor_proceed_at_year'])
					//->where('WEEK(request_vendor_processes.ordered_at)<=', $filters['vendor_proceed_at_week'] - 1);
					->where('YEARWEEK(request_vendor_processes.ordered_at, 2)<=', $filters['vendor_proceed_at_year_week']);
				$baseQuery
					->group_start()
					//->where('YEAR(request_vendor_processes.ask_confirmation_at)>=', $filters['vendor_proceed_at_year'])
					//->where('WEEK(request_vendor_processes.ask_confirmation_at)>', $filters['vendor_proceed_at_week'] - 1)
					->where('YEARWEEK(request_vendor_processes.ask_confirmation_at)>', $filters['vendor_proceed_at_year_week'])
					->or_where('request_vendor_processes.ask_confirmation_at IS NULL')
					->group_end();
			}

			$filterRequesterProceedYearWeek = key_exists('requester_proceed_at_year_week', $filters) && !empty($filters['requester_proceed_at_year_week']);
			if ($filterRequesterProceedYearWeek) {
				$baseQuery->join("(
					SELECT 
						id_requisition, 
						MIN(CASE WHEN STATUS = 'ASK CONFIRMATION' THEN created_at END) AS ask_confirmation_at,
						MIN(CASE WHEN STATUS = 'CONFIRMED' THEN created_at END) AS confirmed_at
					FROM requisition_statuses
					GROUP BY id_requisition
				) AS request_confirmations", 'request_confirmations.id_requisition = requisitions.id')
					->where('request_confirmations.ask_confirmation_at IS NOT NULL')
					//->where('YEAR(request_confirmations.ask_confirmation_at)<=', $filters['requester_proceed_at_year'])
					//->where('WEEK(request_confirmations.ask_confirmation_at)<=', $filters['requester_proceed_at_week'] - 1);
					->where('YEARWEEK(request_confirmations.ask_confirmation_at, 2)<=', $filters['requester_proceed_at_year_week']);
				$baseQuery
					->group_start()
					//->where('YEAR(request_confirmations.confirmed_at)>=', $filters['requester_proceed_at_year'])
					//->where('WEEK(request_confirmations.confirmed_at)>', $filters['requester_proceed_at_week'] - 1)
					->where('YEARWEEK(request_confirmations.confirmed_at)>', $filters['requester_proceed_at_year_week'])
					->or_where('request_confirmations.confirmed_at IS NULL')
					->group_end();
			}

			$filterIncompleteRequestYearWeek = key_exists('incomplete_request_at_year_week', $filters) && !empty($filters['incomplete_request_at_year_week']);
			if ($filterIncompleteRequestYearWeek) {
				$baseQuery->join("(
					SELECT 
						id_requisition, 
						MIN(CASE WHEN STATUS = 'APPROVED' THEN created_at END) AS approved_at,
						MAX(CASE WHEN STATUS = 'DONE' THEN created_at END) AS done_at
					FROM requisition_statuses
					GROUP BY id_requisition
				) AS incomplete_requests", 'incomplete_requests.id_requisition = requisitions.id')
					->where('incomplete_requests.approved_at IS NOT NULL')
					->where('YEARWEEK(incomplete_requests.approved_at, 2)<=', $filters['incomplete_request_at_year_week']);
				$baseQuery
					->group_start()
					->where('YEARWEEK(incomplete_requests.done_at)>', $filters['incomplete_request_at_year_week'])
					->or_where('incomplete_requests.done_at IS NULL')
					->group_end();
			}

            if (key_exists('id_requestion', $filters) && !empty($filters['id_requestion'])) {
                $baseQuery->where_in($this->table . '.id', $filters['id_requestion']);
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
                $baseQuery->order_by($this->table . '.status', 'asc');
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
    public function getDetailReport($filters)
    {
        $report = $this->db
            ->select([
                'selected_date',
                'id_requisition'
            ])
            ->from("(SELECT selected_date FROM 
                (SELECT ADDDATE('2018-01-01',t4.i*10000 + t3.i*1000 + t2.i*100 + t1.i*10 + t0.i) selected_date FROM
                (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t0,
                (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1,
                (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2,
                (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3,
                (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t4) v
                WHERE selected_date BETWEEN DATE_FORMAT('2018-01-01','%Y-01-01') AND CURRENT_DATE ) AS tanggal")
            ->join("(
                    SELECT requisitions_all.*, requisitions_done.created_at AS done_at FROM(
                        SELECT id_requisition, MIN(requisition_statuses.created_at) AS created_at
                        FROM requisition_statuses
                        LEFT JOIN requisitions ON requisitions.id = requisition_statuses.`id_requisition`
                        WHERE requisition_statuses.`status` NOT LIKE 'PENDING%' AND requisitions.`status` NOT IN('PENDING', 'REJECTED', 'CANCELLED') AND requisitions.is_deleted = FALSE
                        GROUP BY id_requisition) AS requisitions_all
                    LEFT JOIN (
                        SELECT id_requisition, MAX(created_at) AS created_at
                        FROM requisition_statuses
                        WHERE requisition_statuses.`status` LIKE 'DONE%'
                        GROUP BY id_requisition 
                    ) AS requisitions_done ON requisitions_done.id_requisition = requisitions_all.id_requisition
                    ) AS uncompleted_requisitions ","DATE(uncompleted_requisitions.created_at)<=selected_date AND DATE(uncompleted_requisitions.done_at) > DATE((selected_date + INTERVAL 1 DAY) + INTERVAL (6 - WEEKDAY((selected_date + INTERVAL 1 DAY))) DAY)
                    OR (DATE(uncompleted_requisitions.created_at)<=selected_date AND uncompleted_requisitions.done_at IS NULL)","left")
            ->group_by("WEEK(selected_date), id_requisition");

        if (key_exists('uncompleted_at_year', $filters) && !empty($filters['uncompleted_at_year'])) {
            $report->where('YEAR(selected_date)', $filters['uncompleted_at_year']);
        }

        if (key_exists('uncompleted_at_week', $filters) && !empty($filters['uncompleted_at_week'])) {
            $report->where('WEEK(selected_date)', $filters['uncompleted_at_week'] - 1);
        }
        
        return $report->get()->result_array();
    }

    /**
     * Get requisition number.
     *
     * @return string
     */
    public function getRequisitionNumber()
    {
        $requisitions = $this->db->query("
            SELECT CAST(SUBSTRING_INDEX(no_requisition, '/', 1) AS UNSIGNED) AS order_number
            FROM requisitions
            WHERE MONTH(created_at) = MONTH(CURDATE())
                AND YEAR(created_at) = YEAR(CURDATE())
            ORDER BY order_number DESC
            LIMIT 1
        ");
        if($requisitions->num_rows()) {
            $currentLastNumber = $requisitions->row_array();
            $nextOrder = intval($currentLastNumber['order_number']) + 1;
        } else {
            $nextOrder = 1;
        }

        return $nextOrder . '/TCI-JKT/' . date('m') . '/' . date('Y');
    }
}
