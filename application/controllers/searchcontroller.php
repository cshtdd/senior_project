<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SearchController extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();

        $this->load->helper('project_summary_view_model');
        $this->load->helper('request');
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
        //ARGHH I spent so much time trying to figure out why search_param was empty
        //$search_param = $this->input->post('q');

        $data['title'] = 'Search';
        $data['no_results'] = true;

        if (isset($search_param) && strlen($search_param) > 0)
        {
            $search_query = $this->removeUndesiredCharacters($search_param);

            $search_query = strtolower($search_query);

            $search_query = trim($search_query);

            $results_search = $this->getResultsWithSearchParam($search_query);

            //$lProjects = $this->getProjectsWithSearchParam($search_query);
            //$lUsers = $this->getUsersWithSearchParam($search_query);

            if (isset($results_search) && count($results_search) > 0)
            {
                $data['lProjects'] = $results_search[0];
                $noProjectsFound = (count($results_search[0])==0);

                $data['lMentors'] = $results_search[1];
                $noMentorsFound = (count($results_search[1])==0);

                $data['lStudents'] = $results_search[2];
                $noStudentsFound = (count($results_search[2])==0);

                $data['no_results'] = $noProjectsFound && $noMentorsFound && $noStudentsFound;
            }
        }

       $this->load->view('search_search', $data);
    }

    public function display_mobile_search()
    {
        $this->load->view('search_mobile_search');
    }

    private function getResultsWithSearchParam($search_query)
    {
        $user_id = getCurrentUserId($this);

        $results_search = array();
        
        $lProjectsFound = array();
        $lProjectIds = array();

        $lUserIds = array();
        $lMentorsFound = array();
        $lStudentsFound = array();

        if (is_test($this))
        {
            $results_search[0] = $this->getProjectsWithSearchParamTest($search_query);
            $results_search[1] = $this->getUsersWithSearchParamTest($search_query);
            $results_search[2] = $this->getUsersWithSearchParamTest($search_query);
        }
        else
        {
                $results_search = $this->searchKeywordDatabaseQueries($search_query);

                if (count($results_search) > 0)
                {
                    if (isset($results_search[0]) && count($results_search[0]) > 0)
                        $lProjectIds = $results_search[0];

                    if (isset($results_search[1]) && count($results_search[1]) > 0)
                        $lUserIds = $results_search[1];
                }
                
                $full_search_query = $this->refineSearchQuery($search_query);

                $length = count($full_search_query);

                if (isset($full_search_query) && ($length > 0))
                {
                    $lProjectsTmp = array();
                    $lUsersTmp = array();
                    for ($i = 0; $i < $length; $i++)
                    {
                        $results_search = $this->searchKeywordDatabaseQueries($full_search_query[$i]);

                        $lProjectsTmp = $results_search[0];
                        $lUsersTmp = $results_search[1];

                        $lProjectIds = $this->combineListIds($lProjectIds, $lProjectsTmp);
                        $lUserIds = $this->combineListIds($lUserIds, $lUsersTmp);
                    }
                }

                if (isset($lProjectIds) && count($lProjectIds))
                {
                    $belongProjectIdsList = $this->SPW_User_Model->userHaveProjects($user_id);
                    $lProjectsFound = $this->SPW_Project_Summary_View_Model->prepareProjectsDataToShow($user_id, $lProjectIds, $belongProjectIdsList, FALSE);
                }
       
                $lMentorIds = array();
                $lStudentIds = array();

                if (isset($lUserIds) && count($lUserIds)>0)
                {
                    $lMentorIds = $this->SPW_User_Model->getMentorIdsFromListIds($lUserIds);
                    $lStudentIds = $this->SPW_User_Model->getStudentIdsFromListIds($lUserIds);

                    if (isset($lMentorIds) && count($lMentorIds)>0)
                    {
                        $lMentorsFound = $this->SPW_User_Summary_View_Model->prepareUsersDataToShow($user_id, $lMentorIds);
                    }

                    if (isset($lStudentIds) && count($lStudentIds)>0)
                    {
                        $lStudentsFound = $this->SPW_User_Summary_View_Model->prepareUsersDataToShow($user_id, $lStudentIds);
                    }   
                }
                
                $results_search[0] = $lProjectsFound;
                $results_search[1] = $lMentorsFound;
                $results_search[2] = $lStudentsFound;  
        }

        return $results_search;
    }

    private function searchKeywordDatabaseQueries($keyword)
    {
        $user_id = getCurrentUserId($this);
        $listProjectsIds = array();
        $lProjectsTmp = array();
        $listUsersIds = array();
        $lUsersTmp = array();

        $listProjectsIds = $this->SPW_Project_Model->searchQueriesOnProjectsForProjects($keyword);
        $lProjectsTmp = $this->SPW_Project_Model->searchQueriesOnSkillsForProjects($keyword);
        $listProjectsIds = $this->combineListIds($listProjectsIds, $lProjectsTmp);

        $lProjectsTmp = $this->SPW_Term_Model->searchQueriesOnTermForUsers($keyword);
        $listProjectsIds = $this->combineListIds($listProjectsIds, $lProjectsTmp);

        $listUsersIds = $this->SPW_User_Model->searchQueriesOnUserNamesForUsers($keyword);

        if (isset($listUsersIds) && (count($listUsersIds)>0))
        {
            $length = count($listUsersIds);

            $belongProjectIds = array();

            for ($i = 0; $i < $length; $i++)
            {
                $lProjectsTmp = $this->SPW_User_Model->userHaveProjects($listUsersIds[$i]);
                $belongProjectIds = $this->combineListIds($belongProjectIds, $lProjectsTmp);
            }

            $listProjectsIds = $this->combineListIds($listProjectsIds, $belongProjectIds);
        }

        $lUsersTmp = $this->SPW_User_Model->searchQueriesOnUserAttributesForUsers($keyword);
        $listUsersIds = $this->combineListIds($listUsersIds, $lUsersTmp);

        $lUsersTmp = $this->SPW_User_Model->searchQueriesOnSkillsForUsers($keyword);
        $listUsersIds = $this->combineListIds($listUsersIds, $lUsersTmp);

        $lUsersTmp = $this->SPW_User_Model->searchQueriesOnExperienceForUsers($keyword);
        $listUsersIds = $this->combineListIds($listUsersIds, $lUsersTmp);

        $lUsersTmp = $this->SPW_Term_Model->searchQueriesOnTermForProjects($keyword);
        $listUsersIds = $this->combineListIds($listUsersIds, $lUsersTmp);

        $res[0] = $listProjectsIds;
        $res[1] = $listUsersIds;

        return $res;
    }

    private function refineSearchQuery($searchQuery)
    {
        $res = array();

        $lSearchQueries = explode(' ', $searchQuery);

        $length = count($lSearchQueries);

        for ($i = 0; $i < $length; $i++)
        {
            if (!$this->isStopWord($lSearchQueries[$i]))
            {
                $res[] = $lSearchQueries[$i];
            }
        }

        return $res;  
    }

    private function combineListIds($list1, $list2)
    {
        if (isset($list1) && isset($list2))
        {
            $res = array_unique(array_merge($list1, $list2));
            sort($res);
            return $res;
        }
        elseif (isset($list1) && !isset($list2)) 
        {
            sort($list1);
            return $list1;
        }
        elseif (!isset($list1) && isset($list2))
        {
            sort($list2);
            return $list2;
        }
        else
        {
            return NULL;
        }    
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
        $user_summ_vm2->invite = true;

        $user3 = new SPW_User_Model();
        $user3->id = 2;
        $user3->first_name = 'Karen';
        $user3->last_name = 'Rodriguez';
        $user3->picture = 'https://si0.twimg.com/profile_images/65653569/amy-silly_bigger.png';

        $user_summ_vm3 = new SPW_User_Summary_View_Model();
        $user_summ_vm3->user = $user3;
        $user_summ_vm3->invite = true;

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

    private function removeUndesiredCharacters($search_param)
    {
        $undesiredCharacters = array('&','$','@','?','~','%','^','[',']','{','}','|',',',';',':',
                                     '=','<','>');

        if (isset($search_param))
        {
            $search_param = str_replace($undesiredCharacters,' ', $search_param); 
        }

        return $search_param;
    }

    private function isStopWord($word)
    {
        $stopWords = array('a','about','above','above','across','after','afterwards','again','against',
                           'all','almost','alone','along','already','also','although','always','am','among',
                           'amongst','amoungst','amount','an','and','another','any','anyhow','anyone',
                           'anything','anyway','anywhere','are','around','as','at','back','be','became',
                           'because','become','becomes','becoming','been','before','beforehand','behind',
                           'being','below','beside','besides','between','beyond','bill','both','bottom',
                           'but','by','call','can','cannot','cant','co','con','could','couldnt','cry',
                           'de','describe','detail','do','done','down','due','during','each','eg','eight',
                           'either','eleven','else','elsewhere','empty','enough','etc','even','ever','every',
                           'everyone','everything','everywhere','except','few','fifteen','fify','fill','find',
                           'fire','first','five','for','former','formerly','forty','found','four','from',
                           'front','full','further','get','give','go','had','has','hasnt','have','he',
                           'hence','her','here','hereafter','hereby','herein','hereupon','hers','herself',
                           'him','himself','his','how','however','hundred','ie','if','in','inc','indeed',
                           'interest','into','is','it','its','itself','keep','last','latter','latterly',
                           'least','less','ltd','made','many','may','me','meanwhile','might','mill','mine',
                           'more','moreover','most','mostly','move','much','must','my','myself','name',
                           'namely','neither','never','nevertheless','next','nine','no','nobody','none',
                           'noone','nor','not','nothing','now','nowhere','of','off','often','on','once',
                           'one','only','onto','or','other','others','otherwise','our','ours','ourselves',
                           'out','over','own','part','per','perhaps','please','put','rather','re','same',
                           'see','seem','seemed','seeming','seems','serious','several','she','should','show',
                           'side','since','sincere','six','sixty','so','some','somehow','someone','something',
                           'sometime','sometimes','somewhere','still','such','system','take','ten','than',
                           'that','the','their','them','themselves','then','thence','there','thereafter',
                           'thereby','therefore','therein','thereupon','these','they','thickv','thin','third',
                           'this','those','though','three','through','throughout','thru','thus','to','together',
                           'too','top','toward','towards','twelve','twenty','two','un','under','until','up',
                           'upon','us','very','via','was','we','well','were','what','whatever','when','whence',
                           'whenever','where','whereafter','whereas','whereby','wherein','whereupon','wherever',
                           'whether','which','while','whither','who','whoever','whole','whom','whose','why',
                           'will','with','within','without','would','yet','you','your','yours','yourself',
                           'yourselves');
        if (isset($word))
        {
            if (in_array($word, $stopWords))
                return true;
        }
 
        return false;
    }
}