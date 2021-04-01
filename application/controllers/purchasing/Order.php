<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Order
 * @property RequisitionModel $requisition
 * @property RequisitionItemModel $requisitionItem
 * @property RequisitionStatusModel $requisitionStatus
 * @property PurchaseOrderModel $purchaseOrder
 * @property PurchaseOfferModel $purchaseOffer
 * @property PurchaseOfferItemModel $purchaseOfferItem
 * @property NotificationModel $notification
 * @property VendorModel $vendor
 * @property UserModel $user
 * @property VendorLocationModel $vendorLocation
 * @property VendorContactModel $vendorContact
 * @property Exporter $exporter
 * @property Mailer $mailer
 * @property Uploader $uploader
 */
class Order extends App_Controller
{
    /**
     * Order constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('RequisitionModel', 'requisition');
        $this->load->model('RequisitionItemModel', 'requisitionItem');
        $this->load->model('RequisitionStatusModel', 'requisitionStatus');
        $this->load->model('PurchaseOrderModel', 'purchaseOrder');
        $this->load->model('PurchaseOfferModel', 'purchaseOffer');
        $this->load->model('PurchaseOfferItemModel', 'purchaseOfferItem');
        $this->load->model('NotificationModel', 'notification');
        $this->load->model('VendorModel', 'vendor');
        $this->load->model('UserModel', 'user');
		$this->load->model('VendorLocationModel', 'vendorLocation');
		$this->load->model('VendorContactModel', 'vendorContact');
        $this->load->model('modules/Exporter', 'exporter');
        $this->load->model('modules/Mailer', 'mailer');
        $this->load->model('modules/Uploader', 'uploader');

        $this->setFilterMethods([
            'data' => 'GET',
            'preview' => 'GET',
            'print_order' => 'GET',
            'preview_draft_email' => 'GET',
            'draft' => 'POST|PUT',
            'item_completion' => 'GET',
            'save_item_completion' => 'POST|PUT',
            'publish' => 'POST|PUT',
            'ask_confirmation' => 'POST|PUT',
            'complete' => 'POST|PUT',
            'notify' => 'POST|PUT',
        ]);
    }

    /**
     * Show purchase offer index page.
     */
    public function index()
    {
        AuthorizationModel::mustAuthorized(PERMISSION_ORDER_VIEW);

        $filters = array_merge($_GET, ['page' => get_url_param('page', 1)]);
        
        $export = $this->input->get('export');
        if ($export) unset($filters['page']);

        $activeRequisitions = $this->purchaseOrder->getIncomingOrder();

        if ($export) {
            $purchaseOrders = $this->purchaseOrder->getAll($filters);
            $this->exporter->exportFromArray('Purchase Orders', $purchaseOrders);
        }

        $noPurchaseOrder = $this->purchaseOrder->getPurchaseNumber();

        $this->render('order/index', compact('activeRequisitions', 'noPurchaseOrder'));
    }

    /**
     * Get order data
     */
    public function data()
    {
        $start = $this->input->get('start');
        $length = $this->input->get('length');

        $filters = array_merge($_GET, [
            'page' => ceil($start / $length) + 1,
            'per_page' => $this->input->get('length'),
            'search' => $this->input->get('search')['value'],
            'sort_by' => $this->input->get('order')[0]['column'],
            'order_method' => $this->input->get('order')[0]['dir']
        ]);

        $purchaseOrders = $this->purchaseOrder->getAll($filters);
        
        foreach ($purchaseOrders['data'] as &$row) $row['no'] = ++$start;

        $this->render_json([
            "draw" => $this->input->get('draw'),
            "recordsTotal" => $purchaseOrders['total_page_data'],
            "recordsFiltered" => $purchaseOrders['total_data'],
            "data" => $purchaseOrders['data']
        ]);
    }

	/**
	 * View purchase order.
	 *
	 * @param $id
	 */
    public function view($id)
	{
		AuthorizationModel::mustAuthorized(PERMISSION_ORDER_VIEW);

		$purchaseOrder = $this->purchaseOrder->getById($id);
		$purchaseOffer = $this->purchaseOffer->getById($purchaseOrder['id_purchase_offer']);
		$purchaseOfferItems = $this->purchaseOfferItem->getBy([
			'purchase_offer_items.id_purchase_offer' => $purchaseOffer['id'],
			'purchase_offer_items.quantity_selected!=' => null,
		]);
		return $this->render("order/view", compact('purchaseOrder', 'purchaseOffer', 'purchaseOfferItems'));
	}

    /**
     * Show draft purchase order.
     *
     * @param $purchaseOfferId
     */
    public function preview($purchaseOfferId)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_ORDER_VIEW);

        $purchaseOffer = $this->purchaseOffer->getById($purchaseOfferId);
        $purchaseOfferItems = $this->purchaseOfferItem->getBy([
            'purchase_offer_items.id_purchase_offer' => $purchaseOfferId,
            'purchase_offer_items.quantity_selected!=' => null,
        ]);
        $requisition = $this->requisition->getById($purchaseOffer['id_requisition']);
        $vendor = $this->vendor->getById($purchaseOffer['id_vendor']);

        $purchaseNo = get_url_param('no_purchase', $this->purchaseOrder->getPurchaseNumber());
        $purchaseDate = get_url_param('purchase_date', format_date('now'));
        $offerDiscountGoods = trim(get_url_param('offer_discount_goods', 0));
        $offerDiscountServices = trim(get_url_param('offer_discount_services', 0));
        $roundingGoods = trim(get_url_param('rounding_goods', 0));
        $roundingServices = trim(get_url_param('rounding_services', 0));
        $taxType = trim(get_url_param('tax_type'));
        $tax = trim(get_url_param('tax', 0));
        $taxBasisType = trim(get_url_param('tax_basis_type'));
        $taxBasis = trim(get_url_param('tax_basis', 0));
        $taxBasisLabel = get_url_param('tax_basis_label', 'Tax Basis');

        $roundingAmount = intval($roundingGoods) + intval($roundingServices);
        $totalBeforeVAT = ($purchaseOffer['selected_extended_total'] - $offerDiscountGoods - $offerDiscountServices) + $roundingAmount;
        if ($taxType == 'percentage') {
            $taxPercentage = $tax;
            $tax = floor($taxPercentage / 100 * $totalBeforeVAT);
        } else {
            $taxPercentage = 0;
        }

        $totalServicePriceAfterDiscount = $purchaseOffer['selected_total_price_services'] - $offerDiscountServices;
        if ($taxBasisType == 'percentage') {
            $taxBasisPercentage = $taxBasis;
            if ($totalServicePriceAfterDiscount > 0) {
                $taxBasis = floor($taxBasisPercentage / 100 * $totalServicePriceAfterDiscount);
            } else {
                $taxBasis = 0;
            }
        } else {
            $taxBasisPercentage = 0;
        }

        $delivery = intval(get_url_param('delivery_cost', 0));
        $miscellaneous = intval(get_url_param('miscellaneous_expense', 0));
        $miscellaneousLabel = get_url_param('miscellaneous_label', 'Miscellaneous');
        $description = get_url_param('description');

        $purchaseOffer['offer_discount'] = intval($offerDiscountGoods) + intval($offerDiscountServices);
        $order = [
            'draft' => true,
            'no_purchase' => $purchaseNo,
            'rounding_goods' => $roundingGoods,
            'rounding_services' => $roundingServices,
            'rounding_amount' => $roundingAmount,
            'tax_percentage' => $taxPercentage,
            'tax' => $tax,
            'tax_basis_percentage' => $taxBasisPercentage,
            'tax_basis' => $taxBasis,
            'tax_basis_label' => $taxBasisLabel,
            'delivery_cost' => $delivery,
            'miscellaneous_expense' => $miscellaneous,
            'miscellaneous_label' => $miscellaneousLabel,
            'date' => $purchaseDate,
            'description' => $description,
            'total_price' => $totalBeforeVAT + $tax + $delivery + $miscellaneous - $taxBasis,
			'purchasing_admin' => get_setting('purchasing_admin'),
			'purchasing_supervisor' => get_setting('purchasing_supervisor'),
        ];

        $html = $this->load->view('order/print', compact('requisition', 'vendor', 'purchaseOffer', 'purchaseOfferItems', 'order'), true);

        $this->exporter->exportToPdf("preview-purchase-order-{$purchaseOfferId}.pdf", $html);
    }

    /**
     * Show draft purchase order.
     *
     * @param $purchaseOrderId
     * @param bool $output
     * @return null|string
     */
    public function print_order($purchaseOrderId, $output = false)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_ORDER_VIEW);

        $order = $this->purchaseOrder->getById($purchaseOrderId);
        $purchaseOffer = $this->purchaseOffer->getById($order['id_purchase_offer']);
        $purchaseOfferItems = $this->purchaseOfferItem->getBy([
            'purchase_offer_items.id_purchase_offer' => $order['id_purchase_offer'],
            'purchase_offer_items.quantity_selected!=' => null,
        ]);
        $requisition = $this->requisition->getById($purchaseOffer['id_requisition']);
        $requisitionStatusReady = $this->requisitionStatus->getBy([
            'requisition_statuses.id_requisition' => $purchaseOffer['id_requisition'],
            'requisition_statuses.status' => RequisitionModel::STATUS_READY,
        ],true);
        $emailDecode = (array)json_decode($requisitionStatusReady['data']);
        $emailDecode = substr($emailDecode['email'],0,3);
        $vendor = $this->vendor->getById($purchaseOffer['id_vendor']);
		$vendorLocations = $this->vendorLocation->getBy(['ref_vendor_locations.id_vendor' => $purchaseOffer['id_vendor']]);

        $options = [
            'buffer' => $output,
            'view' => 'order/print',
            'data' => compact('requisition', 'vendor', 'purchaseOffer', 'purchaseOfferItems', 'order', 'vendorLocations', 'emailDecode')
        ];
        return $this->exporter->exportToPdf("purchase-order-{$purchaseOrderId}.pdf", null, $options);
    }

	/**
	 * Save new purchase offer data.
	 *
	 * @param $purchaseOfferId
	 * @param null $purchaseOrder
	 */
    public function save($purchaseOfferId, $purchaseOrder = null)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_ORDER_CREATE);

        if ($this->validate()) {
            $date = $this->input->post('date');
            $offerDiscountGoods = extract_number($this->input->post('offer_discount_goods'));
            $offerDiscountServices = extract_number($this->input->post('offer_discount_services'));
            $goodsSign = $this->input->post('rounding_goods_sign');
            $serviceSign = $this->input->post('rounding_services_sign');
            $roundingGoods = extract_number($this->input->post('rounding_goods'));
            $roundingServices = extract_number($this->input->post('rounding_services'));
            $taxType = $this->input->post('tax_type');
            $tax = extract_number($this->input->post('tax'));
            $taxBasisType = $this->input->post('tax_basis_type');
            $taxBasis = extract_number($this->input->post('tax_basis'));
            $taxBasisLabel = $this->input->post('tax_basis_label');
            $deliveryCost = extract_number($this->input->post('delivery_cost'));
            $miscellaneousExpense = extract_number($this->input->post('miscellaneous_expense'));
            $miscellaneousExpenseSign = $this->input->post('miscellaneous_expense_sign');
            $miscellaneousLabel = $this->input->post('miscellaneous_label');
            $notes = $this->input->post('note');
            $emailNote = $this->input->post('email_note');

			if (!empty($this->purchaseOrder->getBy(['purchase_orders.id_purchase_offer' => $purchaseOfferId])) && empty($purchaseOrder)) {
				flash('danger', 'Purchase order from this offer already exist, you cannot create another one', '_back');
			}

            $purchaseOffer = $this->purchaseOffer->getById($purchaseOfferId);
            $purchaseOfferItems = $this->purchaseOfferItem->getBy([
                'purchase_offer_items.id_purchase_offer' => $purchaseOfferId,
                'purchase_offer_items.quantity_selected!=' => null,
            ]);
            if ($purchaseOffer['email']=='123@YAHOO.COM') {
                $purchaseOffer['email']=null;
            }
            $requisition = $this->requisition->getById($purchaseOffer['id_requisition']);

            if ($goodsSign == '-') {
                $roundingGoods *= -1;
            }

            if ($serviceSign == '-') {
                $roundingServices *= -1;
            }

            if ($miscellaneousExpenseSign == '-') {
                $miscellaneousExpense *= -1;
            }

            $totalBeforeVAT = $purchaseOffer['selected_total_price'] + intval($roundingGoods) + intval($roundingServices);
            if ($taxType == 'percentage') {
                $taxPercentage = $tax;
                $tax = floor($tax / 100 * $totalBeforeVAT);
            } else {
                $taxPercentage = 0;
            }

            $totalServicePriceAfterDiscount = $purchaseOffer['selected_total_price_services'] - $offerDiscountServices;
            if ($taxBasisType == 'percentage') {
                $taxBasisPercentage = $taxBasis;
                if ($totalServicePriceAfterDiscount > 0) {
                    $taxBasis = floor($taxBasisPercentage / 100 * $totalServicePriceAfterDiscount);
                } else {
                    $taxBasis = 0;
                }
            } else {
                $taxBasisPercentage = 0;
            }

            $this->db->trans_start();

            $this->purchaseOffer->update([
                'offer_discount_goods' => if_empty($offerDiscountGoods, 0),
                'offer_discount_services' => if_empty($offerDiscountServices, 0),
            ], $purchaseOfferId);


			$admin = get_setting('purchasing_admin');
			$supervisor = get_setting('purchasing_supervisor');

			$purchaseData = [
				'date' => format_date($date),
				'rounding_goods' => $roundingGoods,
				'rounding_services' => $roundingServices,
				'tax_percentage' => $taxPercentage,
				'tax' => $tax,
				'tax_basis_percentage' => $taxBasisPercentage,
				'tax_basis' => $taxBasis,
				'tax_basis_label' => if_empty($taxBasisLabel, 'Tax Basis'),
				'delivery_cost' => extract_number($deliveryCost),
				'miscellaneous_expense' => $miscellaneousExpense,
				'miscellaneous_label' => if_empty($miscellaneousLabel, 'Miscellaneous'),
				'description' => implode('||', if_empty($notes, '')),
				'purchasing_admin' => $admin,
				'purchasing_supervisor' => $supervisor,
				'document_status' => PurchaseOrderModel::STATUS_PUBLISHED,
			];

			// create new order or publish from given $purchaseOrder
			if (empty($purchaseOrder)) {
				$noPurchase = $this->purchaseOrder->getPurchaseNumber();
				$purchaseData['no_purchase'] = $this->purchaseOrder->getPurchaseNumber();
				$purchaseData['id_purchase_offer'] = $purchaseOfferId;
				$this->purchaseOrder->create($purchaseData);
				$purchaseOrderId = $this->db->insert_id();
			} else {
				$this->purchaseOrder->update($purchaseData, $purchaseOrder['id']);
				$purchaseOrderId = $purchaseOrder['id'];
				$noPurchase = $purchaseOrder['no_purchase'];
			}

            $this->requisition->update([
                'status' => RequisitionModel::STATUS_IN_PROCESS
            ], $purchaseOffer['id_requisition']);

            $this->requisitionStatus->create([
                'id_requisition' => $purchaseOffer['id_requisition'],
                'status' => RequisitionModel::STATUS_IN_PROCESS,
                'description' => 'Order ' . $noPurchase
            ]);

            $this->purchaseOffer->update([
                'status' => PurchaseOfferModel::STATUS_ORDERED
            ], $purchaseOfferId);

            $userId = if_empty($requisition['id_user_supervisor'], $requisition['id_user']);
            if (!empty($userId)) {
                $data = [
                    'id_user' => $userId,
                    'id_related' => $purchaseOfferId,
                    'channel' => NotificationModel::SUBSCRIBE_ORDER,
                    'event' => NotificationModel::EVENT_ORDER_MUTATION,
                    'payload' => [
                        'message' => "Purchase order {$noPurchase} for {$requisition['request_title']} is published, waiting completing order",
                        'url' => site_url('purchasing/order/view/' . $purchaseOfferId),
                        'time' => format_date('now', 'Y-m-d H:i:s'),
                        'description' => 'Order ' . $noPurchase
                    ]
                ];
                $this->notification->broadcast($data);
            }

            $this->db->trans_complete();

            if ($this->db->trans_status()) {
                
                $emailTo = if_empty($purchaseOffer['email'], get_setting('email_purchasing'));
                $emailTitle = "Purchase Order : {$purchaseOffer['request_title']} ({$noPurchase})";
                $emailTemplate = 'emails/vendors/purchase_order';
                $emailData = [
                    'email' => $purchaseOffer['email'],
                    'note' => $emailNote,
                    'noPurchase' => $noPurchase,
                    'purchaseOffer' => $purchaseOffer,
                    'purchaseOfferItems' => $purchaseOfferItems,
                ];
                $purchasing = $this->user->getByPermission([PERMISSION_REQUISITION_MANAGE]);

                $attachments = [
                    'source' => $this->print_order($purchaseOrderId, true),
                    'file_name' => 'purchase-order.pdf',
                    'mime' => 'application/pdf'
                ];

                $emailOptions = [
                    'cc' => array_column(if_empty($purchasing, []), 'email'),
                    'attachment' => [$attachments]
                ];

                $sendEmail = $this->mailer->send($emailTo, $emailTitle, $emailTemplate, $emailData, $emailOptions);
                if ($sendEmail) {
                    flash('success', "Purchase order of {$purchaseOffer['request_title']} successfully created");
                } else {
                    flash('success', "Purchase order of {$purchaseOffer['request_title']} successfully created, but email is not sent");
                }
            } else {
                flash('danger', 'Create purchase order failed, try again or contact administrator');
            }
        }

        redirect('purchasing/order');
    }

	/**
	 * Create draft purchase order.
	 *
	 * @param $purchaseOfferId
	 */
    public function draft($purchaseOfferId)
	{
		AuthorizationModel::mustAuthorized(PERMISSION_ORDER_CREATE);

		$message = $this->input->post('message');
		$sendEmail = $this->input->post('send_email');
		$purchaseOffer = $this->purchaseOffer->getById($purchaseOfferId);
		$vendorContact = $this->vendorContact->getBy([
			'id_vendor' => $purchaseOffer['id_vendor'],
			'is_primary' => 1
		], true);

		$this->db->trans_start();

		$noPurchase = $this->purchaseOrder->getPurchaseNumber();

		$admin = get_setting('purchasing_admin');
		$supervisor = get_setting('purchasing_supervisor');
		$this->purchaseOrder->create([
			'id_purchase_offer' => $purchaseOfferId,
			'no_purchase' => $noPurchase,
			'date' => format_date('now'),
			'tax_basis_label' => 'Tax Basis',
			'miscellaneous_label' => 'Miscellaneous',
			'purchasing_admin' => $admin,
			'purchasing_supervisor' => $supervisor,
			'document_status' => PurchaseOrderModel::STATUS_DRAFT
		]);
		$purchaseOrderId = $this->db->insert_id();

		$this->requisitionStatus->create([
			'id_requisition' => $purchaseOffer['id_requisition'],
			'status' => RequisitionModel::STATUS_SELECTED,
			'description' => 'Draft ' . $noPurchase . ' : ' . if_empty($message, 'Create draft PO')
		]);

		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			if (!empty($vendorContact) && !empty($vendorContact['email']) && $sendEmail) {
				$purchaseOrder = $this->purchaseOrder->getById($purchaseOrderId);
				$purchaseOfferItems = $this->purchaseOfferItem->getBy([
					'purchase_offer_items.id_purchase_offer' => $purchaseOffer['id'],
					'purchase_offer_items.quantity_selected!=' => null,
				]);
				$token = base64_encode($vendorContact['email'] . '|' . date("Y-m-d", strtotime("+7 days")));

				$emailTo = $vendorContact['email'];
				$emailTitle = "Vendor order completion for " . $purchaseOrder['no_purchase'];
				$emailTemplate = 'emails/vendors/completion';
				$emailData = [
					'email' => $emailTo,
					'purchaseOffer' => $purchaseOffer,
					'purchaseOrder' => $purchaseOrder,
					'purchaseOfferItems' => $purchaseOfferItems,
					'message' => $message,
					'token' => $token,
				];
				$purchasing = $this->user->getByPermission([PERMISSION_ORDER_EDIT]);
				$emailOptions = [
					'cc' => array_column(if_empty($purchasing, []), 'email'),
				];

				$this->mailer->send($emailTo, $emailTitle, $emailTemplate, $emailData, $emailOptions);
			}
			flash('success', "Draft purchase order {$noPurchase} successfully created");
		} else {
			flash('danger', 'Create draft purchase order failed, try again or contact administrator');
		}
		redirect('purchasing/order');
	}

	/**
	 * Create draft purchase order.
	 *
	 * @param $purchaseOfferId
	 */
	public function preview_draft_email($purchaseOfferId)
	{
		AuthorizationModel::mustAuthorized(PERMISSION_ORDER_CREATE);

		$message = $this->input->get('message');
		$purchaseOffer = $this->purchaseOffer->getById($purchaseOfferId);
		$purchaseOfferItems = $this->purchaseOfferItem->getBy([
			'purchase_offer_items.id_purchase_offer' => $purchaseOffer['id'],
			'purchase_offer_items.quantity_selected!=' => null,
		]);
		$vendorContact = $this->vendorContact->getBy([
			'id_vendor' => $purchaseOffer['id_vendor'],
			'is_primary' => 1
		], true);

		$emailData = [
			'email' => $vendorContact['email'],
			'purchaseOffer' => $purchaseOffer,
			'purchaseOfferItems' => $purchaseOfferItems,
			'message' => $message,
		];

		$this->load->view('emails/vendors/completion', $emailData);
	}

	/**
	 * Show form item completion.
	 *
	 * @param $id
	 */
	public function item_completion($id)
	{
		AuthorizationModel::mustAuthorized(PERMISSION_ORDER_EDIT);

		$purchaseOrder = $this->purchaseOrder->getById($id);
		$purchaseOffer = $this->purchaseOffer->getById($purchaseOrder['id_purchase_offer']);
		$purchaseOfferItems = $this->purchaseOfferItem->getBy([
			'purchase_offer_items.id_purchase_offer' => $purchaseOffer['id'],
			'purchase_offer_items.quantity_selected!=' => null,
		]);
		$requisition = $this->requisition->getById($purchaseOffer['id_requisition']);

		$this->render('order/item_completion', compact('purchaseOrder', 'purchaseOffer', 'purchaseOfferItems', 'requisition'));
	}

	/**
	 * Save item completion.
	 *
	 * @param $id
	 */
	public function save_item_completion($id)
	{
		AuthorizationModel::mustAuthorized(PERMISSION_ORDER_EDIT);

		$items = $this->input->post('items');

		$purchaseOrder = $this->purchaseOrder->getById($id);

		$uploadedDocument = $purchaseOrder['document'];
		if (!empty($_FILES['document']['name'])) {
			$uploadFile = $this->uploader->setDriver('s3')->uploadTo('document', [
				'destination' => 'orders/' . date('Y/m')
			]);
			if ($uploadFile) {
				$uploadedData = $this->uploader->getUploadedData();
				$uploadedDocument = $uploadedData['uploaded_path'];
				if (!empty($purchaseOrder['document'])) {
					$this->uploader->delete($purchaseOrder['document']);
				}
			} else {
				flash('warning', $this->uploader->getDisplayErrors());
			}
		} else {
			$uploadFile = true;
		}

		if ($uploadFile) {
			$this->db->trans_start();

			$this->purchaseOffer->update([
				'status' => PurchaseOfferModel::STATUS_IN_PROCESS,
			], $purchaseOrder['id_purchase_offer']);

			$this->purchaseOrder->update([
				'document' => $uploadedDocument,
			], $id);

			foreach ($items as $item) {
				$purchaseOfferItem = $this->purchaseOfferItem->getById($item['purchase_offer_item_id']);
				$this->purchaseOfferItem->update([
					'quantity' => $item['quantity_selected'] > $purchaseOfferItem['quantity'] ? $item['quantity_selected'] : $purchaseOfferItem['quantity'],
					'quantity_selected' => $item['quantity_selected'],
				], $item['purchase_offer_item_id']);

				$this->requisitionItem->update([
					'quantity' => $item['quantity_needed']
				], $purchaseOfferItem['id_requisition_item']);
			}

			$this->db->trans_complete();

			if ($this->db->trans_status()) {
				flash('success', "Purchase order {$purchaseOrder['no_purchase']} successfully updated");
			} else {
				flash('danger', "Update purchase order failed, try again or contact administrator");
			}
			redirect('purchasing/order');
		}

		$this->item_completion($id);
	}

	/**
	 * Publish invoice from draft.
	 * @param $id
	 */
	public function publish($id)
	{
		$purchaseOrder = $this->purchaseOrder->getById($id);

		$this->save($purchaseOrder['id_purchase_offer'], $purchaseOrder);
	}

    /**
     * Ask confirmation order to the requester.
     *
     * @param $id
     */
    public function ask_confirmation($id)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_ORDER_EDIT);

        $validationRules = [
            'received_date' => 'trim|required|max_length[50]',
            'handover_note' => 'trim|required|max_length[1000]'
        ];
        if ($this->validate($validationRules)) {
            $receivedDate = $this->input->post('received_date');
            $handoverNote = $this->input->post('handover_note');

            $purchaseOrder = $this->purchaseOrder->getById($id);

            $this->db->trans_start();

            // set received date and note to purchase order
            $this->purchaseOrder->update([
                'received_date' => if_empty(format_date($receivedDate), null),
                'handover_note' => $handoverNote,
                'handover_date' => date('Y-m-d')
            ], $id);

            // set new status to purchase offer related the purchase order
            $this->purchaseOffer->update([
                'status' => PurchaseOfferModel::STATUS_ASK_CONFIRMATION
            ], $purchaseOrder['id_purchase_offer']);

            // add requisition status history
            $this->requisitionStatus->create([
                'id_requisition' => $purchaseOrder['id_requisition'],
                'status' => PurchaseOfferModel::STATUS_ASK_CONFIRMATION,
                'description' => "Ask Confirmation Order " . $purchaseOrder['no_purchase'],
                'data' => json_encode([
                    'id_purchase_order' => $purchaseOrder['id'],
                    'no_purchase' => $purchaseOrder['no_purchase'],
                    'received_date' => format_date($receivedDate),
                    'handover_note' => $handoverNote,
                    'creator' => UserModel::loginData('name')
                ])
            ]);

            // notify user to ask confirmation
            $requisition = $this->requisition->getById($purchaseOrder['id_requisition']);
            if (!empty($requisition['id_user'])) {
                $data = [
                    'id_user' => $requisition['id_user'],
                    'id_related' => $requisition['id'],
                    'channel' => NotificationModel::SUBSCRIBE_ORDER,
                    'event' => NotificationModel::EVENT_ORDER_MUTATION,
                    'payload' => [
                        'message' => "Requisition {$requisition['request_title']} ask confirmation related order {$purchaseOrder['no_purchase']}",
                        'url' => site_url('purchasing/handover/view/' . $purchaseOrder['id']),
                        'time' => format_date('now', 'Y-m-d H:i:s'),
                        'description' => 'Ask confirmation order'
                    ]
                ];
                $this->notification->broadcast($data);
            }

            $this->db->trans_complete();

            if ($this->db->trans_status()) {
                if (!empty($purchaseOrder['employee_email'])) {
                    $emailTo = $purchaseOrder['employee_email'];
                    $emailTitle = "Handover request {$purchaseOrder['request_title']} asking for confirmation of order {$purchaseOrder['no_purchase']}";
                    $emailTemplate = 'emails/basic';
                    $emailData = [
                        'name' => $purchaseOrder['employee_name'],
                        'email' => $purchaseOrder['employee_email'],
                        'content' => "
                            Requisition {$purchaseOrder['request_title']} ({$purchaseOrder['no_requisition']}) is <b>asked for confirmation</b>, 
                            The order with purchase number {$purchaseOrder['no_purchase']} need to be validated and confirmed. 
                            Items or services is received at <strong>{$receivedDate}</strong>, contact Admin Purchasing if you have some questions.
                            <br>
                            <span style='color: maroon'>
                                If you think this request is irrelevant or there is some mistakes, 
                                you can <b>Unconfirmed</b> this request by clicking Action - Unconfirmed 
                                <a href='" . site_url('purchasing/handover') . "'>here</a>
                            </span>
                            <br><br>
                            Handover Note: " . if_empty($handoverNote, 'no handover message')
                    ];

                    $sendEmail = $this->mailer->send($emailTo, $emailTitle, $emailTemplate, $emailData);
                    if ($sendEmail) {
                        flash('success', "Purchase order {$purchaseOrder['no_purchase']} successfully asked confirmation");
                    } else {
                        flash('success', "Asked confirmation success, but send notification to {$purchaseOrder['employee_name']} failed");
                    }
                } else {
                    flash('warning', 'Requester user does not has an email address');
                }
            } else {
                flash('danger', "Ask confirmation order {$purchaseOrder['no_purchase']} failed, try again or contact your administrator");
            }
        }

        redirect('purchasing/order');
    }

    /**
     * Completing purchase order.
     *
     * @param $id
     */
    public function complete($id)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_ORDER_EDIT);

        if ($this->validate(['rating' => 'trim|required|integer'])) {
            $rating = $this->input->post('rating');
            $remark = $this->input->post('remark');

            $purchaseOrder = $this->purchaseOrder->getById($id);

            $this->db->trans_start();

            $this->purchaseOrder->update([
                'rating' => $rating,
                'remark' => $remark,
            ], $id);

            $this->purchaseOffer->update([
                'status' => PurchaseOfferModel::STATUS_COMPLETED
            ], $purchaseOrder['id_purchase_offer']);

            $this->requisitionStatus->create([
                'id_requisition' => $purchaseOrder['id_requisition'],
                'status' => RequisitionModel::STATUS_DONE,
                'description' => "Purchase Complete Order " . $purchaseOrder['no_purchase']
            ]);

            // decide if all offer is proceed, the set requisition "DONE"
            $selectedPurchaseOffers = $this->purchaseOffer->getBy([
                'purchase_offers.id_requisition' => $purchaseOrder['id_requisition'],
                'purchase_offers.status' => PurchaseOfferModel::STATUS_SELECTED
            ]);
            $orderedPurchaseOffers = $this->purchaseOffer->getBy([
                'purchase_offers.id_requisition' => $purchaseOrder['id_requisition'],
                'purchase_offers.status' => PurchaseOfferModel::STATUS_ORDERED
            ]);
            $outstandingRequisition = array_merge($selectedPurchaseOffers, $orderedPurchaseOffers);
            if (empty($outstandingRequisition)) {
                $this->requisition->update([
                    'status' => RequisitionModel::STATUS_DONE
                ], $purchaseOrder['id_requisition']);

                $requisition = $this->requisition->getById($purchaseOrder['id_requisition']);
                $userId = if_empty($requisition['id_user_supervisor'], $requisition['id_user']);
                if (!empty($userId)) {
                    $data = [
                        'id_user' => $userId,
                        'id_related' => $requisition['id'],
                        'channel' => NotificationModel::SUBSCRIBE_REQUISITION,
                        'event' => NotificationModel::EVENT_REQUISITION_PROCEED,
                        'payload' => [
                            'message' => "Requisition {$requisition['request_title']} is done, all request was ordered",
                            'url' => site_url('requisition/view/' . $requisition['id']),
                            'time' => format_date('now', 'Y-m-d H:i:s'),
                            'description' => 'Requisition completed'
                        ]
                    ];
                    $this->notification->broadcast($data);
                }
            }

            $this->db->trans_complete();

            if ($this->db->trans_status()) {
                flash('success', "Purchase order {$purchaseOrder['no_purchase']} successfully completed");
            } else {
                flash('danger', "Completing order {$purchaseOrder['no_purchase']} failed, try again or contact your administrator");
            }
        }

        redirect('purchasing/order');
    }

    /**
     * Perform deleting purchase order data.
     *
     * @param $id
     */
    public function delete($id)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_ORDER_DELETE);

        $purchaseOrder = $this->purchaseOrder->getById($id);

        $this->db->trans_start();

        $this->purchaseOffer->update([
            'status' => PurchaseOfferModel::STATUS_SELECTED
        ], $purchaseOrder['id_purchase_offer']);

        // check if some purchase offer is available in order state
        $orderedPurchaseOffers = $this->purchaseOffer->getBy([
            'purchase_offers.id_requisition' => $purchaseOrder['id_requisition'],
            'purchase_offers.status' => PurchaseOfferModel::STATUS_ORDERED
        ]);
        $completedPurchaseOffers = $this->purchaseOffer->getBy([
            'purchase_offers.id_requisition' => $purchaseOrder['id_requisition'],
            'purchase_offers.status' => PurchaseOfferModel::STATUS_COMPLETED
        ]);
        $orderedOffers = array_merge($orderedPurchaseOffers, $completedPurchaseOffers);

        if (empty($orderedOffers)) {
            $this->requisition->update([
                'status' => RequisitionModel::STATUS_SELECTED
            ], $purchaseOrder['id_requisition']);
        } else {
            $this->requisition->update([
                'status' => RequisitionModel::STATUS_IN_PROCESS
            ], $purchaseOrder['id_requisition']);
        }

        $this->requisitionStatus->create([
            'id_requisition' => $purchaseOrder['id_requisition'],
            'status' => RequisitionModel::STATUS_SELECTED . ' (REVERTED)'
        ]);

        $this->purchaseOrder->delete(['id_purchase_offer' => $purchaseOrder['id_purchase_offer']]);

        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            flash('warning', "Purchase order {$purchaseOrder['no_purchase']} successfully deleted");
        } else {
            flash('danger', 'Delete order failed, try again or contact administrator');
        }
        redirect('purchasing/order');
    }


    /**
     * Notify requester about the order.
     *
     * @param $id
     */
    public function notify($id)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_ORDER_EDIT);

        $message = $this->input->post('message');
        $purchaseOrder = $this->purchaseOrder->getById($id);

        if (!empty($purchaseOrder['employee_email'])) {
            $emailTo = $purchaseOrder['employee_email'];
            $emailTitle = "Purchase order {$purchaseOrder['no_purchase']} of {$purchaseOrder['request_title']} is completed";
            $emailTemplate = 'emails/basic';
            $emailData = [
                'name' => $purchaseOrder['employee_name'],
                'email' => $purchaseOrder['employee_email'],
                'content' => "
                    Requisition {$purchaseOrder['request_title']} ({$purchaseOrder['no_requisition']}) was <b>proceed</b>, 
                    The order with purchase number {$purchaseOrder['no_purchase']} ({$purchaseOrder['status']}) has been issued and published. 
                    The order must be delivered at {$purchaseOrder['lead_time']}, contact Admin Purchasing if you have some questions.
                    <br><br>
                    Note: " . if_empty($message, 'no additional message')
            ];

            $sendEmail = $this->mailer->send($emailTo, $emailTitle, $emailTemplate, $emailData);
            if ($sendEmail) {
                flash('success', "Email notification to {$purchaseOrder['employee_name']} is successfully sent");
            } else {
                flash('success', "Send notification to {$purchaseOrder['employee_name']} failed");
            }
        } else {
            flash('danger', 'Email requester is not available');
        }

        redirect('purchasing/order');
    }

    /**
     * Return general validation rules.
     *
     * @return array
     */
    protected function _validation_rules()
    {
        return [
            'date' => 'trim|required',
            'email_note' => 'max_length[500]',
        ];
    }
}
