<?php

class User extends CI_Model
{
	private $_db = null; 
	private $table_name = 'users'; 

	public function __construct(){

		parent::__construct();
		
		$this->_db = $this->load->database('default', TRUE);
		$this->_db->from( $this->table_name );
		$this->_db->order_by("firstName", "ASC");
	}

	/* get All Users*/
	public function getUsers( $where = '' ){

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

	/* get User by userID*/
	public function getUserById( $userID ){

		if( !$userID )
			return false;

		$this->_db->select('*');

		$this->_db->where( "`userID`=".$userID );
	
		$query = $this->_db->get();
	
		if($query->num_rows() > 0)
		{
			$rows = $query->result();
			return $rows[0];
		}

		return false;
	}

	public function getActiveUsers(){

		return $this->getUsers( 'active = 1' );
	}

	public function addUser( $userData ){

		$userData['date_added'] 	= date("Y-m-d H:i:s");
		$userData['date_updated'] 	= date("Y-m-d H:i:s");
		return $this->_db->insert( $this->table_name, $userData );
	}

	public function updateUser( $userData ){

		if( !$userData['userID'] )
			return false;

		foreach( $userData as $key => $value )
			$this->_db->set( $key, $value );

		$this->_db->set( 'date_updated', date("Y-m-d H:i:s") );

		$this->_db->where( "userID", $userData['userID'] );
		return $this->_db->update( $this->table_name );
	}

	public function deleteUser( $userID ){

		$this->_db->where("`userID` = {$userID}");
		return $this->_db->delete( $this->table_name );
	}

	public function checkPermission(){

	}

	public function checkLogin( $email, $password ){

		$arr = $this->getUsers( "(`email`='".$email."' OR `username`='".$email."') AND `password`='".$password."'" );

		if( isset($arr[0]))
			return $arr[0];

		return 0;
	}
}
?>