<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Course
 * @property LecturerModel $lecturer
 * @property NotificationModel $notification
 * @property Exporter $exporter
 * @property Uploader $uploader
 */
class College_permit extends App_Controller
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
		$this->render('college_permit/create', compact('kaprodis', 'pembimbings'));
	}
}
