<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Department
 * @property DepartmentModel $department
 * @property DepartmentCategoryModel $departmentCategory
 * @property CategoryModel $category
 * @property Exporter $exporter
 */
class Department extends App_Controller
{
    /**
     * Department constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('DepartmentModel', 'department');
        $this->load->model('DepartmentCategoryModel', 'departmentCategory');
        $this->load->model('CategoryModel', 'category');
        $this->load->model('modules/Exporter', 'exporter');
        $this->setFilterMethods([
            'edit_category' => 'GET',
            'update_category' => 'PUT|PATCH'
        ]);
    }

    /**
     * Show department index page.
     */
    public function index()
    {
        AuthorizationModel::mustAuthorized(PERMISSION_DEPARTMENT_VIEW);

        $filters = array_merge($_GET, ['page' => get_url_param('page', 1)]);

        $export = $this->input->get('export');
        if ($export) unset($filters['page']);

        $departments = $this->department->getAll($filters);

        if ($export) {
            $this->exporter->exportFromArray('Departments', $departments);
        }

        $this->render('department/index', compact('departments'));
    }

    /**
     * Show department data.
     *
     * @param $id
     */
    public function view($id)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_DEPARTMENT_VIEW);

        $department = $this->department->getById($id);
        $departmentCategories = $this->departmentCategory->getBy(['ref_department_categories.id_department' => $id]);

        $this->render('department/view', compact('department', 'departmentCategories'));
    }

    /**
     * Show create department.
     */
    public function create()
    {
        AuthorizationModel::mustAuthorized(PERMISSION_DEPARTMENT_CREATE);

        $this->render('department/create');
    }

    /**
     * Save new department data.
     */
    public function save()
    {
        AuthorizationModel::mustAuthorized(PERMISSION_DEPARTMENT_CREATE);

        if ($this->validate($this->_validation_rules())) {
            $department = $this->input->post('department');
            $description = $this->input->post('description');

            $save = $this->department->create([
                'department' => $department,
                'description' => $description
            ]);

            if ($save) {
                flash('success', "Department {$department} successfully created", 'master/department');
            } else {
                flash('danger', 'Create department failed, try again or contact administrator');
            }
        }
        $this->create();
    }

    /**
     * Show edit department form.
     *
     * @param $id
     */
    public function edit($id)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_DEPARTMENT_EDIT);

        $department = $this->department->getById($id);

        $this->render('department/edit', compact('department'));
    }

    /**
     * Show edit department category form.
     *
     * @param $id
     */
    public function edit_category($id)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_DEPARTMENT_EDIT);

        $department = $this->department->getById($id);
        $categories = $this->category->getAll();
        $departmentCategories = $this->departmentCategory->getBy([
            'ref_department_categories.id_department' => $id
        ]);

        $listCategory = array_column(if_empty($departmentCategories, []), 'id_category');
        foreach ($categories as &$category) {
            if (in_array($category['id'], $listCategory)) {
                $category['selected'] = true;
            } else {
                $category['selected'] = false;
            }
        }

        $this->render('department/edit-category', compact('department', 'categories'));
    }

    /**
     * Update data department by id.
     *
     * @param $id
     */
    public function update($id)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_DEPARTMENT_EDIT);

        if ($this->validate()) {
            $department = $this->input->post('department');
            $description = $this->input->post('description');

            $update = $this->department->update([
                'department' => $department,
                'description' => $description
            ], $id);

            if ($update) {
                flash('success', "Department {$department} successfully updated", 'master/department');
            } else {
                flash('danger', "Update department failed, try again or contact administrator");
            }
        }
        $this->edit($id);
    }

    /**
     * Update data department category by id.
     *
     * @param $id
     */
    public function update_category($id)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_DEPARTMENT_EDIT);

        if ($this->validate(['categories[]' => 'trim'])) {
            $categories = $this->input->post('categories');

            $department = $this->department->getById($id);

            $this->db->trans_start();

            if (!empty($categories)) {
                $this->departmentCategory->delete(['id_department' => $id]);
                foreach ($categories as $categoryId) {
                    $this->departmentCategory->create([
                        'id_department' => $id,
                        'id_category' => $categoryId
                    ]);
                }
            }

            $this->db->trans_complete();

            if ($this->db->trans_status()) {
                flash('success', "List category department {$department['department']} successfully updated", 'master/department');
            } else {
                flash('danger', "Update list category department failed, try again or contact administrator");
            }
        }
        $this->edit_category($id);
    }

    /**
     * Perform deleting department data.
     *
     * @param $id
     */
    public function delete($id)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_DEPARTMENT_DELETE);

        $department = $this->department->getById($id);

        if ($this->department->delete($id, true)) {
            flash('warning', "Department {$department['department']} successfully deleted");
        } else {
            flash('danger', 'Delete department failed, try again or contact administrator');
        }
        redirect('master/department');
    }

    /**
     * Return general validation rules.
     *
     * @return array
     */
    protected function _validation_rules()
    {
        return [
            'department' => 'trim|required|max_length[50]',
            'description' => 'max_length[500]',
        ];
    }
}