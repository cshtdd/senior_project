<?php

class SPW_Role_Model extends CI_Model
{
	public $id;
	public $name;
	public $description;

	public function __construct()
	{
		parent::__construct();
	}

	
	public function get_roles()
	{
		$query = $this->db
					   ->get('spw_role');

		return $query->result();
	}
}
	
?>