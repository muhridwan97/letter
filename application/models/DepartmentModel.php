<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DepartmentModel extends App_Model
{
    protected $table = 'ref_departments';

    public static $tableDepartment = 'ref_departments';

    public function __construct()
    {
        if ($this->config->item('sso_enable')) {
            $this->table = env('DB_HR_DATABASE') . '.ref_departments';
            self::$tableDepartment = env('DB_HR_DATABASE') . '.ref_departments';
        }
    }

    /**
     * Get base query of table.
     *
     * @return CI_DB_query_builder
     */
    protected function getBaseQuery()
    {
        $this->load->model('EmployeeModel');
        return parent::getBaseQuery()
            ->select([
                'COUNT(DISTINCT ref_employees.id) AS total_employee',
                'COUNT(DISTINCT ref_categories.id) AS total_category',
                'COUNT(DISTINCT requisitions.id) AS total_requisition',
            ])
            ->join(EmployeeModel::$tableEmployee, 'ref_employees.id_department = ref_departments.id', 'left')
            ->join('ref_department_categories', 'ref_department_categories.id_department = ref_departments.id', 'left')
            ->join('ref_categories', 'ref_categories.id = ref_department_categories.id_category', 'left')
            ->join('requisitions', 'requisitions.id_employee = ref_employees.id', 'left')
            ->group_by('ref_departments.id');
    }
}
