<?php
/*
	Date: 216-01
	Author: BlueSky
*/
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */

	public function __construct(){

		parent::__construct();

		$this->load->helper('cookie');
		$this->load->helper('url');
		$this->load->model('user');
		$this->load->library('session');
	}

	public function checklogin(){
		//set_cookie('userID', 22, 1561561565);
		echo get_cookie('userID')."<br>";

		echo $this->session->userdata('userID');
		$this->session->unset_userdata("userID");
	}

	public function index(){
		$this->load->view('admin_dashboard');
	}
}
