<?php
/*
	Date: 2016-03
	Author: BlueSky
*/
class Store extends CI_Model
{
	private $_db = null; 
	private $table_name = 'stores'; 

	public function __construct(){

		parent::__construct();
		
		$this->_db = $this->load->database('default', TRUE);
		$this->_db->from( $this->table_name );
		$this->_db->order_by("storeName", "ASC");
	}

	/* get All Store*/
	public function getStores( $where = '' ){

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


	public function getActiveStores(){

		return $this->getStores( 'active = 1' );
	}
	/* get Store by storeID*/
	public function getStoreById( $storeID ){

		if( !$storeID )
			return false;

		$this->_db->select('*');

		$this->_db->where( "`storeID`=".$storeID );
	
		$query = $this->_db->get();

		if($query->num_rows() > 0)
		{
			$rows = $query->result();
			return $rows[0];
		}

		return false;
	}

	public function addStore( $storeData ){

		return $this->_db->insert( $this->table_name, $storeData );
	}

	public function updateStore( $storeData ){

		if( !$storeData['storeID'] )
			return false;

		foreach( $storeData as $key => $value )
			$this->_db->set( $key, $value );
	
		$this->_db->where( "storeID", $storeData['storeID'] );
		return $this->_db->update( $this->table_name );
	}

	public function deleteStore( $storeID ){

		$this->_db->where("`storeID` = {$storeID}");
		return $this->_db->delete( $this->table_name );
	}
}