<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Carbon\Carbon;
/**
 * Class Course
 * @property LecturerModel $lecturer
 * @property AssignmentLetterModel $assignmentLetter
 * @property AssignmentLetterStudentModel $assignmentLetterStudent
 * @property LetterNumberModel $letterNumber
 * @property CourseModel $course
 * @property LessonModel $lesson
 * @property CurriculumModel $curriculum
 * @property NotificationModel $notification
 * @property Exporter $exporter
 * @property Uploader $uploader
 */
class Assignment_letter extends App_Controller
{
	protected $layout = 'layouts/landing';
	/**
	 * Course constructor.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('LecturerModel', 'lecturer');
		$this->load->model('AssignmentLetterModel', 'assignmentLetter');
		$this->load->model('AssignmentLetterStudentModel', 'assignmentLetterStudent');
		$this->load->model('LetterNumberModel', 'letterNumber');
		$this->load->model('NotificationModel', 'notification');
		$this->load->model('modules/Exporter', 'exporter');
		$this->load->model('modules/Uploader', 'uploader');
		$this->load->model('notifications/CreateCourseNotification');

		$this->setFilterMethods([
			'sort' => 'GET|PUT'
		]);
	}

	/**
	 * Show create Research Permit.
	 *
	 */
	public function create()
	{
		$kaprodis = $this->lecturer->getBy(['position' => 'KAPRODI']);
		$pembimbings = $this->lecturer->getAll();
		$this->render('assignment_letter/create', compact('kaprodis', 'pembimbings'));
	}

	/**
	 * Save new assignment letter.
	 */
	public function save()
	{
		if ($this->validate()) {
			$dateNow = Carbon::now()->locale('id');
			$tanggalSekarang = $dateNow->isoFormat('D MMMM YYYY');
			$email = $this->input->post('email');
			$judul = $this->input->post('judul');
			$students = $this->input->post('students');
			$kaprodiId = $this->input->post('kaprodi');
			$kaprodi = $this->lecturer->getById($kaprodiId);
			$this->db->trans_start();

			$no_letter = $this->letterNumber->getLetterNumber();
			$this->letterNumber->create([
				'no_letter' => $no_letter,
			]);
			
			$letterId = $this->db->insert_id();
			$this->assignmentLetter->create([
				'id_kaprodi' => $kaprodiId,
				'id_letter_number' => $letterId,
				'email' => $email,
				'date' => date('Y-m-d'),
				'judul' => $judul,
			]);
			$assignmentLetterId = $this->db->insert_id();

			foreach($students as $student){
				$this->assignmentLetterStudent->create([
					'name' => $student['nama'],
					'jabatan' => $student['jabatan'],
				]);
			}
			
			$this->db->trans_complete();

			if ($this->db->trans_status()) {
				$options = [
					'buffer' => true,
					'view' => 'assignment_letter/print',
					'data' => compact('tanggalSekarang', 'judul', 'students',
										'kaprodi', 'no_letter'),
				];
				$output = $this->exporter->exportToPdf("Laporan Surat Tugas.pdf", null, $options);
				$this->uploader->makeFolder('assignment_letter');
				file_put_contents('uploads/assignment_letter/Laporan Surat Tugas'.$email.'.pdf', $output);
				$filepath = "uploads/assignment_letter/Laporan Surat Tugas".$email.".pdf";

				// Process download
				if(file_exists($filepath)) {
					header('Content-Description: File Transfer');
					header('Content-Type: application/octet-stream');
					header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
					header('Expires: 0');
					header('Cache-Control: must-revalidate');
					header('Pragma: public');
					header('Content-Length: ' . filesize($filepath));
					flush(); // Flush system output buffer
					readfile($filepath);
					die();
				} else {
					flash('warning', "Generate successfully but download fail, please check your email");
				}
				redirect('guest/research-permit/create');
			}
		}
		$this->create();
	}

	/**
	 * Return general validation rules.
	 *
	 * @return array
	 */
	protected function _validation_rules()
	{
		return [
			'email' => 'trim|required|max_length[100]|valid_email',
			'judul' => 'required|max_length[100]',
			'kaprodi' => 'required|max_length[100]',
		];
	}
}
