<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ExamModel extends App_Model
{
	protected $table = 'exams';
	protected $filteredFields = [
		'*',
		'ref_employees.name',
		'curriculum_title',
	];

	const STATUS_ACTIVE = 'ACTIVE';
	const STATUS_INACTIVE = 'INACTIVE';

	/**
	 * ExamModel constructor.
	 */
	public function __construct()
	{
		parent::__construct();

		$this->addFilteredMap('employee', function (CI_DB_query_builder &$baseQuery, &$filters) {
			if (key_exists('employee', $filters) && !empty($filters['employee'])) {
				$baseQuery->where($this->table . '.id_employee', $filters['employee']);
			}
		});

		$this->addFilteredMap('evaluator', function (CI_DB_query_builder &$baseQuery, &$filters) {
			if (key_exists('evaluator', $filters) && !empty($filters['evaluator'])) {
				$baseQuery->where($this->table . '.id_evaluator', $filters['evaluator']);
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
				'ref_departments.department',
				'creators.name AS assigned_by',
				'ref_employees.name AS employee_name',
				'ref_employees.id_user',
				'evaluators.name AS evaluator_name',
				'SUM(IFNULL(exam_exercises.score, 0)) / COUNT(exam_exercises.id_exam) AS score',
				'SUM(IF(exam_exercises.status = "ASSESSED", 1, 0)) AS total_assessed',
				'COUNT(exam_exercises.id_exam) AS total_exams',
			])
			->join('(
				SELECT 
					exam_exercises.id_exam,
					exam_exercises.status,
					SUM(IFNULL(exam_exercise_answers.score, 0)) / COUNT(exam_exercise_answers.id) AS score 
				FROM exam_exercises
				LEFT JOIN exam_exercise_answers ON exam_exercise_answers.id_exam_exercise = exam_exercises.id
				GROUP BY exam_exercises.id
			) AS exam_exercises', 'exam_exercises.id_exam = exams.id', 'left')
			->join('curriculums', 'curriculums.id = exams.id_curriculum', 'left')
			->join(DepartmentModel::$tableDepartment, 'ref_departments.id = curriculums.id_department', 'left')
			->join(EmployeeModel::$tableEmployee, 'ref_employees.id = exams.id_employee', 'left')
			->join(EmployeeModel::$tableEmployee . ' AS evaluators', 'evaluators.id = exams.id_evaluator', 'left')
			->join(UserModel::$tableUser . ' AS creators', 'creators.id = exams.created_by', 'left')
			->group_by('exams.id');
	}
}
