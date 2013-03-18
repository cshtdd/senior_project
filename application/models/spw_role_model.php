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
					  ->where('name !=','head professor')	
					  ->where('name !=','admin')	
					   ->get('spw_role');

		return $query->result();
	}

	//get all available roles
	public function getAllRoles()
	{
		$sql = 'select *
				from spw_role
				where (id <> 1) or (id <> 2)';
		$query = $this->db->query($sql);

		$roleNum = $query->num_rows();
		$lRoles = array();
		if ($roleNum > 0)
        {
        	for ($j = 0; $j < $roleNum; $i++)
        	{
        		$row = $query->row($j, 'SPW_Role_Model');
				$lRoles[] = $row;
        	}
        }

        return $lRoles;
	}
}
	
?>