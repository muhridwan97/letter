<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Skripsi
 * @property SkripsiModel $skripsi
 * @property StudentModel $student
 * @property DepartmentModel $department
 * @property UserModel $user
 * @property Exporter $exporter
 * @property Mailer $mailer
 * @property Uploader $uploader
 */
class Skripsi extends App_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('SkripsiModel', 'skripsi');
        $this->load->model('StudentModel', 'student');

        $this->load->model('DepartmentModel', 'department');
        $this->load->model('UserModel', 'user');
        $this->load->model('modules/Mailer', 'mailer');
        $this->load->model('modules/Exporter', 'exporter');
        $this->load->model('modules/Uploader', 'uploader');
    }

    /**
     * Show Skripsi index page.
     */
    public function index()
    {
        AuthorizationModel::mustAuthorized(PERMISSION_SKRIPSI_VIEW);

        $filters = array_merge($_GET, ['page' => get_url_param('page', 1)]);

        $export = $this->input->get('export');
        if ($export) unset($filters['page']);

        $skripsis = $this->skripsi->getAll($filters);

        if ($export) {
            $this->exporter->exportFromArray('skripsi', $skripsis);
        }

        $this->render('skripsi/index', compact('skripsis'));
    }

    /**
     * Show Skripsi data.
     *
     * @param $id
     */
    public function view($id)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_SKRIPSI_VIEW);

        $skripsi = $this->skripsi->getById($id);

        $this->render('skripsi/view', compact('skripsi'));
    }

    /**
     * Show create Skripsi.
     */
    public function create()
    {
        AuthorizationModel::mustAuthorized(PERMISSION_SKRIPSI_CREATE);

        $students = $this->student->getAll(['status'=> StudentModel::STATUS_ACTIVE]);

        $this->render('skripsi/create', compact('students'));
    }

    /**
     * Save new Skripsi data.
     */
    public function save()
    {
        AuthorizationModel::mustAuthorized(PERMISSION_SKRIPSI_CREATE);

        if ($this->validate()) {
            $skripsiNo = $this->input->post('no_skripsi');
            $name = $this->input->post('name');
            $status = $this->input->post('status');
            $position = $this->input->post('position');
            $user = $this->input->post('user');
            $description = $this->input->post('description');

            $save = $this->skripsi->create([
                'no_skripsi' => $skripsiNo,
                'id_user' => if_empty($user, null),
                'name' => $name,
                'position' => $position,
                'description' => $description,
                'status' => $status,
            ]);

            if ($save) {
                flash('success', "Skripsi {$name} successfully created", 'master/skripsi');
            } else {
                flash('danger', "Create Skripsi failed, try again of contact administrator");
            }
        }
        $this->create();
    }

    /**
     * Show edit Skripsi form.
     * @param $id
     */
    public function edit($id)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_SKRIPSI_EDIT);

        $skripsi = $this->skripsi->getById($id);
        $users = $this->user->getUnattachedUsers($skripsi['id_user']);

        $this->render('skripsi/edit', compact('users', 'skripsi'));
    }

    /**
     * Save new Skripsi data.
     * @param $id
     */
    public function update($id)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_SKRIPSI_EDIT);

        if ($this->validate($this->_validation_rules($id))) {
            $skripsiNo = $this->input->post('no_skripsi');
            $name = $this->input->post('name');
            $status = $this->input->post('status');
            $position = $this->input->post('position');
            $user = $this->input->post('user');
            $description = $this->input->post('description');

            $skripsi = $this->skripsi->getById($id);

            $save = $this->skripsi->update([
                'id_user' => if_empty($user, null),
                'name' => $name,
                'no_skripsi' => $skripsiNo,
                'position' => $position,
                'description' => $description,
                'status' => $status,
            ], $id);

            if ($save) {
                flash('success', "User {$name} successfully updated", 'master/skripsi');
            } else {
                flash('danger', "Update Skripsi failed, try again of contact administrator");
            }
        }
        $this->edit($id);
    }

    /**
     * Perform deleting Skripsi data.
     *
     * @param $id
     */
    public function delete($id)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_SKRIPSI_DELETE);

        $skripsi = $this->skripsi->getById($id);

        if ($this->skripsi->delete($id, true)) {
            flash('warning', "Skripsi {$skripsi['name']} successfully deleted");
        } else {
            flash('danger', "Delete Skripsi failed, try again or contact administrator");
        }
        redirect('master/skripsi');
    }

    /**
     * Return general validation rules.
     *
     * @param array $params
     * @return array
     */
    protected function _validation_rules(...$params)
    {
        $id = isset($params[0]) ? $params[0] : 0;
        return [
            'no_skripsi' => [
                'trim', 'required', 'max_length[100]', ['no_skripsi_exists', function ($no) use ($id) {
                    $this->form_validation->set_message('no_skripsi_exists', 'The %s has been registered before, try another');
                    return empty($this->skripsi->getBy([
                    	'ref_skripsis.no_skripsi' => $no,
						'ref_skripsis.id!=' => $id
					]));
                }]
            ],
            'name' => 'trim|required|max_length[50]',
            'position' => 'trim|required|max_length[50]',
            'status' => 'trim|required|max_length[50]',
        ];
    }

}
