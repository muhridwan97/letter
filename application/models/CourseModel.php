<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CourseModel extends App_Model
{
	protected $table = 'courses';
	protected $filteredFields = [
		'*',
		'curriculum_title',
		'department',
	];

	const STATUS_ACTIVE = 'ACTIVE';
	const STATUS_INACTIVE = 'INACTIVE';

	/**
	 * CourseModel constructor.
	 */
	public function __construct()
	{
		parent::__construct();

		$this->addFilteredMap('department', function (CI_DB_query_builder &$baseQuery, &$filters) {
			if (key_exists('department', $filters) && !empty($filters['department'])) {
				$baseQuery->where('curriculums.id_department', $filters['department']);
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
				'curriculums.curriculum_title',
				'ref_departments.department',
				'COUNT(lessons.id) AS total_lessons',
			])
			->join('curriculums', 'curriculums.id = courses.id_curriculum', 'left')
			->join('(SELECT * FROM lessons WHERE is_deleted = FALSE) AS lessons', 'lessons.id_course = courses.id', 'left')
			->join(DepartmentModel::$tableDepartment, 'ref_departments.id = curriculums.id_department', 'left')
			->group_by('courses.id');
	}

	/**
	 * Get last order of the group.
	 *
	 * @param null $curriculumId
	 * @return int|mixed
	 */
	public function getNextRowOrder($curriculumId = null)
	{
		$baseQuery = $this->db
			->select_max('course_order', 'max_order')
			->from($this->table)
			->where('is_deleted', false);

		if (!empty($curriculumId)) {
			$baseQuery->where('id_curriculum', $curriculumId);
		} else {
			$baseQuery
				->group_start()
				->where('id_curriculum IS NULL')
				->or_where('id_curriculum', 0)
				->or_where('id_curriculum', '')
				->group_end();
		}

		$nextRowOrder = $baseQuery->get()->row_array();

		if (!empty($nextRowOrder)) {
			return $nextRowOrder['max_order'] + 1;
		}

		return 1;
	}
}
