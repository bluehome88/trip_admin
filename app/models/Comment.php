<?php
/*
	Date: 2016-03
	Author: BlueSky
*/
class Comment extends CI_Model
{
	private $_db = null; 
	private $table_name = 'comments'; 

	public function __construct(){

		parent::__construct();
		
		$this->_db = $this->load->database('default', TRUE);
		$this->_db->from( $this->table_name );
		$this->_db->order_by("date_added", "DESC");
	}

	/* get All Comment*/
	public function getComments( $where = '' ){

		$this->_db->select($this->table_name.'.*, users.firstName, users.lastName, users.salesCode');

		if( $where != '' )
			$this->_db->where( $where );

		$this->_db->join('topics', 'topics.topicID='.$this->table_name.'.topicID');
		$this->_db->join('users', 'users.userID='.$this->table_name.'.userID');
	
		$query = $this->_db->get();
	
		if($query->num_rows() > 0)
		{
			$rows = $query->result();
			return $rows;			
		}

		return false;
	}

	/* get comments by topic ID */
	public function getCommentsByTopicID( $topicID ){

		if( !$topicID )
			return false;

		$this->__construct();
		$this->_db->select('*');

		$this->_db->where( "`topicID`=".$topicID );
	
		$query = $this->_db->get();
	
		if($query->num_rows() > 0)
		{
			$rows = $query->result();
			return $rows;			
		}

		return false;
	}


	public function getActiveComments(){

		return $this->getComments( 'active = 1' );
	}
	/* get Comment by commentID*/
	public function getCommentById( $commentID ){

		if( !$commentID )
			return false;

		$this->_db->select('*');

		$this->_db->where( "`commentID`=".$commentID );
	
		$query = $this->_db->get();

		if($query->num_rows() > 0)
		{
			$rows = $query->result();
			return $rows[0];
		}

		return false;
	}

	public function addComment( $commentData ){

		$commentData['date_updated'] 	= date("Y-m-d");

		if( $this->_db->insert( $this->table_name, $commentData ))
			return $this->_db->insert_id();
		else
			return 0;
	}

	public function updateComment( $commentData ){

		if( !$commentData['commentID'] )
			return false;

		foreach( $commentData as $key => $value )
			$this->_db->set( $key, $value );
	
		$this->_db->set( 'date_updated', date("Y-m-d H:i:s") );

		$this->_db->where( "commentID", $commentData['commentID'] );
		return $this->_db->update( $this->table_name );
	}

	public function deleteComment( $commentID ){

		$this->_db->where("`commentID` = {$commentID}");
		return $this->_db->delete( $this->table_name );
	}
}