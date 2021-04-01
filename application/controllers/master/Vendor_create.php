<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Vendors
 * @property VendorModel $vendor
 * @property VendorBankModel $vendorBank
 * @property VendorOfferModel $vendorOffer
 * @property VendorContactModel $vendorContact
 * @property VendorCategoryModel $vendorCategory
 * @property CategoryModel $category
 * @property Exporter $exporter
 * @property Uploader $uploader
 * @property VendorLocationModel $vendorLocation
 * @property ItemCategoryModel $itemCategory
 * @property VendorItemCategoryModel $vendorItemCategory
 * @property UserModel $user
 * @property modules/Mailer $mailer
 * @property CityModel $city
 * @property VendorSupportCityModel $vendorSupportCity
 */
class Vendor_create extends App_Controller
{
	/**
	 * Vendors constructor.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('VendorModel', 'vendor');
		$this->load->model('VendorBankModel', 'vendorBank');
		$this->load->model('VendorOfferModel', 'vendorOffer');
		$this->load->model('VendorContactModel', 'vendorContact');
		$this->load->model('VendorCategoryModel', 'vendorCategory');
		$this->load->model('CategoryModel', 'category');
		$this->load->model('modules/Exporter', 'exporter');
		$this->load->model('modules/Uploader', 'uploader');
		$this->load->model('VendorLocationModel', 'vendorLocation');
        $this->load->model('ItemCategoryModel', 'itemCategory');
		$this->load->model('VendorItemCategoryModel', 'vendorItemCategory');
		$this->load->model('UserModel', 'user');
        $this->load->model('modules/Mailer', 'mailer');
        $this->load->model('CityModel', 'city');
		$this->load->model('VendorSupportCityModel', 'vendorSupportCity');

		$this->setFilterMethods([
			'active' => 'GET',
			'add_offer' => 'GET',
			'save_offer' => 'POST',
			'delete_offer' => 'POST|DELETE',
			'offers' => 'GET',
			'ajax_get_item_category' => 'GET',
			'send' => 'POST',
			'create_vendor' => 'GET',
			'success' => 'GET|POST',
		]);
	}

	/**
	 * Update data vendor by id.
	 *
	 * @param $id
	 */
	public function update($id)
	{
		if ($this->validate()) {
			$vendor = $this->input->post('vendor');
			$isPkp = $this->input->post('is_pkp');
			$hasTaxNumber = $this->input->post('has_tax_number');
			$taxNumber = $this->input->post('tax_number');
			$description = $this->input->post('description');
			$isActive = $this->input->post('is_active');
			$categories = $this->input->post('categories');
			$contacts = $this->input->post('contacts');
			$banks = $this->input->post('banks');
			$locations = $this->input->post('locations');
			$item_categories = $this->input->post('item_categories');
			$business_type = $this->input->post('business_type');
			$is_owned = $this->input->post('is_owned');
			$facilities = $this->input->post('facilities');
			$supports = $this->input->post('supports');

			$oldVendor = $this->vendor->getById($id);

			$uploadedTax = $oldVendor['tax_file'];
			if (!empty($_FILES['tax_file']['name'])) {
				$uploadFile = $this->uploader->uploadTo('tax_file', [
					'destination' => 'tax/' . date('Y/m')
				]);
				if ($uploadFile) {
					$uploadedData = $this->uploader->getUploadedData();
					$uploadedTax = $uploadedData['uploaded_path'];
					if (empty($oldVendor['tax_file'])) {
						$this->uploader->delete($oldVendor['tax_file']);
					}
				} else {
					flash('warning', $this->uploader->getDisplayErrors());
				}
			} else {
				$uploadFile = true;
			}

			if ($uploadFile) {
				$this->db->trans_start();

				$this->vendor->update([
					'vendor' => $vendor,
					'is_pkp' => $isPkp,
					'has_tax_number' => $hasTaxNumber,
					'tax_number' => if_empty($taxNumber, null),
					'tax_file' => if_empty($uploadedTax, null),
					'description' => $description,
					'is_active' => if_empty($isActive, 0),
					'business' => if_empty($business_type, null),
					'is_owned' => if_empty($is_owned, null),
					'facilities' => $facilities,
					'access_token' => null,
					'expired_token' => date('Y-m-d H:i:s'),
					'is_deleted' => 0,
				], $id);

				$this->vendorLocation->delete(['ref_vendor_locations.id_vendor' => $id]);
				foreach ($locations as $location) {
					$this->vendorLocation->create([
						'id_vendor' => $id,
						'city' => $location['city'],
						'address' => $location['address'],
						'office_phone' => $location['office_phone'],
					]);
				}

				$this->vendorSupportCity->delete(['ref_vendor_support_cities.id_vendor' => $id]);
				foreach ($supports as $city) {
					$this->vendorSupportCity->create([
						'id_vendor' => $id,
						'city' => $city,
					]);
				}

				$this->vendorCategory->delete(['ref_vendor_categories.id_vendor' => $id]);
				foreach ($categories as $categoryId) {
					$this->vendorCategory->create([
						'id_vendor' => $id,
						'id_category' => $categoryId,
					]);
				}

				$this->vendorItemCategory->delete(['ref_vendor_item_categories.id_vendor' => $id]);
				foreach ($item_categories as $itemCategoryId) {
					$this->vendorItemCategory->create([
						'id_vendor' => $id,
						'id_item_category' => $itemCategoryId,
					]);
				}

				$this->vendorContact->delete(['ref_vendor_contacts.id_vendor' => $id]);
				foreach ($contacts as $contact) {
					$this->vendorContact->create([
						'id_vendor' => $id,
						'type' => $contact['type'],
						'title' => $contact['title'],
						'name' => $contact['name'],
						'position' => $contact['position'],
						'code' => 62,
						'contact' => $contact['contact'],
						'email' => $contact['email'],
						'is_primary' => get_if_exist($contact, 'is_primary', 0),
					]);
				}

				$this->vendorBank->delete(['ref_vendor_banks.id_vendor' => $id]);
				foreach ($banks as $bank) {
					$this->vendorBank->create([
						'id_vendor' => $id,
						'bank' => $bank['bank'],
						'account_name' => $bank['account_name'],
						'account_number' => $bank['account_number'],
						'is_primary' => get_if_exist($bank, 'is_primary', 0),
					]);
				}

				$this->db->trans_complete();

				if ($this->db->trans_status()) {
					$this->create_vendor($oldVendor['access_token'],true);
				} else {
					flash('danger', "Update vendor failed, try again or contact administrator");
				}
			}
		}
	}

	
	/**
	 * Return general validation rules.
	 *
	 * @return array
	 */
	protected function _validation_rules()
	{
		$baseRule = [
			'vendor' => 'trim|required|max_length[50]',
			// 'city' => 'trim|required|max_length[50]',
			// 'address' => 'trim|required|max_length[200]',
			'tax_number' => 'trim|max_length[50]',
			'is_pkp' => 'trim|in_list[0,1,]',
			'has_tax_number' => 'trim|in_list[0,1,]',
			'categories[]' => 'required',
			'contacts[]' => 'required',
			'description' => 'max_length[500]',
			'is_active' => 'trim|in_list[1,0,]',
		];
		if (key_exists('locations', $_POST)) {
			$locations = $this->input->post('locations');
            $i=0;
            foreach ($locations as $location) {
                $this->form_validation->set_rules('locations['.$i.'][city]','city', 'trim|required|max_length[50]');
                $this->form_validation->set_rules('locations['.$i.'][office_phone]','office phone', 'trim|required|max_length[50]');
                $this->form_validation->set_rules('locations['.$i.'][address]','address', 'trim|required|max_length[200]');
                $i++;
            }
        }
		return $baseRule;
	}

	/**
     * Get item category data.
     */
    public function ajax_get_item_category()
    {
        if ($this->input->server('REQUEST_METHOD') == "GET") {
			$id_categories = $this->input->get('id_category');

            $itemCategories = $this->itemCategory->getBy(['id_category in ('.$id_categories .')' => null]);
            header('Content-Type: application/json');
            echo json_encode($itemCategories);
        }
	}
	
	/**
	 * Show create vendor.
	 */
	public function create_vendor($token,$success=false)
	{
		$vendor = $this->vendor->getBy([
			'access_token'=>$token,
			'expired_token>'=>date('Y-m-d H:i:s'),
		],true,true);
		if(!empty($vendor)){
			$categories = $this->category->getAll();
			$item_categories = $this->itemCategory->getAll();
			$cities = $this->city->getAll();
			$cities = array_column($cities, 'city');
			
			$this->render('vendor/create_vendor', compact('categories', 'item_categories', 'vendor', 'cities'));
		}else {
			if ($success) {
				flash('success', "Data Input Successfully", 'master/vendor_create/success');
			}else{
				show_error('Token selection is invalid');
			}
        }
	}

	public function success(){
		$this->render('vendor/success');
	}
	/**
     * Ask requisition to manager for selection.
     *
     * @param $requisitionId
     */
    public function send()
    {
		AuthorizationModel::mustAuthorized(PERMISSION_VENDOR_CREATE);

		$email = $this->input->post('email');
		
        $this->db->trans_start();

        $this->load->helper('string');
        $token = uniqid() . random_string('alnum', 32);

        $this->vendor->create([
			'access_token' => $token,
			'is_deleted' => 1,
			'expired_token' => date('Y-m-d')." 23:59:59",
		]);
		$id = $this->db->insert_id();

        $this->db->trans_complete();
        if ($this->db->trans_status()) {

            $emailTo = $email;
            $emailTitle = "Company Identity";
            $emailTemplate = 'emails/vendors/create';
            $emailData = [
                'email' => $email,
                'token' => $token,
            ];
            $purchasing = $this->user->getByPermission([PERMISSION_VENDOR_CREATE]);

            $emailOptions = [
                'cc' => array_column(if_empty($purchasing, []), 'email'),
            ];

            $sendEmail = $this->mailer->send($emailTo, $emailTitle, $emailTemplate, $emailData, $emailOptions);
            if ($sendEmail) {
                flash('success', "Email sent successfully", 'master/vendor');
            } else {
				$this->vendor->delete($id);
                flash('success', "Email not sent", 'master/vendor');
            }
        }
    }
}
