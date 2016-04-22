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
		$this->load->model('orders');
		$this->load->model('relations');
		$this->load->model('route');
		$this->load->model('topic');
		$this->load->model('comment');
		
		$this->load->model('store');
		$this->load->model('product');
		$this->load->model('category');

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

	public function makeOrder(){

		if( !$this->getValue("orderData") ){
			echo "error";
			die;
		}
		//$orderData = json_decode( $this->getValue("orderData") );
		echo $this->relations->saveOrderData( $this->getValue("orderData") );
	}
	// ------- end Orders block ------- //

	// ------- start Report block ------- //
	public function calcCompletionRate( $userID, $date = NULL ){

		// get count of routes
		if( $date == NULL )
			$routes = $this->route->getRouteByUserId( $userID );
		else
			$routes = $this->route->getAllRoutes("routes.userID=".$userID." AND routes.routeDate='".$date."'");

		$num_routes = count( $routes );

		// get orders
		if( $date == NULL )
			$where = "userID=".$userID;
		else
			$where = "userID=".$userID." AND orderDate='".$date."'";

		$orders = $this->orders->getOrders( $where );
		$num_orders = sizeof( $orders );

		if( empty($routes) )
			return  "---";

		if( empty($orders) )
			return  "0%";

		return $num_orders / $num_routes * 100 ."%";
	}

	public function getReports(){

		$arrUsers = $this->User->getUsers();
		$arrReports = array();

		foreach( $arrUsers as $k => $val){
			$rate  = $this->calcCompletionRate( $val->userID );

			if( !strcmp($rate, "---") )
				continue;

			$val->completion = $rate;
			
			$arrReports[] = $val;
		}

		echo json_encode( $arrReports );
	}

	public function getPersonCompleteReports( ){

		if( !$this->getValue("userID") ){
			echo "error";
			die;
		}

		$userID = $this->getValue("userID");
		$routes = $this->route->getRouteByUserId( $userID );

		$arrPersonReports = array();
		if( count( $routes ) ){
			foreach( $routes as $k => $route )
			{
				$arrPersonReports[$k]['comp_date'] = $route->routeDate;
				$arrPersonReports[$k]['Area'] = "";
				$arrPersonReports[$k]['daily_completion'] = $this->calcCompletionRate( $userID, $route->routeDate );
			}
		}

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

	public function getRoutes(){

		$userID = $this->getValue('userID');
		$where = '';
		if( $userID != "" && $userID != 0 )
				$where = "routes.userID=".$userID;

		$arrRoutes = $this->route->getAllRoutes( $where );
		echo json_encode( $arrRoutes );
	}

	// -- end of Route -- //
	public function uploadRoute(){

		if( $_FILES['file']['type'] != "text/csv" ){

		    $answer = array( 'answer' => 'error', 'message'=> 'Upload Correct File. File type is CSV' );
		    echo json_encode($answer);
		    exit;
		}

		$file_name = date("Y-m-d").$_FILES[ 'file' ][ 'name' ];
	    $tempPath = $_FILES[ 'file' ][ 'tmp_name' ];
	    $uploadPath = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '../../uploads/route' . DIRECTORY_SEPARATOR . $file_name;

	    move_uploaded_file( $tempPath, $uploadPath );
		

	    $answer = array( 'answer' => 'success', 'message' => $this->importDataFromCSV( $uploadPath ) );

	    echo json_encode($answer);
	    exit;
	}

	public function importDataFromCSV( $filePath = '' ){

		// read file content
        $arrRoutes = array();
        $arrKeys = array();

        $fp = fopen($filePath, 'rb');
        $index = -1;
        while (is_resource($fp) && !feof($fp)) {
            $row = fgets($fp, 16384);
            $arrTemp = explode( "," , $row );
            // set key
            if( empty($arrKeys)){
                foreach ( $arrTemp  as $key => $value) 
                    $arrKeys[] = trim( $value );
            }
            else{
                if( sizeof($arrTemp) != sizeof($arrKeys) )
                    continue;
                foreach ( $arrTemp  as $key => $value) {
                    //if( $$arrKeys[$key] != "" )
                        $arrRoutes[$index][$arrKeys[$key]] = $value;
                }
            }
            $index++;
        }

        if( !in_array('salesCode', $arrKeys) || !in_array('customerCode', $arrKeys) || !in_array('routeDate', $arrKeys))
        	return  "Fields doesn't match";

        $counter = 0;
        try{
			foreach($arrRoutes as $key => $value){
				
				// get userID from salesCode(userCode)
				$userInfo = $this->User->getUsers("`salesCode`='".$value['salesCode']."'");
				if( is_array($userInfo))
					$userID = $userInfo[0]->userID;

				// get storeID from customerCode
				$storeInfo = $this->store->getStores("`storeCode`='".$value['customerCode']."'");
				if( is_array($storeInfo))
					$storeID = $storeInfo[0]->storeID;

				// check exists
				$routeInfo = $this->route->getAllRoutes("routes.userID=$userID AND routes.storeID=$storeID AND routes.routeDate='".$value['routeDate']."'");
				
				if( is_array($routeInfo) )
					continue;

				$data = array("userID" => $userID, "storeID" => $storeID, "routeDate" => $value['routeDate'], "active" => 1);
				$this->route->addRoute( $data);

				$counter++;
			}
			return $counter." Routes Imported";
		}
		catch( Exception $e){
			return "Importing Error!";
		}
	}

	public function getTopics(){

		$arrTopics = array();
		$arrTopics = $this->topic->getTopics();

		echo json_encode( $arrTopics );
	}

	public function getTopicByID(){

		$topicID = $this->getValue('topicID');

		$arrTopics = array();
		$arrTopics = $this->topic->getTopicById( $topicID );

		echo json_encode( $arrTopics);
	}


	public function getComments(){

		$topicID = $this->getValue('topicID');

		$arrComments = array();
		$arrComments = $this->comment->getComments("comments.topicID=".$topicID);

		echo json_encode( $arrComments );
	}

	public function getAllSyncData(){

		$userID = $this->getValue('userID');
		$date = date("Y-m-d");

		if( !$userID )
			return;

		// Get route data
		$arrRoutes = $this->route->getRouteByUserId( $userID );
//echo "<pre>Routes ";
//print_r( $arrRoutes );
//echo "</pre>";

		// Get Store Data
		$arrStores = array();
		if( is_array($arrRoutes) ){
			foreach( $arrRoutes as $route ){
				$temp = $this->store->getStoreById( $route->storeID );
				if( !in_array($temp, $arrStores))
					$arrStores[] = $temp;
			}
		}
//echo "<pre>Stores ";
//print_r( $arrStores );
//echo "</pre>";

		// Get Category Data
		$arrCategories = array();
		foreach($arrStores as $store) {
			$temp = $this->category->getCategories( "storeID=".$store->storeID );
			if( $temp ){
				for($i = 0; $i < count($temp); $i++) {
					$arrCategories[] = $temp[$i];
				}
			}
		}

//echo "<pre>Categories ";
//print_r( $arrCategories );
//echo "</pre>";
		
		// Get Product Data
		$arrProducts = array();
		foreach ($arrCategories as $category) {
			$temp = $this->product->getProducts( "categoryID=".$category->categoryID );
			if( $temp ){
				for($i = 0; $i < count($temp); $i++) {
					$arrProducts[] = $temp[$i];
				}
			} 
		}
//echo "<pre>Products ";
//print_r( $arrProducts );
//echo "</pre>";

		// Get Topic Data
		$arrTopics = array();
		foreach($arrStores as $store) {
			$temp = $this->topic->getTopicsByStoreID( $store->storeID );
			if( $temp ){
				for($i = 0; $i < count($temp); $i++) {
					$arrTopics[] = $temp[$i];
				}
			}
		}
//echo "<pre>Topics ";
//print_r( $arrTopics );
//echo "</pre>";

		// Get Topic Data
		$arrComments = array();
		foreach($arrTopics as $topic) {
			$temp = $this->comment->getCommentsByTopicID( $topic->topicID );
			if( $temp ){
				for($i = 0; $i < count($temp); $i++) {
					$arrComments[] = $temp[$i];
				}
			}
		}
//echo "<pre>Comments ";
//print_r( $arrComments );
//echo "</pre>";

		// Get News Data
		$news = new News();
		$arrNews = $news->getNews( );
//echo "<pre>News ";
//print_r( $arrNews );
//echo "</pre>";

		// Get Order Data
		$arrOrders = array();
		if( is_array($arrRoutes) ){
			foreach( $arrRoutes as $route ){

				$temp = $this->orders->getOrders( "routeID=".$route->routeID );
				if( $temp ){
					for($i = 0; $i < count($temp); $i++) {
						$arrOrders[] = $temp[$i];
					}
				}
			}
		}
//echo "<pre>Orders ";
//print_r( $arrOrders );
//echo "</pre>";

		// Get OrderProducts Data
		$arrOrderProducts = array();
		if( is_array($arrOrders) ){
			foreach( $arrOrders as $order ){

				$temp = $this->relations->getOrderProducts( $order->orderID );
				if( $temp ){
					for($i = 0; $i < count($temp); $i++) {
						$arrOrderProducts[] = $temp[$i];
					}
				}
			}
		}


		$arrReturn = array(	"routes"  =>	$arrRoutes,
							"stores"	=>	$arrStores,
							"category"	=>	$arrCategories,
							"products"	=>	$arrProducts,
							"topics"	=>	$arrTopics,
							"comments"	=>	$arrComments,
							"news"		=>	$arrNews,
							"orders"	=>	$arrOrders,
							"order_products" => $arrOrderProducts
			);
/*echo "<pre>";		
print_r( $arrReturn );
echo "</pre>";*/
		echo json_encode( $arrReturn );
	}

	public function addTopic(){

		$storeID = $this->getValue('storeID');
		$topicTitle = $this->getValue('topicTitle');
		$date_added = $this->getValue('date_added');

		if( !$storeID || $topicTitle == "" )
		{
			echo "error";
			exit;
		}

		$topicData = array(
			"storeID" 	=> $storeID,
			"topicTitle" 	=> $topicTitle,
			"date_added" 	=> $date_added,
			"active" 		=> 1
		);

		echo $this->topic->addTopic( $topicData );
	}

	public function addComment(){

		$topicID = $this->getValue('topicID');
		$userID = $this->getValue('userID');
		$contents = $this->getValue('contents');
		$date_added = $this->getValue('date_added');

		if( !$topicID || !$userID ){
			echo "error";
			exit;
		}
		$commentData = array(
			"topicID" 	=> $topicID,
			"userID" 	=> $userID,
			"contents" 	=> $contents,
			"date_added"=> $date_added,
			"active"	=> 1
		);

		echo $this->comment->addComment( $commentData );
	}
}
