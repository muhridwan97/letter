<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Handover
 * @property HandoverModel $handover
 * @property PurchaseOfferModel $purchaseOffer
 * @property PurchaseOfferItemModel $purchaseOfferItem
 * @property PurchaseOrderModel $purchaseOrder
 * @property RequisitionItemModel $requisitionItem
 * @property RequisitionModel $requisition
 * @property RequisitionStatusModel $requisitionStatus
 * @property EmployeeModel $employee
 * @property NotificationModel $notification
 * @property UserModel $user
 * @property Exporter $exporter
 * @property Mailer $mailer
 */
class Handover extends App_Controller
{
    /**
     * Handover constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('HandoverModel', 'handover');
        $this->load->model('PurchaseOfferModel', 'purchaseOffer');
        $this->load->model('PurchaseOfferItemModel', 'purchaseOfferItem');
        $this->load->model('PurchaseOrderModel', 'purchaseOrder');
        $this->load->model('RequisitionModel', 'requisition');
        $this->load->model('RequisitionItemModel', 'requisitionItem');
        $this->load->model('RequisitionStatusModel', 'requisitionStatus');
        $this->load->model('EmployeeModel', 'employee');
        $this->load->model('NotificationModel', 'notification');
        $this->load->model('UserModel', 'user');
        $this->load->model('modules/Exporter', 'exporter');
        $this->load->model('modules/Mailer', 'mailer');

        $this->setFilterMethods([
            'confirm' => 'POST|PUT',
            'unconfirmed' => 'POST|PUT',
            'print_handover' => 'GET'
        ]);
    }

    /**
     * Show handover confirmation index page.
     */
    public function index()
    {
        AuthorizationModel::mustAuthorized(PERMISSION_REQUISITION_CREATE);

        $filters = array_merge($_GET, ['page' => get_url_param('page', 1)]);

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

        $handovers = $this->handover->getAll($filters);

        if ($export) {
            $this->exporter->exportFromArray('Handovers', $handovers);
        }

        $this->render('handover/index', compact('handovers'));
    }

    /**
     * Show handover data.
     *
     * @param $id
     */
    public function view($id)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_REQUISITION_VIEW);

        $purchaseOrder = $this->purchaseOrder->getById($id);
        $selectedOfferItems = $this->purchaseOfferItem->getBy([
            'purchase_offer_items.id_purchase_offer' => $purchaseOrder['id_purchase_offer'],
            'purchase_offer_items.quantity_selected!=' => null,
        ]);
        $requisitionStatuses = $this->requisitionStatus->getBy([
            'requisition_statuses.id_requisition' => $purchaseOrder['id_requisition']
        ]);
        $statusHandover = [
            PurchaseOfferModel::STATUS_ASK_CONFIRMATION,
            PurchaseOfferModel::STATUS_CONFIRMED,
            PurchaseOfferModel::STATUS_UNCONFIRMED,
            PurchaseOfferModel::STATUS_COMPLETED,
        ];
        foreach ($requisitionStatuses as $index => &$requisitionStatus) {
            if (!in_array($requisitionStatus['status'], $statusHandover)) {
                unset($requisitionStatuses[$index]);
            } else {
                $requisitionStatus['data'] = (array)json_decode($requisitionStatus['data']);
            }
        }
        $requisitionStatuses = array_values($requisitionStatuses);

        $this->render('handover/view', compact('purchaseOrder', 'selectedOfferItems', 'requisitionStatuses'));
    }

    /**
     * Confirm purchase order.
     *
     * @param $id
     */
    public function confirm($id)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_REQUISITION_CREATE);

        $validationRules = [
            'rating' => 'trim|required|integer',
            'received_date' => 'trim|required|max_length[50]',
            'receiving_note' => 'trim|max_length[500]',
            'rating_reason' => 'trim|max_length[500]'
        ];
        if ($this->validate($validationRules)) {
            $receivedDate = $this->input->post('received_date');
            $rating = $this->input->post('rating');
            $receivingNote = $this->input->post('receiving_note');
            $ratingReason = $this->input->post('rating_reason');

            $purchaseOrder = $this->purchaseOrder->getById($id);

            $this->db->trans_start();

            // update rating and received date if necessary
            $this->purchaseOrder->update([
                'received_date_user' => if_empty(format_date($receivedDate), null),
                'receiving_note' => $receivingNote,
                'rating_user_reason' => $ratingReason,
                'rating_user' => $rating,
            ], $id);

            // update status purchase offer to confirmed
            $this->purchaseOffer->update([
                'status' => PurchaseOfferModel::STATUS_CONFIRMED
            ], $purchaseOrder['id_purchase_offer']);

            // add requisition status history
            $this->requisitionStatus->create([
                'id_requisition' => $purchaseOrder['id_requisition'],
                'status' => PurchaseOfferModel::STATUS_CONFIRMED,
                'description' => "Confirm Order " . $purchaseOrder['no_purchase'],
                'data' => json_encode([
                    'id_purchase_order' => $purchaseOrder['id'],
                    'no_purchase' => $purchaseOrder['no_purchase'],
                    'received_date' => format_date($receivedDate),
                    'receiving_note' => $receivingNote,
                    'rating_user' => $rating,
                    'rating_reason' => $ratingReason,
                    'creator' => UserModel::loginData('name')
                ])
            ]);

            // send notification to requester owner and purchasing admin
            $purchasing = $this->user->getByPermission([PERMISSION_REQUISITION_MANAGE]);

            if (!empty($purchaseOrder['id_user'])) {
                $data = [
                    'id_user' => $purchaseOrder['id_user'],
                    'id_related' => $id,
                    'channel' => NotificationModel::SUBSCRIBE_ORDER,
                    'event' => NotificationModel::EVENT_ORDER_MUTATION,
                    'payload' => [
                        'message' => "Handover purchase order {$purchaseOrder['no_purchase']} is confirmed",
                        'url' => site_url('purchasing/handover/view/' . $id),
                        'time' => format_date('now', 'Y-m-d H:i:s'),
                        'description' => $receivingNote
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
                $emailTitle = "Handover request {$purchaseOrder['request_title']} related with order {$purchaseOrder['no_purchase']} is confirmed";
                $emailTemplate = 'emails/basic';
                $emailData = [
                    'name' => $purchaseOrder['employee_name'],
                    'email' => $purchaseOrder['employee_email'],
                    'content' => "
                        Requisition {$purchaseOrder['request_title']} related with purchase order {$purchaseOrder['no_purchase']}) 
                        is <b>confirmed</b>, Purchasing admins will set <strong>complete</strong> to the order.
                        <br><br>
                        Note: " . if_empty($receivingNote, 'no additional message')
                ];
                $emailOptions = [
                    'cc' => array_column(if_empty($purchasing, []), 'email')
                ];

                $sendEmail = $this->mailer->send($emailTo, $emailTitle, $emailTemplate, $emailData, $emailOptions);
                if ($sendEmail) {
                    flash('success', "Purchase order {$purchaseOrder['no_purchase']} successfully confirmed");
                } else {
                    flash('success', "Purchase order {$purchaseOrder['no_purchase']} successfully confirmed, but email is not sent");
                }
            } else {
                flash('danger', "Confirm order {$purchaseOrder['no_purchase']} failed, try again or contact your administrator");
            }
        }

        redirect('purchasing/handover');
    }

    /**
     * Reject purchase order.
     *
     * @param $id
     */
    public function unconfirmed($id)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_REQUISITION_CREATE);

        if ($this->validate(['message' => 'trim|required|max_length[500]'])) {
            $message = $this->input->post('message');

            $purchaseOrder = $this->purchaseOrder->getById($id);

            $this->db->trans_start();

            // update receiving note
            $this->purchaseOrder->update([
                'receiving_note' => $message,
            ], $id);

            // set status purchase offer to unconfirmed
            $this->purchaseOffer->update([
                'status' => PurchaseOfferModel::STATUS_UNCONFIRMED
            ], $purchaseOrder['id_purchase_offer']);

            // add requisition status history
            $this->requisitionStatus->create([
                'id_requisition' => $purchaseOrder['id_requisition'],
                'status' => PurchaseOfferModel::STATUS_UNCONFIRMED,
                'description' => "Unconfirmed Order " . $purchaseOrder['no_purchase'],
                'data' => json_encode([
                    'id_purchase_order' => $purchaseOrder['id'],
                    'no_purchase' => $purchaseOrder['no_purchase'],
                    'receiving_note' => $message,
                    'creator' => UserModel::loginData('name')
                ])
            ]);

            // send notification to requester owner and purchasing admin
            $purchasing = $this->user->getByPermission([PERMISSION_REQUISITION_MANAGE]);

            if (!empty($purchaseOrder['id_user'])) {
                $data = [
                    'id_user' => $purchaseOrder['id_user'],
                    'id_related' => $id,
                    'channel' => NotificationModel::SUBSCRIBE_ORDER,
                    'event' => NotificationModel::EVENT_ORDER_MUTATION,
                    'payload' => [
                        'message' => "Handover purchase order {$purchaseOrder['no_purchase']} is rejected",
                        'url' => site_url('purchasing/handover/view/' . $id),
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
                $emailTo = $purchaseOrder['employee_email'];
                $emailTitle = "Handover request {$purchaseOrder['request_title']} related with order {$purchaseOrder['no_purchase']} is REJECTED";
                $emailTemplate = 'emails/basic';
                $emailData = [
                    'name' => $purchaseOrder['employee_name'],
                    'email' => $purchaseOrder['employee_email'],
                    'content' => "
                        Requisition {$purchaseOrder['request_title']} related with purchase order {$purchaseOrder['no_purchase']}) 
                        is <b>REJECTED</b>, please review the receiving note bellow.
                        <br><br>
                        Note: " . if_empty($message, 'no additional message')
                ];
                $emailOptions = [
                    'cc' => array_column(if_empty($purchasing, []), 'email')
                ];

                $sendEmail = $this->mailer->send($emailTo, $emailTitle, $emailTemplate, $emailData, $emailOptions);
                if ($sendEmail) {
                    flash('warning', "Purchase order {$purchaseOrder['no_purchase']} handover successfully rejected");
                } else {
                    flash('warning', "Purchase order {$purchaseOrder['no_purchase']} handover successfully rejected, but email is not sent");
                }
            } else {
                flash('danger', "Rejecting order {$purchaseOrder['no_purchase']} handover failed, try again or contact your administrator");
            }
        }

        redirect('purchasing/handover');
    }

    /**
     * Print handover statement.
     *
     * @param $purchaseOrderId
     * @param bool $output
     * @return null|string
     */
    public function print_handover($purchaseOrderId, $output = false)
    {
		$order = $this->purchaseOrder->getById($purchaseOrderId);

        AuthorizationModel::mustAuthorized(function() use ($order) {
        	$isOwnedByRequester = AuthorizationModel::isAuthorized(PERMISSION_REQUISITION_CREATE) && $order['id_user'] == UserModel::loginData('id');
        	$isAllowedOrderView = AuthorizationModel::isAuthorized(PERMISSION_ORDER_VIEW);
			return $isOwnedByRequester || $isAllowedOrderView;
		});

        $purchaseOffer = $this->purchaseOffer->getById($order['id_purchase_offer']);
        $purchaseOfferItems = $this->purchaseOfferItem->getBy([
            'purchase_offer_items.id_purchase_offer' => $order['id_purchase_offer'],
            'purchase_offer_items.quantity_selected!=' => null,
        ]);
        $requisition = $this->requisition->getById($purchaseOffer['id_requisition']);
        $requisitionItems = $this->requisitionItem->getBy(['requisition_items.id_requisition' => $requisition['id']]);

        $uncoveredItems = [];
        foreach ($requisitionItems as $requisitionItem) {
            $isFound = false;
            $acquired = 0;
            $lessItem = 0;
            foreach ($purchaseOfferItems as $selectedItem) {
                if ($requisitionItem['id'] == $selectedItem['id_requisition_item']) {
                    $isFound = true;
                    if ($selectedItem['quantity_selected'] < $requisitionItem['quantity']) {
                        $acquired = $selectedItem['quantity_selected'];
                        $lessItem = $requisitionItem['quantity'] - $selectedItem['quantity_selected'];
                    }
                    break;
                }
            }
            if($isFound) {
                if($lessItem > 0) {
                    $uncoveredItems[] = [
                        'item_name' => $requisitionItem['item_name'],
                        'type' => $requisitionItem['type'],
                        'quantity_needed' => $requisitionItem['quantity'],
                        'quantity_less' => $lessItem,
                        'quantity_acquired' => $acquired,
                    ];
                }
            } else {
                $uncoveredItems[] = [
                    'item_name' => $requisitionItem['item_name'],
                    'type' => $requisitionItem['type'],
                    'quantity_needed' => $requisitionItem['quantity'],
                    'quantity_less' => $requisitionItem['quantity'],
                    'quantity_acquired' => 0,
                ];
            }
        }

        $options = [
            'buffer' => $output,
            'view' => 'handover/print',
            'data' => compact('order', 'uncoveredItems')
        ];
        return $this->exporter->exportToPdf("handover-order-{$purchaseOrderId}.pdf", null, $options);
    }

}
