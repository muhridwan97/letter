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
 * @property Uploader $uploader
 */
class Payment_handover extends App_Controller
{
    /**
     * Payment Handover constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('PaymentHandoverModel', 'paymentHandover');
        $this->load->model('PaymentHandoverPoModel', 'paymentHandoverPo');
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
        $this->load->model('modules/Uploader', 'uploader');

        $this->setFilterMethods([
            'upload_handover' => 'POST|GET',
            'upload' => 'POST|GET',
            'print_handover' => 'GET',
            'edit_po' => 'POST|GET',
            'update_po' => 'POST|GET',
        ]);
    }

    /**
     * Show handover confirmation index page.
     */
    public function index()
    {
        AuthorizationModel::mustAuthorized(PERMISSION_PAYMENT_HANDOVER_CREATE);

        $filters = array_merge($_GET, ['page' => get_url_param('page', 1)]);

        $export = $this->input->get('export');
        if ($export) unset($filters['page']);
        
        $handovers = $this->paymentHandover->getAll($filters);

        if ($export) {
            $this->exporter->exportFromArray('Handovers', $handovers);
        }else{
            foreach ($handovers['data'] as &$handover) {
                $handover['details'] = $this->paymentHandoverPo->getBy(['id_handover' => $handover['id']]);
			}
        }

        $this->render('payment_handover/index', compact('handovers'));
    }

    /**
     * Show handover data.
     *
     * @param $id
     */
    public function view($id)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_PAYMENT_HANDOVER_VIEW);

        $paymentHandover = $this->paymentHandover->getById($id);
        $purchaseOrders = $this->paymentHandoverPo->getBy(['id_handover' => $paymentHandover['id']]);

        $this->render('payment_handover/view', compact('paymentHandover', 'purchaseOrders'));
    }

    /**
	 * Show create payment handover.
	 */
	public function create()
	{
		AuthorizationModel::mustAuthorized(PERMISSION_PAYMENT_HANDOVER_CREATE);

        $purchaseOrders = $this->paymentHandoverPo->getPoNotHandoverYet();

		$this->render('payment_handover/create', compact('purchaseOrders'));
    }
    
    /**
	 * Save new payment handover data.
	 */
	public function save()
	{
		AuthorizationModel::mustAuthorized(PERMISSION_PAYMENT_HANDOVER_CREATE);

		if ($this->validate()) {
            $purchaseOrders = $this->input->post('purchase_orders');
            //generate no payment handover
            $no_handover = $this->paymentHandover->getPaymentHandoverNumber();

			$this->db->trans_start();

            $this->paymentHandover->create([
                'no_handover' => $no_handover,
            ]);
            $handoverId = $this->db->insert_id();

            foreach ($purchaseOrders as $purchaseOrder) {
                $this->paymentHandoverPo->create([
                    'id_handover' => $handoverId,
                    'id_purchase_order' => $purchaseOrder,
                ]);
            }

            $paymentHandover = $this->paymentHandover->getById($handoverId);
            $purchaseOrders = $this->paymentHandoverPo->getBy(['id_handover' => $paymentHandover['id']]);

            $this->db->trans_complete();
            
            if ($this->db->trans_status()) {
                flash('success', "Payment handover {$paymentHandover['no_handover']} successfully created", 'purchasing/payment-handover');
			} else {
				flash('danger', 'Create Payment handover failed, try again or contact administrator');
            }

		}
		$this->create();
    }
    
    /**
	 * Show create upload handover.
     * 
     * @param $handoverId
	 */
	public function upload_handover($handoverId)
	{
		AuthorizationModel::mustAuthorized(PERMISSION_PAYMENT_HANDOVER_CREATE);

        $paymentHandover = $this->paymentHandover->getById($handoverId);
        $purchaseOrders = $this->paymentHandoverPo->getBy(['id_handover' => $paymentHandover['id']]);

		$this->render('payment_handover/upload', compact('paymentHandover', 'purchaseOrders'));
    }

    /**
	 * Upload new payment handover data.
	 */
	public function upload()
	{
		AuthorizationModel::mustAuthorized(PERMISSION_PAYMENT_HANDOVER_CREATE);

        $handoverId = $this->input->post('id_handover');
		if ($this->validate([
			'handover_date' => 'trim|required|max_length[50]',
        ])) {
            $noInvoice = $this->input->post('no_invoice');
            $handoverDate = $this->input->post('handover_date');
            $purchaseOrders = $this->input->post('purchase_orders');
            $arrayPo = [];
            foreach ($purchaseOrders as $key => $purchaseOrder) {
                $arrayPo[$purchaseOrder['id_po']] = $purchaseOrder;
            }
            
            $uploadedDocument = '';
            if (!empty($_FILES['document']['name'])) {
                $uploadFile = $this->uploader->setDriver('s3')->uploadTo('document', [
                    'destination' => 'payment-handover/' . date('Y/m')
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

                $this->paymentHandover->update([
                    'handover_date' => format_date($handoverDate),
                    'document' => $uploadedDocument,
                ],$handoverId);

                $purchaseOrderData = $this->paymentHandoverPo->getBy(['id_handover' => $handoverId]);
                
                foreach ($purchaseOrderData as $key => $data) {
                    $this->paymentHandoverPo->update([
                        'vendor_invoice' => $arrayPo[$data['id_purchase_order']]['vendor_invoice'],
                        'invoice_date' => format_date($arrayPo[$data['id_purchase_order']]['invoice_date']),
                    ],['id_purchase_order' => $data['id_purchase_order']]);
                }
    
                $paymentHandover = $this->paymentHandover->getById($handoverId);
    
                $this->db->trans_complete();
                
                if ($this->db->trans_status()) {
					flash('success', "Payment handover {$paymentHandover['no_handover']} successfully uploaded", 'purchasing/payment-handover');
				} else {
					flash('danger', 'Update Payment handover failed, try again or contact administrator');
                }                
            }
            flash('danger', 'Upload Payment handover failed, try again or contact administrator');
		}
		$this->upload_handover($handoverId);
    }

    /**
	 * Show edit po handover.
     * 
     * @param $handoverId
	 */
	public function edit_po($handoverId)
	{
		AuthorizationModel::mustAuthorized(PERMISSION_PAYMENT_HANDOVER_EDIT);

        $paymentHandover = $this->paymentHandover->getById($handoverId);
        $purchaseOrders = $this->paymentHandoverPo->getBy(['id_handover' => $paymentHandover['id']]);
        $listPurchaseOrders = $this->paymentHandoverPo->getPoNotHandoverYet();
        // print_debug($purchaseOrders);
		$this->render('payment_handover/edit_po', compact('listPurchaseOrders', 'paymentHandover', 'purchaseOrders'));
    }

    /**
	 * Update po payment handover data.
	 */
	public function update_po()
	{
		AuthorizationModel::mustAuthorized(PERMISSION_PAYMENT_HANDOVER_EDIT);

        $handoverId = $this->input->post('id_handover');
		if ($this->validate()) {
            $purchaseOrders = $this->input->post('purchase_orders');
            $no_handover = $this->input->post('no_handover');

			$this->db->trans_start();

            $delete = $this->paymentHandoverPo->delete(['id_handover' => $handoverId]);
            if($delete){
                foreach ($purchaseOrders as $purchaseOrder) {
                    $this->paymentHandoverPo->create([
                        'id_handover' => $handoverId,
                        'id_purchase_order' => $purchaseOrder['id_purchase_order'],
                        'vendor_invoice' => if_empty($purchaseOrder['vendor_invoice'], NULL),
                        'invoice_date' => if_empty(format_date($purchaseOrder['invoice_date']), NULL),
                    ]);
                }
            }

            $paymentHandover = $this->paymentHandover->getById($handoverId);
            $purchaseOrders = $this->paymentHandoverPo->getBy(['id_handover' => $paymentHandover['id']]);

            $this->db->trans_complete();
            
            if ($this->db->trans_status()) {
                flash('success', "Payment handover {$no_handover} successfully updated", 'purchasing/payment-handover');
			} else {
				flash('danger', 'Update Payment handover failed, try again or contact administrator');
            }

		}
		$this->edit_po($handoverId);
    }

    /**
	 * Show edit upload handover.
     * 
     * @param $handoverId
	 */
	public function edit($handoverId)
	{
		AuthorizationModel::mustAuthorized(PERMISSION_PAYMENT_HANDOVER_EDIT);

        $paymentHandover = $this->paymentHandover->getById($handoverId);
        $purchaseOrders = $this->paymentHandoverPo->getBy(['id_handover' => $paymentHandover['id']]);

		$this->render('payment_handover/edit', compact('paymentHandover', 'purchaseOrders'));
    }

    /**
	 * Update new payment handover data.
	 */
	public function update()
	{
		AuthorizationModel::mustAuthorized(PERMISSION_PAYMENT_HANDOVER_EDIT);

        $handoverId = $this->input->post('id_handover');
		if ($this->validate([
			'handover_date' => 'trim|required|max_length[50]',
        ])) {
            $noInvoice = $this->input->post('no_invoice');
            $handoverDate = $this->input->post('handover_date');
            $purchaseOrders = $this->input->post('purchase_orders');
            $arrayPo = [];
            foreach ($purchaseOrders as $key => $purchaseOrder) {
                $arrayPo[$purchaseOrder['id_po']] = $purchaseOrder;
            }
            
            $paymentHandover = $this->paymentHandover->getById($handoverId);
            $uploadedDocument = $paymentHandover['document'];
            if (!empty($_FILES['document']['name'])) {
                $uploadFile = $this->uploader->setDriver('s3')->uploadTo('document', [
                    'destination' => 'payment-handover/' . date('Y/m')
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

                $this->paymentHandover->update([
                    'handover_date' => format_date($handoverDate),
                    'document' => $uploadedDocument,
                ],$handoverId);

                $purchaseOrderData = $this->paymentHandoverPo->getBy(['id_handover' => $handoverId]);
                
                foreach ($purchaseOrderData as $key => $data) {
                    $this->paymentHandoverPo->update([
                        'vendor_invoice' => $arrayPo[$data['id_purchase_order']]['vendor_invoice'],
                        'invoice_date' => format_date($arrayPo[$data['id_purchase_order']]['invoice_date']),
                    ],['id_purchase_order' => $data['id_purchase_order']]);
                }
    
    
                $this->db->trans_complete();
                
                if ($this->db->trans_status()) {
					flash('success', "Payment handover {$paymentHandover['no_handover']} successfully uploaded", 'purchasing/payment-handover');
				} else {
					flash('danger', 'Update Payment handover failed, try again or contact administrator');
                }                
            }
            flash('danger', 'Upload Payment handover failed, try again or contact administrator');
		}
		$this->upload_handover($handoverId);
    }

    /**
     * Print handover statement.
     *
     * @param $handoverId
     * @param bool $output
     * @return null|string
     */
    public function print_handover($handoverId, $output = false)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_PAYMENT_HANDOVER_VIEW);

        $paymentHandover = $this->paymentHandover->getById($handoverId);
        $purchaseOrders = $this->paymentHandoverPo->getBy(['id_handover' => $paymentHandover['id']]);
        
        $options = [
            'buffer' => $output,
            'view' => 'payment_handover/print',
            'data' => compact('paymentHandover', 'purchaseOrders')
        ];
        return $this->exporter->exportToPdf("payment-handover-{$handoverId}.pdf", null, $options);
    }

    /**
	 * Return general validation rules.
	 *
	 * @return array
	 */
	protected function _validation_rules()
	{
		$baseRule = [
			'purchase_orders[]' => ['trim', 'required', ['po_exists', function ($purchase_orders) {
                $this->form_validation->set_message('po_exists', 'The item %s has been handover before, try another');
                return empty($this->paymentHandoverPo->getBy('id_purchase_order IN('.if_empty($purchase_orders,"0").')'));
            }]],
		];
		return $baseRule;
	}

}
