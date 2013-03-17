<?php

class SPW_User_Details_View_Model extends SPW_User_Summary_View_Model
{
	//an array of SPW_Skill_Model objects
	public $lSkills;
	//an array of SPW_Experience_Model objects
	public $lExperiences;
	//an array of SPW_Language_Model objects
	public $lLanguages;
	//a SPW_Role_Model object
	public $role;

	//a list of SPW_Role objects with all the available roles
	public $lRoles;

	//a SPW_Term_Model
	public $term;

	//a list of all the available terms students can choose
	public $lTerms;

	public function __construct()
	{
		parent::__construct();
	}

	public function prepareUserDataDetailsToShow($current_user_id, $user_id)
	{
		$userDetailsViewModel = new SPW_User_Details_View_Model();

		$param[0] = $user_id;

		//get user info
		$sql = 'select *
				from spw_user
				where (id = ?)';
		$query = $this->db->query($sql, $param);

		if ($query->num_rows() > 0)
		{
			$user = $query->row(0, 'SPW_User_Model');

			$userDetailsViewModel->user = $user;

			$userDetailsViewModel->invite = $user->canInviteUser($current_user_id, $user_id);
		}

		//get list of skills info of the user
		$sql2 = 'select spw_skill.*
				 from spw_skill, spw_skill_user
				 where (spw_skill_user.user = ?) and (spw_skill.id = spw_skill_user.skill)';
		$query2 = $this->db->query($sql2, $param);
		$skillNum = $query2->num_rows();
		$lSkills = array();
		if ($skillNum > 0)
		{
			for ($j = 0; $j < $skillNum; $j++)
			{
				$row = $query2->row($j, 'SPW_Skill_Model');
				$lSkills[] = $row;
			}
		}
		$userDetailsViewModel->lSkills = $lSkills;

        //get list of esperiences info of the user
		$sql3 = 'select *
				 from spw_experience
				 where (user = ?)';
		$query3 = $this->db->query($sql3, $param);
		$experienceNum = $query3->num_rows();
		$lExperiences = array();
		if ($experienceNum > 0)
		{
			for ($j = 0; $j < $experienceNum; $j++)
			{
				$row = $query3->row($j, 'SPW_Experience_Model');
				$lExperiences[] = $row;
			}
		}
        $userDetailsViewModel->lExperiences = $lExperiences;

        //get list of languages info of the user
        $sql4 = 'select spw_language.*
        		 from spw_language, spw_language_user
        		 where (spw_language_user.user = ?) and (spw_language.id = spw_language_user.language)';
        $query4 = $this->db->query($sql4, $param);
        $languageNum = $query4->num_rows();
        $lLanguages = array();
        if ($languageNum > 0)
        {
        	for ($j = 0; $j < $languageNum; $i++)
        	{
        		$row = $query4->row($j, 'SPW_Language_Model');
				$lLanguages[] = $row;
        	}
        }
        $userDetailsViewModel->lLanguages = $lLanguages;

        //get the role info of the user
        $sql5 = 'select spw_role.*
        		 from spw_role, spw_role_user
        		 where (spw_role_user.user = ?) and (spw_role.id = spw_role_user.role)';
        $query5 = $this->db->query($sql5, $param);

        if ($query5->num_rows() > 0)
        {
        	$role = $query5->row(0, 'SPW_Role_Model');
        	$userDetailsViewModel->role = $role;
        }

        //get all



        $userDetailsViewModel->lRoles = $lRoles;
        $userDetailsViewModel->term = $term;
        $userDetailsViewModel->lTerms = $lTerms;
        
        

        return $userDetailsViewModel;
	}
}
	
?>