<?php
/*
	Date: 216-01
	Author: BlueSky
*/
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {

	public function __construct(){

		parent::__construct();
		$this->load->model('relations');
		$this->load->model('user');
		$this->load->model('news');

	}

	public function index(){

		$current_day = date("N");
		$days_from_monday = 1 - $current_day;
		$monday = date("Y-m-d", strtotime("{$days_from_monday} Days"));

		for( $i = 1-$current_day ; $i < 14-$current_day; $i++ )
		{			
			if( date("N", strtotime("{$i} Days")) ==6 || date("N", strtotime("{$i} Days")) ==7 )
				continue;
			echo "<br>".date("l", strtotime("{$i} Days"))."---".date("Y-m-d", strtotime("{$i} Days"));
		}	
	}

	public function getOrders(){

		$result = $this->relations->getOrders();
		echo "<pre>";
		print_r( $result );
		echo "</pre>";
	}

	public function getOrderDetailInfo($order_id){

		$result = $this->relations->getOrderDetailInfo();
		echo "<pre>";
		print_r( $result );
		echo "</pre>";
	}
}
?>