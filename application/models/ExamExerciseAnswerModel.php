<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ExamExerciseAnswerModel extends App_Model
{
	protected $table = 'exam_exercise_answers';

	/**
	 * ExamExerciseAnswerModel constructor.
	 */
	public function __construct()
	{
		parent::__construct();

		$this->addFilteredMap('exam_exercise', function (CI_DB_query_builder &$baseQuery, &$filters) {
			if (key_exists('exam_exercise', $filters) && !empty($filters['exam_exercise'])) {
				$baseQuery->where($this->table . '.id_exam_exercise', $filters['exam_exercise']);
			}
		});
	}

	/**
	 * @return CI_DB_query_builder
	 */
	protected function getBaseQuery()
	{
		return parent::getBaseQuery()
			->select([
				'questions.answer AS question_answer', // questions join is not reliable
			])
			->join('questions', 'questions.id = exam_exercise_answers.id_question', 'left');
	}
}
