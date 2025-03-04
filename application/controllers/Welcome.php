<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Cargar la librería Escpos
        // $this->load->library('Escpos');
    }
	public function index()
	{
		$this->load->view('welcome_message');
	}
}
