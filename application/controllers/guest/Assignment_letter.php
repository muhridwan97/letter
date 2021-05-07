<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Carbon\Carbon;
/**
 * Class Course
 * @property LecturerModel $lecturer
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
			$terhormat = $this->input->post('terhormat');
			$email = $this->input->post('email');
			$judul = $this->input->post('judul');
			$nama = $this->input->post('nama');
			$nim = $this->input->post('nim');
			$pengambilan_data = $this->input->post('pengambilan_data');
			$metode = $this->input->post('metode');
			$kaprodiId = $this->input->post('kaprodi');
			$pembimbingId = $this->input->post('pembimbing');
			$kaprodi = $this->lecturer->getById($kaprodiId);
			$pembimbing = $this->lecturer->getById($pembimbingId);
			$this->db->trans_start();

			$no_letter = $this->letterNumber->getLetterNumber();
			$this->letterNumber->create([
				'no_letter' => $no_letter,
			]);
			
			$letterId = $this->db->insert_id();
			$this->researchPermit->create([
				'id_kaprodi' => $kaprodiId,
				'id_pembimbing' => $pembimbingId,
				'id_letter_number' => $letterId,
				'nim' => $nim,
				'name' => $nama,
				'email' => $email,
				'terhormat' => $terhormat,
				'date' => date('Y-m-d'),
				'judul' => $judul,
				'pengambilan_data' => $pengambilan_data,
				'metode' => $metode,
			]);
			
			$this->db->trans_complete();

			if ($this->db->trans_status()) {
				$options = [
					'buffer' => true,
					'view' => 'research_permit/print',
					'data' => compact('tanggalSekarang', 'terhormat', 'judul', 'nama', 'nim',
										'pengambilan_data', 'metode', 'kaprodi', 'pembimbing', 'no_letter'),
				];
				$output = $this->exporter->exportToPdf("Surat Izin Penelitian.pdf", null, $options);
				$this->uploader->makeFolder('research_permit');
				file_put_contents('uploads/research_permit/Surat Izin Penelitian'.$nim.'.pdf', $output);
				$filepath = "uploads/research_permit/Surat Izin Penelitian".$nim.".pdf";

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
}
