<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SignatureModel extends App_Model
{
    protected $table = 'signatures';
    
    const TYPE_RESEARCH_PERMIT = 'research-permit';
    const TYPE_ASSIGNMENT_LETTER = 'assignment-letter';
    const TYPE_INTERVIEW_PERMIT = 'interview-permit';
    const TYPE_APPLICATION_LETTER = 'application-letter';
    const TYPE_COURSE_ELIMINATION = 'course-elimination';
    const TYPE_COLLEGE_PERMIT = 'college-permit';
    const TYPE_RECOMMENDATION_LETTER = 'recommendation-letter';
    const TYPE_APPOINTMENT_LECTURER = 'appointment-lecturer';


    /**
     * Get base query of table.
     *
     * @return CI_DB_query_builder
     */
    public function getBaseQuery()
    {
        return parent::getBaseQuery();
    }

    /**
     * Generate unique code number
     * @param int $maxTrial
     * @return string
     */
    public function generateCode($maxTrial = 10)
    {
        $this->load->helper('string');

        $code = strtoupper(random_string('alnum', 50));

        if (!empty($this->getBy(['signatures.code' => $code]))) {
            if ($maxTrial > 0) {
                return $this->generateCode($maxTrial - 1);
            }
        }

        return $code;
    }

    /**
     * Get single model data by id with or without deleted record.
     *
     * @param $code
     * @param bool $withTrashed
     * @return mixed
     */
    public function getByCode($code)
    {
        $baseQuery = $this->getBaseQuery();

        $baseQuery->where($this->table . '.code', $code);

        return $baseQuery->get()->row_array();
    }
}