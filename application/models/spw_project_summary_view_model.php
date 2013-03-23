<?php

class SPW_Project_Summary_View_Model extends CI_Model
{
    //a SPW_Project_Model object
    public $project;

    //a SPW_Term_Model object
    public $term;

    //an array of SPW_Skill_Model objects
    public $lSkills;

    //an array of SPW_User_Summary_View_Model
    public $lMentorSummaries;

    //a SPW_User_Summary object
    public $proposedBySummary;

    //an array of SPW_User_Summary_View_Model
    public $lTeamMemberSummaries;

    public $justList;

    public $displayJoin;
    public $displayLeave;

    public function __construct()
    {
        parent::__construct();
    }

    public function getShortDescription()
    {
        return substr($this->project->description, 0, min(200, strlen($this->project->description)));
    }

    public function getlSkillNames()
    {
        $resultArray = array();

        foreach ($this->lSkills as $iSkill)
        {
            $resultArray[] = $iSkill->name;
        }

        $resultStr = join(', ', $resultArray);
        return $resultStr;
    }

    /* this function fills a list of projects with their data */
    public function prepareProjectsDataToShow($user_id, $lProjectIds, $belongProjectIdsList, $pastProjects)
    {
        $tempUser = new SPW_User_Model();
        $tempProject = new SPW_Project_Model();

        $length = count($lProjectIds);

        $lProjects = array();

        for ($i = 0; $i < $length; $i++)
        {
            $project_summ_vm = new SPW_Project_Summary_View_Model();

            $project_id = $lProjectIds[$i];

            $project = $tempProject->getProjectInfo($project_id);

            if (isset($project))
            {
                $project_summ_vm->project = $project;

                $project_summ_vm->justList = true;

                $term = $tempProject->getProjectTermInfo($project_id);

                if (isset($term))
                {
                    if (!($tempProject->isProjectClosed($term)))
                    {
                        if ($tempUser->isUserAStudent($user_id))
                        {
                            $currentUserTerm = $tempUser->getUserGraduationTerm($user_id);

                            if ($currentUserTerm->id == $term->id)
                                $project_summ_vm->justList = false;
                        }
                        else
                            $project_summ_vm->justList = false; 
                    }

                    $project_summ_vm->term = $term;
                }

                $lSkills = $tempProject->getProjectListOfSkills($project_id);
                if (isset($lSkills) && count($lSkills)>0)
                {
                    $project_summ_vm->lSkills = $lSkills;
                }

                $lMentorsSumm = $tempProject->getMentorsListForProject($project_id);
                if (isset($lMentorsSumm) && count($lMentorsSumm)>0)
                {
                    $project_summ_vm->lMentorSummaries = $lMentorsSumm;
                }

                $proposedBySumm = $tempProject->getProposedByOfProject($project_id);
                if (isset($proposedBySumm))
                {
                    $project_summ_vm->proposedBySummary = $proposedBySumm;
                }
                
                $lStudentsSumm = $tempProject->getStudentsListForProject($project_id);
                if (isset($lStudentsSumm) && count($lStudentsSumm)>0)
                {
                    $project_summ_vm->lTeamMemberSummaries = $lStudentsSumm;
                }

                if (!($project_summ_vm->justList))
                {
                    if (!$pastProjects)
                    {   
                        //if (in_array($lProjectIds[$i], $belongProjectIdsList))
                        if ($this->isProjectInList($belongProjectIdsList, $project_id))
                        {
                            $project_summ_vm->displayLeave = TRUE;
                            $project_summ_vm->displayJoin = FALSE;
                        }
                        else
                        {
                            $project_summ_vm->displayLeave = FALSE;
                            $project_summ_vm->displayJoin = TRUE;
                        }   
                    }
                } 
            }
            else
            {
                throw new Exception('spw_project table error in db...');
            }

            $lProjects[] = $project_summ_vm;
        }
        return $lProjects;
    }

    public function isProjectInList($belongProjIdsList, $project_Id)
    {
        $length = count($belongProjIdsList);

        for ($i = 0; $i < $length; $i++)
        {
            if ($belongProjIdsList[$i] == $project_Id)
                return true;
        }

        return false;
    }

}
    
?>