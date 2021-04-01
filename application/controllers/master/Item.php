<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Item
 * @property ItemModel $item
 * @property Exporter $exporter
 */
class Item extends App_Controller
{
    /**
     * Items constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ItemModel', 'item');
        $this->load->model('modules/Exporter', 'exporter');
    }

    /**
     * Show ITEM index page.
     */
    public function index()
    {
        AuthorizationModel::mustAuthorized(PERMISSION_ITEM_VIEW);

        $filters = array_merge($_GET, ['page' => get_url_param('page', 1)]);

        $export = $this->input->get('export');
        if ($export) unset($filters['page']);

        $items = $this->item->getAll($filters);

        if ($export) {
            $this->exporter->exportFromArray('Items', $items);
        }

        $this->render('item/index', compact('items'));
    }

    /**
     * Show item data.
     *
     * @param $id
     */
    public function view($id)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_ITEM_VIEW);

        $item = $this->item->getById($id);

        $this->render('item/view', compact('item'));
    }

    /**
     * Show create item.
     */
    public function create()
    {
        AuthorizationModel::mustAuthorized(PERMISSION_ITEM_CREATE);

        $this->render('item/create');
    }

    /**
     * Save new item data.
     */
    public function save()
    {
        AuthorizationModel::mustAuthorized(PERMISSION_ITEM_CREATE);

        if ($this->validate()) {
            $item_name = $this->input->post('item_name');
            $brand = $this->input->post('brand');
            $unit = $this->input->post('unit');
			$specification = $this->input->post('specification');
			$isControlledItem = $this->input->post('is_controlled_item');
            $description = $this->input->post('description');

            $save = $this->item->create([
                'no_item' => $this->item->getItemNumber(),
                'item_name' => $item_name,
                'brand' => $brand,
                'unit' => $unit,
				'specification' => $specification,
				'is_controlled_item' => $isControlledItem,
                'description' => $description
            ]);

            if ($save) {
                flash('success', "Items {$item_name} successfully created", 'master/item');
            } else {
                flash('danger', 'Create item category failed, try again or contact administrator');
            }
        }
        $this->create();
    }

    /**
     * Show edit item form.
     *
     * @param $id
     */
    public function edit($id)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_ITEM_EDIT);

        $item = $this->item->getById($id);

        $this->render('item/edit', compact('item'));
    }

    /**
     * Update data category by id.
     *
     * @param $id
     */
    public function update($id)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_ITEM_EDIT);

        if ($this->validate($this->_validation_rules($id))) {
			$item_name = $this->input->post('item_name');
			$brand = $this->input->post('brand');
			$unit = $this->input->post('unit');
			$specification = $this->input->post('specification');
			$isControlledItem = $this->input->post('is_controlled_item');
			$description = $this->input->post('description');

            $update = $this->item->update([
				'item_name' => $item_name,
				'brand' => $brand,
				'unit' => $unit,
				'specification' => $specification,
				'is_controlled_item' => $isControlledItem,
				'description' => $description
            ], $id);

            if ($update) {
                flash('success', "Item {$item_name} successfully updated", 'master/item');
            } else {
                flash('danger', "Update item category failed, try again or contact administrator");
            }
        }
        $this->edit($id);
    }

    /**
     * Perform deleting category data.
     *
     * @param $id
     */
    public function delete($id)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_ITEM_DELETE);

        $item = $this->item->getById($id);

        if ($this->item->delete($id, true)) {
            flash('warning', "Item {$item['item_name']} successfully deleted");
        } else {
            flash('danger', 'Delete item failed, try again or contact administrator');
        }
        redirect('master/item');
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
        	/*'no_item' => [
				'trim', 'required', 'max_length[25]', 'regex_match[/^[a-zA-Z0-9.\-]+$/]', ['item_exists', function ($noItem) use ($id) {
					$this->form_validation->set_message('item_exists', 'The item %s has been registered before, try another');
					return empty($this->item->getBy(['no_item' => $noItem, 'id !=' => $id]));
				}]
			],*/
            'item_name' => 'trim|required|max_length[100]',
			'brand' => 'trim|max_length[50]',
			'unit' => 'trim|max_length[50]',
			'specification' => 'trim|max_length[100]',
            'description' => 'max_length[500]',
        ];
    }
}
