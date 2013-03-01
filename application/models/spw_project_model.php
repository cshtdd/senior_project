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

	/* return the list of suggested user IDs with the highest matches having in
	   count that the user is going to graduate in the same term as the project,
	   is not yet the closed_requests date and the project have been aproved */
	/*public function getSuggestedUsersGivenMyProject($project_id)
	{	
		$param[0] = $project_id;






		$sq = 'select graduation_term
		       from spw_user
		       where (id = ?)';
		$qry = $this->db->query($sq, $param);

		if ($qry->num_rows() > 0)
		{
			$row = $qry->row(0);

			$param[1] = $row->graduation_term;

			$sql = 'select spw_project.id, count(project_skills.skill) as nSkillMatch
		   		  	from spw_project, (select skill
               			   	           from spw_skill_user
               			   	           where user = ?) as skills, (select spw_project.id, skill
                                          			               from spw_project, spw_skill_project, spw_term
                                          			               where (spw_project.id = project) and (spw_project.status = 3) and
                                                                         (spw_term.id = spw_project.delivery_term) and (spw_term.closed_requests > NOW())
                                                                         and (spw_term.id = ?)) as project_skills
		   		  	where (skills.skill=project_skills.skill) and (spw_project.id=project_skills.id)
				  	group by spw_project.id
				  	order by nSkillMatch DESC';

			$query = $this->db->query($sql, $param);

			$param1[0] = $param[1];

			$sql1 = 'select id
				 	 from spw_project
				 	 where (delivery_term = ?) and (status = 3)';

	    	$query1 = $this->db->query($sql1, $param1);

			$totValidProjects = $query1->num_rows();

			$res = $this->chooseRelevantProjects($query, $totValidProjects);

			return $res;
		}

		return NULL;
	}*/
}
	
?>