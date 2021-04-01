<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Selection
 * @property RequisitionModel $requisition
 * @property RequisitionItemModel $requisitionItem
 * @property RequisitionStatusModel $requisitionStatus
 * @property PurchaseOfferModel $purchaseOffer
 * @property PurchaseOfferItemModel $purchaseOfferItem
 * @property NotificationModel $notification
 * @property VendorModel $vendor
 * @property UserModel $user
 * @property Exporter $exporter
 * @property Mailer $mailer
 */
class Selection extends App_Controller
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
        $this->load->model('PurchaseOfferComparisonModel', 'purchaseOfferComparison');
        $this->load->model('NotificationModel', 'notification');
        $this->load->model('VendorModel', 'vendor');
        $this->load->model('UserModel', 'user');
        $this->load->model('modules/Exporter', 'exporter');
        $this->load->model('modules/Uploader', 'uploader');
        $this->load->model('modules/Mailer', 'mailer');

        $this->setFilterMethods([
            'comparison' => 'GET',
            'select' => 'POST|PUT',
            'cancel' => 'POST|PUT',
            'finish_selection' => 'POST|PUT',
            'excel_comparison' => 'GET',
            'add_comparison' => 'POST|GET',
            'save_comparison' => 'POST',
            'edit_comparison' => 'POST|GET',
            'update_comparison' => 'POST|PUT',
            'delete_comparison' => 'Delete',
            'print_comparison' => 'GET',
        ]);
    }

    /**
     * Show purchase offer index page.
     */
    public function index()
    {
        AuthorizationModel::mustAuthorized(PERMISSION_QUOTATION_SELECT);

        $export = $this->input->get('export');

        $requisitionAsk = $this->requisition->getBy(['requisitions.status' => RequisitionModel::STATUS_ASK_SELECTION]);
        $requisitionReady = $this->requisition->getBy(['requisitions.status' => RequisitionModel::STATUS_READY]);
        $requisitions = array_merge($requisitionAsk, $requisitionReady);
		$quotationManageUsers = $this->user->getByPermission(PERMISSION_QUOTATION_MANAGE);

        if ($export) {
            $this->exporter->exportFromArray('Selections', $requisitions);
        }

        $this->render('selection/index', compact('requisitions', 'quotationManageUsers'));
    }

    /**
     * Show comparison purchase offer.
     *
     * @param $requisitionId
     */
    public function comparison($requisitionId)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_QUOTATION_SELECT);

        $requisition = $this->requisition->getById($requisitionId);
        $requisitionItems = $this->requisitionItem->getBy(['requisition_items.id_requisition' => $requisitionId]);

        $purchaseOffers = $this->purchaseOffer->getBy(['purchase_offers.id_requisition' => $requisitionId]);
        foreach ($purchaseOffers as &$purchaseOffer) {
            $purchaseOffer['items'] = $this->purchaseOfferItem->getBy(['purchase_offer_items.id_purchase_offer' => $purchaseOffer['id']]);
            $purchaseOffer['vendor_data'] = $this->vendor->getById($purchaseOffer['id_vendor']);
        }
        $differenceItems = $this->purchaseOffer->getDifferenceOfferItem($requisitionId);

        $this->render('selection/comparison', compact('requisition', 'requisitionItems', 'purchaseOffers', 'differenceItems'));
    }

    /**
     * Set requisition ready for selection.
     *
     * @param $purchaseOfferId
     * @param null $purchaseOfferItemId
     */
    public function select($purchaseOfferId, $purchaseOfferItemId = null)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_QUOTATION_SELECT);

        $purchaseOffer = $this->purchaseOffer->getById($purchaseOfferId);

        $this->db->trans_start();

        $this->purchaseOffer->update([
            'status' => PurchaseOfferModel::STATUS_SELECTED
        ], $purchaseOfferId);

        if (empty($purchaseOfferItemId)) {
            $purchaseOfferItems = $this->purchaseOfferItem->getBy([
                'purchase_offer_items.id_purchase_offer' => $purchaseOfferId
            ]);
            foreach ($purchaseOfferItems as $purchaseOfferItem) {
                $this->purchaseOfferItem->update([
                    'quantity_selected' => $purchaseOfferItem['quantity']
                ], $purchaseOfferItem['id']);
            }
        } else {
            $purchaseOfferItem = $this->purchaseOfferItem->getBy([
                'purchase_offer_items.id_purchase_offer' => $purchaseOfferId,
                'purchase_offer_items.id' => $purchaseOfferItemId
            ], true);

            $selectedQuantity = $this->input->post('quantity_select');
            if ($selectedQuantity > $purchaseOfferItem['quantity']) {
                $selectedQuantity = $purchaseOfferItem['quantity'];
            }
            $this->purchaseOfferItem->update([
                'quantity_selected' => empty($selectedQuantity) ? null : $selectedQuantity
            ], $purchaseOfferItemId);

            // check if all item is unselected then change offer to on review
            if (empty($selectedQuantity)) {
                $purchaseOfferItems = $this->purchaseOfferItem->getBy([
                    'purchase_offer_items.id_purchase_offer' => $purchaseOfferId
                ]);
                $selected = false;
                foreach ($purchaseOfferItems as $purchaseOfferItem) {
                    if (!empty($purchaseOfferItem['quantity_selected'])) {
                        $selected = true;
                        break;
                    }
                }
                if (!$selected) {
                    $this->purchaseOffer->update([
                        'status' => PurchaseOfferModel::STATUS_ON_REVIEW
                    ], $purchaseOfferId);
                }
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            flash('success', "Offer vendor {$purchaseOffer['vendor']} for {$purchaseOffer['request_title']} successfully selected", '_redirect');
        } else {
            flash('danger', "Select vendor {$purchaseOffer['vendor']} failed, try again or contact administrator", '_redirect');
        }

        redirect('purchasing/selection');
    }

    /**
     * Set requisition ready for selection.
     *
     * @param $purchaseOfferId
     */
    public function cancel($purchaseOfferId)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_QUOTATION_SELECT);

        $purchaseOffer = $this->purchaseOffer->getById($purchaseOfferId);

        $purchaseOfferItems = $this->purchaseOfferItem->getBy([
            'purchase_offer_items.id_purchase_offer' => $purchaseOfferId
        ]);

        $this->db->trans_start();

        $this->purchaseOffer->update([
            'status' => PurchaseOfferModel::STATUS_ON_REVIEW
        ], $purchaseOfferId);

        foreach ($purchaseOfferItems as $purchaseOfferItem) {
            $this->purchaseOfferItem->update([
                'quantity_selected' => null
            ], $purchaseOfferItem['id']);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            flash('warning', "Offer vendor {$purchaseOffer['vendor']} for {$purchaseOffer['request_title']} successfully cancelled", '_redirect');
        } else {
            flash('danger', "Select vendor {$purchaseOffer['vendor']} failed, try again or contact administrator", '_redirect');
        }

        redirect('purchasing/selection');
    }

    /**
     * Set requisition ready for selection.
     *
     * @param $requisitionId
     */
    public function finish_selection($requisitionId)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_QUOTATION_SELECT);

        $requisition = $this->requisition->getById($requisitionId);
        $purchaseOffer = $this->purchaseOffer->getBy([
            'purchase_offers.id_requisition' => $requisitionId,
            'purchase_offers.status' => PurchaseOfferModel::STATUS_SELECTED,
        ]);

        if (empty($purchaseOffer)) {
            flash('danger', 'The requisition has not any offer selected yet!', '_redirect');
        } else {
            $this->db->trans_start();

            $this->requisition->update([
                'status' => RequisitionModel::STATUS_SELECTED
            ], $requisitionId);

            $this->requisitionStatus->create([
                'id_requisition' => $requisitionId,
                'status' => RequisitionModel::STATUS_SELECTED,
                'description' => 'Vendor was selected'
            ]);

            $this->purchaseOffer->update(['status' => PurchaseOfferModel::STATUS_UNSELECTED], [
                'purchase_offers.id_requisition' => $requisitionId,
                'purchase_offers.status!=' => PurchaseOfferModel::STATUS_SELECTED,
            ]);

            $userId = if_empty($requisition['id_user_supervisor'], $requisition['id_user']);
            if (!empty($userId)) {
                $data = [
                    'id_user' => $userId,
                    'id_related' => $requisition['id'],
                    'channel' => NotificationModel::SUBSCRIBE_REQUISITION,
                    'event' => NotificationModel::EVENT_REQUISITION_PROCEED,
                    'payload' => [
                        'message' => "Requisition {$requisition['request_title']} is completing for selection",
                        'url' => site_url('requisition/view/' . $requisition['id']),
                        'time' => format_date('now', 'Y-m-d H:i:s'),
                        'description' => 'Set status selected'
                    ]
                ];
                $this->notification->broadcast($data);
            }

            $this->db->trans_complete();

            if ($this->db->trans_status()) {
                $emailTo = get_setting('email_support');
                $emailTitle = "Requisition {$requisition['request_title']} is finished selecting vendor offer";
                $emailTemplate = 'emails/basic';
                $emailData = [
                    'name' => get_setting('purchasing_admin', 'Admin'),
                    'email' => get_setting('email_support'),
                    'content' => "
                        Requisition {$requisition['request_title']} ({$requisition['no_requisition']}) is <b>finished select vendors</b>, 
                        it will proceed to purchase order."
                ];
                $this->mailer->send($emailTo, $emailTitle, $emailTemplate, $emailData);

                flash('warning', "Selecting vendor for {$requisition['request_title']} finished", '_redirect', 'purchasing/selection');
            } else {
                flash('danger', "Finishing selecting vendor {$requisition['request_title']} failed, try again or contact administrator", '_redirect', 'purchasing/selection');
            }
        }

        redirect('purchasing/selection');
    }
    /**
     * Show comparison purchase offer with excel.
     *
     * @param $requisitionId
     */
    public function excel_comparison($requisitionId)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_QUOTATION_SELECT);

        $requisition = $this->requisition->getById($requisitionId);
        $requisitionItems = $this->requisitionItem->getBy(['requisition_items.id_requisition' => $requisitionId]);

        $idSamplePo = [];

        $purchaseOffers = $this->purchaseOffer->getBy(['purchase_offers.id_requisition' => $requisitionId]);
        foreach ($purchaseOffers as &$purchaseOffer) {
            $purchaseOffer['items'] = $this->purchaseOfferItem->getBy(['purchase_offer_items.id_purchase_offer' => $purchaseOffer['id']]);
            $purchaseOffer['comparison_items'] = $this->purchaseOfferComparison->getBy(['purchase_offer_comparisons.id_purchase_offer' => $purchaseOffer['id']]);
            $purchaseOffer['vendor_data'] = $this->vendor->getById($purchaseOffer['id_vendor']);
            $idSamplePo[] = $purchaseOffer['id'];
        }
        $differenceItems = $this->purchaseOffer->getDifferenceOfferItem($requisitionId);

        $comparisonItems = $this->purchaseOfferComparison->getComparisonItem($idSamplePo[0]);
        // print_debug($comparisonItems);

        $this->render('selection/excel_comparison', compact('comparisonItems', 'requisition', 'requisitionItems', 'purchaseOffers', 'differenceItems'));
    }

    /**
     * Add new item for vendor comparison.
     *
     * @param $requisitionId
     */
    public function add_comparison($requisitionId)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_ITEM_CREATE);

        $purchaseOffers = $this->purchaseOffer->getBy(['purchase_offers.id_requisition' => $requisitionId]);

        $this->render('item_comparison/create', compact('purchaseOffers', 'requisitionId'));
    }

    /**
     * Save new item data comparison.
     */
    public function save_comparison()
    {
        AuthorizationModel::mustAuthorized(PERMISSION_ITEM_CREATE);

        $requisitionId = $this->input->post('id_requisition');
        if ($this->validate($this->_validation_rules($requisitionId))) {
            $item_name = $this->input->post('item_name');
            $itemValues = $this->input->post('value_item');

            $this->db->trans_start();

            foreach ($itemValues as $id_purchase_offer => $value) {
                $this->purchaseOfferComparison->create([
                    'id_requisition' => $requisitionId,
                    'id_purchase_offer' => $id_purchase_offer,
                    'comparison_item' => $item_name,
                    'description' => $value
                ]);
            }
            $this->db->trans_complete();

            if ($this->db->trans_status()) {
                flash('success', "Items comparison {$item_name} successfully created", 'purchasing/selection/excel_comparison/'.$requisitionId);
            } else {
                flash('danger', 'Create item comparison failed, try again or contact administrator');
            }
        }
        $this->add_comparison($requisitionId);
    }

    /**
     * Show edit item comparison form.
     *
     * @param $requisitionId
     * @param $comparisonItem
     */
    public function edit_comparison($requisitionId, $comparisonItem)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_ITEM_EDIT);

        $purchaseOffers = $this->purchaseOffer->getBy(['purchase_offers.id_requisition' => $requisitionId]);

        foreach ($purchaseOffers as &$purchaseOffer) {
            $purchaseOffer['item_value'] = $this->purchaseOfferComparison->getBy([
                'id_purchase_offer' => $purchaseOffer['id'],
                'comparison_item' => $comparisonItem,
            ],true);
        }

        $this->render('item_comparison/edit', compact('purchaseOffers', 'requisitionId', 'comparisonItem'));
    }

    /**
     * Update data category by id.
     *
     * @param $requisitionId
     */
    public function update_comparison($requisitionId)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_ITEM_EDIT);
        
        $item_name = $this->input->post('item_name');
        $requisitionId = $this->input->post('id_requisition');
        $itemValues = $this->input->post('value_item');

        $this->db->trans_start();

        $this->purchaseOfferComparison->delete([
            'id_requisition' => $requisitionId,
            'comparison_item' => $item_name,
        ]);

        foreach ($itemValues as $id_purchase_offer => $value) {
            $this->purchaseOfferComparison->create([
                'id_requisition' => $requisitionId,
                'id_purchase_offer' => $id_purchase_offer,
                'comparison_item' => $item_name,
                'description' => $value
            ]);
        }
        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            flash('success', "Item comparison {$item_name} successfully updated", 'purchasing/selection/excel_comparison/'.$requisitionId);
        } else {
            flash('danger', "Update item comparison failed, try again or contact administrator");
        }
    }

    /**
     * Delete data comparison.
     *
     * @param $requisitionId
     * @param $comparisonItem
     */
    public function delete_comparison($requisitionId, $comparisonItem)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_ITEM_DELETE);

        $this->db->trans_start();

        $this->purchaseOfferComparison->delete([
            'id_requisition' => $requisitionId,
            'comparison_item' => $comparisonItem,
        ]);

        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            flash('success', "Item comparison {$comparisonItem} successfully deleted", 'purchasing/selection/excel_comparison/'.$requisitionId);
        } else {
            flash('danger', "Delete item comparison failed, try again or contact administrator");
        }
    }

    /**
     * Print comparison.
     *
     * @param $id
     */
    public function print_comparison($id)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_REQUISITION_MANAGE);

        $requisition = $this->requisition->getById($id);
        $requisitionItems = $this->requisitionItem->getBy(['requisition_items.id_requisition' => $id]);

        $idSamplePo = [];

        $purchaseOffers = $this->purchaseOffer->getBy(['purchase_offers.id_requisition' => $id]);
        foreach ($purchaseOffers as &$purchaseOffer) {
            $purchaseOffer['items'] = $this->purchaseOfferItem->getBy(['purchase_offer_items.id_purchase_offer' => $purchaseOffer['id']]);
            $purchaseOffer['vendor_data'] = $this->vendor->getById($purchaseOffer['id_vendor']);
            $purchaseOffer['comparison_items'] = $this->purchaseOfferComparison->getBy(['purchase_offer_comparisons.id_purchase_offer' => $purchaseOffer['id']]);
            $idSamplePo[] = $purchaseOffer['id'];
        }

        $comparisonItems = $this->purchaseOfferComparison->getComparisonItem($idSamplePo[0]);

        $export = $this->input->get('export');
        if ($export) {
            $exportData = [];
            $no = 1;
            foreach ($requisitionItems as $itemRequest){
                $rowData = [];
                $rowData['no'] = $no++;
                $rowData['item'] = $itemRequest['item_name'];
                foreach ($purchaseOffers as $offer){
                    $hasItem = false;
                    foreach ($offer['items'] as $item){
                        if ($item['id_requisition_item'] == $itemRequest['id']){
                            $hasItem = true;
                            $rowData[$offer['vendor']] = 'Rp '. numerical($item['price']).' / '. $item['unit']. ' ('.$item['quantity'].')';
                        }
                    }
                    if (!$hasItem){
                        $rowData[$offer['vendor']] = '-';
                    }
                }
                $exportData[] = $rowData;
            }
            // add comparison item manual
            foreach ($comparisonItems as $item){
                $rowData = [];
                $rowData['no'] = $no++;
                $rowData['item'] = $item['comparison_item'];
                foreach ($purchaseOffers as $offer){
                    $hasItem = false;
                    foreach ($offer['comparison_items'] as $comp){
                        if ($comp['comparison_item'] == $item['comparison_item']){
                            $hasItem = true;
                            $rowData[$offer['vendor']] = if_empty($comp['description'], '-');
                        }
                    }
                    if (!$hasItem){
                        $rowData[$offer['vendor']] = '-';
                    }
                }
                $exportData[] = $rowData;
            }

            $rowData = [];
            $rowData['no'] = $no++;
            $rowData['item'] = 'SUB TOTAL';
            foreach ($purchaseOffers as $offer){
                $rowData[$offer['vendor']] = 'Rp '. numerical($offer['extended_total']);
            }
            $exportData[] = $rowData;
            // print_debug($exportData);
            $this->exporter->exportFromArray($requisition['request_title'], $exportData);
        }

        $this->load->view('item_comparison/print_comparison', compact('comparisonItems', 'requisition', 'requisitionItems', 'purchaseOffers'));
    }

    /**
	 * Return general validation rules.
	 *
	 * @param mixed ...$params
	 * @return array
	 */
	protected function _validation_rules(...$params)
	{
		$id = isset($params[0]) ? $params[0] : 0;

        return [
        	'item_name' => [
				'trim', 'required', 'max_length[25]', 'regex_match[/^[a-zA-Z0-9.\-]+$/]', ['item_exists', function ($item_name) use ($id) {
					$this->form_validation->set_message('item_exists', 'The item %s has been registered before, try another');
					return empty($this->purchaseOfferComparison->getBy(['comparison_item' => $item_name, 'purchase_offer_comparisons.id_requisition' => $id]));
				}]
			],
        ];
    }
}
