<?php
/*
	Date: 2016-03
	Author: BlueSky
*/
class Topic extends CI_Model
{
	private $_db = null; 
	private $table_name = 'topics'; 

	public function __construct(){

		parent::__construct();
		
		$this->_db = $this->load->database('default', TRUE);
		$this->_db->from( $this->table_name );
		$this->_db->order_by("date_added", "DESC");
	}

	/* get All Topic*/
	public function getTopics( $where = '' ){

		$this->_db->select('*');

		if( $where != '' )
			$this->_db->where( $where );
		$this->_db->join('stores', 'stores.storeID='.$this->table_name.'.storeID');
	
		$query = $this->_db->get();
	
		if($query->num_rows() > 0)
		{
			$rows = $query->result();
			return $rows;			
		}

		return false;
	}

	/* get All Topic*/
	public function getTopicsByStoreID( $storeID ){

		if( !$storeID )
			return false;

		$this->__construct();
		$this->_db->select('*');

		$this->_db->where( "`storeID`=".$storeID );
	
		$query = $this->_db->get();
	
		if($query->num_rows() > 0)
		{
			$rows = $query->result();
			return $rows;			
		}

		return false;
	}

	public function getActiveTopics(){

		return $this->getTopics( 'active = 1' );
	}

	/* get Topic by topicID*/
	public function getTopicById( $topicID ){

		if( !$topicID )
			return false;

		$this->_db->select('*');

		$this->_db->where( "`topicID`=".$topicID );
	
		$query = $this->_db->get();

		if($query->num_rows() > 0)
		{
			$rows = $query->result();
			return $rows[0];
		}

		return false;
	}

	public function addTopic( $topicData ){

		$topicData['date_added'] 	= date("Y-m-d H:i:s");

		return $this->_db->insert( $this->table_name, $topicData );
	}

	public function updateTopic( $topicData ){

		if( !isset($topicData['topicID']) )
			return false;

		foreach( $topicData as $key => $value )
			$this->_db->set( $key, $value );
	
		$this->_db->set( 'date_updated', date("Y-m-d H:i:s") );

		$this->_db->where( "topicID", $topicData['topicID'] );
		return $this->_db->update( $this->table_name );
	}

	public function deleteTopic( $topicID ){

		$this->_db->where("`topicID` = {$topicID}");
		return $this->_db->delete( $this->table_name );
	}
}