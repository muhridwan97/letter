<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Vendor_selection
 * @property UserModel $user
 * @property RequisitionModel $requisition
 * @property RequisitionItemModel $requisitionItem
 * @property RequisitionStatusModel $requisitionStatus
 * @property PurchaseOfferModel $purchaseOffer
 * @property PurchaseOfferItemModel $purchaseOfferItem
 * @property NotificationModel $notification
 * @property VendorModel $vendor
 * @property Exporter $exporter
 * @property Mailer $mailer
 */
class Vendor_selection extends App_Controller
{
    protected $layout = 'layouts/selection';

    /**
     * Requisitions constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('UserModel', 'user');
        $this->load->model('RequisitionModel', 'requisition');
        $this->load->model('RequisitionItemModel', 'requisitionItem');
        $this->load->model('RequisitionStatusModel', 'requisitionStatus');
        $this->load->model('PurchaseOfferModel', 'purchaseOffer');
        $this->load->model('PurchaseOfferItemModel', 'purchaseOfferItem');
        $this->load->model('NotificationModel', 'notification');
        $this->load->model('VendorModel', 'vendor');
        $this->load->model('modules/Exporter', 'exporter');
        $this->load->model('modules/Uploader', 'uploader');
        $this->load->model('modules/Mailer', 'mailer');

        $this->setFilterMethods([
            'select' => 'GET',
            'save_selection' => 'POST|PUT',
        ]);
    }

    /**
     * Check if token is valid.
     *
     * @param $requisitionId
     * @param $token
     * @return bool
     */
    private function checkToken($requisitionId, $token)
    {
        $requisitionStatus = $this->requisitionStatus->getBy([
            'requisition_statuses.id_requisition' => $requisitionId,
            'requisition_statuses.status' => RequisitionModel::STATUS_ASK_SELECTION
        ]);
		
		$requisitionStatusResend = $this->requisitionStatus->getBy([
            'requisition_statuses.id_requisition' => $requisitionId,
            'requisition_statuses.status' => 'ASK SELECTION (RESEND)'
        ]);
		
		$requisitionStatus = array_merge($requisitionStatus, $requisitionStatusResend);

        $tokenMatch = false;
        foreach ($requisitionStatus as $status) {
            $data = (array)json_decode($status['data']);
            if (key_exists('token', $data)) {
                if ($data['token'] == $token) {
                    $tokenMatch = true;
                }
            }
        }

        return $tokenMatch;
    }

    /**
     * Show purchase offer index page.
     *
     * @param $requisitionId
     * @param $token
     */
    public function select($requisitionId, $token)
    {
        if ($this->checkToken($requisitionId, $token)) {
            $requisition = $this->requisition->getById($requisitionId);
            $requisitionItems = $this->requisitionItem->getBy(['requisition_items.id_requisition' => $requisitionId]);

            $purchaseOffers = $this->purchaseOffer->getBy(['purchase_offers.id_requisition' => $requisitionId]);
            foreach ($purchaseOffers as &$purchaseOffer) {
                $purchaseOffer['items'] = $this->purchaseOfferItem->getBy(['purchase_offer_items.id_purchase_offer' => $purchaseOffer['id']]);
                $purchaseOffer['vendor_data'] = $this->vendor->getById($purchaseOffer['id_vendor']);
            }

            $this->render('selection/select', compact('requisition', 'requisitionItems', 'purchaseOffers', 'token'));
        } else {
            show_error('Token selection is invalid');
        }
    }

    /**
     * Save selection.
     *
     * @param $requisitionId
     * @param $token
     */
    public function save_selection($requisitionId, $token)
    {
        if ($this->checkToken($requisitionId, $token)) {

            $email = base64_decode($this->input->post('email'));
            $note = $this->input->post('selection_note');

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
                    'token' => $token,
                    'id_user' => UserModel::loginData('id'),
                    'email' => UserModel::loginData('email', $email)
                ])
            ]);

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

                //$this->mailer->send($emailTo, $emailTitle, $emailTemplate, $emailData, $emailOptions);

                flash('success', 'Selection note successfully submitted', 'purchasing/vendor-selection/select/' . $requisitionId . '/' . $token);
            } else {
                flash('danger', 'Submit selection is failed, try again or contact administrator');
            }
            $this->select($requisitionId, $token);
        } else {
            show_error('Token selection is invalid');
        }
    }
}