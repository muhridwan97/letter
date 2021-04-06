<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AnswerChoicesModel extends App_Model
{
    protected $table = 'answer_choices';

	/**
	 * AnswerChoicesModel constructor.
	 */
	public function __construct()
	{
		parent::__construct();

		$this->addFilteredMap('question', function (CI_DB_query_builder &$baseQuery, &$filters) {
			if (key_exists('question', $filters) && !empty($filters['question'])) {
				$baseQuery->where($this->table . '.id_question', $filters['question']);
			}
		});

		$this->addFilteredMap('sort_random', function (CI_DB_query_builder &$baseQuery, &$filters) {
			if (key_exists('sort_random', $filters) && $filters['sort_random']) {
				$baseQuery->order_by('RAND()');
			}
		});
	}
}
