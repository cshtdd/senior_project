<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SearchController extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();

        $this->load->helper('project_summary_view_model');
        load_project_summary_models($this);

        //$this->output->cache(60);
    }

    public function search_string()
    {
        $search_param = $this->input->get('q', TRUE);
        $redirectUrl = base_url().'search/'.$search_param;
        redirect($redirectUrl);
    }  

    public function search($search_param='')
    {
        $data['title'] = 'Search';
        $data['no_results'] = true;

        if (isset($search_param) && strlen($search_param) > 0)
        {
            $search_query = urldecode($search_param);

            $lProjects = $this->getProjectsWithSearchParam($search_query);
            $lUsers = $this->getUsersWithSearchParam($search_query);

            if (isset($lProjects) && count($lProjects) > 0)
            {
                $data['lProjects'] = $lProjects;
                $data['lUsers'] = $lUsers;
                $data['no_results'] = false;
            }
        }

<<<<<<< HEAD
			if (isset($lProjects) && count($lProjects) > 0)
			{
				$data['lProjects'] = $lProjects;
				$data['lUsers'] = $lUsers;
				$data['no_results'] = false;
			}
		}
=======
        $this->load->view('search_search', $data);
    }
>>>>>>> origin/master

    private function getProjectsWithSearchParam($search_query)
    {
        $lProjectsFound = array();
        $lProjectIds = array();

        if (is_test($this))
        {
            return $this->getProjectsWithSearchParamTest($search_query);
        }
        else
        {
            $lProjectIds = $this->searchKeywordDatabaseQueriesForProjects($search_query);



        }
    }

<<<<<<< HEAD


			$full_search_query = $this->refineSearchQuery(explode(' ', $search_query));

		}
	}
=======
    private function getUsersWithSearchParam($search_query)
    {
        if (is_test($this))
        {
            return $this->getUsersWithSearchParamTest($search_query);
        }
        else
        {
            throw Exception('not implemented');
        }
    }
>>>>>>> origin/master

    private function dumpQueryIdsOnArray($query)
    {
        $res = array();

<<<<<<< HEAD
	

	private function searchKeywordDatabaseQueriesForProjects($keyword)
	{
		$user_id = getCurrentUserId($this);
		$listIds = array();
		$lTmp = array();

		$listIds = $this->SPW_Project_Model->searchQueriesOnProjectsForProjects($keyword, $user_id);

		$lTmp = $this->SPW_Project_Model->searchQueriesOnUsersForProjects($keyword, $user_id);

		$listIds = $this->combineListIds($listIds, $lTmp);
=======
        if (isset($query))
        {
            foreach ($query->result() as $row)
            {
                $res[] = $row->id;
            }
        }

        return $res;

    }

    private function searchKeywordDatabaseQueriesForProjects($keyword)
    {
        $user_id = getCurrentUserId($this);
        $listIds = array();

        if ($this->SPW_User_Model->isUserAStudent($user_id))
        {
            $term = $this->SPW_User_Model->getUserGraduationTerm($user_id);

            $param[0] = $term->id;

            $sql = 'select id
                    from spw_project
                    where (delivery_term = ?) and (status = 3) and
                    ((title = '.$term->id.') or (description = '.$term->id.'))';

            $query = $this->db->query($sql);

            $listIds = $this->dumpQueryIdsOnArray($query);

>>>>>>> origin/master


		


	}

                    $full_search_query = $this->refineSearchQuery(explode(' ', $search_query));
        }
        else
        {

<<<<<<< HEAD
	

=======
        }
    }

    private function refineSearchQuery($lSearchQuery)
    {
>>>>>>> origin/master

    }

    private function combineListIds($list1, $list2)
    {
        $res = array_unique(array_merge($list1, $list2));
        sort($res);
        return $res;
    }

    private function getProjectsWithSearchParamTest($search_query)
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
        $project_summ_vm2->displayJoin = false;
        $project_summ_vm2->displayLeave = true;

        $lProjects = array(
            $project_summ_vm1, 
            $project_summ_vm2,
            $project_summ_vm1, 
            $project_summ_vm2 /*, 
            $project_summ_vm1, 
            $project_summ_vm2,
            $project_summ_vm1, 
            $project_summ_vm2,
            $project_summ_vm1, 
            $project_summ_vm2 */
        );

        return $lProjects;
    }

    private function getUsersWithSearchParamTest($search_query)
    {
        $user1 = new SPW_User_Model();
        $user1->id = 0;
        $user1->first_name = 'Steven';
        $user1->last_name = 'Luis';
        $user1->picture = 'https://si0.twimg.com/profile_images/635660229/camilin87_bigger.jpg';

        $user_summ_vm1 = new SPW_User_Summary_View_Model();
        $user_summ_vm1->user = $user1;

        $user2 = new SPW_User_Model();
        $user2->id = 1;
        $user2->first_name = 'Lolo';
        $user2->last_name = 'Gonzalez';
        $user2->picture = 'https://si0.twimg.com/profile_images/65653569/amy-silly_bigger.png';

        $user_summ_vm2 = new SPW_User_Summary_View_Model();
        $user_summ_vm2->user = $user2;

        $user3 = new SPW_User_Model();
        $user3->id = 2;
        $user3->first_name = 'Karen';
        $user3->last_name = 'Rodriguez';
        $user3->picture = 'https://si0.twimg.com/profile_images/65653569/amy-silly_bigger.png';

        $user_summ_vm3 = new SPW_User_Summary_View_Model();
        $user_summ_vm3->user = $user3;

        $user4 = new SPW_User_Model();
        $user4->id = 3;
        $user4->first_name = 'Gregory';
        $user4->last_name = 'Zhao';
        $user3->picture = 'https://si0.twimg.com/profile_images/2830125611/3532254801ca705a5ffc995bded62d13_bigger.png';

        $user_summ_vm4 = new SPW_User_Summary_View_Model();
        $user_summ_vm4->user = $user4;

        $result = array(
                $user_summ_vm1,
                $user_summ_vm2,
                $user_summ_vm3,
                $user_summ_vm4
            );
        return $result;
    }
}