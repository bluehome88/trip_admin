<?php
/*
	Date: 2016-03
	Author: BlueSky
*/
class Orders extends CI_Model
{
	private $_db = null; 
	private $table_name = 'orders'; 

	public function __construct(){

		parent::__construct();
		
		$this->_db = $this->load->database('default', TRUE);
		$this->_db->from( $this->table_name );
		$this->_db->order_by("orderDate", "DESC");
	}

	/* get All Order*/
	public function getOrders( $where = '' ){

		$this->__construct();
		$this->_db->select('*');

		if( $where != '' )
			$this->_db->where( $where );
	
		$query = $this->_db->get();
	
		if($query->num_rows() > 0)
		{
			$rows = $query->result();
			return $rows;			
		}

		return false;
	}


	public function getActiveOrders(){

		return $this->getOrders( 'active = 1' );
	}
	/* get Order by orderID*/
	public function getOrderById( $orderID ){

		if( !$orderID )
			return false;

		$this->_db->select('*');

		$this->_db->where( "`orderID`=".$orderID );
	
		$query = $this->_db->get();

		if($query->num_rows() > 0)
		{
			$rows = $query->result();
			return $rows[0];
		}

		return false;
	}

	public function addOrder( $orderData ){

		$orderData['orderDate'] 	= date("Y-m-d");
		$this->_db->set( 'date_updated', date("Y-m-d H:i:s") );

		return $this->_db->insert( $this->table_name, $orderData );
	}

	public function updateOrder( $orderData ){

		if( !isset( $orderData['orderID'] ))
			return false;

		foreach( $orderData as $key => $value )
			$this->_db->set( $key, $value );
	
		$this->_db->set( 'date_updated', date("Y-m-d H:i:s") );

		$this->_db->where( "orderID", $orderData['orderID'] );
		return $this->_db->update( $this->table_name );
	}

	public function deleteOrder( $orderID ){

		$this->_db->where("`orderID` = {$orderID}");
		return $this->_db->delete( $this->table_name );
	}
}