<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Requisition
 * @property CategoryModel $category
 * @property CustomerModel $customer
 * @property ItemCategoryModel $itemCategory
 * @property ItemModel $item
 * @property EmployeeModel $employee
 * @property HandoverModel $handover
 * @property RequisitionModel $requisition
 * @property RequisitionItemModel $requisitionItem
 * @property RequisitionStatusModel $requisitionStatus
 * @property RequisitionEmailModel $requisitionEmail
 * @property PurchaseOfferModel $purchaseOffer
 * @property PurchaseOfferItemModel $purchaseOfferItem
 * @property PurchaseOrderModel $purchaseOrder
 * @property VendorModel $vendor
 * @property HeavyEquipmentEntryPermitModel $heavyEquipmentEntryPermit
 * @property NotificationModel $notification
 * @property UserModel $user
 * @property Exporter $exporter
 * @property Uploader $uploader
 * @property Mailer $mailer
 */
class Requisition extends App_Controller
{
    /**
     * Requisition constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('RequisitionModel', 'requisition');
        $this->load->model('RequisitionItemModel', 'requisitionItem');
        $this->load->model('RequisitionStatusModel', 'requisitionStatus');
        $this->load->model('RequisitionEmailModel', 'requisitionEmail');
        $this->load->model('PurchaseOfferModel', 'purchaseOffer');
        $this->load->model('PurchaseOfferItemModel', 'purchaseOfferItem');
        $this->load->model('PurchaseOrderModel', 'purchaseOrder');
        $this->load->model('CategoryModel', 'category');
        $this->load->model('CustomerModel', 'customer');
        $this->load->model('ItemCategoryModel', 'itemCategory');
        $this->load->model('ItemModel', 'item');
        $this->load->model('EmployeeModel', 'employee');
		$this->load->model('HandoverModel', 'handover');
        $this->load->model('VendorModel', 'vendor'); 
        $this->load->model('UserModel', 'user');
        $this->load->model('HeavyEquipmentEntryPermitModel', 'heavyEquipmentEntryPermit');
        $this->load->model('NotificationModel', 'notification');
        $this->load->model('modules/Exporter', 'exporter');
        $this->load->model('modules/Uploader', 'uploader');
        $this->load->model('modules/Mailer', 'mailer');

        $this->setFilterMethods([
            'set_category' => 'GET',
            'update_category' => 'POST|PUT',
            'outstanding' => 'GET',
            'cancelled' => 'GET',
            'approve' => 'POST|PUT',
            'reject' => 'POST|PUT',
            'cancel' => 'POST|PUT',
            'history' => 'GET',
            'print_requisition' => 'GET',
            'print_offer' => 'GET',
            'print_comparison' => 'GET',
            'notify_to_vendor' => 'GET',
            'send_to_vendor' => 'POST',
            'ask_selection' => 'POST|PUT',
            'set_ready' => 'POST|PUT',
            'revert' => 'POST|PUT',
            'ajax_get_requisition' => 'GET',
            'ajax_get_item_category' => 'GET',
            'set_checked' => 'POST|PUT',
            'detail_uncompleted' =>'GET',
            'ajax_is_employee_approver' => 'GET',
            'ajax_get_item' => 'GET',
        ]);
    }

    /**
     * Show requisition index page.
     */
    public function index()
    {
        AuthorizationModel::mustAuthorized(PERMISSION_REQUISITION_VIEW);

        $filters = array_merge($_GET, [
        	'page' => get_url_param('page', 1),
			'with_offer_status' => true
		]);

        $export = $this->input->get('export');
        if ($export) unset($filters['page']);

        $allowManage = AuthorizationModel::isAuthorized(PERMISSION_REQUISITION_MANAGE);
        $hasAdminView = AuthorizationModel::isAuthorized(PERMISSION_REQUISITION_ADMIN_VIEW);
        if (!$allowManage && !$hasAdminView) {
			$employeeId = UserModel::loginData('id_employee');
			$filters['employees'] = [$employeeId];
			if (EmployeeModel::hasSubordinate($employeeId)) {
				$subordinates = $this->employee->getBy([
					'ref_employees.id_employee' => $employeeId
				]);
				$filters['employees'] = array_merge([$employeeId], array_column(if_empty($subordinates, []), 'id'));
			}
        } else {
            if (!isset($filters['status']) || empty($filters['status'])) {
                $filters['status'] = [
                    RequisitionModel::STATUS_APPROVED,
                    RequisitionModel::STATUS_LISTED,
                    RequisitionModel::STATUS_ASK_SELECTION,
                    RequisitionModel::STATUS_READY,
                    RequisitionModel::STATUS_SELECTED,
                    RequisitionModel::STATUS_IN_PROCESS,
                    RequisitionModel::STATUS_DONE,
                ];
            }
        }

        $employeeId = UserModel::loginData('id_employee');
        $categories = $this->category->getAll();
        $requisitions = $this->requisition->getAll($filters);

        if ($export) {
            $this->exporter->exportFromArray('Requisitions', $requisitions);
        }

        $this->render('requisition/index', compact('requisitions', 'employeeId', 'categories'));
    }

    /**
     * Show outstanding requisition
     */
    public function outstanding()
    {
        AuthorizationModel::mustAuthorized(PERMISSION_REQUISITION_VIEW);

        $filters = array_merge($_GET, ['page' => get_url_param('page', 1)]);
        $filters['status'] = [
            RequisitionModel::STATUS_PENDING,
            RequisitionModel::STATUS_REJECTED,
        ];

        $export = $this->input->get('export');
        if ($export) unset($filters['page']);

        $allowManage = AuthorizationModel::isAuthorized(PERMISSION_REQUISITION_MANAGE);
        if (!$allowManage) {
            $employeeId = UserModel::loginData('id_employee');
            $filters['employees'] = [$employeeId];
            if (EmployeeModel::hasSubordinate($employeeId)) {
                $subordinates = $this->employee->getBy([
                    'ref_employees.id_employee' => $employeeId
                ]);
                $filters['employees'] = array_merge([$employeeId], array_column(if_empty($subordinates, []), 'id'));
            }
        }

        $employeeId = UserModel::loginData('id_employee');
        $categories = $this->category->getAll();
        $requisitions = $this->requisition->getAll($filters);

        if ($export) {
            $this->exporter->exportFromArray('Requisitions', $requisitions);
        }

        $this->render('requisition/index', compact('requisitions', 'employeeId', 'categories'), 'Outstanding');
    }

    /**
     * Show cancelled requisition
     */
    public function cancelled()
    {
        AuthorizationModel::mustAuthorized(PERMISSION_REQUISITION_VIEW);

        $filters = array_merge($_GET, ['page' => get_url_param('page', 1)]);
        $filters['status'] = [RequisitionModel::STATUS_CANCELLED];

        $export = $this->input->get('export');
        if ($export) unset($filters['page']);

        $allowManage = AuthorizationModel::isAuthorized(PERMISSION_REQUISITION_MANAGE);
        if (!$allowManage) {
            $employeeId = UserModel::loginData('id_employee');
            $filters['employees'] = [$employeeId];
            if (EmployeeModel::hasSubordinate($employeeId)) {
                $subordinates = $this->employee->getBy([
                    'ref_employees.id_employee' => $employeeId
                ]);
                $filters['employees'] = array_merge([$employeeId], array_column(if_empty($subordinates, []), 'id'));
            }
        }

        $employeeId = UserModel::loginData('id_employee');
        $categories = $this->category->getAll();
        $requisitions = $this->requisition->getAll($filters);

        if ($export) {
            $this->exporter->exportFromArray('Requisitions', $requisitions);
        }

        $this->render('requisition/index', compact('requisitions', 'employeeId', 'categories'), 'Outstanding');
    }

    /**
     * Show requisition data.
     *
     * @param $id
     */
    public function view($id)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_REQUISITION_VIEW);

        $requisition = $this->requisition->getById($id);
        $requisitionItems = $this->requisitionItem->getBy(['requisition_items.id_requisition' => $id]);
        $requisitionStatuses = $this->requisitionStatus->getBy(['requisition_statuses.id_requisition' => $id]);
        foreach ($requisitionStatuses as &$requisitionStatus) {
            $requisitionStatus['data'] = (array)json_decode($requisitionStatus['data']);
        }

        $requisitionEmails = $this->requisitionEmail->getBy(['requisition_emails.id_requisition' => $id]);
        $purchaseOffers = $this->purchaseOffer->getBy(['purchase_offers.id_requisition' => $id]);
        $selectedOfferItems = $this->purchaseOfferItem->getBy([
            'requisition_items.id_requisition' => $id,
            'purchase_offer_items.quantity_selected!=' => null
        ]);
        $customers = $this->customer->getAll();
        //link report
        $heep = $this->heavyEquipmentEntryPermit->getByIdRequisition($id,true);
        if(!empty($heep)){
            if(!empty($heep['checked_in_at'])){
                $heep['date_from']= date('d F Y',strtotime($heep['checked_in_at']));
            }else{
                $heep['date_from']= date('01 F Y',strtotime($heep['created_at']));
            }
            if(!empty($heep['checked_out_at'])){
                $heep['date_to']= date('d F Y',strtotime($heep['checked_out_at']));
            }else{
                if(!empty($heep['checked_in_at'])){
                    $heep['date_to']= date('t F Y',strtotime($heep['checked_in_at']));
                }else{
                    $heep['date_to']= date('t F Y',strtotime($heep['created_at']));
                }
            }
        }

        
        $this->render('requisition/view', compact('requisition', 'requisitionItems', 'requisitionStatuses', 'requisitionEmails', 'purchaseOffers', 'selectedOfferItems', 'customers', 'heep'));
    }

    /**
     * Print requisition data.
     *
     * @param $id
     */
    public function print_requisition($id)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_REQUISITION_VIEW);

        $requisition = $this->requisition->getById($id);
        $requisitionItems = $this->requisitionItem->getBy(['requisition_items.id_requisition' => $id]);

        $html = $this->load->view('requisition/print', compact('requisition', 'requisitionItems'), true);

        $this->exporter->exportToPdf("requisition-{$id}.pdf", $html);
    }

    /**
     * Print requisition offers.
     *
     * @param $id
     * @param bool $output
     * @return null|string
     */
    public function print_offer($id, $output = false)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_REQUISITION_MANAGE);

        $requisition = $this->requisition->getById($id);
        $requisitionItems = $this->requisitionItem->getBy(['requisition_items.id_requisition' => $id]);

        $purchaseOffers = $this->purchaseOffer->getBy(['purchase_offers.id_requisition' => $id]);
        foreach ($purchaseOffers as &$purchaseOffer) {
            $purchaseOffer['items'] = $this->purchaseOfferItem->getBy(['purchase_offer_items.id_purchase_offer' => $purchaseOffer['id']]);
            $purchaseOffer['vendor_data'] = $this->vendor->getById($purchaseOffer['id_vendor']);
        }

        $html = $this->load->view('requisition/print_offer', compact('requisition', 'requisitionItems', 'purchaseOffers'), true);

        return $this->exporter->exportToPdf("offer-requisition-{$id}.pdf", $html, ['buffer' => $output]);
    }

    /**
     * Print requisition offers.
     *
     * @param $id
     */
    public function print_comparison($id)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_REQUISITION_MANAGE);

        $requisition = $this->requisition->getById($id);
        $requisitionItems = $this->requisitionItem->getBy(['requisition_items.id_requisition' => $id]);

        $purchaseOffers = $this->purchaseOffer->getBy(['purchase_offers.id_requisition' => $id]);
        foreach ($purchaseOffers as &$purchaseOffer) {
            $purchaseOffer['items'] = $this->purchaseOfferItem->getBy(['purchase_offer_items.id_purchase_offer' => $purchaseOffer['id']]);
            $purchaseOffer['vendor_data'] = $this->vendor->getById($purchaseOffer['id_vendor']);
        }

        $this->load->view('requisition/print_comparison', compact('requisition', 'requisitionItems', 'purchaseOffers'));
    }

    /**
     * Show captured data in status history.
     *
     * @param $requisitionStatusId
     */
    public function history($requisitionStatusId)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_REQUISITION_VIEW);

        $requisitionStatus = $this->requisitionStatus->getById($requisitionStatusId);

        $data = json_decode($requisitionStatus['data']);
        $requisition = [];
        $requisitionItems = [];

        if (!empty($data)) {
            $data = (array)$data;
            $requisition = (array)$data['requisition'];
            foreach ($data['requisition_items'] as &$item) {
                $item = (array)$item;
            }
            $requisitionItems = $data['requisition_items'];
        }

        $this->render('requisition/history', compact('requisition', 'requisitionItems', 'requisitionStatus'));
    }

    /**
     * Show create requisition.
     */
    public function create()
    {
        AuthorizationModel::mustAuthorized(PERMISSION_REQUISITION_CREATE);

		$allowManage = AuthorizationModel::isAuthorized(PERMISSION_REQUISITION_MANAGE);
		if (!$allowManage) {
			$employeeId = UserModel::loginData('id_employee');

			$handovers = $this->handover->getAll([
				'employees' => $employeeId,
				'statuses' => [PurchaseOfferModel::STATUS_ASK_CONFIRMATION]
			]);

			if (!empty($handovers)) {
				flash('danger', 'You have ' . count($handovers) . ' handover outstanding, please confirm before make another request', '_back', 'requisition');
			}
		}

        $categories = $this->category->getAll();
        $employees = $this->employee->getAll();
        $items = $this->item->getAll();
        $customers = $this->customer->getAll();

        $this->render('requisition/create', compact('customers', 'categories', 'employees', 'items'));
    }

    /**
     * Save new requisition data.
     */
    public function save()
    {
        AuthorizationModel::mustAuthorized(PERMISSION_REQUISITION_CREATE);

        if ($this->validate()) {
            $requestTitle = $this->input->post('request_title');
            $categoryId = $this->input->post('category');
            $employeeId = $this->input->post('employee');
            $deadline = $this->input->post('deadline');
            $deadlineRemark = $this->input->post('deadline_remark');
            $urgency = $this->input->post('urgency');
            $noReferenceRemark = $this->input->post('no_reference_remark');
            $description = $this->input->post('description');
            $items = $this->input->post('items');
            
            $type = $this->input->post('type');
            $message = $this->input->post('message');
            $customer = $this->input->post('customer');
            
            $uploadedDocument = '';
            if (!empty($_FILES['document']['name'])) {
                $uploadFile = $this->uploader->setDriver('s3')->uploadTo('document', [
                    'destination' => 'requisition/' . date('Y/m')
                ]);
                if ($uploadFile) {
                    $uploadedData = $this->uploader->getUploadedData();
                    $uploadedDocument = $uploadedData['uploaded_path'];
                } else {
                    flash('warning', $this->uploader->getDisplayErrors());
                }
            } else {
                $uploadFile = true;
            }

            if ($uploadFile) {
                $this->db->trans_start();

                $employee = $this->employee->getById($employeeId);
                $supervisor = $this->employee->getById($employee['id_employee']);
                $status = empty($supervisor) || $employee['position_level'] == 'MANAGER' ? RequisitionModel::STATUS_APPROVED : RequisitionModel::STATUS_PENDING;
                
                $isChecked = 0;
                if ($type == 'INTERNAL') {
                    $isChecked = 1;
                }
                $this->requisition->create([
                    'id_category' => if_empty($categoryId, null),
                    'id_employee' => if_empty($employeeId, UserModel::loginData('id_employee')),
                    'request_title' => $requestTitle,
                    'no_requisition' => $this->requisition->getRequisitionNumber(),
                    'deadline' => format_date($deadline),
                    'deadline_remark' => $deadlineRemark,
                    'urgency' => $urgency,
                    'document' => if_empty($uploadedDocument, null),
                    'rate_type' => null,
                    'request_type' => null,
                    'status' => $status,
                    'no_reference_remark' => $noReferenceRemark,
                    'description' => $description,
                    'id_customer' => !empty($type) ? if_empty(implode(',', $customer), 'TCI') : NULL,
                    'approved_type' => $type,
                    'is_checked' => $isChecked,
                ]);
                $requisitionId = $this->db->insert_id();

                foreach ($items as $item) {
                    $this->requisitionItem->create([
                        'id_requisition' => $requisitionId,
                        'id_item' => $item['id_item'],
                        'item_name' => $item['item_name'],
                        'brand' => $item['brand'],
                        'type' => $item['type'],
                        'quantity' => $item['quantity'],
                        'unit' => $item['unit'],
                        'description' => $item['description'],
                    ]);
                }

                $requisition = $this->requisition->getById($requisitionId);
                $requisitionItems = $this->requisitionItem->getBy(['requisition_items.id_requisition' => $requisitionId]);

                $this->requisitionStatus->create([
                    'id_requisition' => $requisitionId,
                    'status' => RequisitionModel::STATUS_PENDING,
                    'description' => 'Create new requisition',
                    'data' => json_encode([
                        'requisition' => $requisition,
                        'requisition_items' => $requisitionItems,
                        'creator' => UserModel::loginData('name')
                    ])
                ]);

                if (empty($supervisor) || $employee['position_level'] == 'MANAGER') {
                    $this->requisitionStatus->create([
                        'id_requisition' => $requisitionId,
                        'status' => RequisitionModel::STATUS_APPROVED,
                        'description' => 'Auto approved requisition '.$message
                    ]);
                } else {
                    if (!empty($supervisor['id_user'])) {
                        $data = [
                            'id_user' => $supervisor['id_user'],
                            'id_related' => $requisitionId,
                            'channel' => NotificationModel::SUBSCRIBE_REQUISITION,
                            'event' => NotificationModel::EVENT_REQUISITION_MUTATION,
                            'payload' => [
                                'message' => "Requisition {$requestTitle} was created with deadline {$deadline}",
                                'url' => site_url('requisition/view/' . $requisitionId),
                                'time' => format_date('now', 'Y-m-d H:i:s'),
                                'description' => 'New requisition data'
                            ]
                        ];
                        $this->notification->broadcast($data);
                    }
                }

                $this->db->trans_complete();

                if ($this->db->trans_status()) {
					if (!empty($supervisor['contact_mobile'])) {
						$requestItemList = '';
						foreach ($requisitionItems as $index => $item) {
							$itemQuantity = numerical($item['quantity']);
							$requestItemList .= ($index + 1) . ". {$item['item_name']} {$item['brand']} ({$item['type']}) {$itemQuantity} {$item['unit']}\n";
						}
						$this->notification->broadcast([
							'url' => 'sendMessage',
							'method' => 'POST',
							'payload' => [
								'chatId' => detect_chat_id($supervisor['contact_mobile']),
								'body' => "*PURCHASING*\n---------------\n*Requisition:* {$requisition['request_title']} ({$requisition['no_requisition']})\n*Requester:* {$requisition['employee_name']}\n*Deadline:* {$requisition['deadline']} ({$requisition['urgency']})\n*Items:*\n{$requestItemList}\nPlease review this request on link as follow _" . site_url('requisition/view/' . $requisition['id'] . "_"),
							]
						], NotificationModel::TYPE_CHAT_PUSH);
                    }
                    
                    if (empty($supervisor) || $employee['position_level'] == 'MANAGER') {
                        $requestItemList = '';
						foreach ($requisitionItems as $index => $item) {
							$itemQuantity = numerical($item['quantity']);
							$requestItemList .= ($index + 1) . ". {$item['item_name']} {$item['brand']} ({$item['type']}) {$itemQuantity} {$item['unit']}\n";
						}
						$this->notification->broadcast([
							'url' => 'sendMessage',
							'method' => 'POST',
							'payload' => [
								'chatId' => detect_chat_id(get_setting('wa_purchasing')),
								'body' => "*PURCHASING*\n---------------\n*Requisition:* {$requisition['request_title']} ({$requisition['no_requisition']})\n*Requester:* {$requisition['employee_name']}\n*Deadline:* {$requisition['deadline']} ({$requisition['urgency']})\n*Items:*\n{$requestItemList}\nThe requision has been *approved*, please review this request on link as follow _" . site_url('requisition/view/' . $requisition['id'] . "_"),
							]
						], NotificationModel::TYPE_CHAT_PUSH);
                    }

					$emailTemplate = 'emails/requisitions/requisition';
                    $emailTitle = 'Requisition ' . $requestTitle;
                    $emailCC = $employee['email'];

                    $notifiedUsers = $this->user->getByPermission([
                        PERMISSION_REQUISITION_MANAGE
                    ]);
                    if (empty($supervisor) || $employee['position_level'] == 'MANAGER') {
                        $emailTo = array_column(if_empty($notifiedUsers, []), 'email');
                        $nameTo = 'Purchasing Admin';
                    } else {
                        $emailTo = $supervisor['email'];
                        $nameTo = $supervisor['name'];
                        $emailCC = array_merge(array_column(if_empty($notifiedUsers, []), 'email'), [$emailCC]);
                    }

                    $emailData = [
                        'requisition' => $requisition,
                        'requisitionItems' => $requisitionItems,
                        'name' => $nameTo,
                        'email' => $emailTo
                    ];
                    $sendEmail = $this->mailer->send($emailTo, $emailTitle, $emailTemplate, $emailData, ['cc' => $emailCC]);

                    if ($sendEmail) {
                        flash('success', "Requisition {$requestTitle} successfully created");
                    } else {
                        flash('success', "Requisition {$requestTitle} successfully created, but email is not sent");
                    }
                    redirect('requisition');
                } else {
                    flash('danger', 'Create requisition failed, try again or contact administrator');
                }
            }

        }
        $this->create();
    }

    /**
     * Show edit requisition form.
     *
     * @param $id
     */
    public function edit($id)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_REQUISITION_EDIT);

        $employees = $this->employee->getAll();
        $requisition = $this->requisition->getById($id);
        $requisitionItems = $this->requisitionItem->getBy(['requisition_items.id_requisition' => $id]);
        $categories = $this->category->getAll();
		$items = $this->item->getAll();

		if (!in_array($requisition['status'], [RequisitionModel::STATUS_REJECTED, RequisitionModel::STATUS_PENDING, RequisitionModel::STATUS_APPROVED])) {
			flash('danger', 'You cannot edit in process requisition, use revert to "APPROVED" instead', 'requisition');
		}

        $this->render('requisition/edit', compact('employees', 'requisition', 'requisitionItems', 'categories', 'items'));
    }

    /**
     * Update data requisition by id.
     *
     * @param $id
     */
    public function update($id)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_REQUISITION_EDIT);

        if ($this->validate()) {
            $requestTitle = $this->input->post('request_title');
            $categoryId = $this->input->post('category');
            $employeeId = $this->input->post('employee');
            $deadline = $this->input->post('deadline');
            $deadlineRemark = $this->input->post('deadline_remark');
            $urgency = $this->input->post('urgency');
			$noReferenceRemark = $this->input->post('no_reference_remark');
            $description = $this->input->post('description');
            $items = $this->input->post('items');

            $requisition = $this->requisition->getById($id);

            $uploadedDocument = $requisition['document'];
            if (!empty($_FILES['document']['name'])) {
                $uploadFile = $this->uploader->setDriver('s3')->uploadTo('document', [
                    'destination' => 'requisition/' . date('Y/m')
                ]);
                if ($uploadFile) {
                    $uploadedData = $this->uploader->getUploadedData();
                    $uploadedDocument = $uploadedData['uploaded_path'];
                    if (!empty($requisition['document'])) {
                        $this->uploader->delete($requisition['document']);
                    }
                } else {
                    flash('warning', $this->uploader->getDisplayErrors());
                }
            } else {
                $uploadFile = true;
            }

            if ($uploadFile) {
                $this->db->trans_start();

                $employee = $this->employee->getById($employeeId);;
                $supervisor = $this->employee->getById($employee['id_employee']);

                $newStatus = $requisition['status'];
                if ($requisition['status'] == RequisitionModel::STATUS_PENDING || $requisition['status'] == RequisitionModel::STATUS_REJECTED) {
                    $newStatus = RequisitionModel::STATUS_PENDING;
                    if (empty($supervisor) || $employee['position_level'] == 'MANAGER') {
                        $newStatus = RequisitionModel::STATUS_APPROVED;
                    }
                }

                $this->requisition->update([
                    'id_category' => if_empty($categoryId, null),
                    'id_employee' => if_empty($employeeId, UserModel::loginData('id_employee')),
                    'request_title' => $requestTitle,
                    'deadline' => format_date($deadline),
                    'deadline_remark' => $deadlineRemark,
                    'urgency' => $urgency,
                    'document' => if_empty($uploadedDocument, null),
                    'description' => $description,
					'no_reference_remark' => $noReferenceRemark,
                    'status' => $newStatus,
                ], $id);

				$excludes = array_filter($items, function ($item) {
					return !empty($item['id']);
				});
				$existingRequestItems = $this->requisitionItem->getBy(['requisition_items.id_requisition' => $id]);
				foreach ($existingRequestItems as $existingRequestItem) {
					if (!in_array($existingRequestItem['id'], array_column($excludes, 'id'))) {
						$this->requisitionItem->delete($existingRequestItem['id']);
					}
				}
				foreach ($items as $item) {
					$itemData = [
						'id_requisition' => $id,
						'id_item' => $item['id_item'],
						'item_name' => $item['item_name'],
						'brand' => $item['brand'],
						'type' => $item['type'],
						'quantity' => $item['quantity'],
						'unit' => $item['unit'],
						'description' => $item['description'],
					];
					$requisitionItemId = get_if_exist($item, 'id');
					if (empty($requisitionItemId)) {
						$this->requisitionItem->create($itemData);
					} else {
						$this->requisitionItem->update($itemData, $requisitionItemId);
					}
				}

                $requisition = $this->requisition->getById($id);
                $requisitionItems = $this->requisitionItem->getBy(['requisition_items.id_requisition' => $id]);
                $this->requisitionStatus->create([
                    'id_requisition' => $id,
                    'status' => $newStatus . ' (UPDATE)',
                    'description' => 'Edit requisition',
                    'data' => json_encode([
                        'requisition' => $requisition,
                        'requisition_items' => $requisitionItems,
                        'creator' => UserModel::loginData('name')
                    ])
                ]);

                if (!empty($supervisor['id_user'])) {
                    $data = [
                        'id_user' => $supervisor['id_user'],
                        'id_related' => $id,
                        'channel' => NotificationModel::SUBSCRIBE_REQUISITION,
                        'event' => NotificationModel::EVENT_REQUISITION_MUTATION,
                        'payload' => [
                            'message' => "Requisition {$requestTitle} recently updated",
                            'url' => site_url('requisition/view/' . $id),
                            'time' => format_date('now', 'Y-m-d H:i:s'),
                            'description' => 'Edit requisition data'
                        ]
                    ];
                    $this->notification->broadcast($data);
                }

                $this->db->trans_complete();

                if ($this->db->trans_status()) {
                    if ($requisition['status'] == RequisitionModel::STATUS_PENDING || $requisition['status'] == RequisitionModel::STATUS_REJECTED) {
                        $emailTemplate = 'emails/requisitions/requisition';
                        $emailTitle = 'Requisition (UPDATED) ' . $requestTitle;
                        $emailCC = $employee['email'];

                        if (empty($supervisor) || $employee['position_level'] == 'MANAGER') {
                            $notifiedUsers = $this->user->getByPermission([
                                PERMISSION_REQUISITION_MANAGE
                            ]);
                            $emailTo = array_column(if_empty($notifiedUsers, []), 'email');
                            $nameTo = 'Purchasing Admin';
                        } else {
                            $emailTo = $supervisor['email'];
                            $nameTo = $supervisor['name'];
                        }

                        $emailData = [
                            'requisition' => $requisition,
                            'requisitionItems' => $requisitionItems,
                            'name' => $nameTo,
                            'email' => $emailTo
                        ];
                        //$this->mailer->send($emailTo, $emailTitle, $emailTemplate, $emailData, ['cc' => $emailCC]);
                    }

                    flash('success', "Requisition {$requestTitle} successfully updated", $requisition['status'] == RequisitionModel::STATUS_PENDING ? 'requisition/outstanding' : 'requisition');
                } else {
                    flash('danger', "Update requisition failed, try again or contact administrator");
                }
            }
        }
        $this->edit($id);
    }

    /**
     * Show set category requisition data.
     *
     * @param $id
     */
    public function set_category($id)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_REQUISITION_MANAGE);

        $requisition = $this->requisition->getById($id);
        $requisitionItems = $this->requisitionItem->getBy(['requisition_items.id_requisition' => $id]);
        $categories = $this->category->getAll();
        $itemCategories = $this->itemCategory->getBy([
        	'id_category' => if_empty($this->input->post('category'), $requisition['id_category'])
		]);

        $this->render('requisition/set_category', compact('requisition', 'requisitionItems', 'categories', 'itemCategories'));
    }

    /**
     * Update category requisition data.
     *
	 * @param $id
	 */
	public function update_category($id)
	{
		AuthorizationModel::mustAuthorized(PERMISSION_REQUISITION_MANAGE);

		$rules = [
			'category' => 'required',
			'item_category' => 'required',
			'rate_type' => 'required|in_list[FIXED,ADJUSTABLE]',
			'request_type' => 'required|in_list[ROUTINE,URGENT,PROJECT]',
		];
		if ($this->validate($rules)) {
			$categoryId = $this->input->post('category');
			$itemCategoryId = $this->input->post('item_category');
			$rateType = $this->input->post('rate_type');
			$requestType = $this->input->post('request_type');
			$requisition = $this->requisition->getById($id);

			$this->db->trans_start();

			$this->requisition->update([
				'id_category' => if_empty($categoryId, null),
				'id_item_category' => if_empty($itemCategoryId, null),
				'rate_type' => if_empty($rateType, null),
				'request_type' => if_empty($requestType, null),
			], $id);

			$this->db->trans_complete();

			if ($this->db->trans_status()) {
				flash('success', "Requisition category {$requisition['request_title']} is successfully set");
			} else {
				flash('danger', "Update requisition failed, try again or contact administrator");
			}
			redirect('requisition');
		} else {
			$this->set_category($id);
		}
	}

    /**
     * Approve requisition data.
     *
     * @param $id
     */
    public function approve($id)
    {
		$message = $this->input->post('message');
		$customer = $this->input->post('customer');
		$type = $this->input->post('type');

		$requisition = $this->requisition->getById($id);

		$this->db->trans_start();

		$isChecked = 0;
		if ($type == 'INTERNAL') {
			$isChecked = 1;
		}
		$this->requisition->update([
			'id_customer' => if_empty(implode(',', $customer), 'TCI'),
			'approved_type' => $type,
			'is_checked' => $isChecked,
			'status' => RequisitionModel::STATUS_APPROVED
		], $id);

		$this->requisitionStatus->create([
			'id_requisition' => $id,
			'status' => RequisitionModel::STATUS_APPROVED,
			'description' => $message
		]);

        $purchasing = $this->user->getByPermission([PERMISSION_REQUISITION_MANAGE]);

        if (!empty($requisition['id_user'])) {
            $data = [
                'id_user' => $requisition['id_user'],
                'id_related' => $id,
                'channel' => NotificationModel::SUBSCRIBE_REQUISITION,
                'event' => NotificationModel::EVENT_REQUISITION_VALIDATION,
                'payload' => [
                    'message' => "Requisition {$requisition['request_title']} is approved",
                    'url' => site_url('requisition/view/' . $id),
                    'time' => format_date('now', 'Y-m-d H:i:s'),
                    'description' => $message
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
            $requisitionItems = $this->requisitionItem->getBy(['requisition_items.id_requisition' => $id]);
            $requestItemList = '';
            foreach ($requisitionItems as $index => $item) {
                $itemQuantity = numerical($item['quantity']);
                $requestItemList .= ($index + 1) . ". {$item['item_name']} {$item['brand']} ({$item['type']}) {$itemQuantity} {$item['unit']}\n";
            }
            $this->notification->broadcast([
                'url' => 'sendMessage',
                'method' => 'POST',
                'payload' => [
                    'chatId' => detect_chat_id(get_setting('wa_purchasing')),
                    'body' => "*PURCHASING*\n---------------\n*Requisition:* {$requisition['request_title']} ({$requisition['no_requisition']})\n*Requester:* {$requisition['employee_name']}\n*Deadline:* {$requisition['deadline']} ({$requisition['urgency']})\n*Items:*\n{$requestItemList}\nThe requision has been *approved*, please review this request on link as follow _" . site_url('requisition/view/' . $requisition['id'] . "_"),
                ]
            ], NotificationModel::TYPE_CHAT_PUSH);
            $emailTo = $requisition['employee_email'];
            $emailTitle = "Requisition {$requisition['request_title']} with no {$requisition['no_requisition']} is approved";
            $emailTemplate = 'emails/basic';
            $emailData = [
                'name' => $requisition['employee_name'],
                'email' => $requisition['employee_email'],
                'content' => "
                    Requisition {$requisition['request_title']} ({$requisition['no_requisition']}) is <b>approved</b>, 
                    Purchasing admins will find vendor that suit of your request and contact you in couple hours till days.
                    <br><br>
                    Note: " . if_empty($message, 'no additional message')
            ];
            $emailOptions = [
                'cc' => array_column(if_empty($purchasing, []), 'email')
            ];

            $sendEmail = $this->mailer->send($emailTo, $emailTitle, $emailTemplate, $emailData, $emailOptions);
            if ($sendEmail) {
                flash('success', "Requisition {$requisition['request_title']} is successfully approved", '_back', 'requisition');
            } else {
                flash('success', "Requisition {$requisition['request_title']} is successfully approved, but email is not sent", '_back', 'requisition');
            }
        } else {
            flash('danger', "Validate requisition failed, try again or contact administrator", '_back', 'requisition');
        }
        redirect('requisition');
    }

    /**
     * Reject requisition data.
     *
     * @param $id
     */
    public function reject($id)
    {
        $message = $this->input->post('message');

        $requisition = $this->requisition->getById($id);

        $this->db->trans_start();

        $this->requisition->update([
            'status' => RequisitionModel::STATUS_REJECTED
        ], $id);

        $this->requisitionStatus->create([
            'id_requisition' => $id,
            'status' => RequisitionModel::STATUS_REJECTED,
            'description' => $message
        ]);

        if (!empty($requisition['id_user'])) {
            $data = [
                'id_user' => $requisition['id_user'],
                'id_related' => $id,
                'channel' => NotificationModel::SUBSCRIBE_REQUISITION,
                'event' => NotificationModel::EVENT_REQUISITION_VALIDATION,
                'payload' => [
                    'message' => "Requisition {$requisition['request_title']} is REJECTED, check the note for correction",
                    'url' => site_url('requisition/view/' . $id),
                    'time' => format_date('now', 'Y-m-d H:i:s'),
                    'description' => $message
                ]
            ];
            $this->notification->broadcast($data);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            $emailTo = $requisition['employee_email'];
            $emailTitle = "Requisition {$requisition['request_title']} with no {$requisition['no_requisition']} is rejected";
            $emailTemplate = 'emails/basic';
            $emailData = [
                'name' => $requisition['employee_name'],
                'email' => $requisition['employee_email'],
                'content' => "
                    Requisition {$requisition['request_title']} ({$requisition['no_requisition']}) is <b>REJECTED</b>, 
                    because data is incomplete or invalid, don't worry you can fix it, 
                    read message detail below or contact your Supervisor and Purchasing for further information.
                    <br><br>
                    Note: " . if_empty($message, 'no additional message')
            ];
            $emailOptions = [
                'cc' => $requisition['supervisor_email']
            ];

            $sendEmail = $this->mailer->send($emailTo, $emailTitle, $emailTemplate, $emailData, $emailOptions);
            if ($sendEmail) {
                flash('warning', "Requisition {$requisition['request_title']} is successfully rejected", '_back', 'requisition');
            } else {
                flash('warning', "Requisition {$requisition['request_title']} is successfully rejected, but email is not sent", '_back', 'requisition');
            }
        } else {
            flash('danger', "Validate requisition failed, try again or contact administrator", '_back', 'requisition');
        }
        redirect('requisition');
    }


    /**
     * Cancel requisition data.
     *
     * @param $id
     */
    public function cancel($id)
    {
        $message = $this->input->post('message');

        $requisition = $this->requisition->getById($id);

        $this->db->trans_start();

        $this->requisition->update([
            'status' => RequisitionModel::STATUS_CANCELLED
        ], $id);

        $this->requisitionStatus->create([
            'id_requisition' => $id,
            'status' => RequisitionModel::STATUS_CANCELLED,
            'description' => $message
        ]);

        if (!empty($requisition['id_user'])) {
            $data = [
                'id_user' => $requisition['id_user'],
                'id_related' => $id,
                'channel' => NotificationModel::SUBSCRIBE_REQUISITION,
                'event' => NotificationModel::EVENT_REQUISITION_VALIDATION,
                'payload' => [
                    'message' => "Requisition {$requisition['request_title']} is CANCELLED, all data will be hold",
                    'url' => site_url('requisition/view/' . $id),
                    'time' => format_date('now', 'Y-m-d H:i:s'),
                    'description' => $message
                ]
            ];
            $this->notification->broadcast($data);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            $username = UserModel::loginData('name');
            $emailTo = $requisition['employee_email'];
            $emailTitle = "Requisition {$requisition['request_title']} with no {$requisition['no_requisition']} is cancelled";
            $emailTemplate = 'emails/basic';
            $emailData = [
                'name' => $requisition['employee_name'],
                'email' => $requisition['employee_email'],
                'content' => "
                    Requisition {$requisition['request_title']} ({$requisition['no_requisition']}) is <b>CANCELLED</b> 
                    by {$username} as Purchasing Administrator, 
                    because something unexpected may happen, 
                    read message detail below or contact your Supervisor and Purchasing for further information.
                    <br><br>
                    Note: " . if_empty($message, 'no additional message')
            ];
            $emailOptions = [
                'cc' => $requisition['supervisor_email']
            ];

            $sendEmail = $this->mailer->send($emailTo, $emailTitle, $emailTemplate, $emailData, $emailOptions);
            if ($sendEmail) {
                flash('warning', "Requisition {$requisition['request_title']} is successfully cancelled", '_back', 'requisition');
            } else {
                flash('warning', "Requisition {$requisition['request_title']} is successfully rejected, but email is not sent", '_back', 'requisition');
            }
        } else {
            flash('danger', "Validate requisition failed, try again or contact administrator", '_back', 'requisition');
        }
        redirect('requisition');
    }

    /**
     * Perform deleting requisition data.
     *
     * @param $id
     */
    public function delete($id)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_REQUISITION_DELETE);

        $requisition = $this->requisition->getById($id);

        if ($this->requisition->delete($id, true)) {
            flash('warning', "Requisition {$requisition['request_title']} successfully deleted");
        } else {
            flash('danger', 'Delete requisition failed, try again or contact administrator');
        }
        redirect('requisition');
    }

    /**
     * Notify requisition data to vendor.
     *
     * @param $id
     */
    public function notify_to_vendor($id)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_REQUISITION_MANAGE);

        $requisition = $this->requisition->getById($id);

        if (empty($requisition['id_category'])) {
            flash('danger', 'Requisition category is not set, please update first', '_back', 'requisition');
        }
        
        $vendors = $this->vendor->getBy([
        	'ref_item_categories.id' => $requisition['id_item_category'],
			'ref_vendors.is_active' => 1
		]);

        $this->render('requisition/send', compact('requisition', 'vendors'));
    }

    /**
     * Send data to vendor.
     *
     * @param $id
     */
    public function send_to_vendor($id)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_REQUISITION_MANAGE);

        $vendors = $this->input->post('vendors');
        $sendEmail = $this->input->post('send_email');
        $description = $this->input->post('description');

        $requisition = $this->requisition->getById($id);
        $requisitionItems = $this->requisitionItem->getBy(['requisition_items.id_requisition' => $id]);

        $this->db->trans_start();

        $fetchedVendors = [];

        if ($requisition['status'] == RequisitionModel::STATUS_APPROVED) {
            $this->requisition->update([
                'status' => RequisitionModel::STATUS_LISTED
            ], $id);

            $this->requisitionStatus->create([
                'id_requisition' => $id,
                'status' => RequisitionModel::STATUS_LISTED,
                'description' => $description
            ]);

            $userId = UserModel::loginData('id');
            foreach ($vendors as $vendorId) {
                $vendor = $this->vendor->getById($vendorId);
                $fetchedVendors[] = $vendor;
                $this->requisitionEmail->create([
                    'id_requisition' => $id,
                    'id_vendor' => $vendor['id'],
                    'email' => $vendor['email'],
                    'sent_at' => date('Y-m-d H:i:s'),
                    'sent_by' => $userId
                ]);
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            if ($sendEmail) {
                foreach ($fetchedVendors as $vendor) {
                    if (!empty($vendor) && !empty($vendor['email']) && $vendor['email']!='123@YAHOO.COM') {
                        $emailTo = $vendor['email'];
                        $emailTitle = "Request to vendor {$requisition['request_title']}";
                        $emailTemplate = 'emails/vendors/requisition';
                        $emailData = [
                            'requisition' => $requisition,
                            'requisitionItems' => $requisitionItems,
                            'vendor' => $vendor,
                            'note' => $description,
                        ];
                        $this->mailer->send($emailTo, $emailTitle, $emailTemplate, $emailData);
                    }
                }
            }
            flash('success', 'Requisition has listed to vendor', 'requisition');
        }

        $this->notify_to_vendor($id);
    }

    /**
     * Ask requisition to manager for selection.
     *
     * @param $requisitionId
     */
    public function ask_selection($requisitionId)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_QUOTATION_SELECT);

        $decisionMakerId = $this->input->post('decision_maker');
        $note = $this->input->post('note');

        $user = $this->user->getById($decisionMakerId);
        $email = $user['email'];

        $requisition = $this->requisition->getById($requisitionId);
        $requisitionItems = $this->requisitionItem->getBy(['requisition_items.id_requisition' => $requisitionId]);

        $this->db->trans_start();

        $this->requisition->update([
            'purchasing_note' => $note,
            'decision_maker_email' => $email,
            'id_decision_maker' => $decisionMakerId,
            'status' => RequisitionModel::STATUS_ASK_SELECTION
        ], $requisitionId);

        $this->load->helper('string');
        $token = random_string('alnum', 32);

        $this->requisitionStatus->create([
            'id_requisition' => $requisitionId,
            'status' => RequisitionModel::STATUS_ASK_SELECTION . ($requisition['status'] == RequisitionModel::STATUS_ASK_SELECTION ? ' (RESEND)' : ''),
            'description' => $note,
            'data' => json_encode([
                'token' => $token,
                'email' => $email,
                'id_user' => $decisionMakerId,
                'creator' => UserModel::loginData('name')
            ])
        ]);

        $userId = if_empty($requisition['id_user_supervisor'], $requisition['id_user']);
        if (!empty($userId)) {
            $data = [
                'id_user' => $userId,
                'id_related' => $requisitionId,
                'channel' => NotificationModel::SUBSCRIBE_REQUISITION,
                'event' => NotificationModel::EVENT_REQUISITION_PROCEED,
                'payload' => [
                    'message' => "Requisition {$requisition['request_title']} is asked for vendor selection",
                    'url' => site_url('requisition/view/' . $requisitionId),
                    'time' => format_date('now', 'Y-m-d H:i:s'),
                    'description' => 'Set status ask selection'
                ]
            ];
            $this->notification->broadcast($data);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            $attachments = [];
            $purchaseOffers = $this->purchaseOffer->getBy(['purchase_offers.id_requisition' => $requisitionId]);
            foreach ($purchaseOffers as &$purchaseOffer) {
                if (!empty($purchaseOffer['document'])) {
                    $attachments[] = [
                        'source' => asset_url($purchaseOffer['document']),
                        'file_name' => url_title($purchaseOffer['vendor'] . '-' . str_replace('/', '-', $purchaseOffer['document']))
                    ];
                }
                $purchaseOffer['items'] = $this->purchaseOfferItem->getBy(['purchase_offer_items.id_purchase_offer' => $purchaseOffer['id']]);
                $purchaseOffer['vendor_data'] = $this->vendor->getById($purchaseOffer['id_vendor']);
            }

            $emailTo = $email;
            $emailTitle = "Request vendor selection : {$requisition['request_title']}" . ($requisition['status'] == RequisitionModel::STATUS_ASK_SELECTION ? ' (RESEND)' : '');
            $emailTemplate = 'emails/vendors/selection';
            $emailData = [
                'email' => $email,
                'note' => $note,
                'token' => $token,
                'requisition' => $requisition,
                'requisitionItems' => $requisitionItems,
                'purchaseOffers' => $purchaseOffers,
            ];
            $purchasing = $this->user->getByPermission([PERMISSION_REQUISITION_MANAGE]);

            $attachments[] = [
                'source' => $this->print_offer($requisitionId, true),
                'file_name' => 'vendor-comparison.pdf',
                'mime' => 'application/pdf'
            ];

            $emailOptions = [
                'cc' => array_column(if_empty($purchasing, []), 'email'),
                'attachment' => $attachments
            ];

            $sendEmail = $this->mailer->send($emailTo, $emailTitle, $emailTemplate, $emailData, $emailOptions);
            if ($sendEmail) {
                flash('success', "Requisition {$requisition['request_title']} is asked for selection", '_redirect', 'purchasing/offer');
            } else {
                flash('success', "Requisition {$requisition['request_title']} is asked for selection, but email is not sent", '_redirect', 'purchasing/offer');
            }
        }

        redirect('purchasing/offer');
    }

    /**
     * Set requisition ready for selection.
     *
     * @param $requisitionId
     */
    public function set_ready($requisitionId)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_QUOTATION_MANAGE);

        $note = $this->input->post('note');

        $requisition = $this->requisition->getById($requisitionId);

        $this->db->trans_start();

        $this->requisition->update([
            'selection_note' => $note,
            'status' => RequisitionModel::STATUS_READY
        ], $requisitionId);

        $this->requisitionStatus->create([
            'id_requisition' => $requisitionId,
            'status' => RequisitionModel::STATUS_READY,
            'description' => $note,
            'data' => json_encode([
                'token' => 'Authorized Submission',
                'id_user' => UserModel::loginData('id'),
                'email' => UserModel::loginData('email')
            ])
        ]);

        $userId = if_empty($requisition['id_user_supervisor'], $requisition['id_user']);
        if (!empty($userId)) {
            $data = [
                'id_user' => $userId,
                'id_related' => $requisitionId,
                'channel' => NotificationModel::SUBSCRIBE_REQUISITION,
                'event' => NotificationModel::EVENT_REQUISITION_PROCEED,
                'payload' => [
                    'message' => "Requisition {$requisition['request_title']} is proceed for vendor selection",
                    'url' => site_url('requisition/view/' . $requisitionId),
                    'time' => format_date('now', 'Y-m-d H:i:s'),
                    'description' => 'Set status ready'
                ]
            ];
            $this->notification->broadcast($data);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            $emailTo = get_setting('email_support');
            $emailTitle = "Decision requisition {$requisition['request_title']} is submitted";
            $emailTemplate = 'emails/basic';
            $emailData = [
                'name' => 'Purchasing Admin',
                'email' => get_setting('email_support'),
                'content' => "
                    Decision for requisition {$requisition['request_title']} ({$requisition['no_requisition']}) is <b>submitted</b>, 
                    Purchasing admins will grant and proceed request to make purchase order based on selection.
                    <br><br>
                    Selection Note: " . if_empty($note, 'no additional message')
            ];
            $purchasing = $this->user->getByPermission([PERMISSION_REQUISITION_MANAGE]);
            $emailOptions = [
                'cc' => array_column(if_empty($purchasing, []), 'email')
            ];

            $sendEmail = true;//$this->mailer->send($emailTo, $emailTitle, $emailTemplate, $emailData, $emailOptions);
            if ($sendEmail) {
                flash('success', "Requisition {$requisition['request_title']} ready for vendor selection", '_redirect', 'purchasing/selection');
            } else {
                flash('success', "Requisition {$requisition['request_title']} ready for vendor selection, but email is not sent", '_redirect', 'purchasing/selection');
            }
        }

        redirect('purchasing/selection');
    }

    /**
     * Revert requisition to specific status.
     *
     * @param $requisitionId
     */
    public function revert($requisitionId)
    {
        $status = $this->input->post('status');

        $revertStatuses = [
            RequisitionModel::STATUS_APPROVED,
            RequisitionModel::STATUS_LISTED,
            RequisitionModel::STATUS_READY,
            RequisitionModel::STATUS_SELECTED,
        ];
        $rules = ['status' => 'in_list[' . implode(',', $revertStatuses) . ']'];

        if ($this->validate($rules)) {
            $requisition = $this->requisition->getById($requisitionId);

            $this->db->trans_start();

            $this->requisition->update([
                'status' => $status,
            ], $requisitionId);

            $this->requisitionStatus->create([
                'id_requisition' => $requisitionId,
                'status' => $status . ' (REVERT)',
                'description' => 'Revert to ' . $status
            ]);

            $purchaseOffers = $this->purchaseOffer->getBy(['purchase_offers.id_requisition' => $requisitionId]);

            switch ($status) {
                case RequisitionModel::STATUS_APPROVED:
                    foreach ($purchaseOffers as $purchaseOffer) {
                        $this->purchaseOrder->delete(['purchase_orders.id_purchase_offer' => $purchaseOffer['id']]);
                        $this->purchaseOffer->delete($purchaseOffer['id']);
                    }
                    $this->requisition->update(['purchasing_note' => '', 'selection_note' => '', 'decision_maker_email' => ''], $requisitionId);
                    break;
                case RequisitionModel::STATUS_LISTED:
                    foreach ($purchaseOffers as $purchaseOffer) {
                        $this->purchaseOrder->delete(['purchase_orders.id_purchase_offer' => $purchaseOffer['id']]);
                        $this->purchaseOffer->update(['status' => PurchaseOfferModel::STATUS_ON_REVIEW], $purchaseOffer['id']);
                    }
                    $purchaseOfferItems = $this->purchaseOfferItem->getBy(['requisition_items.id_requisition' => $requisitionId]);
                    foreach ($purchaseOfferItems as $item) {
                        $this->purchaseOfferItem->update([
                            'quantity_selected' => null
                        ], $item['id']);
                    }
                    $this->requisition->update(['purchasing_note' => '', 'selection_note' => '', 'decision_maker_email' => ''], $requisitionId);
                    break;
                case RequisitionModel::STATUS_READY:
                    foreach ($purchaseOffers as $purchaseOffer) {
                        $this->purchaseOrder->delete(['purchase_orders.id_purchase_offer' => $purchaseOffer['id']]);
                        $this->purchaseOffer->update(['status' => PurchaseOfferModel::STATUS_ON_REVIEW], $purchaseOffer['id']);
                    }
                    $purchaseOfferItems = $this->purchaseOfferItem->getBy(['requisition_items.id_requisition' => $requisitionId]);
                    foreach ($purchaseOfferItems as $item) {
                        $this->purchaseOfferItem->update([
                            'quantity_selected' => null
                        ], $item['id']);
                    }
                    break;
                case RequisitionModel::STATUS_SELECTED:
                    foreach ($purchaseOffers as $purchaseOffer) {
                        $po = $this->purchaseOrder->getBy(['purchase_orders.id_purchase_offer' => $purchaseOffer['id']], true);
                        if (!empty($po)) {
                            $this->purchaseOrder->delete(['purchase_orders.id_purchase_offer' => $purchaseOffer['id']]);
                            $this->purchaseOffer->update(['status' => PurchaseOfferModel::STATUS_SELECTED], $purchaseOffer['id']);
                        } else {
                            $this->purchaseOffer->update(['status' => PurchaseOfferModel::STATUS_UNSELECTED], $purchaseOffer['id']);
                        }
                    }
                    break;
            }

            $userId = if_empty($requisition['id_user_supervisor'], $requisition['id_user']);
            if (!empty($userId)) {
                $data = [
                    'id_user' => $userId,
                    'id_related' => $requisition['id'],
                    'channel' => NotificationModel::SUBSCRIBE_REQUISITION,
                    'event' => NotificationModel::EVENT_REQUISITION_PROCEED,
                    'payload' => [
                        'message' => "Requisition {$requisition['request_title']} is REVERTED to {$status}",
                        'url' => site_url('requisition/view/' . $requisition['id']),
                        'time' => format_date('now', 'Y-m-d H:i:s'),
                        'description' => 'Requisition reverted'
                    ]
                ];
                $this->notification->broadcast($data);
            }

            $this->db->trans_complete();

            if ($this->db->trans_status()) {
                flash('warning', "Requisition {$requisition['request_title']} successfully reverted to {$status}");
            } else {
                flash('danger', 'Revert requisition failed, try again or contact administrator');
            }
        }

        redirect('requisition');
    }

    /**
     * Return general validation rules.
     *
     * @return array
     */
    protected function _validation_rules()
    {
        return [
            'employee' => 'trim|required',
            'request_title' => 'trim|required|max_length[100]',
            'deadline' => 'trim|required|max_length[50]',
            'deadline_remark' => 'trim|required|max_length[200]',
            'urgency' => 'trim|max_length[50]',
            'description' => 'max_length[500]',
            'items[]' => 'required'
        ];
    }

    /**
     * Get requisition data.
     */
    public function ajax_get_requisition()
    {
        $requisitionId = get_url_param('id_requisition');
        $requisition = $this->requisition->getById($requisitionId);
        $requisitionItems = $this->requisitionItem->getBy(['requisition_items.id_requisition' => $requisitionId]);
        $vendors = $this->vendor->getBy([
        	'ref_categories.id' => $requisition['id_category'],
			'ref_vendors.is_active' => 1
		]);

        $this->render_json([
            'requisition' => $requisition,
            'requisition_items' => $requisitionItems,
            'vendors' => $vendors
        ]);
    }

     /**
     * Get item category data.
     */
    public function ajax_get_item_category()
    {
        if ($this->input->server('REQUEST_METHOD') == "GET") {
            $category = $this->input->get('category');

            $itemCategories = $this->itemCategory->getBy(['id_category' => $category]);

            $this->render_json($itemCategories);
        }
    }

     /**
     * Get requisition item data.
     */
    public function ajax_get_item()
    {
        if ($this->input->server('REQUEST_METHOD') == "GET") {
            $items = $this->item->getAll();

            $this->render_json($items);
        }
    }

    /**
     * Set checked requisition data.
     *
     * @param $id
     */
    public function set_checked($id)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_REQUISITION_SET_CHECK);
        $message = $this->input->post('message');

        $requisition = $this->requisition->getById($id);

        $this->db->trans_start();

        $this->requisition->update([
            'is_checked' => 1,
            'check_note' => $message,
            'checked_by' => UserModel::loginData('id', 0),
            'checked_at' => date('Y-m-d H:i:s'),
        ], $id);

        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            flash('success', "Requisition {$requisition['request_title']} is successfully checked", '_back', 'requisition');
        } else {
            flash('danger', "Recorded requisition failed, try again or contact administrator", '_back', 'requisition');
        }
        redirect('requisition');
    }

    public function detail_uncompleted(){
        $filters = $_GET;
        $requestionIds = $this->requisition->getDetailReport($filters);
        $justId = [];
        foreach ($requestionIds as $requestion) {
            $justId[] = $requestion['id_requisition'];
        }
        AuthorizationModel::mustAuthorized(PERMISSION_REQUISITION_VIEW);

        $filters = array_merge($_GET, [
        	'page' => get_url_param('page', 1),
			'with_offer_status' => true
        ]);
        unset($filters['uncompleted_at_year']);
        unset($filters['uncompleted_at_week']);
        $filters['id_requestion'] = $justId;

        $export = $this->input->get('export');
        if ($export) unset($filters['page']);

        $allowManage = AuthorizationModel::isAuthorized(PERMISSION_REQUISITION_MANAGE);
        $hasAdminView = AuthorizationModel::isAuthorized(PERMISSION_REQUISITION_ADMIN_VIEW);
        if (!$allowManage && !$hasAdminView) {
			$employeeId = UserModel::loginData('id_employee');
			$filters['employees'] = [$employeeId];
			if (EmployeeModel::hasSubordinate($employeeId)) {
				$subordinates = $this->employee->getBy([
					'ref_employees.id_employee' => $employeeId
				]);
				$filters['employees'] = array_merge([$employeeId], array_column(if_empty($subordinates, []), 'id'));
			}
        } else {
            if (!isset($filters['status']) || empty($filters['status'])) {
                $filters['status'] = [
                    RequisitionModel::STATUS_APPROVED,
                    RequisitionModel::STATUS_LISTED,
                    RequisitionModel::STATUS_ASK_SELECTION,
                    RequisitionModel::STATUS_READY,
                    RequisitionModel::STATUS_SELECTED,
                    RequisitionModel::STATUS_IN_PROCESS,
                    RequisitionModel::STATUS_DONE,
                ];
            }
        }

        $employeeId = UserModel::loginData('id_employee');
        $categories = $this->category->getAll();
        $requisitions = $this->requisition->getAll($filters);

        if ($export) {
            $this->exporter->exportFromArray('Requisitions', $requisitions);
        }

        $this->render('requisition/index', compact('requisitions', 'employeeId', 'categories'));
    }

    /**
     * Get status employee approver.
     */
    public function ajax_is_employee_approver()
    {
        if ($this->input->server('REQUEST_METHOD') == "GET") {
            $employeeId = $this->input->get('employeeId');

            $employee = $this->employee->getById($employeeId);
            $supervisor = $this->employee->getById($employee['id_employee']);
            $status = empty($supervisor) || $employee['position_level'] == 'MANAGER' ? RequisitionModel::STATUS_APPROVED : RequisitionModel::STATUS_PENDING;

            $this->render_json($status);
        }
    }
}
