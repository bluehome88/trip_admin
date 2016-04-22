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

		// insert Order

		if( isset( $arrOrderData['orderID'])){
			$arrOrder = array(
					"userID"	=> $arrOrderData['userID'],
					"orderQty"	=> $arrOrderData['orderQty'],
					"totalPrice"=> $arrOrderData['totalPrice'],
					"routeID"	=> $arrOrderData['routeID'],
					"active"	=> 1,
			);

			$order = new Orders();
			$orderID = $order->addOrder( $arrOrder ) ;

			foreach( $arrOrderData['products'] as $row )
			{
				$product = array("orderID"=> $orderID, "productID" => $row['productID'], "qty" => $row['qty'] );
				$this->_db->insert('order_products', $product );
			}

			$this->_db->set( 'isDone', "1" );
			$this->_db->where( "routeID", $arrOrderData['routeID'] );
			$this->_db->update( 'routes' );

			return "success";
		}
		else
		{
			$orderID = $arrOrderData['orderID'];
			$arrOrder = array(
					"orderID"	=> $orderID,
					"userID"	=> $arrOrderData['userID'],
					"orderQty"	=> $arrOrderData['orderQty'],
					"totalPrice"=> $arrOrderData['totalPrice'],
					"routeID"	=> $arrOrderData['routeID'],
					"active"	=> 1,
			);

			$order = new Orders();
			$order->updateOrder( $arrOrder );

			foreach( $arrOrderData['products'] as $row )
			{
				$sql = "UPDATE order_products SET qty='".$row['qty']."' WHERE orderID='".$orderID."' AND productID='".$row['productID']."'";
				$query = $this->_db->query( $sql );

			}

			return "success";
		}
	}
}
