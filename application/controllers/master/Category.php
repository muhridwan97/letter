<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Categories
 * @property CategoryModel $category
 * @property VendorModel $vendor
 * @property Exporter $exporter
 */
class Category extends App_Controller
{
    /**
     * Categories constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('CategoryModel', 'category');
        $this->load->model('VendorModel', 'vendor');
        $this->load->model('modules/Exporter', 'exporter');
    }

    /**
     * Show category index page.
     */
    public function index()
    {
        AuthorizationModel::mustAuthorized(PERMISSION_CATEGORY_VIEW);

        $filters = array_merge($_GET, ['page' => get_url_param('page', 1)]);

        $export = $this->input->get('export');
        if ($export) unset($filters['page']);

        $categories = $this->category->getAll($filters);

        if ($export) {
            $this->exporter->exportFromArray('Categories', $categories);
        }

        $this->render('category/index', compact('categories'));
    }

    /**
     * Show category data.
     *
     * @param $id
     */
    public function view($id)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_CATEGORY_VIEW);

        $category = $this->category->getById($id);
        $vendors = $this->vendor->getBy(['ref_categories.id' => $category['id']]);

        $this->render('category/view', compact('category', 'vendors'));
    }

    /**
     * Show create category.
     */
    public function create()
    {
        AuthorizationModel::mustAuthorized(PERMISSION_CATEGORY_CREATE);

        $this->render('category/create');
    }

    /**
     * Save new category data.
     */
    public function save()
    {
        AuthorizationModel::mustAuthorized(PERMISSION_CATEGORY_CREATE);

        if ($this->validate($this->_validation_rules())) {
            $category = $this->input->post('category');
            $description = $this->input->post('description');

            $save = $this->category->create([
                'category' => $category,
                'description' => $description
            ]);

            if ($save) {
                flash('success', "Categories {$category} successfully created", 'master/category');
            } else {
                flash('danger', 'Create category failed, try again or contact administrator');
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
        AuthorizationModel::mustAuthorized(PERMISSION_CATEGORY_EDIT);

        $category = $this->category->getById($id);

        $this->render('category/edit', compact('category'));
    }

    /**
     * Update data category by id.
     *
     * @param $id
     */
    public function update($id)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_CATEGORY_EDIT);

        if ($this->validate()) {
            $category = $this->input->post('category');
            $description = $this->input->post('description');

            $update = $this->category->update([
                'category' => $category,
                'description' => $description
            ], $id);

            if ($update) {
                flash('success', "Categories {$category} successfully updated", 'master/category');
            } else {
                flash('danger', "Update category failed, try again or contact administrator");
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
        AuthorizationModel::mustAuthorized(PERMISSION_CATEGORY_DELETE);

        $category = $this->category->getById($id);

        if ($this->category->delete($id, true)) {
            flash('warning', "Categories {$category['category']} successfully deleted");
        } else {
            flash('danger', 'Delete category failed, try again or contact administrator');
        }
        redirect('master/category');
    }

    /**
     * Return general validation rules.
     *
     * @return array
     */
    protected function _validation_rules()
    {
        return [
            'category' => 'trim|required|max_length[50]',
            'description' => 'max_length[500]',
        ];
    }
}