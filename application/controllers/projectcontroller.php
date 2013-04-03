<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ProjectController extends CI_Controller 
{

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('project_summary_view_model');
        $this->load->helper('request');
        $this->load->helper('flash_message');
        load_project_summary_models($this);
        $this->load->model('SPW_Project_Details_View_Model');
        $this->load->model('spw_notification_model');
        //$this->output->cache(60);
    }

    public function past_projects()
    {
        $lProjects = $this->getPastProjectsInternal();

        if ( (!isset($lProjects) || count($lProjects) == 0) )
        {
            $no_results = true;
        }
        else
        {
            $no_results = false;
        }

        $data['title'] = 'Past Projects';
        $data['no_results'] = $no_results;
        $data['lProjects'] = $lProjects;

        $this->load->view('project_past_projects', $data);
    }


    public function current_project()
    {
        $current_project_ids = $this->getBelongProjectIds();

        //print_r($current_project_ids);

        if (!isset($current_project_ids) ||
            count($current_project_ids) <= 1)  //only one single project to display
        {

            if (!isset($current_project_ids) ||
                count($current_project_ids) == 0) //no data to display
            {
                $data['list_title'] = 'My Project';
                $data['no_results'] = true;
                $data['message'] = $this->getMessageForCurrentUserWithoutProject();

                //load the project suggestions for the current user 
                //if it is a student graduating in the current semester
                $data['lSuggestedProjects'] = $this->getSuggestedProjectsForCurrentUserWithNoProject();

                $this->load->view('project_current_project', $data);
            }
            else
            {
                $this->details($current_project_ids[0]); //we are presenting the project details view with the current project
            }
        }
        else //multiple projects to display
        {
            //get the project summary data for the selected projects
            $lProjects = $this->getCurrentProjectsSummariesWithIdsInternal($current_project_ids);

            $data['list_title'] = 'My Projects';
            $data['no_results'] = false;
            $data['lProjects'] = $lProjects;
            $this->load->view('project_current_project', $data);
        }
    }

    public function details($project_id)
    {
        //$this->output->set_output('project details '.$project_id);

        //$data['title'] = 'project details '.$project_id;
        //$this->load->view('project_details2', $data);

        $current_project_ids = $this->getBelongProjectIds();
        $project_details = $this->getProjectDetailsInternal($project_id);

        if (isset($current_project_ids) && (count($current_project_ids)>0))
        {
            $isMyProject = in_array($project_id, $current_project_ids);
        }
        else
        {
            $isMyProject = false;
        }

        //don't allow details edit after close date
        $isProjectClosed = $this->isProjectClosedInternal($project_id);

        //TODO don't allow joining/leaving after close join

        //if we are viewing the details of the current project
        //and the project is not closed
        if ($isMyProject && !$isProjectClosed) 
        {
            $resulting_view_name = 'project_details2_edit';

            //get the people suggestion for the current project
            $data['suggested_mentors'] = $this->getSuggestedMentorsForCurrentProjectInternal($project_id);
            $data['suggested_students'] = $this->getSuggestedStudentsForCurrentProjectInternal($project_id);
            $data['can_leave_project'] = $this->getCurrentUserCanLeaveProjectInternal($project_id);
        }
        else
        {
            $resulting_view_name = 'project_details2';
        }


        if (!isset($project_details))
        {
            $data['no_results'] = true;
        }
        else
        {
            $data['no_results'] = false;
        }

        $data['projectDetails'] = $project_details;
        $data['title'] = 'Project Details';

        $this->load->view($resulting_view_name, $data);
    }

    public function update()
    {
        if (!is_POST_request($this))
        {
            redirect('/');
        }
        else
        {
            //$this->output->set_output('received a valid POST request');

            //reading parameters
            $updated_project_id = $this->input->post('pid');

            $postBackUrl = $this->input->post('pbUrl');
            if (strlen($postBackUrl) == 0) $postBackUrl = '/';

            $updated_project_title = $this->input->post('text-project-title');
            $updated_project_description = $this->input->post('text-description');

            $updated_skill_names_str = $this->input->post('hidden-skill-list');
            $update_mentor_ids_str = $this->input->post('mnthidden-ids');
            $update_team_members_ids_str = $this->input->post('usrhidden-ids');

            $updated_project_max_students = $this->input->post('text-project-max-students');

            $updated_term_id = $this->input->post('dropdown-term');

            if (is_test($this))
            {
                setFlashMessage($this, 'Your project was updated');
                redirect($postBackUrl);
            }
            else
            {
                $current_user_id = getCurrentUserId($this);

                $updated_project = new SPW_Project_Model();
                $updated_project->id = $updated_project_id;

                $new_project = $updated_project->id == -1;

                $updated_project->title = $updated_project_title;
                $updated_project->description = $updated_project_description;
                $updated_project->max_students = $updated_project_max_students;

                if ($this->SPW_User_Model->isUserAStudent($current_user_id))
                {
                    $graduationTerm = $this->SPW_User_Model->getUserGraduationTerm($current_user_id);
                    $updated_project->delivery_term = $graduationTerm->id;
                }
                else
                {
                    $updated_project->delivery_term = $updated_term_id;
                }
                
                if (isset($new_project) && $new_project)
                {
                    $updated_project->proposed_by = $current_user_id;
                    $updated_project->status = 2;
                    $new_project_id = $this->SPW_Project_Model->insert($updated_project);
                    if (isset($new_project_id))
                    {
                        $this->SPW_User_Model->assignProjectToUser($new_project_id, $updated_project->proposed_by);

                        if (isset($updated_skill_names_str) && ($updated_skill_names_str != ''))
                            $this->SPW_Project_Model->assignSkillsToProject($updated_skill_names_str, $new_project_id);

                        setFlashMessage($this, 'Your project was created');
                        $newPostBackUrl = $this->transfromCreateToDetails($postBackUrl, $new_project_id);
                        redirect($newPostBackUrl); 
                    }
                    else
                    {
                        setFlashMessage($this, 'An error ocurred. Try again later');
                        redirect($postBackUrl);
                    }  
                }
                else
                {
                    $project = $this->SPW_Project_Model->getProjectInfo($updated_project->id);

                    if (isset($project))
                    {
                        $project->title = $updated_project->title;
                        $project->description = $updated_project->description;
                        $project->max_students = $updated_project->max_students;
                        $project->delivery_term = $updated_project->delivery_term;

                        $this->SPW_Project_Model->update($project); 

                        if (isset($updated_skill_names_str) && ($updated_skill_names_str != ''))
                            $this->SPW_Project_Model->updateProjectSkills($updated_skill_names_str, $project->id);

                        $this->SPW_Project_Model->updateProjectUsers($update_mentor_ids_str, $update_team_members_ids_str, $project->id);
                    }
                    else
                    {
                        setFlashMessage($this, 'An error ocurred. Try again later');
                    }
                
                    redirect($postBackUrl);
                }
            }
        }
    }

    public function leave()
    {
        if (!is_POST_request($this))
        {
            redirect('/');
        }
        else
        {
            $postBackUrl = $this->input->post('pbUrl');
            if (strlen($postBackUrl) == 0) $postBackUrl = '/';

            $projectId = $this->input->post('pid');
            $currentUserId = getCurrentUserId($this);

            if($this->leaveProjectInternal($projectId, $currentUserId))
            {
                $project_team = $this->spw_project_model->get_team_members($projectId);
                for($i = 0; $i < count($project_team); $i++)
                {
                    $member_id = $project_team[$i];
                    if($member_id != $currentUserId)
                    {
                        $this->spw_notification_model->create_leave_notification_for_user($currentUserId, $member_id, $projectId);
                    }
                }
                
                setFlashMessage($this, 'You have left the project');    
            }
            else
            {
                 setFlashMessage($this, "Error: you can't leave this project");  
            }
            redirect($postBackUrl);
        }
    }

    public function join()
    {
        if (!is_POST_request($this))
        {
            redirect('/');
        }
        else
        {
            $postBackUrl = $this->input->post('pbUrl');
            if (strlen($postBackUrl) == 0) 
                $postBackUrl = '/';

            $project_id = $this->input->post('pid');
            $currentUserId = getCurrentUserId($this);

            $result = $this->spw_notification_model->get_active_join_notification_for_project_from_user($currentUserId, $project_id);
            if(!$result)
            {
                $project_team = $this->spw_project_model->get_team_members($project_id);
                for($i = 0; $i < count($project_team); $i++)
                {
                    $member_id = $project_team[$i];
                    if($member_id != $currentUserId){
                        $this->spw_notification_model->create_join_notification_for_user($currentUserId,$member_id, $project_id);
                    }
                }

                setFlashMessage($this, 'Your join request has been sent');
            }
            else
            {
                $project_title = $this->spw_project_model->get_project_title($project_id);
                $msg = 'You already sent a notification for the project '.$project_title;
                setFlashMessage($this, $msg);
            }
            redirect($postBackUrl);
        }
    }

    public function create_new_project()
    {
        if (is_test($this))
        {
            $term1 = new SPW_Term_Model();
            $term1->id = 1;
            $term1->name = 'Spring 2013';
            $term1->description = 'Spring 2013';
            $term1->start_date = '1-8-2013';
            $term1->end_date = '4-26-2013';

            $term2 = new SPW_Term_Model();
            $term2->id = 2;
            $term2->name = 'Summer 2013';
            $term2->description = 'Summer 2013';
            $term2->start_date = '4-26-2013';
            $term2->end_date = '1-8-2013';

            $term3 = new SPW_Term_Model();
            $term3->id = 3;
            $term3->name = 'Fall 2013';
            $term3->description = 'Fall 2013';
            $term3->start_date = '1-8-2013';
            $term3->end_date = '12-28-2013';

            $lTerms = array(
                    $term1,
                    $term2,
                    $term3
                );

            //TODO redirect to home if not logged in
            $currentUserId = getCurrentUserId($this);

            //TODO read this from the DB eventually
            $projStatus = new SPW_Project_Status_Model();
            $projStatus->id = 1;
            $projStatus->name = 'created';

            $project1 = new SPW_Project_Model();
            $project1->id = -1;
            $project1->title = '';
            $project1->description = '';
            $project1->status = 1;

            //TODO get the current user term from the DB
            /*
            $term1 = new SPW_Term_Model();
            $term1->id = 1;
            $term1->name = 'Spring 2013';
            $term1->description = 'Spring 2013';
            $term1->start_date = '1-8-2013';
            $term1->end_date = '4-26-2013';
            */

            //TODO get the current user data from the db
            $user1 = new SPW_User_Model();
            $user1->id = getCurrentUserId($this);
            $user1->first_name = 'Phillippe';
            $user1->last_name = 'Me';
            $user1->picture = 'https://si0.twimg.com/profile_images/3033419400/07e622e1fb86372b76a2aa605e496aaf_bigger.jpeg';

            $current_user_vm = new SPW_User_Summary_View_Model();
            $current_user_vm->user = $user1;


            $project_details = new SPW_Project_Details_View_Model();
            $project_details->project = $project1;
            $project_details->term = $term1;
            $project_details->proposedBySummary = $current_user_vm;
            $project_details->displayJoin = false;
            $project_details->displayLeave = false;
            //$project_details->onlyShowUserTerm = true;
            $project_details->lTerms = $lTerms;

            $data['projectDetails'] = $project_details;
            $data['title'] = 'Create Project';
            $data['creating_new'] = true;

            $this->load->view('project_details2_edit', $data);
        }
        else
        {
            if(isUserLoggedIn($this))
            {
                $tempTerm = new SPW_Term_Model();

                $project_details = new SPW_Project_Details_View_Model();

                $currentUserId = getCurrentUserId($this);

                $project_details->onlyShowUserTerm = $this->SPW_User_Model->isUserAStudent($currentUserId);

                $project1 = new SPW_Project_Model();
                $project1->id = -1;
                $project1->title = '';
                $project1->proposed_by = $currentUserId;
                $project1->description = '';
                $project1->status = 1;
                $project1->max_students = 5;

                $currentTerm = $tempTerm->getCurrentTermInfo();

                if (isset($project_details->onlyShowUserTerm) && ($project_details->onlyShowUserTerm))
                {
                    $term1 = $this->SPW_User_Model->getUserGraduationTerm($currentUserId);
                }
                else
                {
                    $lTerms = $this->SPW_Term_Model->getAllValidTerms();
                }

                $lUsers = $this->SPW_User_Summary_View_Model->prepareUsersDataToShow($currentUserId, array($currentUserId));
                $current_user_vm = $lUsers[0];

                $project_details->project = $project1;
                if (isset($term1))
                    $project_details->term = $term1;
                else
                    $project_details->term = $currentTerm;
                if (isset($lTerms) && count($lTerms)>0)
                    $project_details->lTerms = $lTerms;
                $project_details->proposedBySummary = $current_user_vm;
                $project_details->displayJoin = false;
                $project_details->displayLeave = false;

                $data['projectDetails'] = $project_details;
                $data['title'] = 'Create Project';
                $data['creating_new'] = true;

                $this->load->view('project_details2_edit', $data);
            }
            else
            {
                redirect('login','refresh');
            }
        }
    }

    public function display_list_of_projects_to_invite_user($user_id)
    {
        //print_r(uri_string());

        $current_project_ids = $this->getBelongProjectIds();
        $userSummaryToInvite = $this->getUserSummaryWithIdInternal($user_id);
        //print_r($current_project_ids);

        //if only one single project to display this URL shouldn't have been visited
        if (!isset($userSummaryToInvite) ||
            !isset($current_project_ids) ||
            count($current_project_ids) <= 1)  
        {
            redirect('/');            
        }
        else //multiple projects to display
        {
            //get the project summary data for the selected projects
            $lProjects = $this->getCurrentProjectsSummariesWithIdsInternal($current_project_ids);

            $data['list_title'] = 'Select a project to invite '.$userSummaryToInvite->getFullName();
            $data['no_results'] = false;
            $data['lProjects'] = $lProjects;
            $data['inviteUserSummary'] = $userSummaryToInvite;
            $data['hideCreateProject'] = true;
            $this->load->view('project_current_project', $data);
        }
    }

    private function getBelongProjectIds()
    {
        if (is_test($this))
        {
            return array(100, 101);
        }
        else
        {
            return $this->SPW_User_Model->userHaveProjectsRegardlessStatus(getCurrentUserId($this));
        }
    }

    private function getPastProjectsInternal()
    {
        if (is_test($this))
        {
            return $this->getPastProjectsInternalTest();
        }
        else
        {
            $user_id = getCurrentUserId($this);
            $lProjectIds = $this->SPW_Project_Model->getPastProjects();
            $lProjects = $this->SPW_Project_Summary_View_Model->prepareProjectsDataToShow($user_id, $lProjectIds, NULL, TRUE);
            return $lProjects;
        }
    }
    private function getPastProjectsInternalTest()
    {
        $projStatus = new SPW_Project_Status_Model();
        $projStatus->id = 2;
        $projStatus->name = 'Closed';       

        $term1 = new SPW_Term_Model();
        $term1->id = 2;
        $term1->name = 'Summer 2012';
        $term1->description = 'Summer 2012';
        $term1->start_date = '5-1-2012';
        $term1->end_date = '8-24-2012';


        $skill1 = new SPW_Skill_Model();
        $skill1->id = 0;
        $skill1->name = 'Cobol';

        $skill2 = new SPW_Skill_Model();
        $skill2->id = 1;
        $skill2->name = 'Matlab';

        $skill3 = new SPW_Skill_Model();
        $skill3->id = 2;
        $skill3->name = 'Gopher';

        $skill4 = new SPW_Skill_Model();
        $skill4->id = 3;
        $skill4->name = 'bash';

        $lSkills1 = array(
            $skill1,
            $skill2,
            $skill3,
            $skill4
        );


        $skill5 = new SPW_Skill_Model();
        $skill5->id = 4;
        $skill5->name = 'Modem At commands';

        $skill6 = new SPW_Skill_Model();
        $skill6->id = 5;
        $skill6->name = 'ISAPI';

        $lSkills2 = array(
            $skill5,
            $skill6
        );


        $user1 = new SPW_User_Model();
        $user1->id = 0;
        $user1->first_name = 'Steven';
        $user1->last_name = 'Luis Sr.';
        $user1->picture = 'https://si0.twimg.com/profile_images/635660229/camilin87_bigger.jpg';

        $user_summ_vm1 = new SPW_User_Summary_View_Model();
        $user_summ_vm1->user = $user1;

        $user2 = new SPW_User_Model();
        $user2->id = 1;
        $user2->first_name = 'Lolo';
        $user2->last_name = 'Gonzalez Sr.';
        $user2->picture = 'https://si0.twimg.com/profile_images/635646997/cashproductions_bigger.jpg';

        $user_summ_vm2 = new SPW_User_Summary_View_Model();
        $user_summ_vm2->user = $user2;

        $user3 = new SPW_User_Model();
        $user3->id = 2;
        $user3->first_name = 'Karen';
        $user3->last_name = 'Rodriguez Sr.';
        $user3->picture = 'https://si0.twimg.com/profile_images/1282173124/untitled-158-2_bigger.jpg';

        $user_summ_vm3 = new SPW_User_Summary_View_Model();
        $user_summ_vm3->user = $user3;

        $user4 = new SPW_User_Model();
        $user4->id = 3;
        $user4->first_name = 'Gregory';
        $user4->last_name = 'Zhao Sr.';
        $user4->picture = 'https://si0.twimg.com/profile_images/1501070030/John_2011_1_500x500_bigger.png';

        $user_summ_vm4 = new SPW_User_Summary_View_Model();
        $user_summ_vm4->user = $user4;




        $project1 = new SPW_Project_Model();
        $project1->id = 1;
        $project1->title = 'Cobol Free Music Sharing Platform';
        $project1->description = 'Poor students need an easy way to access all the music in the world for free.';
        $project1->status = $projStatus;

        $project_summ_vm1 = new SPW_Project_Summary_View_Model();
        $project_summ_vm1->project = $project1;
        $project_summ_vm1->term = $term1;
        $project_summ_vm1->lSkills = $lSkills1;
        $project_summ_vm1->lMentorSummaries = array($user_summ_vm1);
        $project_summ_vm1->proposedBySummary = $user_summ_vm3;


        $project2 = new SPW_Project_Model();
        $project2->id = 2;
        $project2->title = 'Dialup Moodle on Facebook';
        $project2->description = 'Poor students need an easy way to access all the music in the world for free. This Project will make every student really happy.';
        $project2->status = $projStatus;

        $project_summ_vm2 = new SPW_Project_Summary_View_Model();
        $project_summ_vm2->project = $project2;
        $project_summ_vm2->term = $term1;
        $project_summ_vm2->lSkills = $lSkills2;
        $project_summ_vm2->lMentorSummaries = array($user_summ_vm1);
        $project_summ_vm2->lTeamMemberSummaries = array($user_summ_vm4);
        $project_summ_vm2->proposedBySummary = $user_summ_vm2;

        $lProjects = array(
            $project_summ_vm1,
            $project_summ_vm2
        );

        return $lProjects;
    }

    private function getCurrentProjectsSummariesWithIdsInternal($project_ids)
    {
        if (is_test($this))
        {
            return $this->getCurrentProjectsSummariesWithIdsInternalTest($project_ids);
        }
        else
        {   
            $user_id = getCurrentUserId($this);
            return $this->SPW_Project_Summary_View_Model->prepareProjectsDataToShow($user_id, $project_ids, $project_ids, FALSE);
        }
    }
    private function getCurrentProjectsSummariesWithIdsInternalTest($project_ids)
    {
        $projStatus = new SPW_Project_Status_Model();
        $projStatus->id = 1;
        $projStatus->name = 'Open';


        $project1 = new SPW_Project_Model();
        $project1->id = $project_ids[0];
        $project1->title = 'Free Music Sharing Platform';
        $project1->description = 'Poor students need an easy way to access all the music in the world for free.';
        $project1->status = $projStatus;

        $project2 = new SPW_Project_Model();
        $project2->id = $project_ids[1];
        $project2->title = 'Moodle on Facebook';
        $project2->description = 'Poor students need an easy way to access all the music in the world for free. This Project will make every student really happy.';
        $project2->status = $projStatus;



        $project_summ_vm1 = new SPW_Project_Summary_View_Model();
        $project_summ_vm1->project = $project1;

        $project_summ_vm2 = new SPW_Project_Summary_View_Model();
        $project_summ_vm2->project = $project2;

        $lProjects = array(
            $project_summ_vm1,
            $project_summ_vm2
        );

        return $lProjects;
    }

    private function getMessageForCurrentUserWithoutProject()
    {
        $message = '';

        if (is_test($this))
        {
            $message = 'You have not joined a project yet...';
        }
        else
        {
            if ($this->SPW_User_Model->isUserAStudent(getCurrentUserId($this)))
            {
                $term = $this->SPW_User_Model->getUserGraduationTerm(getCurrentUserId($this));
                $closedRequestsDate = date('D, d M Y', strtotime($term->closed_requests));
                $message = 'You have not joined to a project yet. Please do so before '.$closedRequestsDate;
            }
            else
            {
                $message = 'You have not joined to a project yet...';
            }
        }

        return $message;
    }

    private function getSuggestedProjectsForCurrentUserWithNoProject()
    {
        if (is_test($this))
        {
            return $this->getSuggestedProjectsForCurrentUserWithNoProjectTest();
        }
        else
        {
            $user_id = getCurrentUserId($this);
            $listSuggestedProjectIds = $this->SPW_User_Model->getSuggestedProjectsGivenCurrentUser($user_id);
            $belongProjectIdsList = $this->SPW_User_Model->userHaveProjects($user_id);
            return $this->SPW_Project_Summary_View_Model->prepareProjectsDataToShow($user_id, $listSuggestedProjectIds, $belongProjectIdsList, FALSE);
        }
    }
    
    private function getSuggestedProjectsForCurrentUserWithNoProjectTest()
    {
        $projStatus = new SPW_Project_Status_Model();
        $projStatus->id = 1;
        $projStatus->name = 'Open';

        $term1 = new SPW_Term_Model();
        $term1->id = 1;
        $term1->name = 'Spring 2013';
        $term1->description = 'Spring 2013';
        $term1->start_date = '1-8-2013';
        $term1->end_date = '4-26-2013';


        $skill1 = new SPW_Skill_Model();
        $skill1->id = 0;
        $skill1->name = 'Ruby on Rails';

        $skill2 = new SPW_Skill_Model();
        $skill2->id = 1;
        $skill2->name = 'jQuery';

        $skill3 = new SPW_Skill_Model();
        $skill3->id = 2;
        $skill3->name = 'HTML';

        $skill4 = new SPW_Skill_Model();
        $skill4->id = 3;
        $skill4->name = 'CSS';

        $lSkills1 = array(
            $skill1,
            $skill2,
            $skill3,
            $skill4
        );


        $skill5 = new SPW_Skill_Model();
        $skill5->id = 4;
        $skill5->name = 'PHP';

        $skill6 = new SPW_Skill_Model();
        $skill6->id = 5;
        $skill6->name = 'Moodle';

        $lSkills2 = array(
            $skill5,
            $skill6
        );


        $user1 = new SPW_User_Model();
        $user1->id = 0;
        $user1->first_name = 'Steven';
        $user1->last_name = 'Luis';

        $user_summ_vm1 = new SPW_User_Summary_View_Model();
        $user_summ_vm1->user = $user1;

        $user2 = new SPW_User_Model();
        $user2->id = 1;
        $user2->first_name = 'Lolo';
        $user2->last_name = 'Gonzalez';

        $user_summ_vm2 = new SPW_User_Summary_View_Model();
        $user_summ_vm2->user = $user2;

        $user3 = new SPW_User_Model();
        $user3->id = 2;
        $user3->first_name = 'Karen';
        $user3->last_name = 'Rodriguez';

        $user_summ_vm3 = new SPW_User_Summary_View_Model();
        $user_summ_vm3->user = $user3;

        $user4 = new SPW_User_Model();
        $user4->id = 3;
        $user4->first_name = 'Gregory';
        $user4->last_name = 'Zhao';

        $user_summ_vm4 = new SPW_User_Summary_View_Model();
        $user_summ_vm4->user = $user4;




        $project1 = new SPW_Project_Model();
        $project1->id = 1;
        $project1->title = 'Free Music Sharing Platform';
        $project1->description = 'Poor students need an easy way to access all the music in the world for free.';
        $project1->status = $projStatus;

        $project_summ_vm1 = new SPW_Project_Summary_View_Model();
        $project_summ_vm1->project = $project1;
        $project_summ_vm1->term = $term1;
        $project_summ_vm1->lSkills = $lSkills1;
        $project_summ_vm1->lMentorSummaries = array($user_summ_vm1);
        $project_summ_vm1->proposedBySummary = $user_summ_vm3;
        $project_summ_vm1->displayJoin = true;
        $project_summ_vm1->displayLeave = false;


        $project2 = new SPW_Project_Model();
        $project2->id = 2;
        $project2->title = 'Moodle on Facebook';
        $project2->description = 'Poor students need an easy way to access all the music in the world for free. This Project will make every student really happy.';
        $project2->status = $projStatus;

        $project_summ_vm2 = new SPW_Project_Summary_View_Model();
        $project_summ_vm2->project = $project2;
        $project_summ_vm2->term = $term1;
        $project_summ_vm2->lSkills = $lSkills2;
        $project_summ_vm2->lMentorSummaries = array($user_summ_vm1);
        $project_summ_vm2->lTeamMemberSummaries = array($user_summ_vm4);
        $project_summ_vm2->proposedBySummary = $user_summ_vm2;
        $project_summ_vm2->displayJoin = true;
        $project_summ_vm2->displayLeave = false;

        $lProjects = array(
            $project_summ_vm1,
            $project_summ_vm2,
            $project_summ_vm1
        );

        return $lProjects;
    }

    private function getProjectDetailsInternal($project_id)
    {
        if (is_test($this))
        {
            return $this->getProjectDetailsInternalTest($project_id);
        }
        else
        {        
            $user_id = getCurrentUserId($this);
            $tempDetails = new SPW_Project_Details_View_Model();
            $projectDetails = $tempDetails->prepareProjectDetailsDataToShow($user_id, $project_id);
            if (isset($projectDetails))
                return $projectDetails;
            else
                return NULL;
        }
    }
    private function getProjectDetailsInternalTest($project_id)
    {
        $term1 = new SPW_Term_Model();
        $term1->id = 1;
        $term1->name = 'Spring 2013';
        $term1->description = 'Spring 2013';
        $term1->start_date = '1-8-2013';
        $term1->end_date = '4-26-2013';

        $term2 = new SPW_Term_Model();
        $term2->id = 2;
        $term2->name = 'Summer 2013';
        $term2->description = 'Summer 2013';
        $term2->start_date = '4-26-2013';
        $term2->end_date = '1-8-2013';

        $term3 = new SPW_Term_Model();
        $term3->id = 3;
        $term3->name = 'Fall 2013';
        $term3->description = 'Fall 2013';
        $term3->start_date = '1-8-2013';
        $term3->end_date = '12-28-2013';

        $lTerms = array(
                $term1,
                $term2,
                $term3
            );


        $projStatus = new SPW_Project_Status_Model();
        $projStatus->id = 1;
        $projStatus->name = 'Open';

        /*
        $term1 = new SPW_Term_Model();
        $term1->id = 1;
        $term1->name = 'Spring 2013';
        $term1->description = 'Spring 2013';
        $term1->start_date = '1-8-2013';
        $term1->end_date = '4-26-2013';
        */

        $skill1 = new SPW_Skill_Model();
        $skill1->id = 0;
        $skill1->name = 'Ruby on Rails';

        $skill2 = new SPW_Skill_Model();
        $skill2->id = 1;
        $skill2->name = 'jQuery';

        $skill3 = new SPW_Skill_Model();
        $skill3->id = 2;
        $skill3->name = 'HTML';

        $skill4 = new SPW_Skill_Model();
        $skill4->id = 3;
        $skill4->name = 'CSS';

        $lSkills1 = array(
            $skill1,
            $skill2,
            $skill3,
            $skill4
        );


        $user1 = new SPW_User_Model();
        $user1->id = 0;
        $user1->first_name = 'Steven';
        $user1->last_name = 'Luis';
        $user1->picture = 'https://si0.twimg.com/profile_images/635660229/camilin87_bigger.jpg';

        $user_summ_vm1 = new SPW_User_Summary_View_Model();
        $user_summ_vm1->user = $user1;

        $user3 = new SPW_User_Model();
        $user3->id = 2;
        $user3->first_name = 'Karen';
        $user3->last_name = 'Rodriguez';
        $user3->picture = 'https://si0.twimg.com/profile_images/635646997/cashproductions_bigger.jpg';

        $user_summ_vm3 = new SPW_User_Summary_View_Model();
        $user_summ_vm3->user = $user3;

        $user4 = new SPW_User_Model();
        $user4->id = 4;
        $user4->first_name = 'Ming';
        $user4->last_name = 'Zhao';
        $user4->picture = 'https://si0.twimg.com/profile_images/2623528696/iahn1tuacgx31qmlvia3.jpeg';

        $user_summ_vm4 = new SPW_User_Summary_View_Model();
        $user_summ_vm4->user = $user4;

        $user5 = new SPW_User_Model();
        $user5->id = getCurrentUserId($this);
        $user5->first_name = 'John';
        $user5->last_name = 'Siracusa';
        $user5->picture = 'https://si0.twimg.com/profile_images/1501070030/John_2011_1_500x500_bigger.png';

        $user_summ_vm5 = new SPW_User_Summary_View_Model();
        $user_summ_vm5->user = $user5;


        $project1 = new SPW_Project_Model();
        $project1->id = $project_id;
        $project1->title = 'Free Music Sharing Platform';
        $project1->description = 'Poor students need an easy way to access all the music in the world for free.';
        $project1->status = $projStatus;

        $project_summ_vm1 = new SPW_Project_Details_View_Model();
        //$project_summ_vm1->onlyShowUserTerm = true;
        $project_summ_vm1->project = $project1;
        $project_summ_vm1->term = $term1;
        $project_summ_vm1->lSkills = $lSkills1;
        $project_summ_vm1->lMentorSummaries = array($user_summ_vm1, $user_summ_vm4);
        $project_summ_vm1->lTeamMemberSummaries = array($user_summ_vm4, $user_summ_vm5, $user_summ_vm1, $user_summ_vm3);
        $project_summ_vm1->proposedBySummary = $user_summ_vm3;
        $project_summ_vm1->displayJoin = false;
        $project_summ_vm1->displayLeave = true;
        $project_summ_vm1->lTerms = $lTerms;

        return $project_summ_vm1;
    }

    /* gets a list of SPW_User_Summary_View_Model of suggested students */
    private function getSuggestedStudentsForCurrentProjectInternal($project_id)
    {
        if (is_test($this))
        {
            return $this->getSuggestedUsersForCurrentProjectInternalTest($project_id);
        }
        else
        {
            $user_id = getCurrentUserId($this);
            $lStudents = array();
            $lStudentIds = $this->SPW_Project_Model->getSuggestedStudentsGivenMyProject($project_id);
            $lStudents = $this->SPW_User_Summary_View_Model->prepareUsersDataToShow($user_id, $lStudentIds);
            if (isset($lStudents) && (count($lStudents) > 0))
                return $lStudents;
            else
                return NULL;
        }
    }

    /* gets a list of SPW_User_Summary_View_Model of suggested mentors */
    private function getSuggestedMentorsForCurrentProjectInternal($project_id)
    {
        if (is_test($this))
        {
            return $this->getSuggestedUsersForCurrentProjectInternalTest($project_id);
        }
        else
        {
            $user_id = getCurrentUserId($this);
            $lMentors = array();
            $lMentorIds = $this->SPW_Project_Model->getSuggestedMentorsGivenMyProject($project_id);
            $lMentors = $this->SPW_User_Summary_View_Model->prepareUsersDataToShow($user_id, $lMentorIds);
            if (isset($lMentors) && (count($lMentors) > 0))
                return $lMentors;
            else
                return NULL;
        }
    }

    private function getSuggestedUsersForCurrentProjectInternalTest($project_id)
    {
        $user1 = new SPW_User_Model();
        $user1->id = getCurrentUserId($this);
        $user1->first_name = 'Phillippe';
        $user1->last_name = 'Me';
        $user1->picture = 'https://si0.twimg.com/profile_images/3033419400/07e622e1fb86372b76a2aa605e496aaf_bigger.jpeg';

        $user_summ_vm1 = new SPW_User_Summary_View_Model();
        $user_summ_vm1->user = $user1;
        $user_summ_vm1->invite = true; 


        $user2 = new SPW_User_Model();
        $user2->id = 1;
        $user2->first_name = 'Lolo';
        $user2->last_name = 'Gonzalez Sr.';
        $user2->picture = 'https://si0.twimg.com/profile_images/362705903/dad_bigger.jpg';

        $user_summ_vm2 = new SPW_User_Summary_View_Model();
        $user_summ_vm2->user = $user2;
        $user_summ_vm2->invite = true; 


        $user4 = new SPW_User_Model();
        $user4->id = 3;
        $user4->first_name = 'Gregory';
        $user4->last_name = 'Zhao Sr.';
        $user4->picture = 'https://si0.twimg.com/profile_images/556789661/pigman_bigger.jpg';

        $user_summ_vm4 = new SPW_User_Summary_View_Model();
        $user_summ_vm4->user = $user4;
        $user_summ_vm4->invite = true;

        $suggestedUsers = array(
                $user_summ_vm2,
                $user_summ_vm1,
                $user_summ_vm4
            );

        return $suggestedUsers;
    }

    private function isProjectClosedInternal($project_id)
    {
        if (is_test($this))
        {
            return true && false;
        }
        else
        {
            $term = $this->SPW_Project_Model->getProjectDeliveryTerm($project_id);

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
    }

    private function leaveProjectInternal($project_id, $user_id)
    {
        if (is_test($this))
        {
            return true;
        }
        else
        {
            return $this->SPW_User_Model->leaveProjectOnDatabase($user_id, $project_id);
        }
    }


    private function getCurrentUserCanLeaveProjectInternal($project_id)
    {
        if (is_test($this))
        {
            return true;
        }
        else
        {
            $user_id = getCurrentUserId($this);
            return $this->SPW_User_Model->canUserLeaveProject($user_id, $project_id);
        }
    }

    private function getUserSummaryWithIdInternal($userId)
    {
        if (is_test($this))
        {
            return $this->getUserSummaryWithIdInternalTest($userId);
        }
        else
        {
            if (isset($user_id))
            {
                $current_user_id = getCurrentUserId($this);
                $lUsers  = $this->SPW_User_Summary_View_Model->prepareUsersDataToShow($current_user_id, array($userId));
                if (isset($lUsers) && count($lUsers) > 0)
                {
                    return $lUsers[0];
                }
                else
                {
                    throw new Expcetion('User Id not found');
                }
            }
        }
    }
    private function getUserSummaryWithIdInternalTest($userId)
    {
        $user1 = new SPW_User_Model();
        $user1->id = $userId;
        $user1->first_name = 'Phillippe';
        $user1->last_name = 'Me';
        $user1->picture = 'https://si0.twimg.com/profile_images/3033419400/07e622e1fb86372b76a2aa605e496aaf_bigger.jpeg';

        $user_summ_vm1 = new SPW_User_Summary_View_Model();
        $user_summ_vm1->user = $user1;
        $user_summ_vm1->invite = true; 

        return $user_summ_vm1;
    }

    private function transfromCreateToDetails($postBackUrl, $new_project_id)
    {
        $newPostBackUrl = str_replace('create', $new_project_id, $postBackUrl);

        return $newPostBackUrl;
    }
}