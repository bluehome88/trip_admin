<?php
/*
	Date: 216-01
	Author: BlueSky
*/
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

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
		
		//$this->output->enable_profiler(TRUE);
		$this->load->helper('cookie');
		$this->load->helper('url');
		$this->load->model('user');
		$this->load->model('news');
		$this->load->library('session');
		date_default_timezone_set('America/Chicago');
	}

	private function getValue($index){

		if(!isset($_POST[$index]))
			$_POST = json_decode(file_get_contents('php://input'), true);

		if(isset($_GET[$index]))
			return $_GET[$index];
		else if(isset($_POST[$index]))
			return $_POST[$index];
		else
			return '';
	}
	
	// ------- start News block -------//
	public function checkLogin(){

		$arrReturn = array();
		if( $this->session->has_userdata('userInfo') && !$this->getValue('email')){
			$arrReturn['status'] = "success";
			$arrReturn['userInfo'] = $this->session->userdata('userInfo');
			echo json_encode( $arrReturn );
		}
		else if( get_cookie('userInfo') ){
			$arrReturn['status'] = "success";
			$arrReturn['userInfo'] = json_decode( get_cookie('userInfo'));
			echo json_encode( $arrReturn );		}
		else
		{
			$user 	= new User();
			$email 		= $this->getValue('email');
			$password 	= $this->getValue('password');

			if( $email == "" || $password == "" )
			{
				echo json_encode(array("status"=>"failed", "error_message"=>"Empty Email or Password!")); return;
			}

			$res = $user->checkLogin( $email, $password );
			if( isset($res->role) && $res->role != 3 )
			{
				if( $this->getValue('remeber_me') ){
					set_cookie('userInfo', json_encode($res), 60*60*24*15 );
				}

				$this->session->set_userdata( 'userInfo', $res );
				$arrReturn['status'] = "success";
				$arrReturn['userInfo'] = $this->session->userdata('userInfo');
				echo json_encode( $arrReturn );
			}
			else if(isset($res->role))
				echo json_encode(array("status"=>"failed", "error_message"=>"Your account isn't Admin")); 
			else
				echo json_encode(array("status"=>"failed", "error_message"=>"Invalid Email or Password!")); 
		}
		return;
	}

	public function getAllUsers(){
		
		$search = $this->getValue('search_text');
		$user = new User();
		$arrUsers = $user->getUsers( "`firstName` LIKE '%".$search."%' OR `lastName` LIKE '%".$search."%' OR `username` LIKE '%".$search."%' OR `email` LIKE '%".$search."%' " );
		echo json_encode( $arrUsers );
	}

	public function getUserById(){

		$userID = $this->getValue('userID');
		$user = new User();
		echo json_encode( $user->getUserById( $userID ));
	}

	public function saveUser(){

		$arrUser = array(
						"firstName" => $this->getValue("firstName"),
						"lastName"	=> $this->getValue("lastName"),
						"email"		=> $this->getValue("email"),
						"username"	=> $this->getValue("username"),
						"password"	=> $this->getValue("password"),
						"role"		=> $this->getValue("role")
					);

		if( $arrUser["firstName"] == "" || $arrUser["lastName"] == "" ||$arrUser["email"] == "" ||$arrUser["password"] == "" || $arrUser["username"]=="")
			return;

		$user = new User();
		if( $this->getValue("userID") ){
			$arrUser["userID"] = $this->getValue("userID");
			print_r( $user->updateUser( $arrUser ) );
		}
		else
			print_r( $user->addUser( $arrUser ) );
	}

	public function deleteUser(){

		$userID = $this->getValue('userID');
		$user = new User();
		echo $user->deleteUser( $userID );
	}

	public function logout(){
		delete_cookie('userInfo');
		$this->session->unset_userdata("userInfo");
	}

	public function checkExistEmail(){

		$email = $this->getValue('email');
		$user = new User();
		$arrUsers = $user->getUsers( "`email`='".$email."'" );
		echo json_encode( $arrUsers );
	}

	public function checkExistUsername(){

		$email = $this->getValue('username');
		$user = new User();
		$arrUsers = $user->getUsers( "`username`='".$email."'" );
		echo json_encode( $arrUsers );
	}

	// ------- end User block ------- //

	// ------- start News block -------//
	public function saveNews(){

		$arrNews = array(
						"newsTitle" => $this->getValue("newsTitle"),
						"imgPath"	=> $this->getValue("imgPath"),
						"newsContent" => $this->getValue("newsContent")
					);

		if( $arrNews["newsTitle"] == "" ||$arrNews["newsContent"] == "" )
			return;

		$news = new News();
		if( $this->getValue("newsID") ){
			$arrNews["newsID"] = $this->getValue("newsID");
			print_r( $news->updateNews( $arrNews ) );
		}
		else
			print_r( $news->addNews( $arrNews ) );
	}

	public function getNewsList(){
		
		$news = new News();
		$arrNews = $news->getNews( );
		echo json_encode( $arrNews );
	}
	
	public function getNewsById(){

		$newsID = $this->getValue('newsID');
	
		$news = new News();
		echo json_encode( $news->getNewsById( $newsID ));
	}

	public function deleteNews(){

		$newsID = $this->getValue('newsID');
		$news = new News();
		echo json_encode( $news->deleteNews( $newsID ));
	}
	// ------- end News block ------- //

	// ------- start Orders block ------- //
	public function getOrders(){

		$arrOrders = array( array( "firstName"=> "Jennifer", "lastName"=>"Minely", "orderDate"=>"23 Nov 2015","orderQty"=>15,"storeName"=>"Store A,B" ),
							array( "firstName"=> "Mark", "lastName"=>"Otto", "orderDate"=>"21 Nov 2015","orderQty"=>7,"storeName"=>"Store A" ),
							array( "firstName"=> "Jennifer", "lastName"=>"Minely", "orderDate"=>"23 Nov 2015","orderQty"=>15,"storeName"=>"Store A,B" ),
							array( "firstName"=> "Jennifer", "lastName"=>"Minely", "orderDate"=>"23 Nov 2015","orderQty"=>15,"storeName"=>"Store A,B" ),
							array( "firstName"=> "Mark", "lastName"=>"Otto", "orderDate"=>"21 Nov 2015","orderQty"=>7,"storeName"=>"Store A" ),
							array( "firstName"=> "Mark", "lastName"=>"Otto", "orderDate"=>"21 Nov 2015","orderQty"=>7,"storeName"=>"Store A" ),
							array( "firstName"=> "Mark", "lastName"=>"Otto", "orderDate"=>"21 Nov 2015","orderQty"=>7,"storeName"=>"Store A" ),
							array( "firstName"=> "Jennifer", "lastName"=>"Minely", "orderDate"=>"23 Nov 2015","orderQty"=>15,"storeName"=>"Store A,B" ),
							array( "firstName"=> "Mark", "lastName"=>"Otto", "orderDate"=>"21 Nov 2015","orderQty"=>7,"storeName"=>"Store A" ),
							array( "firstName"=> "Mark", "lastName"=>"Otto", "orderDate"=>"21 Nov 2015","orderQty"=>7,"storeName"=>"Store A" ),
							array( "firstName"=> "Jennifer", "lastName"=>"Minely", "orderDate"=>"23 Nov 2015","orderQty"=>15,"storeName"=>"Store A,B" ),
							array( "firstName"=> "Jennifer", "lastName"=>"Minely", "orderDate"=>"23 Nov 2015","orderQty"=>15,"storeName"=>"Store A,B" ),
							array( "firstName"=> "Mark", "lastName"=>"Otto", "orderDate"=>"21 Nov 2015","orderQty"=>7,"storeName"=>"Store A" ),
							array( "firstName"=> "Jennifer", "lastName"=>"Minely", "orderDate"=>"23 Nov 2015","orderQty"=>15,"storeName"=>"Store A,B" ),
							array( "firstName"=> "Mark", "lastName"=>"Otto", "orderDate"=>"21 Nov 2015","orderQty"=>7,"storeName"=>"Store A" ),
							array( "firstName"=> "Mark", "lastName"=>"Otto", "orderDate"=>"21 Nov 2015","orderQty"=>7,"storeName"=>"Store A" ),
							array( "firstName"=> "Jennifer", "lastName"=>"Minely", "orderDate"=>"23 Nov 2015","orderQty"=>15,"storeName"=>"Store A,B" ),
							array( "firstName"=> "Jennifer", "lastName"=>"Minely", "orderDate"=>"23 Nov 2015","orderQty"=>15,"storeName"=>"Store A,B" ),
							array( "firstName"=> "Mark", "lastName"=>"Otto", "orderDate"=>"21 Nov 2015","orderQty"=>7,"storeName"=>"Store A" ),
							array( "firstName"=> "Jennifer", "lastName"=>"Minely", "orderDate"=>"23 Nov 2015","orderQty"=>15,"storeName"=>"Store A,B" ),
							array( "firstName"=> "Mark", "lastName"=>"Otto", "orderDate"=>"21 Nov 2015","orderQty"=>7,"storeName"=>"Store A" ),
							array( "firstName"=> "Mark", "lastName"=>"Otto", "orderDate"=>"21 Nov 2015","orderQty"=>7,"storeName"=>"Store A" ),
							array( "firstName"=> "Jennifer", "lastName"=>"Minely", "orderDate"=>"23 Nov 2015","orderQty"=>15,"storeName"=>"Store A,B" ),
							array( "firstName"=> "Jennifer", "lastName"=>"Minely", "orderDate"=>"23 Nov 2015","orderQty"=>15,"storeName"=>"Store A,B" ),
							array( "firstName"=> "Jennifer", "lastName"=>"Minely", "orderDate"=>"23 Nov 2015","orderQty"=>15,"storeName"=>"Store A,B" ),
					);

		echo json_encode( $arrOrders );
	}
	// ------- end Orders block ------- //

	// ------- start Report block ------- //
	public function getReports(){

		$arrReports = array( array( "userID"=>"1", "firstName"=> "Jennifer", "lastName"=>"Minely", "Area"=>"District 6", "completion"=>"90" ),
							array( "userID"=>"2", "firstName"=> "Mark", "lastName"=>"Otto", "Area"=>"District 4", "completion"=>"80" ),
							array( "userID"=>"1", "firstName"=> "Jennifer", "lastName"=>"Minely", "Area"=>"District 1", "completion"=>"70" ),
							array( "userID"=>"1", "firstName"=> "Jennifer", "lastName"=>"Minely", "Area"=>"District 2", "completion"=>"80" ),
							array( "userID"=>"2", "firstName"=> "Mark", "lastName"=>"Otto", "Area"=>"District 5", "completion"=>"90"),
							array( "userID"=>"2", "firstName"=> "Mark", "lastName"=>"Otto", "Area"=>"District 7", "completion"=>"80"),
							array( "userID"=>"1", "firstName"=> "Jennifer", "lastName"=>"Minely", "Area"=>"District 2", "completion"=>"80" ),
							array( "userID"=>"2", "firstName"=> "Mark", "lastName"=>"Otto", "Area"=>"District 5", "completion"=>"90"),
							array( "userID"=>"2", "firstName"=> "Mark", "lastName"=>"Otto", "Area"=>"District 7", "completion"=>"80"),
							array( "userID"=>"1", "firstName"=> "Jennifer", "lastName"=>"Minely", "Area"=>"District 2", "completion"=>"80" ),
							array( "userID"=>"2", "firstName"=> "Mark", "lastName"=>"Otto", "Area"=>"District 5", "completion"=>"90"),
							array( "userID"=>"2", "firstName"=> "Mark", "lastName"=>"Otto", "Area"=>"District 7", "completion"=>"80"),
							array( "userID"=>"1", "firstName"=> "Jennifer", "lastName"=>"Minely", "Area"=>"District 2", "completion"=>"80" ),
							array( "userID"=>"2", "firstName"=> "Mark", "lastName"=>"Otto", "Area"=>"District 5", "completion"=>"90"),
							array( "userID"=>"2", "firstName"=> "Mark", "lastName"=>"Otto", "Area"=>"District 7", "completion"=>"80"),
							array( "userID"=>"1", "firstName"=> "Jennifer", "lastName"=>"Minely", "Area"=>"District 2", "completion"=>"80" ),
							array( "userID"=>"2", "firstName"=> "Mark", "lastName"=>"Otto", "Area"=>"District 5", "completion"=>"90"),
							array( "userID"=>"2", "firstName"=> "Mark", "lastName"=>"Otto", "Area"=>"District 7", "completion"=>"80"),
							array( "userID"=>"1", "firstName"=> "Jennifer", "lastName"=>"Minely", "Area"=>"District 2", "completion"=>"80" ),
							array( "userID"=>"2", "firstName"=> "Mark", "lastName"=>"Otto", "Area"=>"District 5", "completion"=>"90"),
							array( "userID"=>"2", "firstName"=> "Mark", "lastName"=>"Otto", "Area"=>"District 7", "completion"=>"80"),
							array( "userID"=>"2", "firstName"=> "Mark", "lastName"=>"Otto", "Area"=>"District 5", "completion"=>"90"),
							array( "userID"=>"2", "firstName"=> "Mark", "lastName"=>"Otto", "Area"=>"District 7", "completion"=>"80"),

					);

		echo json_encode( $arrReports );
	}

	public function getPersonCompleteReports( ){

		$arrPersonReports = array(
				array( "comp_date"=>"23 Dec 2015", "Area"=>"District 6", "daily_completion"=>"90" ),
				array( "comp_date"=>"22 Dec 2015", "Area"=>"District 4", "daily_completion"=>"80" ),
				array( "comp_date"=>"5 Jan 2016", "Area"=>"District 1", "daily_completion"=>"70" ),
				array( "comp_date"=>"8 Jan 2016", "Area"=>"District 2", "daily_completion"=>"90" ),
				array( "comp_date"=>"22 Dec 2015", "Area"=>"District 4", "daily_completion"=>"80" ),
				array( "comp_date"=>"5 Jan 2016", "Area"=>"District 1", "daily_completion"=>"70" ),
				array( "comp_date"=>"8 Jan 2016", "Area"=>"District 2", "daily_completion"=>"90" ),
				array( "comp_date"=>"22 Dec 2015", "Area"=>"District 4", "daily_completion"=>"80" ),
				array( "comp_date"=>"5 Jan 2016", "Area"=>"District 1", "daily_completion"=>"70" ),
				array( "comp_date"=>"8 Jan 2016", "Area"=>"District 2", "daily_completion"=>"90" ),
				array( "comp_date"=>"22 Dec 2015", "Area"=>"District 4", "daily_completion"=>"80" ),
				array( "comp_date"=>"5 Jan 2016", "Area"=>"District 1", "daily_completion"=>"70" ),
				array( "comp_date"=>"8 Jan 2016", "Area"=>"District 2", "daily_completion"=>"90" ),
				array( "comp_date"=>"22 Dec 2015", "Area"=>"District 4", "daily_completion"=>"80" ),
				array( "comp_date"=>"5 Jan 2016", "Area"=>"District 1", "daily_completion"=>"70" ),
				array( "comp_date"=>"8 Jan 2016", "Area"=>"District 2", "daily_completion"=>"90" ),
		);

		echo json_encode( $arrPersonReports );
	}
	// ------- end Reports block ------- //

	public function uploadFile(){

		if ( !empty( $_FILES ) ) {

			$file_name = date("Y-m-d").$_FILES[ 'file' ][ 'name' ];
		    $tempPath = $_FILES[ 'file' ][ 'tmp_name' ];
		    $uploadPath = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '../../uploads/news' . DIRECTORY_SEPARATOR . $file_name;

		    move_uploaded_file( $tempPath, $uploadPath );
		    $answer = array( 'answer' => 'success', 'Path' => "uploads/news". DIRECTORY_SEPARATOR .$file_name );
		    $json = json_encode( $answer );
		    echo $json;
		} else {
		    echo 'No files';
		}
	}
}
