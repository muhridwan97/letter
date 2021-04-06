<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LessonModel extends App_Model
{
	protected $table = 'lessons';
	protected $filteredFields = [
		'*',
		'curriculum_title',
		'course_title',
		'department',
	];

	/**
	 * LessonModel constructor.
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
				$baseQuery->where('courses.id_curriculum', $filters['curriculum']);
			}
		});

		$this->addFilteredMap('course', function (CI_DB_query_builder &$baseQuery, &$filters) {
			if (key_exists('course', $filters) && !empty($filters['course'])) {
				$baseQuery->where($this->table . '.id_course', $filters['course']);
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
				'courses.course_title',
				'curriculums.id AS id_curriculum',
				'curriculums.curriculum_title',
				'ref_departments.id AS id_department',
				'ref_departments.department',
				'COUNT(DISTINCT exercises.id) AS total_exercises',
			])
			->join('courses', 'courses.id = lessons.id_course', 'left')
			->join('curriculums', 'curriculums.id = courses.id_curriculum', 'left')
			->join('(SELECT * FROM exercises WHERE is_deleted = FALSE) AS exercises', 'exercises.id_reference = lessons.id AND exercises.type = "lesson-exercise"', 'left')
			->join(DepartmentModel::$tableDepartment, 'ref_departments.id = curriculums.id_department', 'left')
			->group_by('lessons.id');
	}

	/**
	 * Get last order of the group.
	 *
	 * @param null $courseId
	 * @return int|mixed
	 */
	public function getNextRowOrder($courseId = null)
	{
		$baseQuery = $this->db
			->select_max('lesson_order', 'max_order')
			->from($this->table)
			->where('is_deleted', false);

		if (!empty($courseId)) {
			$baseQuery->where('id_course', $courseId);
		} else {
			$baseQuery
				->group_start()
				->where('id_course IS NULL')
				->or_where('id_course', 0)
				->or_where('id_course', '')
				->group_end();
		}

		$nextRowOrder = $baseQuery->get()->row_array();

		if (!empty($nextRowOrder)) {
			return $nextRowOrder['max_order'] + 1;
		}

		return 1;
	}
}
