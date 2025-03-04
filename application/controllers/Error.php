<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Error extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//Load Dependencies
	}

	// List all your items
	public function index( $offset = 0 )
	{
			$this->load->view('errors/404.php');
	}

}

/* End of file Error.php */
/* Location: ./application/controllers/Error.php */
