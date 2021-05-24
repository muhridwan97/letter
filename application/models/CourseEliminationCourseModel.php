<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CourseEliminationCourseModel extends App_Model
{
    protected $table = 'course_elimination_courses';

    /**
     * Get base query of table.
     *
     * @return CI_DB_query_builder
     */
    public function getBaseQuery()
    {
        return parent::getBaseQuery();
    }

}
