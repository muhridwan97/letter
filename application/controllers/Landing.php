<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
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
	}

    /**
     * Show dashboard page.
     */
    public function index()
    {
        $this->render('landing/index');
    }
}
