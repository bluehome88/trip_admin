<?php
/*
	Date: 2016-03
	Author: BlueSky
*/
class Relations extends CI_Model
{
	private $_db = null;

	public function __construct(){

		parent::__construct();

		$this->_db = $this->load->database('default', TRUE);
	}

	public function getOrders( $where = "1" ){

		$sql = "SELECT o.orderID as orderID, o.orderDate as orderDate, u.firstName as firstName, u.lastName as lastName, s.storeName as storeName, o.orderQty as orderQty
				FROM orders AS o
				LEFT JOIN users AS u ON (o.userID=u.userID)
				LEFT JOIN routes AS r ON (r.routeID=o.routeID)
				LEFT JOIN stores AS s ON (s.storeID=r.storeID)
				WHERE o.active=1 AND s.active=1 AND r.active=1 AND ".$where;

		$query = $this->_db->query( $sql );
		return $query->result();
	}

	public function getOrderDetailInfo( $order_id = 1 ){

		$sql = "SELECT * FROM orders AS o
				LEFT JOIN order_products AS op ON ( o.orderID = op.orderID )
				LEFT JOIN routes AS r ON (r.routeID=o.routeID)
				LEFT JOIN stores AS s ON (s.storeID=r.storeID)
				LEFT JOIN products AS p ON (op.productID = p.productID)
				LEFT JOIN category as c ON (p.categoryID = c.categoryID)
				WHERE o.active=1 AND s.active=1 AND r.active=1 AND p.active=1 AND c.active=1
			";

		$query = $this->_db->query( $sql );
		return $query->result();
	}

	public function getReports(){
		$sql = "SELECT * FROM orders AS o
				LEFT JOIN order_products AS op ON ( o.orderID = op.orderID )
				LEFT JOIN routes AS r ON (r.routeID=o.routeID)
				LEFT JOIN stores AS s ON (s.storeID=r.storeID)
				LEFT JOIN products AS p ON (op.productID = p.productID)
				LEFT JOIN category as c ON (p.categoryID = c.categoryID)
				WHERE o.active=1 AND s.active=1 AND r.active=1 AND p.active=1 AND c.active=1
			";

		$query = $this->_db->query( $sql );
		return $query->result();
	}

	public function getOrderProducts( $orderID ){
		$sql = "SELECT * FROM order_products 
				WHERE orderID=".$orderID."
			";

		$query = $this->_db->query( $sql );
		return $query->result();		
	}

	public function saveOrderData( $arrOrderData ){

/*
	$arrOrderData = array(
		array("orderInfo" => array(
									"userID" 		=> "",
									"orderData"		=> "",
									"orderQty"		=> "",
									"totalPrice"	=> "",
									"routeID"		=> "",
									"date_updated"	=> "",
									"active" 		=> 1,
							)),
		array("order_products" => array(
									0=> array("orderID"=>"", "productID"=>"", "qty"=>""),
									1=> array("orderID"=>"", "productID"=>"", "qty"=>""),
							)),
		array("order_attachment" => array(
							))
	);
*/

		// insert Order
		/*$arrOrder = array(
				"userID"	=> 2,
				"orderQty"	=> 3,
				"totalPrice"=> 4,
				"routeID"	=> 3,
				"active"	=> 1,
		);

		$order = new Orders();
		print_r( $order->addOrder( $arrOrder ) ); /**/

		// Save Order Products
		
		$arrProducts = array(
				"productID"	=> 1,
				
		);

	}
}
