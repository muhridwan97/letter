<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Requisitions
 * @property CategoryModel $category
 * @property RequisitionModel $requisition
 * @property RequisitionItemModel $requisitionItem
 * @property RequisitionStatusModel $requisitionStatus
 * @property PurchaseOfferModel $purchaseOffer
 * @property PurchaseOfferItemModel $purchaseOfferItem
 * @property VendorModel $vendor
 * @property UserModel $user
 * @property NotificationModel $notification
 * @property Exporter $exporter
 * @property Uploader $uploader
 * @property Mailer $mailer
 */
class Offer extends App_Controller
{
    /**
     * Requisitions constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('RequisitionModel', 'requisition');
        $this->load->model('RequisitionItemModel', 'requisitionItem');
        $this->load->model('RequisitionStatusModel', 'requisitionStatus');
        $this->load->model('PurchaseOfferModel', 'purchaseOffer');
        $this->load->model('PurchaseOfferItemModel', 'purchaseOfferItem');
        $this->load->model('CategoryModel', 'category');
        $this->load->model('VendorModel', 'vendor');
        $this->load->model('UserModel', 'user');
        $this->load->model('NotificationModel', 'notification');
        $this->load->model('modules/Exporter', 'exporter');
        $this->load->model('modules/Uploader', 'uploader');
        $this->load->model('modules/Mailer', 'mailer');
    }

    /**
     * Show purchase offer index page.
     */
    public function index()
    {
        AuthorizationModel::mustAuthorized(PERMISSION_QUOTATION_VIEW);

        $filters = array_merge($_GET, ['page' => get_url_param('page', 1)]);

        $export = $this->input->get('export');
        if ($export) unset($filters['page']);

		$purchaseOffers = $this->purchaseOffer->getAll($filters);
		$activeRequisitions = $this->requisition->getBy(['requisitions.status' => RequisitionModel::STATUS_LISTED]);
		foreach ($activeRequisitions as &$activeRequisition) {
			$activeRequisition['confirm_top_manager'] = false;
			if (in_array($activeRequisition['request_type'], [RequisitionModel::REQUEST_TYPE_URGENT, RequisitionModel::REQUEST_TYPE_PROJECT])) {
				$offers = $this->purchaseOffer->getBy(['purchase_offers.id_requisition' => $activeRequisition['id']]);
				foreach ($offers as $purchaseOffer) {
					if($purchaseOffer['total_price'] >= 5000000) {
						$activeRequisition['confirm_top_manager'] = true;
						break;
					}
				}
			}
		}
		$quotationManageUsers = $this->user->getByPermission(PERMISSION_QUOTATION_MANAGE);

        if ($export) {
            if ($export == 'requisition') {
                $this->exporter->exportFromArray('Active Requisition', $activeRequisitions);
            } else {
                $this->exporter->exportFromArray('Selections', $purchaseOffers);
            }
        }

        $this->render('offer/index', compact('purchaseOffers', 'activeRequisitions', 'quotationManageUsers'));
    }

    /**
     * Show purchase offer data.
     *
     * @param $id
     */
    public function view($id)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_QUOTATION_VIEW);

        $purchaseOffer = $this->purchaseOffer->getById($id);
        $purchaseOfferItems = $this->purchaseOfferItem->getBy(['purchase_offer_items.id_purchase_offer' => $id]);
        $selectedOfferItems = $this->purchaseOfferItem->getBy([
            'purchase_offer_items.id_purchase_offer' => $id,
            'purchase_offer_items.quantity_selected!=' => null,
        ]);

        $this->render('offer/view', compact('purchaseOffer', 'purchaseOfferItems', 'selectedOfferItems'));
    }

    /**
     * Show create purchase offer.
     *
     * @param null $requisitionId
     */
    public function create($requisitionId = null)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_QUOTATION_CREATE);

        $requisitions = $this->requisition->getBy(['requisitions.status' => RequisitionModel::STATUS_LISTED]);
        $requisitionItems = [];
        $vendors = [];
        foreach ($requisitions as &$requisition) {
            if ($requisition['id'] == $requisitionId) {
                $requisition['_selected'] = true;
                $vendors = $this->vendor->getBy([
                	'ref_item_categories.id' => $requisition['id_item_category'],
			        'ref_vendors.is_active' => 1
				]);
                $requisitionItems = $this->requisitionItem->getBy(['requisition_items.id_requisition' => $requisitionId]);
            } else {
                $requisition['_selected'] = false;
            }
        }

        $this->render('offer/create', compact('requisitions', 'requisitionItems', 'vendors'));
    }

    /**
     * Save new purchase offer data.
     */
    public function save()
    {
        AuthorizationModel::mustAuthorized(PERMISSION_QUOTATION_CREATE);

        if ($this->validate()) {
            $requisitionId = $this->input->post('requisition');
            $vendorId = $this->input->post('vendor');
            $leadTime = $this->input->post('lead_time');
            $top = $this->input->post('top');
            $offerDiscountGoods = $this->input->post('offer_discount_goods');
            $offerDiscountServices = $this->input->post('offer_discount_services');
            $description = $this->input->post('description');
            $items = $this->input->post('taken_items');

            $requisition = $this->requisition->getById($requisitionId);
            $vendor = $this->vendor->getById($vendorId);

            $uploadedDocument = '';
            if (!empty($_FILES['document']['name'])) {
                $uploadFile = $this->uploader->setDriver('s3')->uploadTo('document', [
                    'destination' => 'offer/' . date('Y/m')
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

                $this->purchaseOffer->create([
                    'id_requisition' => $requisitionId,
                    'id_vendor' => $vendorId,
                    'lead_time' => format_date($leadTime),
                    'document' => if_empty($uploadedDocument, null),
                    'status' => PurchaseOfferModel::STATUS_ON_REVIEW,
                    'top' => $top,
                    'offer_discount_goods' => extract_number($offerDiscountGoods),
                    'offer_discount_services' => extract_number($offerDiscountServices),
                    'description' => $description
                ]);
                $purchaseOfferId = $this->db->insert_id();

                foreach ($items as $item) {
                    $this->purchaseOfferItem->create([
                        'id_purchase_offer' => $purchaseOfferId,
                        'id_requisition_item' => $item['id_requisition_item'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'discount' => $item['discount'],
                        'description' => $item['description'],
                    ]);
                }

                $this->requisitionStatus->create([
                    'id_requisition' => $requisitionId,
                    'status' => 'OFFERED',
                    'description' => 'Offer from ' . $vendor['vendor']
                ]);

                $userId = if_empty($requisition['id_user_supervisor'], $requisition['id_user']);
                if (!empty($userId)) {
                    $data = [
                        'id_user' => $userId,
                        'id_related' => $purchaseOfferId,
                        'channel' => NotificationModel::SUBSCRIBE_OFFER,
                        'event' => NotificationModel::EVENT_OFFER_MUTATION,
                        'payload' => [
                            'message' => "Purchase offer {$vendor['vendor']} for {$requisition['request_title']} is submitted",
                            'url' => site_url('purchasing/offer/view/' . $purchaseOfferId),
                            'time' => format_date('now', 'Y-m-d H:i:s'),
                            'description' => 'Offer from ' . $vendor['vendor']
                        ]
                    ];
                    $this->notification->broadcast($data);
                }

                $this->db->trans_complete();

                if ($this->db->trans_status()) {
                    flash('success', "Purchase offer of {$requisition['request_title']} successfully created", 'purchasing/offer');
                } else {
                    flash('danger', 'Create purchase offer failed, try again or contact administrator');
                }
            }
        }

        $this->create($this->input->post('requisition'));
    }

    /**
     * Show edit purchase offer.
     *
     * @param null $id
     */
    public function edit($id = null)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_QUOTATION_EDIT);

        $purchaseOffer = $this->purchaseOffer->getById($id);
        $purchaseOfferItems = $this->purchaseOfferItem->getBy(['purchase_offer_items.id_purchase_offer' => $id]);

        $requisition = $this->requisition->getById($purchaseOffer['id_requisition']);
        $listedRequisitions = $this->requisition->getBy(['requisitions.status' => RequisitionModel::STATUS_LISTED]);

        $requisitions = array_merge([$requisition], $listedRequisitions);
        $requisitionItems = $this->requisitionItem->getBy(['requisition_items.id_requisition' => $requisition['id']]);
        $vendors = $this->vendor->getBy([
        	'ref_categories.id' => $requisition['id_category'],
			'ref_vendors.is_active' => 1
		]);

		if (!in_array($purchaseOffer['status'], [PurchaseOfferModel::STATUS_ON_REVIEW])) {
			flash('danger', 'You cannot edit in process vendor offer, use revert to "LISTED" instead', 'purchasing/offer');
		}

        $this->render('offer/edit', compact('purchaseOffer', 'purchaseOfferItems', 'requisitions', 'requisitionItems', 'vendors'));
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
            $requisitionId = $this->input->post('requisition');
            $vendorId = $this->input->post('vendor');
            $leadTime = $this->input->post('lead_time');
            $description = $this->input->post('description');
            $top = $this->input->post('top');
            $offerDiscountGoods = $this->input->post('offer_discount_goods');
            $offerDiscountServices = $this->input->post('offer_discount_services');
            $items = $this->input->post('taken_items');

            $purchaseOffer = $this->purchaseOffer->getById($id);
            $requisition = $this->requisition->getById($requisitionId);

            $uploadedDocument = $purchaseOffer['document'];
            if (!empty($_FILES['document']['name'])) {
                $uploadFile = $this->uploader->setDriver('s3')->uploadTo('document', [
                    'destination' => 'offer/' . date('Y/m')
                ]);
                if ($uploadFile) {
                    $uploadedData = $this->uploader->getUploadedData();
                    $uploadedDocument = $uploadedData['uploaded_path'];
                    if (!empty($purchaseOffer['document'])) {
                        $this->uploader->delete($purchaseOffer['document']);
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
                    'id_requisition' => $requisitionId,
                    'id_vendor' => $vendorId,
                    'lead_time' => format_date($leadTime),
                    'document' => if_empty($uploadedDocument, null),
                    'top' => $top,
                    'offer_discount_goods' => extract_number($offerDiscountGoods),
                    'offer_discount_services' => extract_number($offerDiscountServices),
                    'description' => $description
                ], $id);

                $this->purchaseOfferItem->delete(['purchase_offer_items.id_purchase_offer' => $id]);
                foreach ($items as $item) {
                    $this->purchaseOfferItem->create([
                        'id_purchase_offer' => $id,
                        'id_requisition_item' => $item['id_requisition_item'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'discount' => $item['discount'],
                        'description' => $item['description'],
                    ]);
                }

                $this->db->trans_complete();

                if ($this->db->trans_status()) {
                    flash('success', "Purchase offer of {$requisition['request_title']} successfully updated", 'purchasing/offer');
                } else {
                    flash('danger', "Update purchase offer failed, try again or contact administrator");
                }
            }
        }
        $this->edit($id);
    }

    /**
     * Perform deleting purchase offer data.
     *
     * @param $id
     */
    public function delete($id)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_QUOTATION_DELETE);

        $purchaseOffer = $this->purchaseOffer->getById($id);

        if ($this->purchaseOffer->delete($id, true)) {
            flash('warning', "Purchase offer {$purchaseOffer['requisition']} successfully deleted");
        } else {
            flash('danger', 'Delete purchase offer failed, try again or contact administrator');
        }
        redirect('purchasing/offer');
    }

    /**
     * Return general validation rules.
     *
     * @return array
     */
    protected function _validation_rules()
    {
        return [
            'requisition' => 'trim|required|integer',
            'vendor' => 'trim|required|integer',
            'lead_time' => 'trim|required|max_length[50]',
            'description' => 'max_length[500]',
            'top' => 'trim|required|integer|max_length[50]',
            'offer_discount_goods' => 'trim|max_length[50]',
            'offer_discount_services' => 'trim|max_length[50]',
            'taken_items[]' => 'required'
        ];
    }
}
