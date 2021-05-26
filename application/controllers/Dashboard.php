<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property LecturerModel $lecturer
 * @property StudentModel $student
 * @property LessonModel $lesson
 * @property LetterNumberModel $letterNumber
 * @property ExamExerciseModel $examExercise
 * Class Dashboard
 */
class Dashboard extends App_Controller
{
	/**
	 * Dashboard constructor.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('LecturerModel', 'lecturer');
		$this->load->model('StudentModel', 'student');
		$this->load->model('LessonModel', 'lesson');
		$this->load->model('LetterNumberModel', 'letterNumber');
		$this->load->model('ExamExerciseModel', 'examExercise');
		$this->load->model('TrainingModel', 'training');
	}

    /**
     * Show dashboard page.
     */
    public function index()
    {
		$data = [
			'totalLecturer' => $this->lecturer->getBy([], 'COUNT'),
			'totalStudent' => $this->student->getBy([], 'COUNT'),
			'totalLesson' => $this->lesson->getBy([], 'COUNT'),
			'totalLetterNumber' => $this->letterNumber->getBy([], 'COUNT'),
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

        $this->render('dashboard/index', $data);
    }
}
