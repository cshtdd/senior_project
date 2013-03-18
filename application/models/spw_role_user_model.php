<?php

class SPW_Role_User_Model extends CI_Model
{
	
	public function __construct()
	{
		parent::__construct();
	}

	public function get_role($user_id)
	{
		$query = $this->db
					  ->where('id',$user_id)
					  ->get('spw_role_user');

		if($query->num_rows() > 0)
		{
			return $query->result()[0];	
		}else{
			return null;
		}
		
	}
}
	
?>