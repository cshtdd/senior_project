<?php

class SPW_User_Model extends CI_Model
{
	public $id;
	public $first_name;
	public $last_name;
	public $email;
	//this will be a url
	public $picture;
	//didn't we talk about a separate table... ahhh I remember,
	//this field will be blank fro google users 
	public $hash_pwd;
	public $summary;

	//the id of the SPW_Term
	public $graduation_term;
	//the id of the SPW_Project
	public $project;


	public function __construct()
	{
		parent::__construct();
	}

	public function verify_user($email_address, $pwd)
	{
	    $query = $this->db
					  ->where('email', $email_address)
					  ->where('hash_pwd', sha1( $pwd))
					  ->limit(1)
					  ->get('spw_user');
		if($query->num_rows() > 0)
		{
			return $query->result();
		}
		else
		{
			return false; 
		}
	}

	/* return the list of suggested projects IDs with the highest matches having in
	   count that the project is valid for the current term, is not yet the closed_requests
	   date and the project have been aproved */
	public function getSuggestedProjectsGivenCurrentUser($user_id)
	{	
		$param[0] = $user_id;

		$sql = 'select spw_project.id, count(project_skills.skill) as nSkillMatch
		   		  from spw_project, (select skill
               			   	         from spw_skill_user
               			   	         where user = ?) as skills, (select spw_project.id, skill
                                          			             from spw_project, spw_skill_project, spw_term
                                          			             where (spw_project.id = project) and (spw_project.status = 3) and
                                                                       (spw_term.id = spw_project.delivery_term) and (spw_term.closed_requests > NOW())) as project_skills
		   		  where (skills.skill=project_skills.skill) and (spw_project.id=project_skills.id)
				  group by spw_project.id
				  order by nSkillMatch DESC';

		$query = $this->db->query($sql, $param);

		$sql1 = 'select id
				 from spw_project';

	    $query1 = $this->db->query($sql1);

		$totValidProjects = $query1->num_rows();

		$res = $this->chooseRelevantProjects($query, $totValidProjects);

		return $res;
	}


	/* return the id of the project the user belong or false if does not have a project */
	public function userHaveProject($user_id)
	{
		$param[0] = $user_id;

		$sql = 'select project
				from spw_user
				where (id = ?) and (project is not null)';

		$query = $this->db->query($sql, $param);

		if ($query->num_rows() > 0)
		{
			$row = $query->row(0);
			return $row->project;
		}
		else
		{
			return NULL;
		}
	}

	/* given the full list of projects with at least one match, determines which can
	   actually be suggested to the user*/
	private function chooseRelevantProjects($allSuggestedProjects, $totalValidProjects)
	{
		$count = 0;
		$ratio = 3;
		$lSuggestedProjectIds = array();
		$ratioProjects = round($totalValidProjects / $ratio);
		$flag = true;

		foreach ($allSuggestedProjects->result() as $row)
		{
			if ($flag)
			{
				if (($row->nSkillMatch == 1) && ($count == 0))
				{
					$flag = false;
					$lSuggestedProjectIds[$count] = $row->id;
					$count++;
				}
				else
				{
					if (($row->nSkillMatch >= 2) && ($count < $ratioProjects))
					{
						$lSuggestedProjectIds[$count] = $row->id;
						$count++;
					}
				}
			}
			else
			{
				if ($count < $ratioProjects)
				{
					$lSuggestedProjectIds[$count] = $row->id;
					$count++;
				}
			}
		}

		return $lSuggestedProjectIds;
	}

	public function check_already_registered($email_address)
	{
		$query = $this->db
					  //->where('google_id','NULL')
					  //->where('linkedin_id','NULL')
					  ->where('email',$email_address)
					  ->get('spw_user');
		if($query->num_rows() > 0)
		{
			return $query->result();
		}
		else
		{
			return false;
		}
	}

	public function create_new_user($email_address, $password)
	{
		$data = array(
		   'email' =>  $email_address ,
		   'hash_pwd' =>  sha1($password),
		);

		$this->db->insert('spw_user', $data);
		return $this->db->insert_id();
	}
}
	
?>