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
					  ->where('user',$user_id)
					  ->get('spw_role_user');

		if($query->num_rows() > 0)
		{
			return $query->row();	
		}else{
			return null;
		}
		
	}

	public function update_roles_for_user($spw_id, $newRoleId)
	{
		$this->db->where('user',$spw_id);
		$this->db->delete('spw_role_user');

		$data = array(
					'user' => $spw_id,
					'role' => $newRoleId,
					);

		$this->db->insert('spw_role_user',$data);
		
	}
}
	
?>