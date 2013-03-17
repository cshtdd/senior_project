<?php

class SPW_Skill_User_Model extends CI_Model
{
	
	public function __construct()
	{
		parent::__construct();
	}

	public function insert($spw_id, $skill_id)
	{
		$data = array(
		   'skill' => $skill_id,
		   'user' => $spw_id,
		);

		$this->db->insert('spw_skill_user', $data); 
	}

	public function delete_skills_for_user($spw_id)
	{
		$this->db->where('user', $spw_id);
		$this->db->delete('spw_skill_user');
	}

}
	
?>