<?php
/*
	Date: 216-01
	Author: BlueSky
*/
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

	public function __construct(){

		parent::__construct();
		
		$this->load->helper('cookie');
		$this->load->helper('url');
		
		// load models
		$this->load->model('User');
		$this->load->model('News');
		$this->load->model('relations');

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
	
	// ------- start User block -------//
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
				echo json_encode(array("status"=>"error", "error_message"=>"Empty Email or Password!")); return;
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
				echo json_encode(array("status"=>"error", "error_message"=>"Your account isn't Admin")); 
			else
				echo json_encode(array("status"=>"error", "error_message"=>"Invalid Email or Password!")); 
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

		/*
			$arrOrder Row Data fields
			array( 	"orderID"=>1, 
					"firstName"=> "Jennifer", 
					"lastName"=>"Minely", 
					"orderDate"=>"23 Nov 2015",
					"orderQty"=>15,
					"storeName"=>"Store A,B" )
		*/
		$arrOrders = array();
		$arrOrders = $this->relations->getOrders();

		echo json_encode( $arrOrders );
	}

	public function getOrderDetails(){
		
		if( !$this->getValue("orderID") ){
			echo "error";
			die;
		}

		$arrOrderInfo 	= $this->relations->getOrders("orderID=".$this->getValue("orderID"));
		$arrProducts 	= $this->relations->getOrderDetailInfo(1);

		$arrReturn = array(	
								"store_name" 	=> $arrOrderInfo[0]->storeName, 
								"person_name" 	=> $arrOrderInfo[0]->firstName." ".$arrOrderInfo[0]->lastName,
								"order_date" 	=> $arrOrderInfo[0]->orderDate,
								"product_info" 	=> $arrProducts
														
							);
		
		echo json_encode( $arrReturn );
	}
	// ------- end Orders block ------- //

	// ------- start Report block ------- //
	private function calcCompletionRate( $user_id ){
		return $user_id;
	}

	public function getReports(){

		$arrUsers = $this->user->getUsers();
		$arrReturn = array(); 

		/*foreach( $arrUsers as $k => $val){
			$arrReturn[$k] = $val;
			$arrReturn[$k]->completion = $this->calcCompletionRate( $val->userID );
		}*/
		/*
		Row Data Fields 
		array( 
			"userID"=>"2", 
			"firstName"=> "Mark", 
			"lastName"=>"Otto", 
			"areaInfo"=>"District 4", 
			"completion"=>"80" ),

		);*/

		echo json_encode( $arrReturn );
	}

	public function getPersonCompleteReports( ){

		if( !$this->getValue("userID") ){
			echo "error";
			die;
		}

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


	// -- Part of Route -- //
	public function getAreaList(){

		$arrAreaList = array(
			array( "id"=>"1", "area_code"=>"", "name"=>"Scooby Doo" ),
			array( "id"=>"2", "area_code"=>"", "name"=>"Shaggy Rodgers" ),
			array( "id"=>"3", "area_code"=>"", "name"=>"Fred Jones" ),
			array( "id"=>"4", "area_code"=>"", "name"=>"Scooby" ),
			array( "id"=>"5", "area_code"=>"", "name"=>"Daphne Blake" ),
			array( "id"=>"6", "area_code"=>"", "name"=>"Velma Dinkley" ),
			array( "id"=>"7", "area_code"=>"", "name"=>"Fred Jones" ),
			array( "id"=>"8", "area_code"=>"", "name"=>"Daphne Blake" ),
			array( "id"=>"9", "area_code"=>"", "name"=>"Velma Dinkley" ),
		);
		echo json_encode( $arrAreaList );
	}

	public function getWeeklyRoutes(){

	}

	public function getBiweeklyRoutes(){

	}

	public function getRouteDetails(){

	}

	// -- end of Route -- //
}
