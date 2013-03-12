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
	public $summary_spw;

	public $headline_linkedIn;
	public $summary_linkedIn;
	public $positions_linkedIn;

	//the id of the SPW_Term
	public $graduation_term;
	//the id of the SPW_Project
	public $project;
	public $google_id;
	public $linkedin_id;

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



	public function is_owned_registered($email_address)
	{
		$query = $this->db
					   ->where('google_id',NULL)
					   ->where('linkedin_id',NULL)
					   ->where('email',$email_address)
					   ->get('spw_user');

		if($query->num_rows() > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function is_google_registered($id)
	{
		$query = $this->db
					   ->where('google_id',$id)
					   ->get('spw_user');

		if($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{
			   return $row->id;
			}
		}
		else
		{
			return 0;
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

	public function create_new_google_user($email_address, $given_name, $family_name,$google_id)
 	{
 	
 		$data = array(
 		   'email' =>  $email_address ,
 		   'first_name' => $given_name, 
 		   'last_name' => $family_name,
 		   'google_id' => $google_id,
 		);
 
 		$this->db->insert('spw_user', $data);
 		return $this->db->insert_id();
 	}
 	
 	public function is_facebook_registered($id)
	{
		$query = $this->db
					   ->where('facebook_id',$id)
					   ->get('spw_user');

		if($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{
			   return $row->id;
			}
		}
		else
		{
			return 0;
		}
	}

	public function create_new_facebook_user($email_address, $given_name, $family_name,$facebook_id)
 	{
 	
 		$data = array(
 		   'email' =>  $email_address ,
 		   'first_name' => $given_name, 
 		   'last_name' => $family_name,
 		   'facebook_id' => $facebook_id,
 		);
 
 		$this->db->insert('spw_user', $data);
 		return $this->db->insert_id();
 	}
 	
 
 	/* return a SPW_Term_Model info corresponding to the user id */
	public function getUserGraduationTerm($user_id)
	{
		$param[0] = $user_id;
		$sql = 'select spw_term.*
		        from spw_user, spw_term
		        where (spw_user.id = ?) and (spw_user.graduation_term = spw_term.id)';
		$query = $this->db->query($sql, $param);
		if ($query->num_rows() > 0)
		{
			$row = $query->row(0, 'SPW_Term_Model');
			return $row;
		}
		
		return NULL;
	}

	/* return a SPW_User_Model info corresponding to the user id */
	public function getUserInfo($user_id)
	{
		$param[0] = $user_id;
		$sql = 'select *
		        from spw_user
		        where id = ?';
		$query = $this->db->query($sql, $param);
		if ($query->num_rows() > 0)
		{
			$row = $query->row(0, 'SPW_User_Model');
			return $row;
		}

		return NULL;
	}

	/* return the list of suggested projects IDs with the highest matches having in
	   count that the project delivery_term is the same as the user, is not yet the 
	   closed_requests date and the project have been aproved */
	public function getSuggestedProjectsGivenCurrentUser($user_id)
	{	
		if ($this->isUserAStudent($user_id))
		{
			$term = $this->getUserGraduationTerm($user_id);

			if (isset($term))
			{
				$param[0] = $user_id;

				$param[1] = $term->id;

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
			}
		}
		elseif ($this->isUserPossibleMentor($user_id))
		{
			$param[0] = $user_id;

			$sql = 'select spw_project.id, count(project_skills.skill) as nSkillMatch
		   		  	from spw_project, (select skill
               			   	           from spw_skill_user
               			   	           where user = ?) as skills, (select spw_project.id, skill
                                          			               from spw_project, spw_skill_project, spw_term
                                          			               where (spw_project.id = project) and (spw_project.status = 3) and
                                                                         (spw_term.id = spw_project.delivery_term) and (spw_term.closed_requests > NOW())) 
																		 as project_skills
		   		  	where (skills.skill=project_skills.skill) and (spw_project.id=project_skills.id)
				  	group by spw_project.id
				  	order by nSkillMatch DESC';

			$query = $this->db->query($sql, $param);

			$sql1 = 'select spw_project.id
	         		 from spw_project, spw_term
	         		 where (spw_project.status = 3) and (spw_term.id = spw_project.delivery_term) 
	                   	    and (spw_term.end_date > NOW())';

	    	$query1 = $this->db->query($sql1);
		}

		if (isset($query))
		{
			$totValidProjects = $query1->num_rows();

			$res = $this->chooseRelevantIds($query, $totValidProjects);

			return $res;
		}

		return NULL;
	}

	/* Given the suggested projects this function returns the regular projects */
	public function getRegularProjectIds($lSuggestedProjectIds, $user_id)
	{
		if ($this->isUserAStudent($user_id))
		{
			$term = $this->getUserGraduationTerm($user_id);

			if (isset($term))
			{
				$param[0] = $term->id;

				$sql = 'select spw_project.id
	         			from spw_project, spw_term
	         			where (spw_project.status = 3) and (spw_term.id = spw_project.delivery_term) 
	                   	  	and (spw_term.end_date > NOW()) and (spw_term.id = ?)
             			order by id ASC';

        		$query = $this->db->query($sql, $param);
			}
		}
		elseif ($this->isUserPossibleMentor($user_id))
		{
			$sql = 'select spw_project.id
	         		from spw_project, spw_term
	         		where (spw_project.status = 3) and (spw_term.id = spw_project.delivery_term) 
	                   	  and (spw_term.end_date > NOW())
             		order by id ASC';

             $query = $this->db->query($sql);
		}

		if (isset($query))
		{
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

		return NULL;
	}

	public function isUserAStudent($user_id)
	{
		$param[0] = $user_id;
		$sql = 'select id
				from spw_role_user
				where (user = ?) and (role = 5)';
		$query = $this->db->query($sql, $param);
		if ($query->num_rows() > 0)
		{
			return true;
		}
		return NULL;
	}

	public function isUserAdmin($user_id)
	{
		$param[0] = $user_id;
		$sql = 'select id
				from spw_role_user
				where (user = ?) and (role = 1)';
		$query = $this->db->query($sql, $param);
		if ($query->num_rows() > 0)
		{
			return true;
		}
		return NULL;
	}

	public function isUserHeadProfessor($user_id)
	{
		$param[0] = $user_id;
		$sql = 'select id
				from spw_role_user
				where (user = ?) and (role = 2)';
		$query = $this->db->query($sql, $param);
		if ($query->num_rows() > 0)
		{
			return true;
		}
		return NULL;
	}

	public function isUserPossibleMentor($user_id)
	{
		$param[0] = $user_id;
		$sql = 'select id
				from spw_role_user
				where (user = ?) and ((role = 2) or (role = 3) or (role = 4))';
		$query = $this->db->query($sql, $param);
		if ($query->num_rows() > 0)
		{
			return true;
		}
		return NULL;
	}

	/* return the id of the projects the user belong or false if does not have a project */
	public function userHaveProjects($user_id)
	{
		$param[0] = $user_id;

		if ($this->isUserAStudent($user_id))
		{
			$sql = 'select project
					from spw_user, spw_project
					where (spw_user.id = ?) and (project = spw_project.id) 
					       and (spw_project.status = 3)';
		}
		else
		{
			$sql = 'select project
					from spw_mentor_project, spw_project
					where (mentor = ?) and (spw_project.id = spw_mentor_project.project) 
					       and (spw_project.status = 3)';
		}

		$query = $this->db->query($sql, $param);

		if ($query->num_rows() > 0)
		{
			$res = array();

			foreach ($query->result() as $row)
			{
				$res[] = $row->project;
			}

			return $res;
		}
		else
		{
			return NULL;
		}
	}

	/* checks if the user is going to graduate on the current term */
	public function isUserGraduating($user_id)
	{
		$sql = 'select id
				from spw_term
				where (start_date <= NOW()) and (end_date > NOW())';
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0)
		{
			$param[0] = $user_id;
			$row = $query->row(0);
			$param[1] = $row->id;
			$sql1 = 'select id
					 from spw_user
					 where (id = ?) and (graduation_term = ?)';
			$query1 = $this->db->query($sql1, $param);
			if ($query1->num_rows() > 0)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return NULL;
		}
	}

	/* given the full list of Ids with at least one match, determines which can
	   actually be suggested to */
	private function chooseRelevantIds($allSuggestedIds, $totalValidIds)
	{
		$count = 0;
		$ratio = 3;
		$lSuggestedIds = array();
		$ratioIds = round($totalValidIds / $ratio);
		$flag = true;

		foreach ($allSuggestedIds->result() as $row)
		{
			if ($flag)
			{
				if (($row->nSkillMatch == 1) && ($count == 0))
				{
					$flag = false;
					$lSuggestedIds[$count] = $row->id;
					$count++;
				}
				else
				{
					if (($row->nSkillMatch >= 2) && ($count < $ratioIds))
					{
						$lSuggestedIds[$count] = $row->id;
						$count++;
					}
				}
			}
			else
			{
				if ($count < $ratioIds)
				{
					$lSuggestedIds[$count] = $row->id;
					$count++;
				}
			}
		}

		return $lSuggestedIds;
	}

}
	
?>