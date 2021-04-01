<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Dashboard
 * @property ReportModel $report
 * @property RequisitionModel $requisition
 */
class Dashboard extends App_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('ReportModel', 'report');
        $this->load->model('RequisitionModel', 'requisition');
    }

    /**
     * Show dashboard page.
     */
    public function index()
    {
        $allStats = $this->report->getPurchaseTotalStats();
        $currentMonthlyStats = $this->report->getMonthlyTotalStats(date('Y'), date('m'));

        $lastMonth = date("Y-m-d", strtotime("-1 month"));
        $extractedMonth = format_date($lastMonth, 'm');
        $extractedYear = format_date($lastMonth, 'Y');
        $lastMonthStats = $this->report->getMonthlyTotalStats($extractedYear, $extractedMonth);
        if ($lastMonthStats['total_price'] == 0) {
            $diffPercent = 100;
        } else {
            $diffPercent = $currentMonthlyStats['total_price'] / $lastMonthStats['total_price'] * 100;
        }
        $currentMonthlyStats['last_total_price'] = $lastMonthStats['total_price'];
        $currentMonthlyStats['diff_percent'] = $diffPercent;

        $requisitionStats = [
            'total' => $totalRequisition = $this->requisition->getTotal(),
            'proceed' => $this->requisition->getBy(['requisitions.status' => RequisitionModel::STATUS_DONE], 'COUNT'),
            'outstanding' => $this->db->from('requisitions')
                ->where('status!=', 'DONE')
                ->where('status!=', 'REJECTED')
                ->where('status!=', 'CANCELLED')
                ->where('status!=', 'PENDING')
                ->where('is_deleted', false)
                ->count_all_results(),
        ];

        $manageRequisition = AuthorizationModel::hasPermission([PERMISSION_REQUISITION_MANAGE, PERMISSION_REQUISITION_ADMIN_VIEW]);
        $requisitions = $this->requisition->getAll([
            'page' => 1,
            'per_page' => 5,
            'sort_by' => 'created_at',
            'order_method' => 'desc',
            'employees' => $manageRequisition ? '' : UserModel::loginData('id_employee')
        ]);

        $this->render('dashboard/index', compact('allStats', 'currentMonthlyStats', 'requisitionStats', 'requisitions'));
    }
}
