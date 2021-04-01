<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Report
 * @property CategoryModel $category
 * @property ItemModel $item
 * @property ItemCategoryModel $itemCategory
 * @property ReportModel $report
 * @property PurchaseOrderModel $purchaseOrder
 * @property Exporter $exporter
 */
class Report extends App_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('CategoryModel', 'category');
        $this->load->model('ItemModel', 'item');
        $this->load->model('ItemCategoryModel', 'itemCategory');
        $this->load->model('ReportModel', 'report');
        $this->load->model('PurchaseOrderModel', 'purchaseOrder');
        $this->load->model('modules/Exporter', 'exporter');

        $this->setFilterMethods([
            'summary' => 'GET',
            'summary_detail' => 'GET',
            'satisfied_recipient' => 'GET',
            'reorder_item' => 'GET',
            'reorder_item_detail' => 'GET',
            'satisfied_detail' => 'GET',
            'category_period' => 'GET',
            'control' => 'GET',
            'statistic' => 'GET',
            'item_prices' => 'GET',
            'po_invoice' => 'GET',
        ]);

        AuthorizationModel::mustAuthorized(PERMISSION_REPORT_GENERAL_VIEW);
    }

    /**
     * Show result search page.
     */
    public function summary()
    {
        $filters = array_merge($_GET, ['page' => get_url_param('page', 1)]);

        $export = $this->input->get('export');
        if ($export) unset($filters['page']);

        $reports = $this->report->getPurchaseHistory($filters);

        if ($export) {
            $this->exporter->exportFromArray('Purchase Summary', $reports);
        }

        $this->render('report/summary', compact('reports'));
    }

    /**
     * Show report summary detail page.
     *
     * @param $year
     * @param $month
     */
    public function summary_detail($year, $month)
    {
        $filters = array_merge($_GET, ['page' => get_url_param('page', 1)]);
        $filters['year'] = $year;
        $filters['month'] = $month;

        $export = $this->input->get('export');
        if ($export) unset($filters['page']);

        $reports = $this->report->getPurchaseHistoryDetail($filters);

        if ($export) {
            $this->exporter->exportFromArray('Purchase Summary ' . $year . '-' . $month, $reports);
        }

        $this->render('report/summary_detail', compact('reports', 'year', 'month'));
    }

	/**
	 * Get report satisfied recipient
	 */
    public function satisfied_recipient()
	{
		$filters = $_GET;
		if (empty(get_url_param('year'))) {
			$filters['year'] = date('Y');
		}

		/* Generate date in week range
		$date = Carbon::now();
		for ($i = 1; $i <= 53; $i++) {
			$date->setISODate($filters['year'], $i);
			if($i == 1) {
				echo $filters['year'] . '-01-01';
			} else {
				echo $date->startOfWeek(0)->toDateString();
			}
			echo ' - ';
			if($i <= 52) {
				echo $date->endOfWeek(6)->toDateString();
			} else {
				echo $filters['year'] . '-12-31';
			}
			echo '<br>';
		}
		// Test week range standard ISO-8601
		print_debug(get_week_date_range(1, 2019), false);
		print_debug(get_week_date_range(53, 2019), false);
		print_debug(get_week_date_range(1, 2020), false);
		print_debug(get_week_date_range(53, 2020), false);
		print_debug(get_week_date_range(1, 2021), false);
		echo '----------';
		// Test week range sql mode 2
		print_debug(get_week_date_range_sql_mode_2(1, 2019), false);
		print_debug(get_week_date_range_sql_mode_2(52, 2019), false);
		print_debug(get_week_date_range_sql_mode_2(1, 2020), false);
		print_debug(get_week_date_range_sql_mode_2(1, 2021));
		*/

		$reports = $this->report->getSatisfiedRecipientReport($filters);
		if (!empty($reports) && empty(get_url_param('month'))) {
			$firstWeek = end($reports);
			$firstWeekRange = get_week_date_range_sql_mode_2($firstWeek['week'], $firstWeek['year']);
			if ($firstWeekRange['week_start'] != $filters['year'] . '-01-01') {
				$lastYearFilter = $filters;
				$lastYearFilter['year'] = $filters['year'] - 1;
				$lastYearReports = $this->report->getSatisfiedRecipientReport($lastYearFilter);
				if (!empty($lastYearReports)) {
					$reports[] = $lastYearReports[0];
				}
			}
		}

		if ($this->input->get('export')) {
			$this->exporter->exportFromArray('Satisfied recipient', $reports);
		} else {
			$this->render('report/satisfied_recipient', compact('reports'));
		}
	}

	/**
	 * Show satisfied detail by filters
	 */
	function satisfied_detail()
	{
		$filters = $_GET;
		if (!key_exists('status', $filters)) {
			$filters['status'] = PurchaseOfferModel::STATUS_COMPLETED;
		}
		$purchaseOrders = $this->report->getSatisfiedRecipientDetailReport($filters);

		if ($this->input->get('export')) {
			$this->exporter->exportFromArray('Satisfied orders', $purchaseOrders);
		} else {
			$this->render('report/satisfied_detail', compact('purchaseOrders'));
		}
	}

	/**
	 * Show weekly reorder item comparison.
	 */
	public function reorder_item()
	{
		$filters = $_GET;
		if (empty(get_url_param('year'))) {
			$filters['year'] = date('Y');
		}
		$reports = $this->report->getReorderItemReport($filters);

		if ($this->input->get('export')) {
			$this->exporter->exportFromArray('Reorder item', $reports);
		} else {
			$this->render('report/reorder_item', compact('reports'));
		}
	}

	/**
	 * Show reorder item detail price (cheapest or more expensive)
	 */
	public function reorder_item_detail()
	{
		$filters = $_GET;
		if (empty(get_url_param('type'))) {
			$filters['type'] = 'all';
		}
		$reports = $this->report->getReorderItemDetailReport($filters);

		if ($this->input->get('export')) {
			foreach ($reports as &$report) {
				unset($report['latest_order_id']);
				unset($report['cheapest_order_id']);
				if (!empty($this->input->get('type'))) {
					unset($report['cheapest_price']);
					unset($report['latest_price']);
					unset($report['cheapest_order_no_purchase']);
					unset($report['cheapest_order_created_at']);
					unset($report['latest_order_no_purchase']);
					unset($report['latest_order_created_at']);
					unset($report['comparator_order_id']);
				}
			}
			$this->exporter->exportFromArray('Reorder item' . if_empty(get_url_param('type'), '', ' '), $reports);
		} else {
			$this->render('report/reorder_item_detail', compact('reports'));
		}
	}

	/**
	 * Show category period report,
	 * group report into the purchase amount or time of order in group of collection dates.
	 */
	function category_period()
	{
		$categories = $this->category->getAll();

		$periodReports = [];
		$categoryPurchasePeriods = [];
		$categoryOrderPeriods = [];
		foreach (get_url_param('dates', []) as $date) {
			/**
			 * Get transaction in between of the date,
			 * this report grouped by category and category item.
			 *
			 * $report = [
			 * 	 [
			 *		"category" => "BUILDING & EQUAL"
			 * 		"item_category" => "PAGAR"
			 * 		"total_price" => 166903863,
			 * 		"total_order" => 23
			 *  ],
			 * 	[
			 *		"category" => "BUILDING & EQUAL"
			 * 		"item_category" => "PAGAR"
			 * 		"total_price" => TEMBOK,
			 * 		"total_order" => 23
			 *  ],
			 * ]
			 */
			$reports = $this->report->getCategoryPeriodReport([
				'date_from' => $date['from'],
				'date_to' => $date['to'],
			]);

			// we need group per category, reduce multi row with same category (sum price and total category)
			$categoryReports = array_reduce($reports, function($carry, $item) {
				$existingCategory = get_if_exist($carry, $item['id_category'], [
					'total_price' => 0,
					'total_order' => 0,
				]);
				$carry[$item['id_category']] = [
					'id_category' => $item['id_category'],
					'category' => $item['category'],
					'total_price' => $existingCategory['total_price'] + $item['total_price'],
					'total_order' => $existingCategory['total_order'] + $item['total_order'],
				];
				return $carry;
			});
			$categoryReports = array_values(if_empty($categoryReports, []));

			// just add placeholder for empty result
			$reportItem = [
				'date_from' => $date['from'],
				'date_to' => $date['to'],
				'id_category_biggest' => 0,
				'category_biggest' => '',
				'amount' => 0,
				'id_category_times' => 0,
				'category_times' => '',
				'order' => 0,
			];

			if (!empty($categoryReports)) {
				// sort by total price then get the most largest item (first element)
				usort($categoryReports, function ($a, $b) {
					return $b['total_price'] - $a['total_price'];
				});
				$reportItem['id_category_biggest'] = $categoryReports[0]['id_category'];
				$reportItem['category_biggest'] = $categoryReports[0]['category'];
				$reportItem['amount'] = $categoryReports[0]['total_price'];

				// sort by total order then get the most largest item (first element)
				usort($categoryReports, function ($a, $b) {
					return $b['total_order'] - $a['total_order'];
				});
				$reportItem['id_category_times'] = $categoryReports[0]['id_category'];
				$reportItem['category_times'] = $categoryReports[0]['category'];
				$reportItem['order'] = $categoryReports[0]['total_order'];
			}
			$periodReports[] = $reportItem;

			// add category purchase and order
			foreach ($categories as $category) {
				$categoryPurchasePeriods[$category['id']]['category'] = $category['category'];
				$categoryOrderPeriods[$category['id']]['category'] = $category['category'];

				$totalPurchase = 0;
				$totalOrder = 0;
				foreach ($categoryReports as $categoryReport) {
					if ($category['id'] == $categoryReport['id_category']) {
						$totalPurchase = $categoryReport['total_price'];
						$totalOrder = $categoryReport['total_order'];
						break;
					}
				}
				$categoryPurchasePeriods[$category['id']][$date['from'] . ' - ' . $date['to']] = $totalPurchase;
				$categoryOrderPeriods[$category['id']][$date['from'] . ' - ' . $date['to']] = $totalOrder;

				// add item category
				$itemCategories = $this->itemCategory->getBy(['ref_item_categories.id_category' => $category['id']]);
				if (empty($itemCategories) && !array_key_exists('item_categories', $categoryPurchasePeriods[$category['id']])) {
					$categoryPurchasePeriods[$category['id']]['item_categories'] = [];
				} else {
					$itemCategories[] = [
						'id' => 0,
						'item_name' => 'NO ITEM CATEGORY'
					];
				}
				$categoryOrderPeriods[$category['id']]['item_categories'] = [];
				foreach ($itemCategories as $itemCategory) {
					$categoryPurchasePeriods[$category['id']]['item_categories'][$itemCategory['id']]['item_category'] = $itemCategory['item_name'];
					$categoryOrderPeriods[$category['id']]['item_categories'][$itemCategory['id']]['item_category'] = $itemCategory['item_name'];

					$totalPurchaseItem = 0;
					$totalOrderItem = 0;
					foreach ($reports as $itemCategoryReport) {
						if ($category['id'] == $itemCategoryReport['id_category'] && $itemCategory['id'] == $itemCategoryReport['id_item_category']) {
							$totalPurchaseItem = $itemCategoryReport['total_price'];
							$totalOrderItem = $itemCategoryReport['total_order'];
							break;
						}
					}
					$categoryPurchasePeriods[$category['id']]['item_categories'][$itemCategory['id']][$date['from'] . ' - ' . $date['to']] = $totalPurchaseItem;
					$categoryOrderPeriods[$category['id']]['item_categories'][$itemCategory['id']][$date['from'] . ' - ' . $date['to']] = $totalOrderItem;
				}
			}
		}
		$this->skipEmptyCategoryPeriod($categoryPurchasePeriods);
		$this->skipEmptyCategoryPeriod($categoryOrderPeriods);

		switch ($this->input->get('export')) {
			case 'category-period':
				$this->exporter->exportFromArray('Period report', $periodReports);
				break;
			case 'purchase-period':
				$categoryPurchasePeriods = $this->addSummaryPeriodReport($categoryPurchasePeriods);
				$this->exporter->exportFromArray('Purchase report', $categoryPurchasePeriods);
				break;
			case 'order-period':
				$categoryOrderPeriods = $this->addSummaryPeriodReport($categoryOrderPeriods);
				$this->exporter->exportFromArray('Order report', $categoryOrderPeriods);
				break;
			default:
				$this->render('report/category_period', compact('periodReports', 'categoryPurchasePeriods', 'categoryOrderPeriods'));
		}
	}

	/**
	 * Add summary report.
	 *
	 * @param $reports
	 * @return mixed
	 */
	private function addSummaryPeriodReport($reports)
	{
		foreach ($reports as $index => $item) {
			unset($reports[$index]['item_categories']);
		}
		$reports['total'] = ['total' => 'TOTAL'];
		foreach (get_url_param('dates') as $date) {
			$key = $date['from'] . ' - ' . $date['to'];
			$total = array_sum(array_column($reports, $key));
			$reports['total'][$key] = $total;
		}
		return $reports;
	}

	/**
	 * Skip empty category period.
	 *
	 * @param $reports
	 */
	private function skipEmptyCategoryPeriod(&$reports)
	{
		foreach ($reports as &$report) {
			foreach ($report['item_categories'] as $index => $item) {
				$allEmpty = true;
				foreach (get_url_param('dates', []) as $date) {
					if (!empty($item[$date['from'] . ' - ' . $date['to']])) {
						$allEmpty = false;
						break;
					}
				}
				if ($allEmpty) {
					unset($report['item_categories'][$index]);
				}
			}
		}
	}

    /**
     * Show report control page.
     */
    public function control()
    {
        $filters = array_merge($_GET, ['page' => get_url_param('page', 1)]);

        $export = $this->input->get('export');
        if ($export) unset($filters['page']);

        $reports = $this->report->getControlData($filters);

        if ($export) {
            $this->exporter->exportFromArray('Control Data', $reports);
        }

        $this->render('report/control', compact('reports'));
	}
	
	/**
     * Show report PO - Invoice.
     */
    public function po_invoice()
    {
        $filters = array_merge($_GET, ['page' => get_url_param('page', 1)]);

        $export = $this->input->get('export');
        if ($export) unset($filters['page']);

        $reports = $this->report->getPoInvoiceData($filters);

        if ($export) {
			$reports = array_map(function($tag) {
				return array(
					'no_requisition' => $tag['no_requisition'],
					'request_date' => $tag['request_date'],
					'requisition_deadline' => $tag['requisition_deadline'],
					'vendor' => $tag['vendor'],
					'no_purchase' => $tag['no_purchase'],
					'purchase_date' => $tag['purchase_date'],
					'vendor_invoice' => $tag['vendor_invoice'],
					'vendor_invoice_date' => $tag['vendor_invoice_date'],
					'no_finance' => $tag['no_handover'],
					'finance_date' => $tag['handover_date']
				) ;
			}, $reports);
            $this->exporter->exportFromArray('PO - Invoice', $reports);
        }

        $this->render('report/po_invoice', compact('reports'));
    }

    /**
     * Show report control page.
     */
    public function item_prices()
    {
        $filters = array_merge($_GET, ['page' => get_url_param('page', 1)]);

        $export = $this->input->get('export');
        if ($export) unset($filters['page']);

        $reports = $this->report->getItemPrices($filters);

        if ($export) {
            $this->exporter->exportFromArray('Purchase Item', $reports);
        }
        $items = $this->item->getAll();

        $this->render('report/item', compact('reports', 'items'));
    }

    /**
     * Show report statistic page.
     */
    public function statistic()
    {
        $demandOffer = $this->report->getDemandOfferData();
        $purchaseCost = $this->report->getPurchaseCost();

        $this->render('report/statistic', compact('demandOffer', 'purchaseCost'));
    }
}
