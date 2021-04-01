<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ReportModel extends App_Model
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('PurchaseOfferModel');
	}

	/**
	 * Get dashboard summary data.
	 *
	 * @return array
	 */
	public function getPurchaseTotalStats()
	{
		$statistic = $this->db->select([
			'COUNT(DISTINCT purchase_orders.id) AS total_transaction',
			'IFNULL(SUM(purchase_offers.total), 0) AS total',
			'IFNULL(SUM(purchase_offers.total) + SUM(delivery_cost + miscellaneous_expense + rounding_goods + rounding_services + tax - tax_basis), 0) AS total_price',
		])
			->from('purchase_orders')
			->join('(
                SELECT purchase_offers.id, SUM((price * quantity_selected) - (discount * quantity_selected)) - offer_discount_goods - offer_discount_services AS total
                FROM purchase_offers
                INNER JOIN purchase_offer_items 
                    ON purchase_offer_items.id_purchase_offer = purchase_offers.id 
                WHERE purchase_offers.status = "COMPLETED" OR purchase_offers.status = "ORDERED"
                GROUP BY purchase_offers.id
            ) AS purchase_offers', 'purchase_offers.id = purchase_orders.id_purchase_offer');

		return $statistic->get()->row_array();
	}

	/**
	 * Get dashboard summary data.
	 *
	 * @param $year
	 * @param $month
	 * @return array
	 */
	public function getMonthlyTotalStats($year, $month)
	{
		$statistic = $this->db->select([
			'COUNT(DISTINCT purchase_orders.id) AS total_transaction',
			'IFNULL(SUM(purchase_offers.total), 0) AS total',
			'IFNULL(SUM(purchase_offers.total) + SUM(delivery_cost + miscellaneous_expense + rounding_goods + rounding_services + tax - tax_basis), 0) AS total_price',
		])
			->from('purchase_orders')
			->join('(
                SELECT purchase_offers.id, SUM((price * quantity_selected) - (discount * quantity_selected)) - offer_discount_goods - offer_discount_services AS total
                FROM purchase_offers
                INNER JOIN purchase_offer_items 
                    ON purchase_offer_items.id_purchase_offer = purchase_offers.id 
                WHERE purchase_offers.status = "COMPLETED" OR purchase_offers.status = "ORDERED"
                GROUP BY purchase_offers.id
            ) AS purchase_offers', 'purchase_offers.id = purchase_orders.id_purchase_offer')
			->where([
				'YEAR(purchase_orders.date)' => $year,
				'MONTH(purchase_orders.date)' => $month,
			]);

		return $statistic->get()->row_array();
	}

	/**
	 * Get purchase history.
	 *
	 * @param array $filters
	 * @return array
	 */
	public function getPurchaseHistory($filters = [])
	{
		$this->db->start_cache();

		$purchases = $this->db->select([
			'YEAR(purchase_orders.date) AS year',
			'MONTH(purchase_orders.date) AS month',
			'IFNULL(SUM(purchase_orders.delivery_cost), 0) AS total_delivery_cost',
			'COUNT(DISTINCT purchase_orders.id) AS total_transaction',
			'IFNULL(SUM(purchase_offers.total_item), 0) AS total_item',
			'IFNULL(SUM(purchase_offers.total), 0) AS total',
			'IFNULL(SUM(purchase_offers.total) + SUM(delivery_cost + miscellaneous_expense + rounding_goods + rounding_services + tax - tax_basis), 0) AS total_price',
		])
			->from('purchase_orders')
			->join('(
                SELECT 
                    purchase_offers.id,
                    COUNT(DISTINCT purchase_offer_items.id) AS total_item, 
                    SUM((price * quantity_selected) - (discount * quantity_selected)) - offer_discount_goods - offer_discount_services AS total
                FROM purchase_offers
                INNER JOIN purchase_offer_items 
                    ON purchase_offer_items.id_purchase_offer = purchase_offers.id 
                WHERE purchase_offers.status = "COMPLETED" OR purchase_offers.status = "ORDERED"
                GROUP BY purchase_offers.id
            ) AS purchase_offers', 'purchase_offers.id = purchase_orders.id_purchase_offer')
			->where('purchase_orders.is_deleted', 'false')
			->group_by('YEAR(purchase_orders.date), MONTH(purchase_orders.date)');

		$this->db->stop_cache();

		if (key_exists('per_page', $filters) && !empty($filters['per_page'])) {
			$perPage = $filters['per_page'];
		} else {
			$perPage = 20;
		}

		if (key_exists('page', $filters) && !empty($filters['page'])) {
			$currentPage = $filters['page'];

			$totalData = $this->db->count_all_results();

			if (key_exists('sort_by', $filters) && !empty($filters['sort_by'])) {
				if (key_exists('order_method', $filters) && !empty($filters['order_method'])) {
					$purchases->order_by($filters['sort_by'], $filters['order_method']);
				} else {
					$purchases->order_by($filters['sort_by'], 'asc');
				}
			} else {
				$purchases
					->order_by('YEAR(purchase_orders.date)', 'desc')
					->order_by('MONTH(purchase_orders.date)', 'desc');
			}
			$pageData = $purchases->limit($perPage, ($currentPage - 1) * $perPage)->get()->result_array();

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
		} else {
			$purchases->order_by('purchase_orders.date', 'desc');
		}

		$data = $purchases->get()->result_array();

		$this->db->flush_cache();

		return $data;
	}

	/**
	 * Get purchase history.
	 *
	 * @param array $filters
	 * @return array
	 */
	public function getPurchaseHistoryDetail($filters = [])
	{
		$this->db->start_cache();

		$purchases = $this->db->select([
			'purchase_orders.id',
			'requisitions.no_requisition',
			'requisitions.request_title',
			'ref_vendors.vendor',
			'purchase_orders.no_purchase',
			'purchase_orders.date',
			'purchase_orders.tax',
			'purchase_orders.tax_percentage',
			'purchase_orders.miscellaneous_expense',
			'purchase_orders.delivery_cost',
			'purchase_orders.tax_basis',
			'purchase_orders.tax_basis_percentage',
			'IFNULL(SUM(purchase_offers.total), 0) AS total',
			'IFNULL(SUM(purchase_offers.total) + SUM(delivery_cost + miscellaneous_expense + rounding_goods + rounding_services + tax - tax_basis), 0) AS total_price',
			'purchase_orders.rating',
		])
			->from('purchase_orders')
			->join('(
                SELECT 
                    purchase_offers.id, id_vendor, id_requisition,
                    COUNT(DISTINCT purchase_offer_items.id) AS total_item, 
                    SUM((price * quantity_selected) - (discount * quantity_selected)) - offer_discount_goods - offer_discount_services AS total
                FROM purchase_offers
                INNER JOIN purchase_offer_items 
                    ON purchase_offer_items.id_purchase_offer = purchase_offers.id 
                WHERE purchase_offers.status = "COMPLETED" OR purchase_offers.status = "ORDERED"
                GROUP BY id, id_vendor, id_requisition
            ) AS purchase_offers', 'purchase_offers.id = purchase_orders.id_purchase_offer')
			->join('ref_vendors', 'ref_vendors.id = purchase_offers.id_vendor')
			->join('requisitions', 'requisitions.id = purchase_offers.id_requisition')
			->join(EmployeeModel::$tableEmployee, 'ref_employees.id = requisitions.id_employee', 'left')
			->where('purchase_orders.is_deleted', 'false')
			->group_by('purchase_orders.id')
			->order_by('purchase_orders.date', 'desc');

		if (key_exists('year', $filters) && !empty($filters['year'])) {
			$purchases->where('YEAR(purchase_orders.date)', $filters['year']);
		}

		if (key_exists('month', $filters) && !empty($filters['month'])) {
			$purchases->where('MONTH(purchase_orders.date)', $filters['month']);
		}

		$this->db->stop_cache();

		if (key_exists('per_page', $filters) && !empty($filters['per_page'])) {
			$perPage = $filters['per_page'];
		} else {
			$perPage = 20;
		}

		if (key_exists('page', $filters) && !empty($filters['page'])) {
			$currentPage = $filters['page'];

			$totalData = $this->db->count_all_results();

			if (key_exists('sort_by', $filters) && !empty($filters['sort_by'])) {
				if (key_exists('order_method', $filters) && !empty($filters['order_method'])) {
					$purchases->order_by($filters['sort_by'], $filters['order_method']);
				} else {
					$purchases->order_by($filters['sort_by'], 'asc');
				}
			} else {
				$purchases->order_by('purchase_orders.date', 'desc');
			}
			$pageData = $purchases->limit($perPage, ($currentPage - 1) * $perPage)->get()->result_array();

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
		} else {
			$purchases->order_by('purchase_orders.date', 'desc');
		}

		$data = $purchases->get()->result_array();

		$this->db->flush_cache();

		return $data;
	}

	/**
	 * Get satisfied recipient report.
	 *
	 * @param array $filters
	 * @return array|array[]
	 */
	public function getSatisfiedRecipientReport($filters = [])
	{
		$filterMonth = '';
		if (key_exists('month', $filters) && !empty($filters['month'])) {
			$filterMonth = "AND MONTH(created_at) = '{$filters['month']}'";
		}

		$baseQuery = $this->db
			->select([
				'weeks.year_week',
				'weeks.year',
				'weeks.week',
				//'IFNULL(uncompleted_requisitions.uncompleted_requisition, 0) AS uncompleted_requisition',
				'IFNULL(incomplete_requests.incomplete_requisition, 0) AS uncompleted_requisition',
				'IFNULL(requisitions.approved_requisition, 0) AS approved_requisition',
				'IFNULL(request_staff_processes.staff_proceed_requisition, 0) AS staff_proceed_requisition',
				'IFNULL(request_vendor_processes.vendor_proceed_requisition, 0) AS vendor_proceed_requisition',
				'IFNULL(request_confirmations.confirmation_requisition, 0) AS requester_proceed_requisition',
				'IFNULL(purchase_orders.average_user_rating, 0) AS average_user_rating',
				'IFNULL(purchase_orders.completed_purchase_order, 0) AS completed_purchase_order',
				'IFNULL(purchase_orders.total_price, 0) AS total_price',
			])
			// to optimize query use "year_week only" to join (remove year and week)
			->from("(
				SELECT DISTINCT
					YEARWEEK(created_at, 2) AS year_week, 
					LEFT(YEARWEEK(created_at, 2), LENGTH(YEARWEEK(created_at, 2)) - 2) AS year, 
					WEEK(created_at, 2) AS week 
				FROM requisition_statuses 
				WHERE created_at IS NOT NULL {$filterMonth}
			) AS weeks")
			// when use MAX use to find latest approved 'APPROVED%'
			->join("(
				SELECT
					YEARWEEK(requisition_statuses.approved_at, 2) AS year_week,
					LEFT(YEARWEEK(requisition_statuses.approved_at, 2), LENGTH(YEARWEEK(requisition_statuses.approved_at, 2)) - 2) AS year, 
					WEEK(requisition_statuses.approved_at, 2) AS week,
					COUNT(requisition_statuses.id_requisition) AS approved_requisition
				FROM requisitions
				INNER JOIN (
					SELECT id_requisition, MIN(created_at) AS approved_at 
					FROM requisition_statuses 
					WHERE status = 'APPROVED'
					GROUP BY id_requisition
				) AS requisition_statuses ON requisition_statuses.id_requisition = requisitions.id
				WHERE requisitions.status NOT IN('PENDING', 'REJECTED', 'CANCELLED') AND requisitions.is_deleted = FALSE
				GROUP BY year_week, year, week
			) AS requisitions", 'requisitions.year_week = weeks.year_week', 'left')
			->join("(
				SELECT weeks.year_week, weeks.year, weeks.week, COUNT(incomplete_requests.id) AS incomplete_requisition
				FROM (
					SELECT DISTINCT 
						YEARWEEK(created_at, 2) AS year_week,
						LEFT(YEARWEEK(created_at, 2), LENGTH(YEARWEEK(created_at, 2)) - 2) AS year, 
						WEEK(created_at, 2) AS week 
					FROM requisition_statuses
				) AS weeks
				LEFT JOIN (
					SELECT
						requisitions.id,
						requisitions.no_requisition,
						YEARWEEK(requisition_statuses.approved_at, 2) AS approved_year_week,
						LEFT(YEARWEEK(requisition_statuses.approved_at, 2), LENGTH(YEARWEEK(requisition_statuses.approved_at, 2)) - 2) AS approved_year,
						WEEK(requisition_statuses.approved_at, 2) AS approved_week,
						YEARWEEK(requisition_statuses.done_at, 2) AS done_year_week,
						LEFT(YEARWEEK(requisition_statuses.done_at, 2), LENGTH(YEARWEEK(requisition_statuses.done_at, 2)) - 2) AS done_year,
						WEEK(requisition_statuses.done_at, 2) AS done_week
					FROM requisitions
					INNER JOIN (
						SELECT 
							id_requisition, 
							MIN(CASE WHEN STATUS = 'APPROVED' THEN created_at END) AS approved_at,
							MAX(CASE WHEN STATUS = 'DONE' THEN created_at END) AS done_at
						FROM requisition_statuses
						GROUP BY id_requisition
					) AS requisition_statuses ON requisition_statuses.id_requisition = requisitions.id
					WHERE requisitions.is_deleted = FALSE 
						AND requisitions.status != 'CANCELLED' 
						AND requisitions.status != 'REJECTED'
						AND requisition_statuses.approved_at IS NOT NULL
				) AS incomplete_requests 
					ON incomplete_requests.approved_year_week <= weeks.year_week
						AND (
							incomplete_requests.done_year_week IS NULL 
							OR 
							incomplete_requests.done_year_week > weeks.year_week
						)
				GROUP BY weeks.year_week, weeks.year, weeks.week
			) AS incomplete_requests", 'incomplete_requests.year_week = weeks.year_week')
			->join("(
				SELECT weeks.year_week, weeks.year, weeks.week, COUNT(request_staff_processes.id) AS staff_proceed_requisition
				FROM (
					SELECT DISTINCT 
						YEARWEEK(created_at, 2) AS year_week,
						LEFT(YEARWEEK(created_at, 2), LENGTH(YEARWEEK(created_at, 2)) - 2) AS year, 
						WEEK(created_at, 2) AS week 
					FROM requisition_statuses
				) AS weeks
				LEFT JOIN (
					SELECT
						requisitions.id,
						requisitions.no_requisition,
						YEARWEEK(requisition_statuses.approved_at, 2) AS approved_year_week,
						LEFT(YEARWEEK(requisition_statuses.approved_at, 2), LENGTH(YEARWEEK(requisition_statuses.approved_at, 2)) - 2) AS approved_year,
						WEEK(requisition_statuses.approved_at, 2) AS approved_week,
						YEARWEEK(requisition_statuses.ordered_at, 2) AS ordered_year_week,
						LEFT(YEARWEEK(requisition_statuses.ordered_at, 2), LENGTH(YEARWEEK(requisition_statuses.ordered_at, 2)) - 2) AS ordered_year,
						WEEK(requisition_statuses.ordered_at, 2) AS ordered_week,
						YEARWEEK(requisition_statuses.confirmed_at, 2) AS confirmed_year_week,
						YEARWEEK(requisition_statuses.done_at, 2) AS done_year_week
					FROM requisitions
					INNER JOIN (
						SELECT 
							id_requisition, 
							MIN(CASE WHEN STATUS = 'APPROVED' THEN created_at END) AS approved_at,
							MIN(CASE WHEN STATUS = 'IN PROCESS' THEN created_at END) AS ordered_at,
							MIN(CASE WHEN STATUS = 'CONFIRMED' THEN created_at END) AS confirmed_at,
							MAX(CASE WHEN STATUS = 'DONE' THEN created_at END) AS done_at
						FROM requisition_statuses
						GROUP BY id_requisition
					) AS requisition_statuses ON requisition_statuses.id_requisition = requisitions.id
					WHERE requisitions.is_deleted = FALSE 
						AND requisitions.status != 'CANCELLED' 
						AND requisitions.status != 'REJECTED'
						AND requisition_statuses.approved_at IS NOT NULL
				) AS request_staff_processes 
					ON request_staff_processes.approved_year_week <= weeks.year_week
						AND (
							request_staff_processes.ordered_year_week IS NULL 
							OR 
							request_staff_processes.ordered_year_week > weeks.year_week
						)
						OR
						request_staff_processes.confirmed_year_week <= weeks.year_week
						AND (
							request_staff_processes.done_year_week IS NULL 
							OR 
							request_staff_processes.done_year_week > weeks.year_week
						)
				GROUP BY weeks.year_week, weeks.year, weeks.week
			) AS request_staff_processes", 'request_staff_processes.year_week = weeks.year_week')
			->join("(
				SELECT weeks.year_week, weeks.year, weeks.week, COUNT(request_vendor_processes.id) AS vendor_proceed_requisition
				FROM (
					SELECT DISTINCT 
						YEARWEEK(created_at, 2) AS year_week,
						LEFT(YEARWEEK(created_at, 2), LENGTH(YEARWEEK(created_at, 2)) - 2) AS year, 
						WEEK(created_at, 2) AS week 
					FROM requisition_statuses
				) AS weeks
				LEFT JOIN (
					SELECT
						requisitions.id,
						requisitions.no_requisition,
						YEARWEEK(requisition_statuses.ordered_at, 2) AS ordered_year_week,
						LEFT(YEARWEEK(requisition_statuses.ordered_at, 2), LENGTH(YEARWEEK(requisition_statuses.ordered_at, 2)) - 2) AS ordered_year,
						WEEK(requisition_statuses.ordered_at, 2) AS ordered_week,
						YEARWEEK(requisition_statuses.ask_confirmation_at, 2) AS ask_confirmation_year_week,
						LEFT(YEARWEEK(requisition_statuses.ask_confirmation_at, 2), LENGTH(YEARWEEK(requisition_statuses.ask_confirmation_at, 2)) - 2) AS ask_confirmation_year,
						WEEK(requisition_statuses.ask_confirmation_at, 2) AS ask_confirmation_week
					FROM requisitions
					INNER JOIN (
						SELECT 
							id_requisition, 
							MIN(CASE WHEN STATUS = 'IN PROCESS' THEN created_at END) AS ordered_at,
							IFNULL(
								MIN(CASE WHEN STATUS = 'ASK CONFIRMATION' THEN created_at END),
								MIN(CASE WHEN STATUS = 'DONE' THEN created_at END)
							)AS ask_confirmation_at
						FROM requisition_statuses
						GROUP BY id_requisition
					) AS requisition_statuses ON requisition_statuses.id_requisition = requisitions.id
					WHERE requisitions.is_deleted = FALSE 
						AND requisitions.status != 'CANCELLED' 
						AND requisitions.status != 'REJECTED'
						AND requisition_statuses.ordered_at IS NOT NULL
				) AS request_vendor_processes 
					ON request_vendor_processes.ordered_year_week <= weeks.year_week
						AND (
							request_vendor_processes.ask_confirmation_year_week IS NULL
							OR
							request_vendor_processes.ask_confirmation_year_week > weeks.year_week
						)
				GROUP BY weeks.year_week, weeks.year, weeks.week
			) AS request_vendor_processes", 'request_vendor_processes.year_week = weeks.year_week')
			->join("(
				SELECT weeks.year_week, weeks.year, weeks.week, COUNT(request_confirmations.id) AS confirmation_requisition
				FROM (
					SELECT DISTINCT 
						YEARWEEK(created_at, 2) AS year_week,
						LEFT(YEARWEEK(created_at, 2), LENGTH(YEARWEEK(created_at, 2)) - 2) AS year, 
						WEEK(created_at, 2) AS week 
					FROM requisition_statuses
				) AS weeks
				LEFT JOIN (
					SELECT
						requisitions.id,
						requisitions.no_requisition,
						YEARWEEK(requisition_statuses.ask_confirmation_at, 2) AS ask_confirmation_year_week,
						LEFT(YEARWEEK(requisition_statuses.ask_confirmation_at, 2), LENGTH(YEARWEEK(requisition_statuses.ask_confirmation_at, 2)) - 2) AS ask_confirmation_year,
						WEEK(requisition_statuses.ask_confirmation_at, 2) AS ask_confirmation_week,
						YEARWEEK(requisition_statuses.confirmed_at, 2) AS confirmed_year_week,
						LEFT(YEARWEEK(requisition_statuses.confirmed_at, 2), LENGTH(YEARWEEK(requisition_statuses.confirmed_at, 2)) - 2) AS confirmed_year,
						WEEK(requisition_statuses.confirmed_at, 2) AS confirmed_week
					FROM requisitions
					INNER JOIN (
						SELECT 
							id_requisition, 
							MIN(CASE WHEN STATUS = 'ASK CONFIRMATION' THEN created_at END) AS ask_confirmation_at,
							MIN(CASE WHEN STATUS = 'CONFIRMED' THEN created_at END) AS confirmed_at
						FROM requisition_statuses
						GROUP BY id_requisition
					) AS requisition_statuses ON requisition_statuses.id_requisition = requisitions.id
					WHERE requisitions.is_deleted = FALSE 
						AND requisitions.status != 'CANCELLED' 
						AND requisitions.status != 'REJECTED'
						AND requisition_statuses.ask_confirmation_at IS NOT NULL
				) AS request_confirmations 
					ON request_confirmations.ask_confirmation_year_week <= weeks.year_week
						AND (
							request_confirmations.confirmed_year_week IS NULL
							OR
							request_confirmations.confirmed_year_week > weeks.year_week
						)
				GROUP BY weeks.year_week, weeks.year, weeks.week
			) AS request_confirmations", 'request_confirmations.year_week = weeks.year_week')
			->join("(
				SELECT
					YEARWEEK(purchase_orders.completed_at, 2) AS year_week,
					LEFT(YEARWEEK(purchase_orders.completed_at, 2), LENGTH(YEARWEEK(purchase_orders.completed_at, 2)) - 2) AS year,
					WEEK(purchase_orders.completed_at, 2) AS week,
					AVG(IFNULL(purchase_orders.rating_user, 0)) AS average_user_rating,
					COUNT(purchase_orders.id) AS completed_purchase_order,
					SUM(purchase_orders.total_price) AS total_price
				FROM (
					SELECT  
						purchase_orders.id,
						purchase_offers.updated_at AS completed_at,
						purchase_orders.id_purchase_offer,
						purchase_orders.rating_user,
						purchase_orders.is_deleted,
						SUM((purchase_offer_items.quantity_selected * purchase_offer_items.price) - (IFNULL(purchase_offer_items.discount, 0) * purchase_offer_items.quantity_selected)) - offer_discount_goods - offer_discount_services
							+ purchase_orders.delivery_cost 
							+ purchase_orders.miscellaneous_expense 
							+ purchase_orders.rounding_goods 
							+ purchase_orders.rounding_services 
							+ purchase_orders.tax 
							- purchase_orders.tax_basis AS total_price
					FROM purchase_orders
					LEFT JOIN purchase_offers ON purchase_offers.id = purchase_orders.id_purchase_offer
					LEFT JOIN purchase_offer_items ON purchase_offer_items.id_purchase_offer = purchase_offers.id
					WHERE purchase_offers.status = 'COMPLETED' 
						AND purchase_offers.is_deleted = FALSE 
						AND purchase_orders.is_deleted = FALSE
					GROUP BY purchase_orders.id
				) AS purchase_orders
				GROUP BY year_week, year, week
            ) AS purchase_orders", 'purchase_orders.year_week = weeks.year_week', 'left')
			/*->join("(
				SELECT YEAR(selected_date)AS tahun, WEEK(selected_date) AS minggu, COUNT(DISTINCT id_requisition) AS uncompleted_requisition FROM
					(SELECT selected_date FROM
						(SELECT ADDDATE('2018-01-01',t4.i*10000 + t3.i*1000 + t2.i*100 + t1.i*10 + t0.i) selected_date FROM
						(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t0,
						(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1,
						(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2,
						(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3,
						(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t4) v
						WHERE selected_date BETWEEN DATE_FORMAT('2018-01-01','%Y-01-01') AND CURRENT_DATE ) AS tanggal
					LEFT JOIN (
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
					) AS uncompleted_requisitions
					ON DATE(uncompleted_requisitions.created_at)<=selected_date AND DATE(uncompleted_requisitions.done_at)> DATE((selected_date + INTERVAL 1 DAY) + INTERVAL (6 - WEEKDAY((selected_date + INTERVAL 1 DAY))) DAY)
					OR (DATE(uncompleted_requisitions.created_at)<=selected_date AND uncompleted_requisitions.done_at IS NULL)
					GROUP BY YEAR(selected_date), WEEK(selected_date)
			) AS uncompleted_requisitions", 'uncompleted_requisitions.tahun = weeks.year AND uncompleted_requisitions.minggu = weeks.week', 'left')*/
			//->order_by('year', 'desc')
			//->order_by('week', 'desc')
			->order_by('year_week', 'desc');

		if (key_exists('year', $filters) && !empty($filters['year'])) {
			$baseQuery->having('year', $filters['year']);
		}

		$reports = $baseQuery->get()->result_array();

		// fill the gap week
		if (!empty($reports) && empty(get_url_param('month'))) {
			for ($i = 1; $i < $reports[0]['week']; $i++) {
				$notFound = true;
				foreach ($reports as $index => $report) {
					if ($report['week'] == $i) {
						$notFound = false;
						break;
					}
				}
				if ($notFound) {
					array_splice($reports, count($reports) - $i + 1, 0, [
						[
							'year' => $filters['year'],
							'week' => $i,
							'uncompleted_requisition' => '0',
							'approved_requisition' => '0',
							'staff_proceed_requisition' => '0',
							'vendor_proceed_requisition' => '0',
							'average_user_rating' => '0',
							'completed_purchase_order' => '0',
							'total_price' => '0',
						]
					]);
				}
			}
		}

		return $reports;
	}

	/**
	 * Get satisfied recipient detail.
	 *
	 * @param array $filters
	 * @return array|array[]
	 */
	public function getSatisfiedRecipientDetailReport($filters = [])
	{
		$this->load->model('DepartmentModel');
		$baseQuery = $this->db->select([
			'purchase_orders.id',
			'purchase_orders.no_purchase',
			'purchase_orders.date',
			'requisitions.request_title',
			'ref_employees.name AS employee_name',
			'ref_departments.department',
			'ref_work_locations.location',
			'ref_vendors.vendor',
			'SUM((purchase_offer_items.quantity_selected * purchase_offer_items.price) - (IFNULL(purchase_offer_items.discount, 0) * purchase_offer_items.quantity_selected)) - offer_discount_goods - offer_discount_services
				+ purchase_orders.delivery_cost 
				+ purchase_orders.miscellaneous_expense 
				+ purchase_orders.rounding_goods 
				+ purchase_orders.rounding_services 
				+ purchase_orders.tax 
				- purchase_orders.tax_basis AS total_price',
			'purchase_orders.rating_user',
			'purchase_orders.rating',
		])
			->from('purchase_orders')
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
				'purchase_offers.is_deleted' => false,
				'purchase_orders.is_deleted' => false,
			])
			->group_by('purchase_orders.id, purchase_offers.id');

		if (key_exists('completed_at_year', $filters) && !empty($filters['completed_at_year'])) {
			//$baseQuery->where('YEAR(purchase_offers.updated_at)', $filters['completed_at_year']);
		}

		if (key_exists('completed_at_week', $filters) && !empty($filters['completed_at_week'])) {
			//$baseQuery->where('WEEK(purchase_offers.updated_at)', $filters['completed_at_week'] - 1);
		}

		if (key_exists('completed_at_year_week', $filters) && !empty($filters['completed_at_year_week'])) {
			$baseQuery->where('YEARWEEK(purchase_offers.updated_at)', $filters['completed_at_year_week']);
		}

		if (key_exists('status', $filters) && !empty($filters['status'])) {
			if (is_array($filters['status'])) {
				$baseQuery->where_in('purchase_offers.status', $filters['status']);
			} else {
				$baseQuery->where('purchase_offers.status', $filters['status']);
			}
		}

		if (key_exists('category', $filters) && !empty($filters['category'])) {
			$baseQuery->where('requisitions.id_category', $filters['category']);
		}

		if (key_exists('item_category', $filters)) {
			$baseQuery->where('requisitions.id_item_category', if_empty($filters['item_category'], null));
		}

		$dateType = get_if_exist($filters, 'date_type', 'purchase_orders.created_at');
		if (key_exists('date_from', $filters) && !empty($filters['date_from'])) {
			$baseQuery->where('DATE(' . $dateType . ')>=', format_date($filters['date_from']));
		}

		if (key_exists('date_to', $filters) && !empty($filters['date_to'])) {
			$baseQuery->where('DATE(' . $dateType . ')<=', format_date($filters['date_to']));
		}

		if (key_exists('vendor', $filters) && !empty($filters['vendor'])) {
			$baseQuery->where('ref_vendors.vendor', $filters['vendor']);
		}

		return $baseQuery->get()->result_array();
	}

	/**
	 * Get reorder query data.
	 *
	 * @param array $filters
	 * @return string
	 */
	private function getReorderQuery($filters = [])
	{
		$baseTransactionQuery = "
			SELECT 
				purchase_orders.id,
				purchase_orders.no_purchase,
				requisition_items.id_item,
				ref_items.item_name,
				(purchase_offer_items.price - purchase_offer_items.discount) AS price,
				purchase_orders.created_at
			FROM purchase_orders
			INNER JOIN purchase_offers ON purchase_offers.id = purchase_orders.id_purchase_offer
			INNER JOIN purchase_offer_items ON purchase_offer_items.id_purchase_offer = purchase_offers.id
			INNER JOIN requisition_items ON requisition_items.id = purchase_offer_items.id_requisition_item
			INNER JOIN ref_items ON ref_items.id = requisition_items.id_item
			WHERE purchase_orders.is_deleted = FALSE AND ref_items.is_controlled_item = 1
			HAVING id_item IS NOT NULL
		";

		if (key_exists('month', $filters) && !empty($filters['month'])) {
			$baseTransactionQuery .= " AND MONTH(purchase_orders.created_at) = '{$filters['month']}'";
		}

		$baseQuery = "
			SELECT 
				YEARWEEK(transactions.created_at, 2) AS year_week,
				LEFT(YEARWEEK(transactions.created_at, 2), LENGTH(YEARWEEK(transactions.created_at, 2)) - 2) AS year, 
				WEEK(transactions.created_at, 2) AS week,
				transactions.id, 
				transactions.no_purchase, 
				transactions.created_at, 
				transactions.id_item, 
				transactions.item_name,  
				transactions.price, 
				MIN(transaction_histories.price) AS cheapest_price,
				(
					SELECT price 
					FROM ({$baseTransactionQuery}) AS latest_transactions
					WHERE id = MAX(transaction_histories.id) AND id_item = transaction_histories.id_item
					LIMIT 1
				) AS latest_price
			FROM ({$baseTransactionQuery}) AS transactions
			INNER JOIN ({$baseTransactionQuery}) AS transaction_histories 
				ON transaction_histories.id_item = transactions.id_item
					AND transactions.created_at > transaction_histories.created_at
			GROUP BY year_week, year, week, id, id_item, price
		";

		// replace existing query with latest data purchase order
		if (key_exists('type', $filters) && !empty($filters['type'])) {
			$baseQuery = "
				SELECT 
					transactions.*,
					MIN(cheapest_orders.id) AS cheapest_order_id,
					MIN(cheapest_orders.no_purchase) AS cheapest_order_no_purchase,
					MIN(cheapest_orders.created_at) AS cheapest_order_created_at,
					latest_orders.id AS latest_order_id,
					latest_orders.no_purchase AS latest_order_no_purchase,
					latest_orders.created_at AS latest_order_created_at
				FROM (
					SELECT 
						YEARWEEK(transactions.created_at, 2) AS year_week,
						LEFT(YEARWEEK(transactions.created_at, 2), LENGTH(YEARWEEK(transactions.created_at, 2)) - 2) AS year, 
						WEEK(transactions.created_at, 2) AS week,
						transactions.id, 
						transactions.no_purchase, 
						transactions.created_at, 
						transactions.id_item, 
						transactions.item_name,  
						transactions.price, 
						MIN(transaction_histories.price) AS cheapest_price,
						(
							SELECT price 
							FROM ({$baseTransactionQuery}) AS latest_transactions
							WHERE id = MAX(transaction_histories.id) AND id_item = transaction_histories.id_item
							LIMIT 1
						) AS latest_price,			
						MAX(transaction_histories.id) AS latest_id
					FROM ({$baseTransactionQuery}) AS transactions
					INNER JOIN ({$baseTransactionQuery}) AS transaction_histories 
						ON transaction_histories.id_item = transactions.id_item
							AND transactions.created_at > transaction_histories.created_at
					GROUP BY year_week, year, week, id, id_item, price
				) AS transactions
				LEFT JOIN ({$baseTransactionQuery}) AS cheapest_orders 
					ON cheapest_orders.created_at < transactions.created_at
						AND cheapest_orders.id_item = transactions.id_item
							AND cheapest_orders.price = transactions.cheapest_price
				LEFT JOIN purchase_orders AS latest_orders 
					ON latest_orders.id = transactions.latest_id
				GROUP BY year_week, year, week, id, id_item, price
			";
		}

		return $baseQuery;
	}

	/**
	 * Get reorder item report.
	 *
	 * @param array $filters
	 * @return array|array[]
	 */
	public function getReorderItemReport($filters = [])
	{
		$reorderQuery = $this->getReorderQuery($filters);

		$baseQuery = $this->db
			->select([
				"transactions.year_week",
				"transactions.year",
				"transactions.week",
				"COUNT(transactions.id_item) AS reorder_item",
				"SUM(IF(transactions.price < cheapest_price, 1, 0)) AS cheaper_than_before",
				"SUM(IF(transactions.price = cheapest_price, 1, 0)) AS same_as_before",
				"SUM(IF(transactions.price > cheapest_price, 1, 0)) AS more_expensive_than_before",
				"SUM(IF(transactions.price < latest_price, 1, 0)) AS cheaper_than_latest",
				"SUM(IF(transactions.price = latest_price, 1, 0)) AS same_as_latest",
				"SUM(IF(transactions.price > latest_price, 1, 0)) AS more_expensive_than_latest"
			])
			->from("({$reorderQuery}) AS transactions")
			->order_by('year_week', 'desc')
			->group_by('year_week, year, week');

		if (key_exists('year', $filters) && !empty($filters['year'])) {
			$baseQuery->having('year', $filters['year']);
		}

		$reports = $baseQuery->get()->result_array();

		// fill the gap week
		if (!empty($reports) && empty(get_url_param('month'))) {
			for ($i = 1; $i < $reports[0]['week']; $i++) {
				$notFound = true;
				foreach ($reports as $index => $report) {
					if ($report['week'] == $i) {
						$notFound = false;
						break;
					}
				}
				if ($notFound) {
					array_splice($reports, count($reports) - $i + 1, 0, [
						[
							'year_week' => $filters['year'] . $i,
							'year' => $filters['year'],
							'week' => $i,
							'reorder_item' => '0',
							'cheaper_than_before' => '0',
							'same_as_before' => '0',
							'more_expensive_than_before' => '0',
							'cheaper_than_latest' => '0',
							'same_as_latest' => '0',
							'more_expensive_than_latest' => '0',
						]
					]);
				}
			}
		}

		return $reports;
	}

	/**
	 * Get reorder item before.
	 *
	 * @param array $filters
	 * @return array|array[]
	 */
	public function getReorderItemDetailReport($filters = [])
	{
		$reorderQuery = $this->getReorderQuery($filters);

		$baseQuery = $this->db
			->select([
				"transactions.*",
			])
			->from("({$reorderQuery}) AS transactions");

		if (key_exists('year_week', $filters) && !empty($filters['year_week'])) {
			$baseQuery->where('transactions.year_week', $filters['year_week']);
		}

		if (key_exists('type', $filters) && !empty($filters['type'])) {
			if (in_array($filters['type'], ['cheaper-than-before', 'same-as-before', 'expensive-than-before'])) {
				$baseQuery
					->select(['transactions.cheapest_price AS comparator_price'])
					->select(['transactions.cheapest_order_id AS comparator_order_id'])
					->select(['transactions.cheapest_order_no_purchase AS comparator_no_purchase'])
					->select(['transactions.cheapest_order_created_at AS comparator_created_at']);
			}
			if (in_array($filters['type'], ['cheaper-than-latest', 'same-as-latest', 'expensive-than-latest'])) {
				$baseQuery
					->select(['transactions.latest_price AS comparator_price'])
					->select(['transactions.latest_order_id AS comparator_order_id'])
					->select(['transactions.latest_order_no_purchase AS comparator_no_purchase'])
					->select(['transactions.latest_order_created_at AS comparator_created_at']);
			}
			switch ($filters['type']) {
				case 'cheaper-than-before':
					$baseQuery->where('transactions.price < transactions.cheapest_price', null);
					break;
				case 'same-as-before':
					$baseQuery->where('transactions.price = transactions.cheapest_price', null);
					break;
				case 'expensive-than-before':
					$baseQuery->where('transactions.price > transactions.cheapest_price', null);
					break;
				case 'cheaper-than-latest':
					$baseQuery->where('transactions.price < transactions.latest_price', null);
					break;
				case 'same-as-latest':
					$baseQuery->where('transactions.price = transactions.latest_price', null);
					break;
				case 'expensive-than-latest':
					$baseQuery->where('transactions.price > transactions.latest_price', null);
					break;
			}
		}

		return $baseQuery->get()->result_array();
	}

	/**
	 * Get category period report.
	 *
	 * @param array $filters
	 * @return array|array[]
	 */
	public function getCategoryPeriodReport($filters = [])
	{
		$baseQuery = $this->db
			->select([
				'ref_categories.id AS id_category',
				'ref_categories.category',
				'ref_item_categories.id AS id_item_category',
				'ref_item_categories.item_name AS item_category',
				'SUM(purchase_orders.total_price) AS total_price',
				'COUNT(purchase_orders.id) AS total_order'
			])
			->from('requisitions')
			->join('ref_categories', 'ref_categories.id = requisitions.id_category')
			->join('ref_item_categories', 'ref_item_categories.id = requisitions.id_item_category', 'left')
			->join('purchase_offers', 'purchase_offers.id_requisition = requisitions.id')
			->join('(
				SELECT  
					purchase_orders.id,
					purchase_orders.id_purchase_offer,
					purchase_orders.is_deleted,
					SUM((purchase_offer_items.quantity_selected * purchase_offer_items.price) - (IFNULL(purchase_offer_items.discount, 0) * purchase_offer_items.quantity_selected)) - offer_discount_goods - offer_discount_services
						+ purchase_orders.delivery_cost 
						+ purchase_orders.miscellaneous_expense 
						+ purchase_orders.rounding_goods 
						+ purchase_orders.rounding_services 
						+ purchase_orders.tax 
						- purchase_orders.tax_basis AS total_price
				FROM purchase_orders
				LEFT JOIN purchase_offers ON purchase_offers.id = purchase_orders.id_purchase_offer
				LEFT JOIN purchase_offer_items ON purchase_offer_items.id_purchase_offer = purchase_offers.id
				GROUP BY purchase_orders.id
			) AS purchase_orders', 'purchase_orders.id_purchase_offer = purchase_offers.id')
			->where([
				'purchase_offers.status' => 'COMPLETED',
				'requisitions.is_deleted' => false,
				'purchase_offers.is_deleted' => false,
				'purchase_orders.is_deleted' => false,
			])
			->group_by('ref_categories.id, ref_item_categories.id');

		if (key_exists('date_from', $filters) && !empty($filters['date_from'])) {
			$baseQuery->where('DATE(purchase_offers.updated_at)>=', format_date($filters['date_from']));
		}

		if (key_exists('date_to', $filters) && !empty($filters['date_to'])) {
			$baseQuery->where('DATE(purchase_offers.updated_at)<=', format_date($filters['date_to']));
		}

		return $baseQuery->get()->result_array();
	}

    /**
     * Get control data.
     *
     * @param array $filters
     * @return array
     */
    public function getControlData($filters = [])
    {
        $this->load->model('DepartmentModel');

        $this->db->start_cache();

        $purchases = $this->db->select([
            'requisitions.no_requisition',
            'requisitions.request_title',
            'requisitions.created_at AS request_date',
            'requisitions.status AS requisition_status',
            'last_approved.created_at AS last_approved_at',
            'last_listed.created_at AS last_notify_vendor_at',
            'ref_employees.name AS employee_name',
            'ref_departments.department',
            'supervisors.name AS supervisor_name',
            'ref_categories.category',
            'ref_item_categories.item_name AS item_category',
            'ref_vendors.vendor',
            'vendor_contacts.name AS contact_person',
            'vendor_contacts.contact',
            'vendor_contacts.email',
            'purchase_offers.created_at AS offer_date',
            'purchase_offers.status AS offer_status',
            'purchase_offers.lead_time',
            'last_ready.created_at AS last_submit_to_manager_at',
            'last_selected.created_at AS last_vendor_selected_at',
            'purchase_orders.no_purchase',
            'purchase_orders.date AS purchase_date',
            'purchase_orders.tax',
            'purchase_orders.tax_percentage',
            'purchase_orders.miscellaneous_expense',
            'purchase_orders.delivery_cost',
            'purchase_offers.offer_discount_goods',
            'purchase_offers.offer_discount_services',
            'IFNULL(SUM(purchase_offers.total_item), 0) AS total_item_type',
            'IFNULL(SUM(purchase_offers.quantity_selected), 0) AS total_item_quantity',
            'IFNULL(SUM(purchase_offers.total), 0) AS total',
            'IFNULL(SUM(purchase_offers.total) + SUM(delivery_cost + miscellaneous_expense + rounding_goods + rounding_services + tax - tax_basis), 0) AS total_price',
            'purchase_orders.rating',
            'last_done.created_at AS completed_at',
        ])
            ->from('requisitions')
            ->join('ref_categories', 'ref_categories.id = requisitions.id_category', 'left')
            ->join('ref_item_categories', 'ref_item_categories.id = requisitions.id_item_category', 'left')
            ->join(EmployeeModel::$tableEmployee, 'ref_employees.id = requisitions.id_employee', 'left')
            ->join(EmployeeModel::$tableEmployee . ' AS supervisors', 'ref_employees.id_employee = supervisors.id', 'left')
            ->join(DepartmentModel::$tableDepartment, 'ref_departments.id = ref_employees.id_department', 'left')
            ->join('(
                SELECT 
                    purchase_offers.id, id_vendor, id_requisition, lead_time, offer_discount_goods, offer_discount_services, status, purchase_offers.created_at,
                    COUNT(DISTINCT purchase_offer_items.id) AS total_item, 
                    SUM(quantity_selected) AS quantity_selected,
                    SUM((price * quantity_selected) - (discount * quantity_selected)) - offer_discount_goods - offer_discount_services AS total
                FROM purchase_offers
                INNER JOIN purchase_offer_items 
                    ON purchase_offer_items.id_purchase_offer = purchase_offers.id 
                WHERE purchase_offers.status = "COMPLETED" OR purchase_offers.status = "ORDERED"
                GROUP BY purchase_offers.id, id_vendor, id_requisition, offer_discount_goods, offer_discount_services, status, purchase_offers.created_at
            ) AS purchase_offers', 'purchase_offers.id_requisition = requisitions.id')
            ->join('ref_vendors', 'ref_vendors.id = purchase_offers.id_vendor', 'left')
            ->join('(
                SELECT DISTINCT id_vendor, name, contact, email 
                FROM ref_vendor_contacts 
                WHERE is_primary = 1
                ) AS vendor_contacts', 'vendor_contacts.id_vendor = ref_vendors.id', 'left')
            ->join('purchase_orders', 'purchase_orders.id_purchase_offer = purchase_offers.id', 'left')
            ->join('(
                SELECT id_requisition, MAX(created_at) AS created_at
                FROM requisition_statuses
                WHERE STATUS LIKE "APPROVED%"
                GROUP BY id_requisition DESC
                ) AS last_approved', 'last_approved.id_requisition = requisitions.id', 'left')
            ->join('(
                SELECT id_requisition, MAX(created_at) AS created_at
                FROM requisition_statuses
                WHERE STATUS LIKE "LISTED%"
                GROUP BY id_requisition DESC
                ) AS last_listed', 'last_listed.id_requisition = requisitions.id', 'left')
            ->join('(
                SELECT id_requisition, MAX(created_at) AS created_at
                FROM requisition_statuses
                WHERE STATUS LIKE "READY%"
                GROUP BY id_requisition DESC
                ) AS last_ready', 'last_ready.id_requisition = requisitions.id', 'left')
            ->join('(
                SELECT id_requisition, MAX(created_at) AS created_at
                FROM requisition_statuses
                WHERE STATUS LIKE "SELECTED%"
                GROUP BY id_requisition DESC
                ) AS last_selected', 'last_selected.id_requisition = requisitions.id', 'left')
            ->join('(
                SELECT id_requisition, MAX(created_at) AS created_at
                FROM requisition_statuses
                WHERE STATUS LIKE "DONE%"
                GROUP BY id_requisition DESC
                ) AS last_done', 'last_done.id_requisition = requisitions.id', 'left')
            ->group_by('requisitions.id, purchase_offers.id, purchase_orders.id, vendor_contacts.name, vendor_contacts.contact, vendor_contacts.email')
            ->order_by('requisitions.id', 'desc');

        if (!empty($filters)) {
            if (key_exists('search', $filters) && !empty($filters['search'])) {
                $purchases->group_start();
                $fields = $this->db->list_fields('requisitions');
                foreach ($fields as $field) {
                    $purchases->or_like('requisitions.' . $field, trim($filters['search']));
                }
                $fields = $this->db->list_fields('purchase_orders');
                foreach ($fields as $field) {
                    $purchases->or_like('purchase_orders.' . $field, trim($filters['search']));
                }
                $purchases->group_end();
            }

            if (key_exists('date_from', $filters) && !empty($filters['date_from'])) {
                $purchases->where('requisitions.created_at>=', format_date($filters['date_from']));
            }

            if (key_exists('date_to', $filters) && !empty($filters['date_to'])) {
                $purchases->where('requisitions.created_at<=', format_date($filters['date_to']));
            }

            if (key_exists('status', $filters) && !empty($filters['status'])) {
                if (!is_array($filters['status'])) {
                    $filters['status'] = [$filters['status']];
                }
                $purchases->where_in('requisitions.status', $filters['status']);
            }
        }

        $this->db->stop_cache();

        if (key_exists('per_page', $filters) && !empty($filters['per_page'])) {
            $perPage = $filters['per_page'];
        } else {
            $perPage = 20;
        }
        if (key_exists('page', $filters) && !empty($filters['page'])) {
            $currentPage = $filters['page'];

            $totalData = $this->db->count_all_results();

            if (key_exists('sort_by', $filters) && !empty($filters['sort_by'])) {
                if (key_exists('order_method', $filters) && !empty($filters['order_method'])) {
                    $purchases->order_by($filters['sort_by'], $filters['order_method']);
                } else {
                    $purchases->order_by($filters['sort_by'], 'asc');
                }
            } else {
                $purchases->order_by('purchase_orders.date', 'desc');
            }
            $pageData = $purchases->limit($perPage, ($currentPage - 1) * $perPage)->get()->result_array();

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
        } else {
            $purchases->order_by('requisitions.' . $this->id, 'desc');
        }

        $data = $purchases->get()->result_array();

        $this->db->flush_cache();

        return $data;
    }

    /**
     * Get PO - Invoice data.
     *
     * @param array $filters
     * @return array
     */
    public function getPoInvoiceData($filters = [])
    {
        $this->db->start_cache();

        $purchases = $this->db->select([
            'requisitions.id AS requisition_id',
            'requisitions.no_requisition',
            'requisitions.created_at AS request_date',
            'requisitions.deadline AS requisition_deadline',
            'ref_vendors.vendor',
			'purchase_orders.id AS purchase_order_id',
            'purchase_orders.no_purchase',
            'purchase_orders.date AS purchase_date',
            'payment_handovers.id AS handover_id',
            'payment_handovers.no_handover',
            'payment_handovers.handover_date',
            'payment_handover_po.vendor_invoice',
            'payment_handover_po.invoice_date AS vendor_invoice_date',
        ])
            ->from('requisitions')
            ->join(EmployeeModel::$tableEmployee, 'ref_employees.id = requisitions.id_employee', 'left')
            ->join(EmployeeModel::$tableEmployee . ' AS supervisors', 'ref_employees.id_employee = supervisors.id', 'left')
            ->join('(
                SELECT 
                    purchase_offers.id, id_vendor, id_requisition, lead_time, offer_discount_goods, offer_discount_services, status, purchase_offers.created_at,
                    COUNT(DISTINCT purchase_offer_items.id) AS total_item, 
                    SUM(quantity_selected) AS quantity_selected,
                    SUM((price * quantity_selected) - (discount * quantity_selected)) - offer_discount_goods - offer_discount_services AS total
                FROM purchase_offers
                INNER JOIN purchase_offer_items 
                    ON purchase_offer_items.id_purchase_offer = purchase_offers.id 
                GROUP BY purchase_offers.id, id_vendor, id_requisition, offer_discount_goods, offer_discount_services, status, purchase_offers.created_at
            ) AS purchase_offers', 'purchase_offers.id_requisition = requisitions.id')
            ->join('ref_vendors', 'ref_vendors.id = purchase_offers.id_vendor', 'left')
            ->join('purchase_orders', 'purchase_orders.id_purchase_offer = purchase_offers.id', 'left')
            ->join('payment_handover_po', 'payment_handover_po.id_purchase_order = purchase_orders.id', 'left')
            ->join('payment_handovers', 'payment_handover_po.id_handover = payment_handovers.id', 'left')
			->where('purchase_orders.id is not null')
            ->group_by('requisitions.id, purchase_offers.id, purchase_orders.id')
            ->order_by('requisitions.id', 'desc');

        if (!empty($filters)) {
            if (key_exists('search', $filters) && !empty($filters['search'])) {
                $purchases->group_start();
                $fields = $this->db->list_fields('requisitions');
                foreach ($fields as $field) {
                    $purchases->or_like('requisitions.' . $field, trim($filters['search']));
                }
                $fields = $this->db->list_fields('purchase_orders');
                foreach ($fields as $field) {
                    $purchases->or_like('purchase_orders.' . $field, trim($filters['search']));
                }
                $fields = $this->db->list_fields('payment_handovers');
                foreach ($fields as $field) {
                    $purchases->or_like('payment_handovers.' . $field, trim($filters['search']));
                }
                $fields = $this->db->list_fields('ref_vendors');
                foreach ($fields as $field) {
                    $purchases->or_like('ref_vendors.' . $field, trim($filters['search']));
                }
                $purchases->group_end();
            }

            if (key_exists('date_from', $filters) && !empty($filters['date_from'])) {
                if (key_exists('date_by', $filters) && !empty($filters['date_by'])) {
                    switch ($filters['date_by']) {
                        case 'deadline':
                            $purchases->where('requisitions.deadline>=', format_date($filters['date_from']));
                            break;
                        case 'purchase_date':
                            $purchases->where('purchase_orders.date>=', format_date($filters['date_from']));
                            break;
                        case 'vendor_invoice_date':
                            $purchases->where('payment_handover_po.invoice_date>=', format_date($filters['date_from']));
                            break;
                        case 'handover_date':
                            $purchases->where('payment_handovers.handover_date>=', format_date($filters['date_from']));
                            break;
                        default:
                            $purchases->where('requisitions.created_at>=', format_date($filters['date_from']));
                            break;
                    }
                }else{
                    $purchases->where('requisitions.created_at>=', format_date($filters['date_from']));
                }
            }

            if (key_exists('date_to', $filters) && !empty($filters['date_to'])) {
                if (key_exists('date_by', $filters) && !empty($filters['date_by'])) {
                    switch ($filters['date_by']) {
                        case 'deadline':
                            $purchases->where('requisitions.deadline<=', format_date($filters['date_to']));
                            break;
                        case 'purchase_date':
                            $purchases->where('purchase_orders.date<=', format_date($filters['date_to']));
                            break;
                        case 'vendor_invoice_date':
                            $purchases->where('payment_handover_po.invoice_date<=', format_date($filters['date_to']));
                            break;
                        case 'handover_date':
                            $purchases->where('payment_handovers.handover_date<=', format_date($filters['date_to']));
                            break;
                        default:
                            $purchases->where('requisitions.created_at<=', format_date($filters['date_to']));
                            break;
                    }
                }else{
                    $purchases->where('requisitions.created_at<=', format_date($filters['date_to']));
                }
            }

            if (key_exists('status', $filters) && !empty($filters['status'])) {
                if (!is_array($filters['status'])) {
                    $filters['status'] = [$filters['status']];
                }
                $purchases->where_in('requisitions.status', $filters['status']);
            }
        }

        $this->db->stop_cache();

        if (key_exists('per_page', $filters) && !empty($filters['per_page'])) {
            $perPage = $filters['per_page'];
        } else {
            $perPage = 20;
        }
        if (key_exists('page', $filters) && !empty($filters['page'])) {
            $currentPage = $filters['page'];

            $totalData = $this->db->count_all_results();

            if (key_exists('sort_by', $filters) && !empty($filters['sort_by'])) {
                if (key_exists('order_method', $filters) && !empty($filters['order_method'])) {
                    $purchases->order_by($filters['sort_by'], $filters['order_method']);
                } else {
                    $purchases->order_by($filters['sort_by'], 'asc');
                }
            } else {
                $purchases->order_by('purchase_orders.date', 'desc');
            }
            $pageData = $purchases->limit($perPage, ($currentPage - 1) * $perPage)->get()->result_array();

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
        } else {
            $purchases->order_by('requisitions.' . $this->id, 'desc');
        }

        $data = $purchases->get()->result_array();

        $this->db->flush_cache();

        return $data;
    }

    /**
     * Get purchase item history.
     *
     * @param array $filters
     * @return array
     */
    public function getItemPrices($filters = [])
    {
        $offer = $this->db->select([
            '"" AS id_purchase_order',
            '"" AS id_purchase_offer',
            '"" AS id_requisition',
            '"" AS request_title',
            '"" AS request_date',
            '"" AS id_item',
            'item_name',
            '1 AS quantity',
            '"UNIT" AS unit',
            'price AS unit_price',
            'vendor',
			'ref_vendor_offers.created_at AS offer_date',
            '"" AS no_purchase',
            '"" AS purchase_date',
            '"OFFER" AS item_price_type',
        ])
            ->from('ref_vendor_offers')
            ->join('ref_vendors', 'ref_vendors.id = ref_vendor_offers.id_vendor');

        $offerQuery = $offer->get_compiled_select();

        $purchased = $this->db->select([
            'purchase_orders.id AS id_purchase_order',
            'purchase_offers.id AS id_purchase_offer',
            'requisitions.id AS id_requisition',
            'requisitions.request_title',
            'requisitions.created_at AS request_date',
            'ref_items.id AS id_item',
			'IFNULL(ref_items.item_name, requisition_items.item_name) AS item_name',
            'purchase_offer_items.quantity_selected AS quantity',
            'requisition_items.unit',
            'purchase_offer_items.price AS unit_price',
            'ref_vendors.vendor',
			'purchase_offers.created_at AS offer_date',
            'purchase_orders.no_purchase',
            'purchase_orders.date AS purchase_date',
            '"PURCHASED" AS item_price_type',
        ])
            ->from('purchase_orders')
            ->join('purchase_offers', 'purchase_offers.id = purchase_orders.id_purchase_offer')
            ->join('purchase_offer_items', 'purchase_offer_items.id_purchase_offer = purchase_offers.id')
            ->join('requisition_items', 'requisition_items.id = purchase_offer_items.id_requisition_item')
			->join('ref_items', 'ref_items.id = requisition_items.id_item', 'left')
            ->join('ref_vendors', 'ref_vendors.id = purchase_offers.id_vendor')
            ->join('requisitions', 'requisitions.id = purchase_offers.id_requisition')
            ->join(EmployeeModel::$tableEmployee, 'ref_employees.id = requisitions.id_employee', 'left')
            ->where('purchase_orders.is_deleted', 'false');

        $purchasedQuery = $purchased->get_compiled_select();

        $this->db->start_cache();

        $itemPrices = $this->db->from("(
            $offerQuery
            UNION ALL
            $purchasedQuery
        ) AS items");

        if (!empty($filters)) {
            if (key_exists('search', $filters) && !empty($filters['search'])) {
                $searchFields = [
                    'request_title', 'item_name', 'unit', 'quantity', 'unit_price', 'no_purchase', 'vendor', 'item_price_type'
                ];
                $itemPrices->group_start();
                foreach ($searchFields as $field) {
                    $itemPrices->or_like($field, trim($filters['search']));
                }
                $itemPrices->group_end();
            }

            if (key_exists('item', $filters) && !empty($filters['item'])) {
                $itemPrices->where('id_item', $filters['item']);
            }

			$dateType = get_if_exist($filters, 'date_type', 'request_date');
			if (key_exists('date_from', $filters) && !empty($filters['date_from'])) {
				$itemPrices->where('DATE(' . $dateType . ')>=', format_date($filters['date_from']));
			}

			if (key_exists('date_to', $filters) && !empty($filters['date_to'])) {
				$itemPrices->where('DATE(' . $dateType . ')<=', format_date($filters['date_to']));
			}
        }

        $this->db->stop_cache();

        if (key_exists('per_page', $filters) && !empty($filters['per_page'])) {
            $perPage = $filters['per_page'];
        } else {
            $perPage = 20;
        }

        if (key_exists('page', $filters) && !empty($filters['page'])) {
            $currentPage = $filters['page'];

            $totalData = $this->db->count_all_results();

            if (key_exists('sort_by', $filters) && !empty($filters['sort_by'])) {
                if (key_exists('order_method', $filters) && !empty($filters['order_method'])) {
                    $itemPrices->order_by($filters['sort_by'], $filters['order_method']);
                } else {
                    $itemPrices->order_by($filters['sort_by'], 'asc');
                }
            } else {
                $itemPrices->order_by('offer_date', 'desc');
            }
            $pageData = $itemPrices->limit($perPage, ($currentPage - 1) * $perPage)->get()->result_array();

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
        } else {
            $itemPrices->order_by('offer_date', 'desc');
        }

        $data = $itemPrices->get()->result_array();

        $this->db->flush_cache();

        return $data;
    }

    /**
     * Get latest demand and offer data.
     *
     * @return array
     */
    public function getDemandOfferData()
    {
        $report = $this->db->query("
            SELECT 
              DATE_FORMAT(requisitions.date, '%b %Y') AS date, 
              IFNULL(total_request, 0) AS requests, 
              IFNULL(total_order, 0) AS orders 
            FROM (
              SELECT 
                COUNT(id) AS total_request, 
                CONCAT(YEAR(created_at), '-', MONTH(created_at), '-1') AS date
              FROM requisitions
              GROUP BY CONCAT(YEAR(created_at), '-', MONTH(created_at), '-1')
            ) requisitions
               
            LEFT JOIN (
              SELECT 
                COUNT(id) AS total_order, 
                CONCAT(YEAR(date), '-', MONTH(date), '-1') AS date
              FROM purchase_orders
              GROUP BY CONCAT(YEAR(date), '-', MONTH(date), '-1')
            ) AS orders
              ON requisitions.date = orders.date
            
            ORDER BY DATE_FORMAT(requisitions.date, '%Y %m') DESC
            LIMIT 12
        ");

        return $report->result_array();
    }

    /**
     * Get total purchase cost in last 12 months.
     * @return array
     */
    public function getPurchaseCost()
    {
        $report = $this->db->query("
            SELECT DATE_FORMAT(date, '%b %Y') AS date, total_cost 
            FROM (
                SELECT 
                  CONCAT(YEAR(purchase_orders.date), '-', MONTH(purchase_orders.date), '-1') AS date,
                  IFNULL(SUM(purchase_offers.total) + SUM(delivery_cost + miscellaneous_expense + rounding_goods + rounding_services + tax - tax_basis), 0) AS total_cost
                FROM (
                    SELECT purchase_offers.id, SUM((price * quantity_selected) - (discount * quantity_selected)) - offer_discount_goods - offer_discount_services AS total
                    FROM purchase_offers
                    INNER JOIN purchase_offer_items 
                        ON purchase_offer_items.id_purchase_offer = purchase_offers.id 
                    WHERE purchase_offers.status = 'COMPLETED' OR purchase_offers.status = 'ORDERED'
                    GROUP BY purchase_offers.id
                ) AS purchase_offers
                INNER JOIN purchase_orders
                  ON purchase_orders.id_purchase_offer = purchase_offers.id
                GROUP BY CONCAT(YEAR(purchase_orders.date), '-', MONTH(purchase_orders.date), '-1')
            ) AS total_costs
            ORDER BY DATE_FORMAT(date, '%Y %m') DESC
            LIMIT 12
        ");

        return $report->result_array();
    }
}
