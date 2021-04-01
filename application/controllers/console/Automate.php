<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Automate
 * @property ReportModel $report
 * @property PurchaseOrderModel $purchaseOrder
 * @property PurchaseOfferModel $purchaseOffer
 * @property RequisitionModel $requisition
 * @property RequisitionStatusModel $requisitionStatus
 * @property PurchaseOfferItemModel $purchaseOfferItem
 * @property HandoverModel $handover
 * @property EmployeeModel $employee
 * @property NotificationModel $notification
 * @property UserModel $user
 * @property Mailer $mailer
 * @property Exporter $exporter
 */
class Automate extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (is_cli() || env('APP_ENVIRONMENT') == 'development') {
            echo 'Automate module is initiating...' . PHP_EOL;
        } else {
            echo "This module is CLI only!" . PHP_EOL;
            die();
        }
    }

    /**
     * Clean old temp upload files.
     * call in terminal: `php index.php automate clean-old-temp 14`
     *
     * @param int $age in days
     * @throws Exception
     */
    public function clean_old_temp($age = 7)
    {
        $this->load->helper('directory');

        $path = './uploads/temp/';
        $map = directory_map($path, 1);
        $totalOldFiles = 0;
        $totalOldDirs = 0;
        $today = new DateTime();

        foreach ($map as $file) {
            if (is_dir($path . $file)) {
                $stat = stat($path . $file);
                $dirTimestamp = new DateTime(date("F d Y H:i:s.", $stat['mtime']));
                $dirInterval = $today->diff($dirTimestamp)->format('%R%a');
                if (intval($dirInterval) <= -$age) {
                    if (@rmdir($path . $file)) {
                        echo 'Directory: ' . ($path . $file) . ' was deleted' . PHP_EOL;
                        $totalOldDirs++;
                    }
                }
            }

            $fileTimestamp = new DateTime(date("F d Y H:i:s.", filectime($path . $file)));
            $interval = $today->diff($fileTimestamp)->format('%R%a');
            if (intval($interval) <= -$age && $file != '.gitkeep') {
                if (file_exists($path . $file)) {
                    if (@unlink($path . $file)) {
                        echo 'File: ' . ($path . $file) . ' was deleted' . PHP_EOL;
                        $totalOldFiles++;
                    }
                }
            }
        }
        echo $totalOldFiles . ' files and ' . $totalOldDirs . ' directories were deleted (more than ' . $age . ' days old)' . PHP_EOL;
    }

    /**
     * Send email weekly to.
     * @throws Exception
     */
    public function report_weekly()
    {
        $this->load->model('ReportModel', 'report');
        $this->load->model('RequisitionModel', 'requisition');
        $this->load->model('UserModel', 'user');
        $this->load->model('modules/Mailer', 'mailer');
        $this->load->model('modules/Exporter', 'exporter');

        $filters = [
            'status' => [
                RequisitionModel::STATUS_PENDING,
                RequisitionModel::STATUS_REJECTED,
                RequisitionModel::STATUS_APPROVED,
                RequisitionModel::STATUS_LISTED,
                RequisitionModel::STATUS_ASK_SELECTION,
                RequisitionModel::STATUS_READY,
                RequisitionModel::STATUS_SELECTED,
                RequisitionModel::STATUS_IN_PROCESS,
            ]
        ];
        $controlData = $this->report->getControlData($filters);
        $excelData = $this->exporter->exportFromArray('Control Data', $controlData, false);

        $date = new DateTime();
        $date->sub(new DateInterval('P7D')); // weekly
        $lastWeek = $date->format('Y-m-d');
        $today = date('Y-m-d');
        $formattedDateLastWeek = (new DateTime($lastWeek))->format('d F Y');
        $formattedDateToday = (new DateTime($today))->format('d F Y');

		$purchasing = $this->user->getByPermission(PERMISSION_QUOTATION_MANAGE);
        $emailTo = get_setting('email_support');
        $emailTitle = "Weekly activity report purchasing  period {$formattedDateLastWeek} to {$formattedDateToday}";
        $emailTemplate = 'emails/basic';
        $emailData = [
            'name' => 'Purchasing Admin',
            'email' => $emailTo,
            'content' => "
                We would like to inform you about latest activities in E-purchasing PT. Transcon Indonesia period 
                <b>{$formattedDateLastWeek}</b> to <b>{$formattedDateToday}</b>. <br><b>This Email Contains Attachment</b>
            "
        ];
        $emailOptions = [
            'cc' => array_column(if_empty($purchasing, []), 'email'),
            'attachment' => $excelData
        ];

        $sendEmail = $this->mailer->send($emailTo, $emailTitle, $emailTemplate, $emailData, $emailOptions);
        if (!$sendEmail) {
            log_message('error', 'Email weekly failed to be sent: ' . $this->email->print_debugger(['headers']));
        }
    }

    /**
     * Daily routine to check if pass 6 days the order is not confirmed,
     * then perform auto confirm.
     */
    public function check_unconfirmed_order()
    {
        $this->load->model('RequisitionStatusModel', 'requisitionStatus');
        $this->load->model('PurchaseOfferModel', 'purchaseOffer');
        $this->load->model('PurchaseOrderModel', 'purchaseOrder');
        $this->load->model('UserModel', 'user');
        $this->load->model('NotificationModel', 'notification');
        $this->load->model('modules/Mailer', 'mailer');

        $askConfirmationOrders = $this->purchaseOrder->getBy([
            'purchase_offers.status' => PurchaseOfferModel::STATUS_ASK_CONFIRMATION
        ]);
        foreach ($askConfirmationOrders as $purchaseOrder) {
            $statuses = $this->requisitionStatus->getBy([
                'requisition_statuses.id_requisition' => $purchaseOrder['id_requisition'],
                'requisition_statuses.status' => PurchaseOfferModel::STATUS_ASK_CONFIRMATION
            ]);
            if (!empty($statuses)) {
                $lastAskConfirmation = end($statuses);
                $dayElapsed = difference_date(format_date($lastAskConfirmation['created_at']), date('Y-m-d'));
                if ($dayElapsed >= 7) {
                    // auto confirmed by system

                    $this->db->trans_start();

                    // update rating and received date if necessary
                    $this->purchaseOrder->update([
                        'received_date_user' => if_empty($purchaseOrder['received_date'], null),
                        'receiving_note' => 'Auto confirmed after 7 days',
                    ], $purchaseOrder['id']);

                    // update status purchase offer to confirmed
                    $this->purchaseOffer->update([
                        'status' => PurchaseOfferModel::STATUS_CONFIRMED
                    ], $purchaseOrder['id_purchase_offer']);

                    // add requisition status history
                    $this->requisitionStatus->create([
                        'id_requisition' => $purchaseOrder['id_requisition'],
                        'status' => PurchaseOfferModel::STATUS_CONFIRMED,
                        'description' => "Auto Confirm Order " . $purchaseOrder['no_purchase'],
                        'data' => json_encode([
                            'id_purchase_order' => $purchaseOrder['id'],
                            'no_purchase' => $purchaseOrder['no_purchase'],
                            'received_date' => $purchaseOrder['received_date'],
                            'receiving_note' => 'Auto confirmed after 7 days',
                            'rating_user' => '',
                            'rating_reason' => '',
                            'creator' => ''
                        ])
                    ]);

                    // send notification to requester owner and purchasing admin
                    $purchasing = $this->user->getByPermission([PERMISSION_REQUISITION_MANAGE]);

                    if (!empty($purchaseOrder['id_user'])) {
                        $data = [
                            'id_user' => $purchaseOrder['id_user'],
                            'id_related' => $purchaseOrder['id'],
                            'channel' => NotificationModel::SUBSCRIBE_ORDER,
                            'event' => NotificationModel::EVENT_ORDER_MUTATION,
                            'payload' => [
                                'message' => "Handover purchase order {$purchaseOrder['no_purchase']} is AUTO confirmed",
                                'url' => site_url('purchasing/handover/view/' . $purchaseOrder['id']),
                                'time' => format_date('now', 'Y-m-d H:i:s'),
                                'description' => 'Auto confirmed after 7 days'
                            ]
                        ];
                        $this->notification->broadcast($data);

                        foreach ($purchasing as $adminPurchasing) {
                            $data['id_user'] = $adminPurchasing['id'];
                            $this->notification->broadcast($data);
                        }
                    }

                    $this->db->trans_complete();
                    if ($this->db->trans_status()) {
                        $emailTo = $purchaseOrder['employee_email'];
                        $emailTitle = "Handover request {$purchaseOrder['request_title']} related with order {$purchaseOrder['no_purchase']} is AUTO confirmed";
                        $emailTemplate = 'emails/basic';
                        $emailData = [
                            'name' => $purchaseOrder['employee_name'],
                            'email' => $purchaseOrder['employee_email'],
                            'content' => "
                                Requisition {$purchaseOrder['request_title']} related with purchase order {$purchaseOrder['no_purchase']}) 
                                is <b>AUTO CONFIRMED</b>, Purchasing admins will set <strong>complete</strong> to the order.
                                <br><br>
                                Note: the order is AUTO CONFIRMED because late to confirm in 7 days"
                        ];
                        $emailOptions = [
                            'cc' => array_column(if_empty($purchasing, []), 'email')
                        ];

                        $sendEmail = $this->mailer->send($emailTo, $emailTitle, $emailTemplate, $emailData, $emailOptions);
                        if (!$sendEmail) {
                            log_message('error', 'Email weekly failed to be sent: ' . $this->email->print_debugger(['headers']));
                        }
                    }
                }
            }
        }
    }

	/**
	 * Send to decision maker of outstanding requisition.
	 */
	public function vendor_selection_reminder()
	{
		$this->load->model('NotificationModel', 'notification');
		$this->load->model('RequisitionModel', 'requisition');
		$this->load->model('PurchaseOfferModel', 'purchaseOffer');
		$this->load->model('PurchaseOfferItemModel', 'purchaseOfferItem');
		$this->load->model('EmployeeModel', 'employee');
		$this->load->model('UserModel', 'user');

		$requisitions = $this->requisition->getBy(['requisitions.status' => RequisitionModel::STATUS_ASK_SELECTION]);
		if (!empty($requisitions)) {
			$requisitionMessage = "🛒 *PURCHASING ASK SELECTION*\n";
			foreach ($requisitions as $requisition) {
				$purchaseOffers = $this->purchaseOffer->getBy(['purchase_offers.id_requisition' => $requisition['id']]);

				$requisitionMessage .= "-------------------------------------\n";
				$requisitionMessage .= "*Requisition:* {$requisition['request_title']}\n";
				$requisitionMessage .= "*No Request:* {$requisition['no_requisition']}\n";
				$requisitionMessage .= "*Requester:* {$requisition['employee_name']}\n";
				$requisitionMessage .= "*Urgency:* {$requisition['urgency']}\n";
				$requisitionMessage .= "*Deadline:* {$requisition['deadline']}\n";
				$requisitionMessage .= "*Type:* {$requisition['request_type']}\n";
				$requisitionMessage .= "*Decision Maker:* {$requisition['decision_maker_email']}\n\n";
				$requisitionMessage .= "💰 *Vendor Offers:* \n";
				foreach ($purchaseOffers as $purchaseOfferIndex => $purchaseOffer) {
					$requisitionMessage .= ($purchaseOfferIndex + 1) . ". {$purchaseOffer['vendor']} dengan harga total *Rp. " . numerical($purchaseOffer['total_price']) . "*\n";
				}
				$requisitionMessage .= "\n👷🏼‍♂️ *Admin Recommendation:* {$requisition['purchasing_note']}\n";
			}

			$quotationManageUsers = $this->user->getByPermission(PERMISSION_QUOTATION_MANAGE);
			foreach ($quotationManageUsers as $quotationManageUser) {
				$employee = $this->employee->getBy(['ref_employees.id_user' => $quotationManageUser['id']], true);
				if (!empty($employee['contact_mobile'])) {
					$this->notification->broadcast([
						'url' => 'sendMessage',
						'method' => 'POST',
						'payload' => [
							'chatId' => detect_chat_id($employee['contact_mobile']),
							'body' => $requisitionMessage . "\n\n*Please submit your recommendation immediately!* ✅",
						]
					], NotificationModel::TYPE_CHAT_PUSH);
				}
			}
		} else {
			echo 'No outstanding ask selection';
		}
	}

	/**
	 * Send to decision maker of outstanding requisition.
	 */
	public function requisition_approval_reminder()
	{
		$this->load->model('NotificationModel', 'notification');
		$this->load->model('RequisitionModel', 'requisition');
		$this->load->model('EmployeeModel', 'employee');
		$this->load->model('UserModel', 'user');

		$requisitions = $this->requisition->getBy(['requisitions.status' => RequisitionModel::STATUS_PENDING]);

		if (!empty($requisitions)) {
			foreach ($requisitions as $requisition) {
				$requisitionMessage = "🛒 *REQUISITION PENDING APPROVAL*\n";
				$requisitionMessage .= "--------------------------------------------\n";
				$requisitionMessage .= "*Requisition:* {$requisition['request_title']}\n";
				$requisitionMessage .= "*No Request:* {$requisition['no_requisition']}\n";
				$requisitionMessage .= "*Requester:* {$requisition['employee_name']}\n";
				$requisitionMessage .= "*Urgency:* {$requisition['urgency']}\n";
				$requisitionMessage .= "*Deadline:* {$requisition['deadline']}";

				$employee = $this->employee->getById($requisition['id_supervisor']);
				if (!empty($employee['contact_mobile'])) {
					$this->notification->broadcast([
						'url' => 'sendMessage',
						'method' => 'POST',
						'payload' => [
							'chatId' => detect_chat_id($employee['contact_mobile']),
							'body' => $requisitionMessage . "\n\n*Please approve ✅ / reject ⛔ the requisition immediately!*",
						]
					], NotificationModel::TYPE_CHAT_PUSH);
				} else {
					echo $employee['name'] . ' has no contact mobile';
				}
			}
		} else {
			echo 'No outstanding pending approval';
		}
	}

	/**
	 * Remind user to confirm their handover.
	 */
	public function confirmation_reminder()
	{
		$this->load->model('UserModel', 'user');
		$this->load->model('HandoverModel', 'handover');
		$this->load->model('PurchaseOfferModel', 'purchaseOffer');
		$this->load->model('NotificationModel', 'notification');

		$handovers = $this->handover->getAll([
			'statuses' => [PurchaseOfferModel::STATUS_ASK_CONFIRMATION]
		]);

		if (!empty($handovers)) {
			$employeeIds = array_unique(array_column($handovers, 'id_employee'));
			foreach ($employeeIds as $employeeId) {
				// filter by employee
				$userHandovers = array_filter($handovers, function($handover) use ($employeeId) {
					return $handover['id_employee'] == $employeeId;
				});

				// get contact from data to reduce performing query
				$contactMobile = get_if_exist(reset($userHandovers), 'contact_mobile');

				if (!empty($contactMobile)) {
					$title = "*Outstanding Handover Requisition:*\n";
					$message = array_reduce($userHandovers, function($carryMessage, $item) {
						$carryMessage .= "------------------------------------------\n";
						$carryMessage .= "*Requisition:* {$item['request_title']}\n";
						$carryMessage .= "*No Request:* {$item['no_requisition']}\n";
						$carryMessage .= "*Category:* {$item['category']}\n";
						$carryMessage .= "*Requester:* {$item['employee_name']}\n";
						$carryMessage .= "*Status:* {$item['status']}\n";
						$carryMessage .= "*Last Updated:* " . relative_time($item['last_updated_offer']) . "\n";
						return $carryMessage;
					}, $title);
					$message .= "\n*New request will be blocked, please confirm request above.*";

					$result = $this->notification->broadcast([
						'url' => 'sendMessage',
						'method' => 'POST',
						'payload' => [
							'chatId' => detect_chat_id($contactMobile),
							'body' => $message
						]
					], NotificationModel::TYPE_CHAT_PUSH);

					if ($result) {
						echo "Outstanding handover {$contactMobile} already sent";
					} else {
						echo "Outstanding handover {$contactMobile} failed to be sent";
					}
				} else {
					echo "Outstanding handover not sent because employee does not has mobile contact";
				}
			}
		} else {
			echo "No outstanding handover";
		}
	}
}
