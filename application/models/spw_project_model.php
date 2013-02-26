<?php

class SPW_Project_Model extends CI_Model
{
	public $id;
	public $title;
	public $description;
	public $max_students;

	//the id of the SPW_User
	public $team_leader;

	//the id of the SPW_Term
	public $delivery_term;

	//The id of the SPW_Project_Status
	public $status;

	public function __construct()
	{
		parent::__construct();
	}

	public function getRegularProjectIds($lSuggestedProjectIds)
	{
		$sql = 'select spw_project.id
	         	from spw_project, spw_term
	         	where (spw_project.status = 3) and (spw_term.id = spw_project.delivery_term) 
	                   and (spw_term.end_date > NOW())
             	order by id ASC';

        $query = $this->db->query($sql);

        sort($lSuggestedProjectIds);

        $lValidProjects = array();

        foreach ($query->result() as $row)
		{
			$lValidProjects[] = $row->id;
		}

		$res = array_diff($lValidProjects, $lSuggestedProjectIds);

		$res = array_values($res);

		return $res;
	}
}
	
?>