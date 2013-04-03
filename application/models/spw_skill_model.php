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

	public function getAllSkillsNamesString()
	{
		$sql = 'select name
				from spw_skill';
		$query = $this->db->query($sql);

		$allSkillNames = array();	

		if ($query->num_rows() >0 )
		{
        	foreach ($query->result() as $row) 
        	{
        		$name = $row->name;
				$allSkillNames[] = '"'.$name.'"';
			}

        	$allSkillNamesStr = join(', ', $allSkillNames);

        	return $allSkillNamesStr;
    	}
    	else
    		return '';      
	}

	/* Inserts a new skill in spw_skill */
	public function insert($skill_obj)
    {
        $data = array('name'             => $skill_obj->name,
                      'website_active'   => $skill_obj->website_active
                     );

        $this->db->insert('spw_skill', $data); 

        return $this->db->insert_id();
    }

	/* Looks for a skill name and return the id if found a match */
	public function existsSkillOnTable($skillName)
	{
		$param[0] = $skillName;
		$sql = 'select id
				from spw_skill
				where (name = ?)';
		$query = $this->db->query($sql, $param);

		if (isset($query) && ($query->num_rows()>0))
		{
			$res = $query->result_array();
			return $res[0]['id'];
		}
		else
			return NULL;
	}

	public function getListSkillNamesOfProject($project_id)
	{
		$param[0] = $project_id;
		$sql = 'select spw_skill.name
				from spw_skill, spw_skill_project
				where (spw_skill_project.project = ?) and (spw_skill_project.skill = spw_skill.id)';
		$query = $this->db->query($sql, $param);

		if ($query->num_rows()>0)
		{
			$res = array();
			foreach ($query->result() as $row) 
			{
				$res[] = ucfirst(strtolower($row->name));
			}
			return $res;
		}
		else
			return NULL;
	}
}
	
?>