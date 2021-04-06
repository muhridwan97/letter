<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CurriculumModel extends App_Model
{
	protected $table = 'curriculums';
	protected $filteredFields = [
		'*',
		'department',
	];

	const STATUS_ACTIVE = 'ACTIVE';
	const STATUS_INACTIVE = 'INACTIVE';

	/**
	 * CurriculumModel constructor.
	 */
	public function __construct()
	{
		parent::__construct();

		$this->addFilteredMap('department', function (CI_DB_query_builder &$baseQuery, &$filters) {
			if (key_exists('department', $filters) && !empty($filters['department'])) {
				$baseQuery->where($this->table . '.id_department', $filters['department']);
			}
		});

		$this->addFilteredMap('status', function (CI_DB_query_builder &$baseQuery, &$filters) {
			if (key_exists('status', $filters) && !empty($filters['status'])) {
				$baseQuery->where($this->table . '.status', $filters['status']);
			}
		});

		$this->addFilteredMap('except', function (CI_DB_query_builder &$baseQuery, &$filters) {
			if (key_exists('except', $filters) && !empty($filters['except'])) {
				$baseQuery->or_where($this->table . '.id', $filters['except']);
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
				'ref_departments.department',
				'COUNT(DISTINCT courses.id) AS total_courses',
				'COUNT(DISTINCT exercises.id) AS total_exams',
			])
			->join(DepartmentModel::$tableDepartment, 'ref_departments.id = curriculums.id_department', 'left')
			->join('(SELECT * FROM courses WHERE is_deleted = FALSE) AS courses', 'courses.id_curriculum = curriculums.id', 'left')
			->join('(SELECT * FROM exercises WHERE is_deleted = FALSE) AS exercises', 'exercises.id_reference = curriculums.id AND exercises.type = "curriculum-exam"', 'left')
			->group_by('curriculums.id');
	}

	/**
	 * Get last order of the curriculum.
	 *
	 * @param null $departmentId
	 * @return int|mixed
	 */
	public function getNextRowOrder($departmentId = null)
	{
		$baseQuery = $this->db
			->select_max('curriculum_order', 'max_order')
			->from($this->table)
			->where('is_deleted', false);

		if (!empty($departmentId)) {
			$baseQuery->where('id_department', $departmentId);
		} else {
			$baseQuery
				->group_start()
				->where('id_department IS NULL')
				->or_where('id_department', 0)
				->or_where('id_department', '')
				->group_end();
		}

		$nextRowOrder = $baseQuery->get()->row_array();

		if (!empty($nextRowOrder)) {
			return $nextRowOrder['max_order'] + 1;
		}

		return 1;
	}
}
