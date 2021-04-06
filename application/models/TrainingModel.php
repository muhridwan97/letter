<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TrainingModel extends App_Model
{
	protected $table = 'trainings';
	protected $filteredFields = [
		'*',
		'ref_employees.name',
		'curriculum_title',
	];

	const STATUS_ACTIVE = 'ACTIVE';
	const STATUS_INACTIVE = 'INACTIVE';

	/**
	 * TrainingModel constructor.
	 */
	public function __construct()
	{
		parent::__construct();

		$this->addFilteredMap('employee', function (CI_DB_query_builder &$baseQuery, &$filters) {
			if (key_exists('employee', $filters) && !empty($filters['employee'])) {
				$baseQuery->where($this->table . '.id_employee', $filters['employee']);
			}
		});

		$this->addFilteredMap('curriculum', function (CI_DB_query_builder &$baseQuery, &$filters) {
			if (key_exists('curriculum', $filters) && !empty($filters['curriculum'])) {
				$baseQuery->where($this->table . '.id_curriculum', $filters['curriculum']);
			}
		});

		$this->addFilteredMap('status', function (CI_DB_query_builder &$baseQuery, &$filters) {
			if (key_exists('status', $filters) && !empty($filters['status'])) {
				$baseQuery->where($this->table . '.status', $filters['status']);
			}
		});

		$this->addFilteredMap('sort_by', function (CI_DB_query_builder &$baseQuery, &$filters) {
			if (!key_exists('sort_by', $filters)) {
				$baseQuery->order_by("FIELD(trainings.status, 'ACTIVE', 'INACTIVE')");
				$baseQuery->order_by("trainings.id", 'desc');
			}
		});
	}

	/**
	 * Get base query of table.
	 *
	 * @return CI_DB_query_builder
	 */
	protected function getBaseQuery()
	{
		return parent::getBaseQuery()
			->select([
				'curriculums.curriculum_title',
				'curriculums.cover_image',
				'creators.name AS assigned_by',
				'ref_departments.department',
				'ref_employees.name AS employee_name',
				'ref_employees.id_user',
				'COUNT(DISTINCT courses.id) AS total_courses',
			])
			->join('curriculums', 'curriculums.id = trainings.id_curriculum', 'left')
			->join('(SELECT * FROM courses WHERE is_deleted = FALSE) AS courses', 'courses.id_curriculum = curriculums.id', 'left')
			->join(DepartmentModel::$tableDepartment, 'ref_departments.id = curriculums.id_department', 'left')
			->join(EmployeeModel::$tableEmployee, 'ref_employees.id = trainings.id_employee', 'left')
			->join(UserModel::$tableUser . ' AS creators', 'creators.id = trainings.created_by', 'left')
			->group_by('trainings.id');
	}
}
