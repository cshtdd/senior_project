<?php

class SPW_Language_User_Model extends CI_Model
{
	
	public function __construct()
	{
		parent::__construct();
	}

	public function get_languages_for_user($user_id)
	{
		$query = $this->db
					   ->where('user',$user_id)
					   ->select('language')
					   ->get('spw_language_user');

		return $query->result();
	}

	public function insert($spw_id, $language_id)
	{
		$data = array(
		   'language' => $language_id,
		   'user' => $spw_id,
		);

		$this->db->insert('spw_language_user', $data); 
	}

	public function delete_languages_for_user($spw_id)
	{
		$this->db->where('user', $spw_id);
		$this->db->delete('spw_language_user');
	}		

}
	
?>