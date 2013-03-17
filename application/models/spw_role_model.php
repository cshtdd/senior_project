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

	//get all available roles
	public function getAllRoles()
	{
		$sql = 'select *
				from spw_role
				where (id <> 1)';
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