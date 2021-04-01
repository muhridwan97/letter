<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Cities
 * @property CityModel $city
 * @property Exporter $exporter
 */
class City extends App_Controller
{
    /**
     * Cities constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('CityModel', 'city');
        $this->load->model('modules/Exporter', 'exporter');
    }

    /**
     * Show city index page.
     */
    public function index()
    {
        AuthorizationModel::mustAuthorized(PERMISSION_CITY_VIEW);

        $filters = array_merge($_GET, ['page' => get_url_param('page', 1)]);

        $export = $this->input->get('export');
        if ($export) unset($filters['page']);

        $cities = $this->city->getAll($filters);

        if ($export) {
            $this->exporter->exportFromArray('cities', $cities);
        }

        $this->render('city/index', compact('cities'));
    }

    /**
     * Show city data.
     *
     * @param $id
     */
    public function view($id)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_CITY_VIEW);

        $city = $this->city->getById($id);

        $this->render('city/view', compact('city'));
    }

    /**
     * Show create city.
     */
    public function create()
    {
        AuthorizationModel::mustAuthorized(PERMISSION_CITY_CREATE);
        $provinces = $this->city->getProvince();
        $provinces = array_column($provinces, 'province');
        // print_debug($provinces);
        $this->render('city/create',compact('provinces'));
    }

    /**
     * Save new city data.
     */
    public function save()
    {
        AuthorizationModel::mustAuthorized(PERMISSION_CITY_CREATE);

        if ($this->validate($this->_validation_rules())) {
            $city = $this->input->post('city');
            $province = $this->input->post('province');
            $description = $this->input->post('description');
            
            $save = $this->city->create([
                'city' => $city,
                'province' => $province,
                'description' => $description
            ]);

            if ($save) {
                flash('success', "Cities {$city} successfully created", 'master/city');
            } else {
                flash('danger', 'Create city failed, try again or contact administrator');
            }
        }
        $this->create();
    }

    /**
     * Show edit city form.
     *
     * @param $id
     */
    public function edit($id)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_CITY_EDIT);

        $city = $this->city->getById($id);
        $provinces = $this->city->getProvince();
        $provinces = array_column($provinces, 'province');
        // print_debug($provinces);
        $this->render('city/edit', compact('city', 'provinces'));
    }

    /**
     * Update data city by id.
     *
     * @param $id
     */
    public function update($id)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_CITY_EDIT);

        if ($this->validate()) {
            $city = $this->input->post('city');
            $province = $this->input->post('province');
            $description = $this->input->post('description');

            $update = $this->city->update([
                'city' => $city,
                'province' => $province,
                'description' => $description
            ], $id);

            if ($update) {
                flash('success', "Cities {$city} successfully updated", 'master/city');
            } else {
                flash('danger', "Update city failed, try again or contact administrator");
            }
        }
        $this->edit($id);
    }

    /**
     * Perform deleting city data.
     *
     * @param $id
     */
    public function delete($id)
    {
        AuthorizationModel::mustAuthorized(PERMISSION_CITY_DELETE);

        $city = $this->city->getById($id);

        if ($this->city->delete($id, true)) {
            flash('warning', "Cities {$city['city']} successfully deleted");
        } else {
            flash('danger', 'Delete city failed, try again or contact administrator');
        }
        redirect('master/city');
    }

    /**
     * Return general validation rules.
     *
     * @return array
     */
    protected function _validation_rules()
    {
        return [
            'city' => 'trim|required|max_length[50]',
            'description' => 'max_length[500]',
        ];
    }
}