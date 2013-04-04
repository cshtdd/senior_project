<?php

class SPW_Project_Status_Model extends CI_Model
{
	public $id;
	public $name;

	public function __construct()
	{
		parent::__construct();
	}

	public function getStatusName($status_id)
	{
		$query = $this->db
					   ->where('id',$status_id)
					   ->select('name')
					   ->get('spw_project_status');

		if ($query->num_rows() > 0)
		{
			return $query->row()->name;
		}
		else
		{
			throw new Exception('Status not found');
		}	
	}
}
	
?>