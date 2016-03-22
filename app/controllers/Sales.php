<?php
/*
	Date: 216-01
	Author: BlueSky
*/
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales extends CI_Controller {

	public function __construct(){

		parent::__construct();
	}

	public function index(){

	}
	
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
							array( "firstName"=> "Jennifer", "lastName"=>"Minely", "orderDate"=>"23 Nov 2015","orderQty"=>15,"storeName"=>"Store A,B" ),
					);

		echo json_encode( $arrOrders );
	}
}
?>