<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Vendor_completion
 * @property UserModel $user
 * @property VendorModel $vendor
 * @property RequisitionModel $requisition
 * @property RequisitionItemModel $requisitionItem
 * @property PurchaseOfferModel $purchaseOffer
 * @property PurchaseOfferItemModel $purchaseOfferItem
 * @property PurchaseOrderModel $purchaseOrder
 * @property Mailer $mailer
 * @property Uploader $uploader
 */
class Vendor_completion extends App_Controller
{
    protected $layout = 'layouts/static';

    /**
     * Vendor_completion constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->model('UserModel', 'user');
        $this->load->model('VendorModel', 'vendor');
        $this->load->model('RequisitionModel', 'requisition');
        $this->load->model('RequisitionItemModel', 'requisitionItem');
        $this->load->model('PurchaseOfferModel', 'purchaseOffer');
        $this->load->model('PurchaseOfferItemModel', 'purchaseOfferItem');
        $this->load->model('PurchaseOrderModel', 'purchaseOrder');
        $this->load->model('modules/Uploader', 'uploader');
        $this->load->model('modules/Mailer', 'mailer');

        $this->setFilterMethods([
            'order' => 'GET',
            'save_order' => 'POST|PUT',
        ]);
    }

	/**
	 * Check credential access.
	 *
	 * @param $purchaseOrder
	 * @param $token
	 * @return bool
	 */
    private function checkCredential($purchaseOrder, $token)
	{
		$tokenDecoded = base64_decode($token);
		$tokenParts = explode('|', $tokenDecoded);

		$isTokenValid = !empty($tokenParts) && count($tokenParts) == 2;
		$isPurchaseOrderDraft = !empty($purchaseOrder) && $purchaseOrder['document_status'] == PurchaseOrderModel::STATUS_DRAFT;

		if($isTokenValid && $isPurchaseOrderDraft) {
			$email = $tokenParts[0];
			$expiredAt = $tokenParts[1];
			$vendor = $this->vendor->getById($purchaseOrder['id_vendor']);

			$isTokenActive = date('Y-m-d') < $expiredAt;
			$isTokenMatchedWithOwner = strtolower($vendor['email']) == strtolower($email);

			if ($isTokenActive && $isTokenMatchedWithOwner) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Show form order completion.
	 *
	 * @param $id
	 */
    public function order($id)
	{
		$purchaseOrder = $this->purchaseOrder->getById($id);

		if ($this->checkCredential($purchaseOrder, $this->input->get('token'))) {
			$purchaseOffer = $this->purchaseOffer->getById($purchaseOrder['id_purchase_offer']);
			$purchaseOfferItems = $this->purchaseOfferItem->getBy([
				'purchase_offer_items.id_purchase_offer' => $purchaseOffer['id'],
				'purchase_offer_items.quantity_selected!=' => null,
			]);
			$requisition = $this->requisition->getById($purchaseOffer['id_requisition']);

			$this->render('order/vendor_completion', compact('purchaseOrder', 'purchaseOffer', 'purchaseOfferItems', 'requisition'));
		} else {
			show_error('Credential or data is invalid');
		}
	}

	/**
	 * Save order completion.
	 *
	 * @param $id
	 */
    public function save_order($id)
    {
		$purchaseOrder = $this->purchaseOrder->getById($id);

        if ($this->checkCredential($purchaseOrder, $this->input->get('token'))) {
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
					'status' => PurchaseOfferModel::STATUS_UPLOADED,
				], $purchaseOrder['id_purchase_offer']);

				$this->purchaseOrder->update([
					'document' => if_empty($uploadedDocument, null),
				], $id);

				$this->db->trans_complete();

				if ($this->db->trans_status()) {
					$emailTo = get_setting('email_support');
					$emailTitle = "Vendor completion for draft {$purchaseOrder['no_purchase']} is submitted";
					$emailTemplate = 'emails/basic';
					$emailData = [
						'name' => 'Purchasing Admin',
						'email' => get_setting('email_support'),
						'content' => "
							Vendor {$purchaseOrder['vendor']} is already submit completion document for purchase order {$purchaseOrder['no_purchase']} 
							from request {$purchaseOrder['request_title']} ({$purchaseOrder['no_requisition']}), 
							Purchasing admin needs proceed item data to make purchase order based on vendor's documents. 
						"
					];
					$purchasing = $this->user->getByPermission([PERMISSION_REQUISITION_MANAGE]);
					$emailOptions = [
						'cc' => array_column(if_empty($purchasing, []), 'email')
					];

					$this->mailer->send($emailTo, $emailTitle, $emailTemplate, $emailData, $emailOptions);

					flash('success', "Document completion {$purchaseOrder['no_purchase']} successfully updated");
				} else {
					flash('danger', "Update document failed, try again or contact administrator");
				}
				redirect('purchasing/vendor-completion/order/' . $id . '?token=' . $this->input->get('token'));
			}
			$this->order($id);
        } else {
            show_error('Credential or data is invalid');
        }
    }
}
