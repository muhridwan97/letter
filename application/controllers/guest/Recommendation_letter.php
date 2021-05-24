<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Carbon\Carbon;
/**
 * Class Course
 * @property LecturerModel $lecturer
 * @property LetterNumberModel $letterNumber
 * @property RecommendationLetterModel $recommendationLetter
 * @property NotificationModel $notification
 * @property Exporter $exporter
 * @property Uploader $uploader
 */
class Recommendation_letter extends App_Controller
{
	protected $layout = 'layouts/landing';
	/**
	 * Course constructor.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('LecturerModel', 'lecturer');
		$this->load->model('LetterNumberModel', 'letterNumber');
		$this->load->model('RecommendationLetterModel', 'recommendationLetter');
		$this->load->model('NotificationModel', 'notification');
		$this->load->model('modules/Exporter', 'exporter');
		$this->load->model('modules/Uploader', 'uploader');
		$this->load->model('notifications/CreateCourseNotification');

		$this->setFilterMethods([
			'sort' => 'GET|PUT'
		]);
	}

	/**
	 * Show create Recommendation Letter.
	 *
	 */
	public function create()
	{
		$kaprodis = $this->lecturer->getBy(['position' => 'KAPRODI']);
		$pembimbings = $this->lecturer->getAll();
		$this->render('recommendation_letter/create', compact('kaprodis', 'pembimbings'));
	}

	/**
	 * Save new Recommendation Letter.
	 */
	public function save()
	{
		if ($this->validate()) {
			$dateNow = Carbon::now()->locale('id');
			$tanggalSekarang = $dateNow->isoFormat('D MMMM YYYY');
			$email = $this->input->post('email');
			$namaDosen = $this->input->post('nama_dosen');
			$jabatan = $this->input->post('jabatan');
			$prodi = $this->input->post('prodi');
			$namaMahasiswa = $this->input->post('nama_mahasiswa');
			$nim = $this->input->post('nim');
			$rekomendasi = $this->input->post('rekomendasi');
			$kaprodiId = $this->input->post('kaprodi');
			$kaprodi = $this->lecturer->getById($kaprodiId);
			$this->db->trans_start();

			$no_letter = $this->letterNumber->getLetterNumber();
			$this->letterNumber->create([
				'no_letter' => $no_letter,
			]);
			
			$letterId = $this->db->insert_id();
			$this->recommendationLetter->create([
				'id_kaprodi' => $kaprodiId,
				'id_letter_number' => $letterId,
				'email' => $email,
				'nama_dosen' => $namaDosen,
				'jabatan' => $jabatan,
				'prodi' => $prodi,
				'nama_mahasiswa' => $namaMahasiswa,
				'nim' => $nim,
				'rekomendasi' => $rekomendasi,
				'date' => date('Y-m-d'),
			]);
			
			$this->db->trans_complete();

			if ($this->db->trans_status()) {
				$options = [
					'buffer' => true,
					'view' => 'recommendation_letter/print',
					'data' => compact('tanggalSekarang', 'namaDosen', 'jabatan', 'namaMahasiswa', 'nim',
										'rekomendasi', 'kaprodi', 'no_letter', 'prodi'),
				];
				$output = $this->exporter->exportToPdf("Surat Izin Penelitian.pdf", null, $options);
				$this->uploader->makeFolder('recommendation_letter');
				file_put_contents('uploads/recommendation_letter/Surat Izin Penelitian'.$nim.'.pdf', $output);
				$filepath = "uploads/recommendation_letter/Surat Izin Penelitian".$nim.".pdf";

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
			'nama_dosen' => 'required|max_length[100]',
			'jabatan' => 'required|max_length[100]',
			'prodi' => 'required|max_length[100]',
			'nama_mahasiswa' => 'required|max_length[100]',
			'nim' => 'required|max_length[100]',
			'kaprodi' => 'required|max_length[100]',
			'rekomendasi' => 'required|max_length[100]',
		];
	}
}
