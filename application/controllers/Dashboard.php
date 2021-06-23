<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property LecturerModel $lecturer
 * @property StudentModel $student
 * @property SkripsiModel $skripsi
 * @property LetterNumberModel $letterNumber
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
		$this->load->model('SkripsiModel', 'skripsi');
		$this->load->model('LetterNumberModel', 'letterNumber');
	}

    /**
     * Show dashboard page.
     */
    public function index()
    {
		$data = [
			'totalLecturer' => $this->lecturer->getBy([], 'COUNT'),
			'totalStudent' => $this->student->getBy([], 'COUNT'),
			'totalSkripsi' => $this->skripsi->getBy([], 'COUNT'),
			'totalLetterNumber' => $this->letterNumber->getBy([], 'COUNT'),
		];

        $this->render('dashboard/index', $data);
    }
}
