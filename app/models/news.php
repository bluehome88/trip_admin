<?php
/*
	Date: 2016-01
	Author: BlueSky
*/
class News extends CI_Model
{
	private $_db = null; 
	private $table_name = 'news'; 

	public function __construct(){

		parent::__construct();
		
		$this->_db = $this->load->database('default', TRUE);
		$this->_db->from( $this->table_name );
		$this->_db->order_by("date_added", "DESC");
	}

	/* get All News*/
	public function getNews( $where = '' ){

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


	public function getActiveNews(){

		return $this->getNews( 'status = 1' );
	}
	/* get News by newsID*/
	public function getNewsById( $newsID ){

		if( !$newsID )
			return false;

		$this->_db->select('*');

		$this->_db->where( "`newsID`=".$newsID );
	
		$query = $this->_db->get();

		if($query->num_rows() > 0)
		{
			$rows = $query->result();
			return $rows[0];
		}

		return false;
	}

	public function addNews( $newsData ){

		$newsData['date_added'] 	= date("Y-m-d H:i:s");
		$newsData['date_updated'] 	= date("Y-m-d H:i:s");

		return $this->_db->insert( $this->table_name, $newsData );
	}

	public function updateNews( $newsData ){

		if( !isset($newsData['newsID']) )
			return false;

		foreach( $newsData as $key => $value )
			$this->_db->set( $key, $value );
	
		$this->_db->set( 'date_updated', date("Y-m-d H:i:s") );

		$this->_db->where( "newsID", $newsData['newsID'] );
		return $this->_db->update( $this->table_name );
	}

	public function deleteNews( $newsID ){

		$this->_db->where("`newsID` = {$newsID}");
		return $this->_db->delete( $this->table_name );
	}
}