<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Logbook
 * @property LogbookModel $logbook
 * @property LecturerModel $lecturer
 * @property UserModel $user
 * @property Exporter $exporter
 * @property Mailer $mailer
 * @property Uploader $uploader
 */
class Logbook extends App_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('LogbookModel', 'logbook');
        $this->load->model('LecturerModel', 'lecturer');
        $this->load->model('UserModel', 'user');
        $this->load->model('modules/Mailer', 'mailer');
        $this->load->model('modules/Exporter', 'exporter');
        $this->load->model('modules/Uploader', 'uploader');
    }

    /**
     * Show Logbook index page.
     */
    public function index()
    {
        AuthorizationModel::mustAuthorized(PERMISSION_LOGBOOK_VIEW);

        $filters = array_merge($_GET, ['page' => get_url_param('page', 1)]);

        $export = $this->input->get('export');
        if ($export) unset($filters['page']);

        $logbooks = $this->lecturer->getAll($filters);

        if ($export) {
            $this->exporter->exportFromArray('logbook', $logbooks);
        }

        $this->render('logbook/index', compact('logbooks'));
    }

    /**
     * Show Logbook data.
     *
     * @param $id
     */
    public function view($id)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_LOGBOOK_VIEW);

        $logbook = $this->logbook->getById($id);

        $this->render('logbook/view', compact('logbook'));
    }

    /**
     * Show create Logbook.
     */
    public function create()
    {
        AuthorizationModel::mustAuthorized(PERMISSION_LOGBOOK_CREATE);

        $users = $this->user->getUnattachedUsers();

        $this->render('logbook/create', compact('users'));
    }

    /**
     * Save new Logbook data.
     */
    public function save()
    {
        AuthorizationModel::mustAuthorized(PERMISSION_LOGBOOK_CREATE);

        if ($this->validate()) {
            $logbookNo = $this->input->post('no_logbook');
            $name = $this->input->post('name');
            $status = $this->input->post('status');
            $position = $this->input->post('position');
            $user = $this->input->post('user');
            $description = $this->input->post('description');

            $save = $this->logbook->create([
                'no_logbook' => $logbookNo,
                'id_user' => if_empty($user, null),
                'name' => $name,
                'position' => $position,
                'description' => $description,
                'status' => $status,
            ]);

            if ($save) {
                flash('success', "Logbook {$name} successfully created", 'master/logbook');
            } else {
                flash('danger', "Create Logbook failed, try again of contact administrator");
            }
        }
        $this->create();
    }

    /**
     * Show edit Logbook form.
     * @param $id
     */
    public function edit($id)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_LOGBOOK_EDIT);

        $logbook = $this->logbook->getById($id);
        $users = $this->user->getUnattachedUsers($logbook['id_user']);

        $this->render('logbook/edit', compact('users', 'logbook'));
    }

    /**
     * Save new Logbook data.
     * @param $id
     */
    public function update($id)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_LOGBOOK_EDIT);

        if ($this->validate($this->_validation_rules($id))) {
            $logbookNo = $this->input->post('no_logbook');
            $name = $this->input->post('name');
            $status = $this->input->post('status');
            $position = $this->input->post('position');
            $user = $this->input->post('user');
            $description = $this->input->post('description');

            $logbook = $this->logbook->getById($id);

            $save = $this->logbook->update([
                'id_user' => if_empty($user, null),
                'name' => $name,
                'no_logbook' => $logbookNo,
                'position' => $position,
                'description' => $description,
                'status' => $status,
            ], $id);

            if ($save) {
                flash('success', "User {$name} successfully updated", 'master/logbook');
            } else {
                flash('danger', "Update Logbook failed, try again of contact administrator");
            }
        }
        $this->edit($id);
    }

    /**
     * Perform deleting Logbook data.
     *
     * @param $id
     */
    public function delete($id)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_LOGBOOK_DELETE);

        $logbook = $this->logbook->getById($id);

        if ($this->logbook->delete($id, true)) {
            flash('warning', "Logbook {$logbook['name']} successfully deleted");
        } else {
            flash('danger', "Delete Logbook failed, try again or contact administrator");
        }
        redirect('master/logbook');
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
            'no_logbook' => [
                'trim', 'required', 'max_length[100]', ['no_logbook_exists', function ($no) use ($id) {
                    $this->form_validation->set_message('no_logbook_exists', 'The %s has been registered before, try another');
                    return empty($this->logbook->getBy([
                    	'ref_logbooks.no_logbook' => $no,
						'ref_logbooks.id!=' => $id
					]));
                }]
            ],
            'name' => 'trim|required|max_length[50]',
            'position' => 'trim|required|max_length[50]',
            'status' => 'trim|required|max_length[50]',
        ];
    }

}
