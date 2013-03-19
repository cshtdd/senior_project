<?php

class SPW_Skill_Model extends CI_Model
{
	public $id;
	public $name;
	//IsEnabled?
	public $website_active = true;

	public function __construct()
	{
		parent::__construct();
	}

	public function get_skill($skill_id)
	{
		$query = $this->db
					   ->where('id',$skill_id)
					   ->get('spw_skill');

		if($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{
			   return $row;
			}
		}

	}

	public function get_skill_by_name($skill_name)
	{
		$query = $this->db
					   ->where('name',$skill_name)
					   ->get('spw_skill');

		if($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{
			   return $row->id;
			}
		}else{
			$data = array(
			   'name' => $skill_name,
			   'website_active' => 0
			);

			$this->db->insert('spw_skill', $data); 
			return $this->db->insert_id();
		}
	}

	public function get_skillname($id)
	{
		$query = $this->db
					   ->where('id',$id)
					   ->select('name')
					   ->get('spw_skill');

		if ($query->num_rows() > 0)
		{
			return $query->row()->name;
		}
		else
		{
			throw new Exception('Skill Id not found');
		}
	}

}
	
?>