<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ExerciseModel extends App_Model
{
    protected $table = 'exercises';

    const TYPE_LESSON_EXERCISE = 'lesson-exercise';
    const TYPE_CURRICULUM_EXAM = 'curriculum-exam';

    const CATEGORY_CHOICES = 'CHOICES';
    const CATEGORY_ESSAY = 'ESSAY';
    const CATEGORY_PRACTICE = 'PRACTICE';

    const QUESTION_SEQUENCE_RANDOM = 'RANDOM';
    const QUESTION_SEQUENCE_IN_ORDER = 'IN ORDER';

	/**
	 * ExerciseModel constructor.
	 */
	public function __construct()
	{
		parent::__construct();

		$this->addFilteredMap('type', function (CI_DB_query_builder &$baseQuery, &$filters) {
			if (key_exists('type', $filters)) {
				if ($filters['type'] == 'collection') {
					$baseQuery->where($this->table . '.type IS NULL');
				} elseif (!empty($filters['type'])) {
					$baseQuery->where($this->table . '.type', $filters['type']);
				}
			}
		});

		$this->addFilteredMap('category', function (CI_DB_query_builder &$baseQuery, &$filters) {
			if (key_exists('category', $filters) && !empty($filters['category'])) {
				$baseQuery->where($this->table . '.category', $filters['category']);
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
				'COUNT(questions.id) AS total_questions',
			])
			->join('(SELECT * FROM questions WHERE is_deleted = FALSE) AS questions', 'questions.id_exercise = exercises.id', 'left')
			->group_by('exercises.id');
	}

	/**
	 * Get related data exam from curriculum exercise.
	 *
	 * @param $examId
	 * @param $curriculumId
	 * @return array|array[]
	 */
	public function getExamData($examId, $curriculumId)
	{
		$baseQuery = $this->db
			->select([
				'exercises.id AS id_exercise',
				'exercises.exercise_title',
				'curriculums.curriculum_title',
				'exercises.category',
				'total_questions.total_questions',
				'exam_exercises.started_at',
				'exam_exercises.finished_at',
				'IFNULL(exam_exercises.status, "PENDING") AS status',
				'exam_exercises.description',
				'SUM(exam_exercise_answers.score) / COUNT(exam_exercise_answers.id) AS score',
			])
			->from('curriculums')
			->join('exercises', 'exercises.id_reference = curriculums.id AND exercises.type = "curriculum-exam"')
			->join('(
				SELECT id_exercise, COUNT(id) AS total_questions
				FROM questions
				GROUP BY id_exercise
			) AS total_questions', 'total_questions.id_exercise = exercises.id', 'left')
			->join('exam_exercises', "exam_exercises.id_exercise = exercises.id AND exam_exercises.id_exam = '{$examId}'", 'left')
			->join('exam_exercise_answers', 'exam_exercise_answers.id_exam_exercise = exam_exercises.id', 'left')
			->group_by('exercises.id, exam_exercises.id')
			->where('curriculums.id', $curriculumId);

		return $baseQuery->get()->result_array();
	}
}
