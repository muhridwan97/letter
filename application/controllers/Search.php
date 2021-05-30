<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Search
 * @property SkripsiModel $skripsi
 * @property LogbookModel $logbook
 * @property LessonModel $lesson
 */
class Search extends App_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('SkripsiModel', 'skripsi');
        $this->load->model('LogbookModel', 'logbook');
        $this->load->model('LessonModel', 'lesson');
    }

    /**
     * Show result search page.
     */
    public function index()
    {
        $q = get_url_param('q');
        $skripsis = empty($q) ? [] : $this->skripsi->search($q, 6);
        $logbooks = empty($q) ? [] : $this->logbook->search($q, 4);
        $lessons = empty($q) ? [] : $this->lesson->search($q, 10);

        $this->render('search/index', compact('skripsis', 'logbooks', 'lessons'));
    }
}
