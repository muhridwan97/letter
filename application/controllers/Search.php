<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Search
 * @property CurriculumModel $curriculum
 * @property CourseModel $course
 * @property LessonModel $lesson
 */
class Search extends App_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('CurriculumModel', 'curriculum');
        $this->load->model('CourseModel', 'course');
        $this->load->model('LessonModel', 'lesson');
    }

    /**
     * Show result search page.
     */
    public function index()
    {
        $q = get_url_param('q');
        $curriculums = empty($q) ? [] : $this->curriculum->search($q, 6);
        $courses = empty($q) ? [] : $this->course->search($q, 4);
        $lessons = empty($q) ? [] : $this->lesson->search($q, 10);

        $this->render('search/index', compact('curriculums', 'courses', 'lessons'));
    }
}
