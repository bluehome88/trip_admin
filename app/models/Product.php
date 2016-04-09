<?php
/*
	Date: 2016-03
	Author: BlueSky
*/
class Product extends CI_Model
{
	private $_db = null; 
	private $table_name = 'products'; 

	public function __construct(){

		parent::__construct();
		
		$this->_db = $this->load->database('default', TRUE);
		$this->_db->from( $this->table_name );
		$this->_db->order_by("productName", "ASC");
	}

	/* get All Product*/
	public function getProducts( $where = '' ){

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


	public function getActiveProducts(){

		return $this->getProducts( 'active = 1' );
	}
	/* get Product by productID*/
	public function getProductById( $productID ){

		if( !$productID )
			return false;

		$this->_db->select('*');

		$this->_db->where( "`productID`=".$productID );
	
		$query = $this->_db->get();

		if($query->num_rows() > 0)
		{
			$rows = $query->result();
			return $rows[0];
		}

		return false;
	}

	public function addProduct( $productData ){

		return $this->_db->insert( $this->table_name, $productData );
	}

	public function updateProduct( $productData ){

		if( !isset($productData['productID']) )
			return false;

		foreach( $productData as $key => $value )
			$this->_db->set( $key, $value );
	
		$this->_db->where( "productID", $productData['productID'] );
		return $this->_db->update( $this->table_name );
	}

	public function deleteProduct( $productID ){

		$this->_db->where("`productID` = {$productID}");
		return $this->_db->delete( $this->table_name );
	}
}