<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Carbon\Carbon;
/**
 * Class Course
 * @property LecturerModel $lecturer
 * @property CourseEliminationModel $courseElimination
 * @property CourseEliminationCourseModel $courseEliminationCourse
 * @property CourseModel $course
 * @property LessonModel $lesson
 * @property CurriculumModel $curriculum
 * @property NotificationModel $notification
 * @property Exporter $exporter
 * @property Uploader $uploader
 * @property Mailer $mailer
 */
class Course_elimination extends App_Controller
{
	protected $layout = 'layouts/landing';
	/**
	 * Course constructor.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('LecturerModel', 'lecturer');
		$this->load->model('CourseEliminationModel', 'courseElimination');
		$this->load->model('CourseEliminationCourseModel', 'courseEliminationCourse');
		$this->load->model('NotificationModel', 'notification');
		$this->load->model('modules/Exporter', 'exporter');
		$this->load->model('modules/Uploader', 'uploader');
		$this->load->model('modules/Mailer', 'mailer');
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
		$this->render('course_elimination/create', compact('kaprodis', 'pembimbings'));
	}

	/**
	 * Save new course elimination.
	 */
	public function save()
	{
		if ($this->validate()) {
			$dateNow = Carbon::now()->locale('id');
			$tanggalSekarang = $dateNow->isoFormat('D MMMM YYYY');
			$email = $this->input->post('email');
			$nama = $this->input->post('nama');
			$nim = $this->input->post('nim');
			$sks = $this->input->post('sks');
			$sks_pilihan = $this->input->post('sks_pilihan');
			$courses = $this->input->post('courses');
			$kaprodiId = $this->input->post('kaprodi');
			$kaprodi = $this->lecturer->getById($kaprodiId);
			$pembimbingId = $this->input->post('pembimbing');
			$pembimbing = $this->lecturer->getById($pembimbingId);
			$this->db->trans_start();
			
			$letterId = $this->db->insert_id();
			$this->courseElimination->create([
				'id_kaprodi' => $kaprodiId,
				'id_pembimbing' => $pembimbingId,
				'email' => $email,
				'date' => date('Y-m-d'),
				'name' => $nama,
				'nim' => $nim,
				'sks' => $sks,
				'sks_pilihan' => $sks_pilihan,
			]);
			$courseEliminationId = $this->db->insert_id();

			foreach($courses as $course){
				$this->courseEliminationCourse->create([
					'mata_kuliah' => $course['nama'],
					'sks' => $course['sks'],
					'nilai' => $course['nilai'],
				]);
			}
			
			$this->db->trans_complete();

			if ($this->db->trans_status()) {
				$options = [
					'buffer' => true,
					'view' => 'course_elimination/print',
					'data' => compact('tanggalSekarang', 'nama', 'nim', 'sks', 'sks_pilihan', 'courses',
										'kaprodi', 'pembimbing'),
				];
				$output = $this->exporter->exportToPdf("Laporan Surat Hapus Matkul.pdf", null, $options);
				$this->uploader->makeFolder('course_elimination');
				file_put_contents('uploads/course_elimination/Laporan Surat Hapus Matkul'.$email.'.pdf', $output);
				$filepath = "uploads/course_elimination/Laporan Surat Hapus Matkul".$email.".pdf";
				
				//notif email
				$attachments = [];
				$uploadedPath = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'course_elimination' . DIRECTORY_SEPARATOR . 'Laporan Surat Hapus Matkul'.$email.'.pdf';
				$attachments[] = [
					'source' => $uploadedPath,
				];

                $emailOptions = [
                    'attachment' => $attachments,
                ];

                $emailTo = $email;
                $emailTitle = "Surat Hapus Matkul";
                $emailTemplate = 'emails/basic';
                $emailData = [
                    'title' => 'Surat Hapus Matkul',
                    'name' => $nama,
                    'email' => $emailTo,
                    'content' => 'Berikut ini terlampir Surat Hapus Matkul anda',
                ];
                $this->mailer->send($emailTo, $emailTitle, $emailTemplate, $emailData, $emailOptions);				
				
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
				redirect('guest/course-elimination/create');
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
			'nama' => 'required|max_length[100]',
			'nim' => 'required|max_length[100]',
			'sks' => 'required|max_length[100]|numeric',
			'sks_pilihan' => 'required|max_length[100]|numeric',
			'kaprodi' => 'required|max_length[100]',
			'pembimbing' => 'required|max_length[100]',
		];
	}
}
