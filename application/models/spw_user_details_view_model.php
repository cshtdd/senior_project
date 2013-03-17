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
		$tempUser = new SPW_User_Model();
		$tempRole = new SPW_Role_Model();
		$tempTerm = new SPW_Term_Model();

		$userDetailsViewModel = new SPW_User_Details_View_Model();

		$user = $tempUser->getUserInfo($user_id);
		if (isset($user))
		{
			$userDetailsViewModel->user = $user;

			$userDetailsViewModel->invite = $user->canInviteUser($current_user_id, $user_id);
		}

		$lSkills = $tempUser->getUserListOfSkills($user_id);
		if (isset($lSkills) && count($lSkills)>0)
		{
			$userDetailsViewModel->lSkills = $lSkills;
		}

        $lExperiences = $tempUser->getUserListOfExperiences($user_id);
        if (isset($lExperiences) && count($lExperiences)>0)
        {
        	$userDetailsViewModel->lExperiences = $lExperiences;
        }
        
        $lLanguages = $tempUser->getUserListOfLanguages($user_id);
        if (isset($lLanguages) && count($lLanguages)>0)
        {
        	$userDetailsViewModel->lLanguages = $lLanguages;
        }
        
        $role = $tempUser->getUserRole($user_id);
        if (isset($role))
        {
        	$userDetailsViewModel->role = $role;
        }
        
        $lRoles = $tempRole->getAllRoles();
        if (isset($lRoles) && count($lRoles)>0)
        {
        	$userDetailsViewModel->lRoles = $lRoles;
        }

        $term = $tempUser->getUserGraduationTerm($user_id);
        if (isset($term))
        {
        	$userDetailsViewModel->term = $term;
        }

        $lTerms = $tempTerm->getAllValidTerms();
        if (isset($lTerms) && count($lTerms)>0)
        {
        	$userDetailsViewModel->lTerms = $lTerms;
        }
        
        return $userDetailsViewModel;
	}
}
	
?>