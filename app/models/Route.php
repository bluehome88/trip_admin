<?php
/*
	Date: 2016-03
	Author: BlueSky
*/
class Route extends CI_Model
{
	private $_db = null;
	private $table_name = 'routes';

	public function __construct(){

		parent::__construct();

		$this->_db = $this->load->database('default', TRUE);
		$this->_db->from( $this->table_name );
		$this->_db->order_by("routeDate", "DESC");
	}

	/* get All Route*/
	public function getAllRoutes( $where = '' ){

		$this->__construct();
		$this->_db->select('*');

		if( $where != '' )
			$this->_db->where( $where );

		$this->_db->join('stores', 'stores.storeID='.$this->table_name.'.storeID');
		$this->_db->join('users', 'users.userID='.$this->table_name.'.userID');

		$query = $this->_db->get();

		if($query->num_rows() > 0)
		{
			$rows = $query->result();
			return $rows;
		}

		return false;
	}


	public function getActiveRoutes(){

		return $this->getRoutes( 'active = 1' );
	}
	/* get Route by routeID*/
	public function getRouteById( $routeID ){

		if( !$routeID )
			return false;

		$this->_db->select('*');

		$this->_db->where( "`routeID`=".$routeID );

		$query = $this->_db->get();

		if($query->num_rows() > 0)
		{
			$rows = $query->result();
			return $rows[0];
		}

		return false;
	}

	/* get Route by routeID*/
	public function getRouteByUserId( $userID, $date="" ){

		$this->__construct();
		if( !$userID )
			return false;

		$this->_db->select('*');

		$this->_db->where( "`userID`=".$userID );
		if( $date )
			$this->_db->where( "`routeDate`='". $date ."'" );

		$query = $this->_db->get();

		if($query->num_rows() > 0)
		{
			$rows = $query->result();
			return $rows;
		}

		return false;
	}

	public function addRoute( $routeData ){

		return $this->_db->insert( $this->table_name, $routeData );
	}

	public function updateRoute( $routeData ){

		if( !isset($routeData['routeID']) )
			return false;

		foreach( $routeData as $key => $value )
			$this->_db->set( $key, $value );

		$this->_db->set( 'date_updated', date("Y-m-d H:i:s") );

		$this->_db->where( "routeID", $routeData['routeID'] );
		return $this->_db->update( $this->table_name );
	}

	public function deleteRoute( $routeID ){

		$this->_db->where("`routeID` = {$routeID}");
		return $this->_db->delete( $this->table_name );
	}
}
