<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Items
 * @property ItemCategoryModel $item
 * @property CategoryModel $category
 * @property Exporter $exporter
 */
class Item_category extends App_Controller
{
    /**
     * Items constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('CategoryModel', 'category');
        $this->load->model('ItemCategoryModel', 'item');
        $this->load->model('modules/Exporter', 'exporter');
    }

    /**
     * Show ITEM index page.
     */
    public function index()
    {
        AuthorizationModel::mustAuthorized(PERMISSION_ITEM_CATEGORY_VIEW);

        $filters = array_merge($_GET, ['page' => get_url_param('page', 1)]);

        $export = $this->input->get('export');
        if ($export) unset($filters['page']);

        $items = $this->item->getAll($filters);

        if ($export) {
            $this->exporter->exportFromArray('Items', $items);
        }

        $this->render('item_category/index', compact('items'));
    }

    /**
     * Show item data.
     *
     * @param $id
     */
    public function view($id)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_ITEM_CATEGORY_VIEW);

        $item = $this->item->getById($id);

        $this->render('item_category/view', compact('item'));
    }

    /**
     * Show create item.
     */
    public function create()
    {
        AuthorizationModel::mustAuthorized(PERMISSION_ITEM_CATEGORY_CREATE);

        $categories = $this->category->getAll();
        $this->render('item_category/create', compact('categories'));
    }

    /**
     * Save new category data.
     */
    public function save()
    {
        AuthorizationModel::mustAuthorized(PERMISSION_ITEM_CATEGORY_CREATE);

        if ($this->validate($this->_validation_rules())) {
            $category = $this->input->post('category');
            $item_name = $this->input->post('item_name');
            $is_reserved = $this->input->post('is_reserved');
            $description = $this->input->post('description');
            $code = $this->item->getItemCode();

            $save = $this->item->create([
                'id_category' => $category,
                'item_code' => $code,
                'item_name' => $item_name,
                'is_reserved' => $is_reserved,
                'description' => $description
            ]);

            if ($save) {
                flash('success', "Items {$item_name} successfully created", 'master/item-category');
            } else {
                flash('danger', 'Create item category failed, try again or contact administrator');
            }
        }
        $this->create();
    }

    /**
     * Show edit category form.
     *
     * @param $id
     */
    public function edit($id)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_ITEM_CATEGORY_EDIT);

        $item = $this->item->getById($id);
        $categories = $this->category->getAll();
        $this->render('item_category/edit', compact('item', 'categories'));
    }

    /**
     * Update data category by id.
     *
     * @param $id
     */
    public function update($id)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_ITEM_CATEGORY_EDIT);

        if ($this->validate()) {
            $category = $this->input->post('category');
            $item_name = $this->input->post('item_name');
            $is_reserved = $this->input->post('is_reserved');
            $description = $this->input->post('description');

            $update = $this->item->update([
                'id_category' => $category,
                'item_name' => $item_name,
                'is_reserved' => $is_reserved,
                'description' => $description
            ], $id);

            if ($update) {
                flash('success', "Item {$item_name} successfully updated", 'master/item-category');
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
        AuthorizationModel::mustAuthorized(PERMISSION_ITEM_CATEGORY_DELETE);

        $item = $this->item->getById($id);

        if ($this->item->delete($id, true)) {
            flash('warning', "Item category {$item['item_name']} successfully deleted");
        } else {
            flash('danger', 'Delete item failed, try again or contact administrator');
        }
        redirect('master/item-category');
    }

    /**
     * Return general validation rules.
     *
     * @return array
     */
    protected function _validation_rules()
    {
        return [
            'category' => 'trim|required',
            'item_name' => 'trim|required|max_length[100]',
            'description' => 'max_length[500]',
        ];
    }
}
