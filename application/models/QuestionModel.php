<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class QuestionModel extends App_Model
{
    protected $table = 'questions';

	/**
	 * QuestionModel constructor.
	 */
	public function __construct()
	{
		parent::__construct();

		$this->addFilteredMap('exercise', function (CI_DB_query_builder &$baseQuery, &$filters) {
			if (key_exists('exercise', $filters) && !empty($filters['exercise'])) {
				$baseQuery->where($this->table . '.id_exercise', $filters['exercise']);
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
				'(
					SELECT id FROM answer_choices
					WHERE is_correct_answer = 1 
						AND answer_choices.id_question = questions.id
				) AS id_correct_answer_choice',
			]);
	}
}
