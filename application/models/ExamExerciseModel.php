<?php

use Carbon\Carbon;

defined('BASEPATH') or exit('No direct script access allowed');

class ExamExerciseModel extends App_Model
{
	protected $table = 'exam_exercises';

	const STATUS_PENDING = 'PENDING';
	const STATUS_STARTED = 'STARTED';
	const STATUS_FINISHED = 'FINISHED';
	const STATUS_ASSESSED = 'ASSESSED';

	/**
	 * ExamExerciseModel constructor.
	 */
	public function __construct()
	{
		parent::__construct();

		$this->addFilteredMap('employee', function (CI_DB_query_builder &$baseQuery, &$filters) {
			if (key_exists('employee', $filters) && !empty($filters['employee'])) {
				$baseQuery->where('exams.id_employee', $filters['employee']);
			}
		});

		$this->addFilteredMap('limit', function (CI_DB_query_builder &$baseQuery, &$filters) {
			if (key_exists('limit', $filters) && !empty($filters['limit'])) {
				$baseQuery->limit($filters['limit']);
			}
		});

		$this->addFilteredMap('exam', function (CI_DB_query_builder &$baseQuery, &$filters) {
			if (key_exists('exam', $filters) && !empty($filters['exam'])) {
				$baseQuery->where($this->table . '.id_exam', $filters['exam']);
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
				'ref_employees.name AS employee_name',
				'exams.id_employee',
				'exams.id_evaluator',
				'evaluators.name AS evaluator_name',
				'exercises.exercise_title',
				'exercises.category',
				'exercises.duration',
				'total_questions.total_questions',
				'SUM(IFNULL(exam_exercise_answers.score, 0)) / COUNT(exam_exercise_answers.id) AS score',
			])
			->join('exams', 'exams.id = exam_exercises.id_exam', 'left')
			->join(EmployeeModel::$tableEmployee, 'ref_employees.id = exams.id_employee', 'left')
			->join(EmployeeModel::$tableEmployee . ' AS evaluators', 'evaluators.id = exams.id_evaluator', 'left')
			->join('curriculums', 'curriculums.id = exams.id_curriculum', 'left')
			->join('exercises', 'exercises.id = exam_exercises.id_exercise')
			->join('(
				SELECT id_exercise, COUNT(id) AS total_questions
				FROM questions
				GROUP BY id_exercise
			) AS total_questions', 'total_questions.id_exercise = exercises.id', 'left')
			->join('exam_exercise_answers', 'exam_exercise_answers.id_exam_exercise = exam_exercises.id', 'left')
			->group_by('exam_exercises.id');
	}

	/**
	 * Get quiz time limit (started_at + duration).
	 *
	 * @param $examExercise
	 * @return string
	 */
	public function getQuizTimeLimit($examExercise)
	{
		$startedAt = Carbon::createFromFormat('Y-m-d H:i:s', $examExercise['started_at']);
		$duration = Carbon::createFromFormat('H:i:s', $examExercise['duration']);
		$limitTime = $startedAt->copy()
			->addHours($duration->hour)
			->addMinutes($duration->minute)
			->addSeconds($duration->second);

		return $limitTime->toDateTimeString();
	}

	/**
	 * Get quiz time left or timeout.
	 *
	 * @param $examExercise
	 * @return false|string
	 */
	public function getQuizTimeLeft($examExercise)
	{
		$startedAt = Carbon::createFromFormat('Y-m-d H:i:s', $examExercise['started_at']);
		$duration = Carbon::createFromFormat('H:i:s', $examExercise['duration']);

		$limitTime = $startedAt->copy()
			->addHours($duration->hour)
			->addMinutes($duration->minute)
			->addSeconds($duration->second);

		$timeLeft = Carbon::now()->diff($limitTime);
		return $timeLeft->format('%r%H:%I:%S');

		/*
		if ($limitTime->isPast()) {
			return false;
		} else {
			$timeLeft = Carbon::now()->diff($limitTime);
			return $timeLeft->format('%r%H:%I:%S');
		}
		*/
	}
}
