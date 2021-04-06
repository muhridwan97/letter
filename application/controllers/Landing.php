<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property CurriculumModel $curriculum
 * @property CourseModel $course
 * @property LessonModel $lesson
 * @property TrainingModel $training
 * @property ExamExerciseModel $examExercise
 * Class Dashboard
 */
class Landing extends App_Controller
{
	protected $layout = 'layouts/landing';
	/**
	 * Dashboard constructor.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('CurriculumModel', 'curriculum');
		$this->load->model('CourseModel', 'course');
		$this->load->model('LessonModel', 'lesson');
		$this->load->model('TrainingModel', 'training');
		$this->load->model('ExamExerciseModel', 'examExercise');
	}

    /**
     * Show dashboard page.
     */
    public function index()
    {
		$data = [
			'totalCurriculum' => $this->curriculum->getBy([], 'COUNT'),
			'totalCourse' => $this->course->getBy([], 'COUNT'),
			'totalLesson' => $this->lesson->getBy([], 'COUNT'),
			'totalTraining' => $this->training->getBy([], 'COUNT'),
		];

		$data['latestExams'] = $this->examExercise->getAll([
			'employee' => AuthorizationModel::hasPermission(PERMISSION_EXAM_MANAGE)
				? 0 : UserModel::loginData('id_employee', -1),
			'limit' => 10,
		]);
		$data['activeTrainings'] = $this->training->getAll([
			'employee' => UserModel::loginData('id_employee', -1),
			'status' => TrainingModel::STATUS_ACTIVE,
		]);

        $this->render('landing/index', $data);
    }
}
