<?php

class SPW_Project_Details_View_Model extends SPW_Project_Summary_View_Model
{
     //a list of all the available valid terms to choose
    public $lTerms;

    public $onlyShowUserTerm;

    public function __construct()
    {
        parent::__construct();
    }

    public function getCurrentSkillNames()
    {
        if (isset($this->lSkills) && count($this->lSkills) > 0)
        {
            $resultArray = array();

            foreach ($this->lSkills as $iSkill)
            {
                $resultArray[] = '"'.$iSkill->name.'"';
            }

            $resultStr = join(', ', $resultArray);

            return $resultStr;
        }
        else
        {
            return '';
        }
    }

    public function prepareProjectDetailsDataToShow($user_id, $project_id)
    {
        $lProjectIds = array();
        $lProjectIds[] = $project_id;

        $tempUser = new SPW_User_Model();
        $belongProjectIdsList = $tempUser->userHaveProjects($user_id);

        $lProjectsSumm = $this->prepareProjectsDataToShow($user_id, $lProjectIds, $belongProjectIdsList, FALSE);            

        if (isset($lProjectsSumm) && count($lProjectsSumm) == 1)
        {
            $projectDetails = new SPW_Project_Details_View_Model();

            $projectDetails->onlyShowUserTerm = $tempUser->isUserAStudent($user_id);

            if (!$projectDetails->onlyShowUserTerm)
            {
                $tempTerm = new SPW_Term_Model();
                $projectDetails->lTerms = $tempTerm->getAllValidTerms();
            }

            $projectDetails->project = $lProjectsSumm[0]->project;

            $projectDetails->term = $lProjectsSumm[0]->term;

            $projectDetails->lSkills = $lProjectsSumm[0]->lSkills;

            $projectDetails->lMentorSummaries = $lProjectsSumm[0]->lMentorSummaries;

            $projectDetails->proposedBySummary = $lProjectsSumm[0]->proposedBySummary;

            $projectDetails->lTeamMemberSummaries = $lProjectsSumm[0]->lTeamMemberSummaries;

            return $projectDetails;
        }
        else
            return NULL;
    }   
}

?>