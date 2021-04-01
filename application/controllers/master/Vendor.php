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
 * @property Mailer $mailer
 * @property CityModel $city
 * @property VendorSupportCityModel $vendorSupportCity
 */
class Vendor extends App_Controller
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
			'inactive' => 'GET',
			'add_offer' => 'GET',
			'save_offer' => 'POST',
			'delete_offer' => 'POST|DELETE',
			'offers' => 'GET',
			'ajax_get_item_category' => 'GET',
			'send' => 'POST',
		]);
	}

	/**
	 * Show vendor index page.
	 */
	public function index()
	{
		AuthorizationModel::mustAuthorized(PERMISSION_VENDOR_VIEW);

		$filters = array_merge($_GET, ['page' => get_url_param('page', 1)]);

		$export = $this->input->get('export');
		if ($export) unset($filters['page']);

		$vendors = $this->vendor->getAll($filters);
        $cities = $this->city->getAll();
		$cities = array_column($cities, 'city');
        $provinces = $this->city->getProvince();
		$provinces = array_column($provinces, 'province');
		$categories = $this->category->getAll();
		$categories = array_column($categories, 'category');
		$item_categories = $this->itemCategory->getAll();
		$item_categories = array_column($item_categories, 'item_name');

		if ($export) {
			$this->exporter->exportFromArray('Vendors', $vendors);
		}

		$this->render('vendor/index', compact('vendors', 'cities', 'provinces', 'categories', 'item_categories'), 'Data Vendors');
	}

	/**
	 * Show vendor active only index page.
	 */
	public function active()
	{
		AuthorizationModel::mustAuthorized(PERMISSION_VENDOR_VIEW);

		$filters = array_merge($_GET, ['page' => get_url_param('page', 1), 'activity' => 'ACTIVE']);

		$export = $this->input->get('export');
		if ($export) unset($filters['page']);

		$vendors = $this->vendor->getAll($filters);
        $cities = $this->city->getAll();
		$cities = array_column($cities, 'city');
        $provinces = $this->city->getProvince();
        $provinces = array_column($provinces, 'province');
		$categories = $this->category->getAll();
		$categories = array_column($categories, 'category');
		$item_categories = $this->itemCategory->getAll();
		$item_categories = array_column($item_categories, 'item_name');

		if ($export) {
			$this->exporter->exportFromArray('Active Vendors', $vendors);
		}

		$this->render('vendor/index', compact('vendors', 'cities', 'provinces', 'categories', 'item_categories'), 'Active Vendors');
	}

	/**
	 * Show vendor active only index page.
	 */
	public function inactive()
	{
		AuthorizationModel::mustAuthorized(PERMISSION_VENDOR_VIEW);

		$filters = array_merge($_GET, ['page' => get_url_param('page', 1), 'activity' => 'INACTIVE','is_active' => 'no']);

		$export = $this->input->get('export');
		if ($export) unset($filters['page']);

		$vendors = $this->vendor->getAll($filters);
        $cities = $this->city->getAll();
		$cities = array_column($cities, 'city');
        $provinces = $this->city->getProvince();
        $provinces = array_column($provinces, 'province');
		$categories = $this->category->getAll();
		$categories = array_column($categories, 'category');
		$item_categories = $this->itemCategory->getAll();
		$item_categories = array_column($item_categories, 'item_name');

		if ($export) {
			$this->exporter->exportFromArray('Inactive Vendors', $vendors);
		}

		$this->render('vendor/index', compact('vendors', 'cities', 'provinces', 'categories', 'item_categories'), 'Inactive Vendors');
	}

	/**
	 * Show vendor data.
	 *
	 * @param $id
	 */
	public function view($id)
	{
		AuthorizationModel::mustAuthorized(PERMISSION_VENDOR_VIEW);

		$vendor = $this->vendor->getById($id);
		$vendorContacts = $this->vendorContact->getBy(['ref_vendor_contacts.id_vendor' => $id]);
		$vendorBanks = $this->vendorBank->getBy(['ref_vendor_banks.id_vendor' => $id]);
		$vendorCategories = $this->vendorCategory->getBy(['ref_vendor_categories.id_vendor' => $id]);
		$vendorOffers = $this->vendorOffer->getBy(['ref_vendor_offers.id_vendor' => $id]);
		$vendorLocations = $this->vendorLocation->getBy(['ref_vendor_locations.id_vendor' => $id]);
		$vendorItemCategories = $this->vendorItemCategory->getBy(['ref_vendor_item_categories.id_vendor' => $id]);
		
		$this->render('vendor/view', compact('vendor', 'vendorContacts', 'vendorBanks', 'vendorCategories', 'vendorOffers', 'vendorLocations', 'vendorItemCategories'));
	}

	/**
	 * Show master vendor data.
	 */
	public function offers()
	{
		AuthorizationModel::mustAuthorized(PERMISSION_VENDOR_VIEW);

		$filters = array_merge($_GET, ['page' => get_url_param('page', 1)]);

		$export = $this->input->get('export');
		if ($export) unset($filters['page']);

		$vendorOffers = $this->vendorOffer->getAll($filters);

		if ($export) {
			$this->exporter->exportFromArray('Vendor Offers', $vendorOffers);
		}

		$this->render('vendor/offers', compact('vendorOffers'));
	}

	/**
	 * Show create vendor.
	 */
	public function create()
	{
		AuthorizationModel::mustAuthorized(PERMISSION_VENDOR_CREATE);

		$categories = $this->category->getAll();
		$item_categories = $this->itemCategory->getAll();
        $cities = $this->city->getAll();
		$cities = array_column($cities, 'city');

		$this->render('vendor/create', compact('categories', 'item_categories', 'cities'));
	}

	/**
	 * Save new vendor data.
	 */
	public function save()
	{
		AuthorizationModel::mustAuthorized(PERMISSION_VENDOR_CREATE);

		if ($this->validate()) {
			$vendor = $this->input->post('vendor');
			// $city = $this->input->post('city');
			// $address = $this->input->post('address');
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

			$uploadedTax = '';
			if (!empty($_FILES['tax_file']['name'])) {
				$uploadFile = $this->uploader->uploadTo('tax_file', [
					'destination' => 'tax/' . date('Y/m')
				]);
				if ($uploadFile) {
					$uploadedData = $this->uploader->getUploadedData();
					$uploadedTax = $uploadedData['uploaded_path'];
				} else {
					flash('warning', $this->uploader->getDisplayErrors());
				}
			} else {
				$uploadFile = true;
			}

			if ($uploadFile) {
				$this->db->trans_start();

				$this->vendor->create([
					'vendor' => $vendor,
					// 'city' => $city,
					// 'address' => $address,
					'is_pkp' => $isPkp,
					'has_tax_number' => $hasTaxNumber,
					'tax_number' => if_empty($taxNumber, null),
					'tax_file' => if_empty($uploadedTax, null),
					'description' => $description,
					'is_active' => if_empty($isActive, 0),
					'business' => if_empty($business_type, null),
					'is_owned' => if_empty($is_owned, null),
					'facilities' => $facilities,
				]);
				$vendorId = $this->db->insert_id();

				foreach ($locations as $location) {
					$this->vendorLocation->create([
						'id_vendor' => $vendorId,
						'city' => $location['city'],
						'address' => $location['address'],
						'office_phone' => $location['office_phone'],
					]);
				}

				foreach ($supports as $city) {
					$this->vendorSupportCity->create([
						'id_vendor' => $vendorId,
						'city' => $city,
					]);
				}

				foreach ($categories as $categoryId) {
					$this->vendorCategory->create([
						'id_vendor' => $vendorId,
						'id_category' => $categoryId,
					]);
				}

				foreach ($item_categories as $itemCategoryId) {
					$this->vendorItemCategory->create([
						'id_vendor' => $vendorId,
						'id_item_category' => $itemCategoryId,
					]);
				}

				foreach ($contacts as $contact) {
					$this->vendorContact->create([
						'id_vendor' => $vendorId,
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

				foreach ($banks as $bank) {
					$this->vendorBank->create([
						'id_vendor' => $vendorId,
						'bank' => $bank['bank'],
						'account_name' => $bank['account_name'],
						'account_number' => $bank['account_number'],
						'is_primary' => get_if_exist($bank, 'is_primary', 0),
					]);
				}

				$this->db->trans_complete();

				if ($this->db->trans_status()) {
					flash('success', "Vendors {$vendor} successfully created", 'master/vendor');
				} else {
					flash('danger', 'Create vendor failed, try again or contact administrator');
				}
			}

		}
		$this->create();
	}

	/**
	 * Show add vendor offer
	 * @param $id
	 */
	public function add_offer($id = null)
	{
		AuthorizationModel::mustAuthorized(PERMISSION_VENDOR_CREATE);

		$vendors = $this->vendor->getBy([
			'ref_vendors.is_active' => 1
		]);
		$vendor = $this->vendor->getById($id);

		$this->render('vendor/add_offer', compact('vendor', 'vendors'));
	}

	/**
	 * Save vendor offer items.
	 *
	 * @param $id
	 */
	public function save_offer($id = null)
	{
		if ($this->validate(['items[]' => 'required'])) {
			$vendor = $this->input->post('vendor');
			$items = $this->input->post('items');

			if (empty($id)) {
				$id = $vendor;
			}

			$this->db->trans_start();

			foreach ($items as $item) {
				$this->vendorOffer->create([
					'id_vendor' => $id,
					'item_name' => $item['item_name'],
					'brand' => $item['brand'],
					'price' => extract_number($item['price']),
					'discount' => extract_number($item['discount']),
					'price_date' => format_date($item['price_date']),
					'price_until' => if_empty(format_date($item['price_until']), null),
					'description' => $item['description'],
				]);
			}

			$this->db->trans_complete();

			if ($this->db->trans_status()) {
				flash('success', 'Vendor offer items successfully added', 'master/vendor/view/' . $id);
			} else {
				flash('danger', 'Adding item offer');
			}
		}

		$this->add_offer($id);
	}

	/**
	 * Show edit vendor form.
	 *
	 * @param $id
	 */
	public function edit($id)
	{
		AuthorizationModel::mustAuthorized(PERMISSION_VENDOR_EDIT);

		$vendor = $this->vendor->getById($id);
		$categories = $this->category->getAll();
        $cities = $this->city->getAll();
		$cities = array_column($cities, 'city');

		$vendorCategories = $this->vendorCategory->getBy(['ref_vendor_categories.id_vendor' => $id]);
		$listCategory = array_column(if_empty($vendorCategories, []), 'id_category');
		foreach ($categories as &$category) {
			if (in_array($category['id'], $listCategory)) {
				$category['selected'] = true;
			} else {
				$category['selected'] = false;
			}
		}
		$idString = implode(", ", $listCategory); 
		$itemCategories = $this->itemCategory->getBy(['ref_categories.id in ('.$idString.')' => null]);
		$vendorItemCategories = $this->vendorItemCategory->getBy(['ref_vendor_item_categories.id_vendor' => $id]);
		$listCategory = array_column(if_empty($vendorItemCategories, []), 'id_item_category');
		foreach ($itemCategories as &$category) {
			if (in_array($category['id'], $listCategory)) {
				$category['selected'] = true;
			} else {
				$category['selected'] = false;
			}
		}

		$supportCities = $this->city->getAll();
		$support_cities = $this->vendorSupportCity->getBy(['id_vendor' => $id]);
		$listSupportCities = array_column(if_empty($support_cities, []), 'city');
		foreach ($supportCities as &$supportCity) {
			if (in_array($supportCity['city'], $listSupportCities)) {
				$supportCity['selected'] = true;
			} else {
				$supportCity['selected'] = false;
			}
		}

		$vendorContacts = $this->vendorContact->getBy(['ref_vendor_contacts.id_vendor' => $id]);
		$vendorBanks = $this->vendorBank->getBy(['ref_vendor_banks.id_vendor' => $id]);
		$vendorLocations = $this->vendorLocation->getBy(['ref_vendor_locations.id_vendor' => $id]);
		// print_debug($vendorLocations);
		$this->render('vendor/edit', compact('vendor', 'categories', 'vendorContacts', 'vendorBanks', 'vendorLocations', 'itemCategories', 'cities', 'supportCities'));
	}

	/**
	 * Update data vendor by id.
	 *
	 * @param $id
	 */
	public function update($id)
	{
		AuthorizationModel::mustAuthorized(PERMISSION_VENDOR_EDIT);

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
					flash('success', "Vendors {$vendor} successfully updated", 'master/vendor');
				} else {
					flash('danger', "Update vendor failed, try again or contact administrator");
				}
			}
		}
		$this->edit($id);
	}

	/**
	 * Perform deleting vendor data.
	 *
	 * @param $id
	 */
	public function delete($id)
	{
		AuthorizationModel::mustAuthorized(PERMISSION_VENDOR_DELETE);

		$vendor = $this->vendor->getById($id);

		if ($this->vendor->delete($id, true)) {
			flash('warning', "Vendor {$vendor['vendor']} successfully deleted");
		} else {
			flash('danger', 'Delete vendor failed, try again or contact administrator');
		}
		redirect('master/vendor');
	}

	/**
	 * Perform deleting vendor data.
	 *
	 * @param $id
	 */
	public function delete_offer($id)
	{
		AuthorizationModel::mustAuthorized(PERMISSION_VENDOR_DELETE);

		$vendorOffer = $this->vendorOffer->getById($id);

		if ($this->vendorOffer->delete($id)) {
			flash('warning', "Vendor offer {$vendorOffer['item_name']} successfully deleted", '_back');
		} else {
			flash('danger', 'Delete vendor offer failed, try again or contact administrator');
		}
		redirect('master/vendor/view/' . $vendorOffer['id_vendor']);
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
                $this->form_validation->set_rules('locations['.$i.'][office_phone]','office phone', 'trim|required|max_length[50]|numeric');
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
