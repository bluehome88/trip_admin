<?php
/*
	Date: 2016-03
	Author: BlueSky
*/
class Category extends CI_Model
{
	private $_db = null; 
	private $table_name = 'category'; 

	public function __construct(){

		parent::__construct();
		
		$this->_db = $this->load->database('default', TRUE);
		$this->_db->from( $this->table_name );
		$this->_db->order_by("categoryName", "ASC");
	}

	/* get All Category*/
	public function getCategories( $where = '' ){

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


	public function getActiveCategories(){

		return $this->getCategories( 'active = 1' );
	}
	/* get Category by categoryID*/
	public function getCategoryById( $categoryID ){

		if( !$categoryID )
			return false;

		$this->_db->select('*');

		$this->_db->where( "`categoryID`=".$categoryID );
	
		$query = $this->_db->get();

		if($query->num_rows() > 0)
		{
			$rows = $query->result();
			return $rows[0];
		}

		return false;
	}

	public function addCategory( $categoryData ){

		return $this->_db->insert( $this->table_name, $categoryData );
	}

	public function updateCategory( $categoryData ){

		if( !$categoryData['categoryID'] )
			return false;

		foreach( $categoryData as $key => $value )
			$this->_db->set( $key, $value );
	
		$this->_db->where( "categoryID", $categoryData['categoryID'] );
		return $this->_db->update( $this->table_name );
	}

	public function deleteCategory( $categoryID ){

		$this->_db->where("`categoryID` = {$categoryID}");
		return $this->_db->delete( $this->table_name );
	}
}