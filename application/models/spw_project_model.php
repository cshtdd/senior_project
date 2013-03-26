<?php

class SPW_Project_Model extends CI_Model
{
    public $id;
    public $title;
    public $description;
    public $max_students = 5;

    //the id of the SPW_User
    public $proposed_by;

    //the id of the SPW_Term
    public $delivery_term;

    //The id of the SPW_Project_Status
    public $status;

    public function __construct()
    {
        parent::__construct();
    }

    /* obtain past projects */
    public function getPastProjects()
    {
        $sql = 'select spw_project.id
                from spw_project, spw_term
                where (spw_project.status <> 4) and (spw_term.id = spw_project.delivery_term) 
                        and (spw_term.end_date < NOW())
                order by id ASC';

        $query = $this->db->query($sql);

        if (isset($query))
        {
            $lPastProjects = array();

            foreach ($query->result() as $row)
            {
                $lPastProjects[] = $row->id;
            }

            return $lPastProjects;
        }

        return NULL;
    }

    /* Inserts a new project in spw_project */
    public function insert($project_obj)
    {
        $data = array(
                        'title'         => $project_obj->title,
                        'description'   => $project_obj->description,
                        'max_students'  => $project_obj->max_students,
                        'proposed_by'   => $project_obj->proposed_by,
                        'delivery_term' => $project_obj->delivery_term,
                        'status'        => $project_obj->status
                         );

        $this->db->insert('spw_project', $data); 

        return $this->db->insert_id();
    }

    public function getProjectDeliveryTerm($project_id)
    {
        $param[0] = $project_id;
        $sql = 'select spw_term.*
                from spw_project, spw_term
                where (spw_project.id = ?) and (spw_project.delivery_term = spw_term.id)';
        $query = $this->db->query($sql, $param);

        if ($query->num_rows() > 0)
        {
            $row = $query->row(0, 'SPW_Term_Model');
            return $row;
        }
        
        return NULL;
    }

    /* return the list of suggested student IDs with the highest matches having in
       count that the student is going to graduate in the same term as the project,
       and is not yet the closed_requests date */
    public function getSuggestedStudentsGivenMyProject($project_id)
    {   
        $term = $this->getProjectDeliveryTerm($project_id);
        if (isset($term))
        {
            $param[0] = $project_id;

            $param[1] = $term->id;

            $sql = 'select spw_user.id, count(user_skills.skill) as nSkillMatch
                    from spw_user, (select skill
                                    from spw_skill_project
                                    where project = ?) as skills, (select spw_user.id, skill
                                                                   from spw_user, spw_skill_user, spw_term
                                                                   where (spw_user.id = user) and (spw_term.id = spw_user.graduation_term) and                                        (spw_term.closed_requests > NOW())
                                                                         and (spw_term.id = ?) and (spw_term.closed_requests > NOW())) as user_skills
                    where (skills.skill=user_skills.skill) and (spw_user.id=user_skills.id)
                    group by spw_user.id
                    order by nSkillMatch DESC';

            $query = $this->db->query($sql, $param);

            $param1[0] = $term->id;

            $sql1 = 'select id
                     from spw_user
                     where (graduation_term = ?)';

            $query1 = $this->db->query($sql1, $param1);
        }
        
        if ($query->num_rows() > 0)
        {
            $refinedUserIds = $this->discardUsersBelongProject($query, $project_id);

            if (count($refinedUserIds) > 0)
            {

                $totValidStudents = $query1->num_rows();

                $res = $this->chooseRelevantIds($refinedUserIds, $totValidStudents);

                return $res;
            }
        }

        return NULL;
    }

    /* takes a list of user ids and returns a list of user ids that do no belong to
       the project */
    public function discardUsersBelongProject($lUserIds, $project_id)
    {
        $item = array();
        $res = array();

        foreach ($lUserIds->result() as $row)
        {
            if (!$this->doesUserBelongToProject($row->id ,$project_id))
            {
                $item['id'] = $row->id;
                $item['nSkillMatch'] = $row->nSkillMatch;
                $res[] = $item;
            }
        }

        return $res;
    }

    //checks if the closed_requests is on
    public function isProjectClosed($term)
    {
        if (isset($term))
            {
                $currentDate = date('Y-m-d');
                if ($term->closed_requests > $currentDate)
                {
                    return false;
                }
                else
                {
                    return true;
                }
            }
    }

    /* return a SPW_Project_Model info corresponding to the project id */
    public function getProjectInfo($project_id)
    {
        $param[0] = $project_id;
        
        $sql = 'select *
                from spw_project
                where (id = ?)';
        $query = $this->db->query($sql, $param);

        if ($query->num_rows() > 0)
        {
            $row = $query->row(0, 'SPW_Project_Model');
            return $row;
        }

        return NULL;
    }

    /* return a SPW_Term_Model info corresponding to the project id */
    public function getProjectTermInfo($project_Id)
    {
        $param[0] = $project_Id;

        $sql = 'select spw_term.*
                from spw_term, spw_project
                where (spw_project.id = ?) and (spw_project.delivery_term = spw_term.id)';
        $query = $this->db->query($sql, $param);

        if ($query->num_rows() > 0)
        {
            $res = $query->row(0, 'SPW_Term_Model');

            return $res;
        }

        return NULL;
    }

    //get the students info that belong to the project
    public function getStudentsListForProject($project_id)
    {
        $param[0] = $project_id;

        $sql = 'select spw_user.*
                from spw_role_user, spw_user
                where (spw_user.project = ?) and (spw_role_user.user = spw_user.id) and 
                      (spw_role_user.role = 5)';
        $query = $this->db->query($sql, $param);

        $studentsNum = $query->num_rows();
        $lStudentsSumm = array();
        if ($studentsNum > 0)
        {
            for ($j = 0; $j < $studentsNum; $j++)
            {
                $row = $query->row($j, 'SPW_User_Model');
                $user_summ_vm = new SPW_User_Summary_View_Model();
                $user_summ_vm->user = $row;
                $lStudentsSumm[] = $user_summ_vm;
            }
        }

        return $lStudentsSumm;
    }

    //get the proposed_by user info of the project
    public function getProposedByOfProject($project_id)
    {
        $param[0] = $project_id;

        $sql = 'select spw_user.*
                from spw_user, spw_project
                where (spw_project.id = ?) and (spw_user.id = spw_project.proposed_by)';
        $query = $this->db->query($sql, $param);

        if ($query->num_rows() > 0)
        {
            $row = $query->row(0, 'SPW_User_Model');
            $proposedBySumm = new SPW_User_Summary_View_Model();
            $proposedBySumm->user = $row;

            return $proposedBySumm;
        }

        return NULL;
    }

    //get list of skills info of the project
    public function getProjectListOfSkills($project_id)
    {
        $param[0] = $project_id;

        $sql = 'select spw_skill.*
                from spw_skill, spw_skill_project
                where (spw_skill_project.project = ?) and (spw_skill.id = spw_skill_project.skill)';
        $query = $this->db->query($sql, $param);

        $skillNum = $query->num_rows();
        $lSkills = array();
        if ($skillNum > 0)
        {
            for ($j = 0; $j < $skillNum; $j++)
            {
                $row = $query->row($j, 'SPW_Skill_Model');
                $lSkills[] = $row;
            }
        }

        return $lSkills;
    }

    //get the users that belong to the project as mentors
    public function getMentorsListForProject($project_id)
    {
        $param[0] = $project_id;

        $sql = 'select spw_user.*
                from spw_user, spw_mentor_project
                where (spw_mentor_project.project = ?) and (spw_mentor_project.mentor = spw_user.id)';
        $query = $this->db->query($sql, $param);

        $mentorsNum = $query->num_rows();
        $lMentorsSumm = array();
        if ($mentorsNum > 0)
        {
            for ($j = 0; $j < $mentorsNum; $j++)
            {
                $row = $query->row($j, 'SPW_User_Model');
                $user_summ_vm = new SPW_User_Summary_View_Model();
                $user_summ_vm->user = $row;
                $lMentorsSumm[] = $user_summ_vm;
            }
        }

        return $lMentorsSumm;
    }

    /* checks whether a user id belongs to the given project */
    public function doesUserBelongToProject($user_id ,$project_id)
    {
        $param[0] = $user_id;
        $param[1] = $project_id;

        if ($this->SPW_User_Model->isUserAStudent($user_id))
        {
            $sql = 'select id
                    from spw_user
                    where (id = ?) and (project = ?)';
        }
        elseif ($this->SPW_User_Model->isUserPossibleMentor($user_id))
        {
            $sql = 'select mentor
                    from spw_mentor_project
                    where (mentor = ?) and (project = ?)';
        }
        
        $query = $this->db->query($sql, $param);

        if ($query->num_rows() > 0)
        {
            return true;
        }
        
        return false;   
    }

    /* return the list of suggested mentor IDs with the highest matches, and
       is not yet the closed_requests date */
    public function getSuggestedMentorsGivenMyProject($project_id)
    {
        $term = $this->getProjectDeliveryTerm($project_id);
        if (isset($term))
        {
            $param[0] = $project_id;

            $param[1] = $term->id;

            $sql = 'select spw_user.id, count(user_skills.skill) as nSkillMatch
                    from spw_user, (select skill
                                    from spw_skill_project
                                    where project = ?) as skills, (select spw_user.id, skill
                                                                   from spw_user, spw_skill_user, spw_term
                                                                   where (spw_user.id = user) and (spw_user.graduation_term is null) and 
                                                                         (spw_term.id = ?) and (spw_term.closed_requests > NOW())) as user_skills
                    where (skills.skill=user_skills.skill) and (spw_user.id=user_skills.id)
                    group by spw_user.id
                    order by nSkillMatch DESC';

            $query = $this->db->query($sql, $param);

            $sql1 = 'select id
                     from spw_user
                     where (graduation_term is null)';

            $query1 = $this->db->query($sql1);
        }

        if ($query->num_rows() > 0)
        {
            $refinedUserIds = $this->discardUsersBelongProject($query, $project_id);

            if (count($refinedUserIds) > 0)
            {
                $totValidMentors = $query1->num_rows();

                $res = $this->chooseRelevantIds($refinedUserIds, $totValidMentors);

                return $res;
            }
        }

        return NULL;

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
        $length = count($allSuggestedIds);

        for ($i = 0; $i < $length; $i++)
        {
            if ($flag)
            {
                if (($allSuggestedIds[$i]['nSkillMatch'] == 1) && ($count == 0))
                {
                    $flag = false;
                    $lSuggestedIds[$count] = $allSuggestedIds[$i]['id'];
                    $count++;
                }
                else
                {
                    if (($allSuggestedIds[$i]['nSkillMatch'] >= 2) && ($count < $ratioIds))
                    {
                        $lSuggestedIds[$count] = $allSuggestedIds[$i]['id'];
                        $count++;
                    }
                }
            }
            else
            {
                if ($count < $ratioIds)
                {
                    $lSuggestedIds[$count] = $allSuggestedIds[$i]['id'];
                    $count++;
                }
            }
        }

        return $lSuggestedIds;
    }

    /* searching for keyword in skill records */
    public function searchQueriesOnSkillsForProjects($keyword)
    {
        $keyword = '%'.$keyword.'%';

        $param[0] = $keyword;

        $sql = "select spw_project.id
                from spw_project, spw_skill, spw_skill_project
                where (spw_skill_project.project = spw_project.id) and 
                      (spw_skill_project.skill = spw_skill.id) and
                      (spw_project.status <> 4) and (spw_skill.name like ?)";

        $query = $this->db->query($sql, $param);

        if ($query->num_rows() > 0)
        {
            $user = new SPW_User_Model();
            return $user->dumpQueryIdsOnArray($query);
        }
        else
            return NULL;
    }

    /* searching for keyword in project records */
    public function searchQueriesOnProjectsForProjects($keyword)
    {
        $keyword = '%'.$keyword.'%';

        $param[0] = $keyword;
        $param[1] = $keyword;

        $sql = "select id
                from spw_project
                where (status <> 4) and
                      ((title like ?) or (description like ?))";

        $query = $this->db->query($sql, $param);

        if ($query->num_rows() > 0)
        {
            $user = new SPW_User_Model();
            return $user->dumpQueryIdsOnArray($query);
        }
        else
            return NULL;
    }

    public function assignSkillsToProject($updated_skill_names_str, $project_id)
    {
        $lSkillNames = $this->explodeCommaSeparatedSkillNamesStr($updated_skill_names_str);

        $length = count($lSkillNames);

        $tempSkill = new SPW_Skill_Model();

        for ($i = 0; $i < $length; $i++)
        {
            $skill_id = $tempSkill->existsSkillOnTable($lSkillNames[$i]);

            if (!isset($skill_id))
            {
                $tempSkill->name = $lSkillNames[$i];
                $tempSkill->website_active = 0;

                $skill_id = $tempSkill->insert($tempSkill);
            }

            if (isset($skill_id))
            {
                $data = array('skill'  => $skill_id, 
                              'project' => $project_id
                             );

                $this->db->insert('spw_skill_project', $data);

                $skill_id = NULL;
            }
            else
                throw new Exception('An error occurred on database');  
        }
    }

    public function explodeCommaSeparatedSkillNamesStr($skillNamesStr)
    {
        $listNamesArray = explode(',', $skillNamesStr);

        return $listNamesArray;
    }
}
    
?>