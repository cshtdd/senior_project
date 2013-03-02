<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UserController extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();

		/*
		$this->load->model('SPW_Project_Model');
		$this->load->model('SPW_Term_Model');
		$this->load->model('SPW_Skill_Model');
		$this->load->model('SPW_User_Model');
		$this->load->model('SPW_Project_Status_Model');
		$this->load->model('SPW_User_Summary_View_Model');
		$this->load->model('SPW_Project_Summary_View_Model');
		*/
		//$this->output->cache(60);
	}


	public function profile($user_id='')
	{
		$this->output->set_output('user profile '.$user_id);
	}

	public function current_user()
	{
		$current_user_id = getCurrentUserId($this);
		$this->profile($current_user_id);
	} 

	public function linkedIn_initiate()
	{
	 	// setup before redirecting to Linkedin for authentication.
         $linkedin_config = array(
             'appKey'       => '1ky0pyoc0rpe',
             'appSecret'    => '7WIPfrEkya3QT3LR',
             'callbackUrl'  => 'http://srprog-spr13-01.aul.fiu.edu/senior-projects/user/linkedIn_callback'
         );
        
        $this->load->library('linkedin', $linkedin_config);
        $this->linkedin->setResponseFormat(LINKEDIN::_RESPONSE_JSON);
        $token = $this->linkedin->retrieveTokenRequest();
        
        $this->session->set_flashdata('oauth_request_token_secret',$token['linkedin']['oauth_token_secret']);
        $this->session->set_flashdata('oauth_request_token',$token['linkedin']['oauth_token']);
        
        $link = "https://api.linkedin.com/uas/oauth/authorize?oauth_token=". $token['linkedin']['oauth_token'];  
        redirect($link);
	}

	public  function linkedIn_cancel() {
    
        echo 'Linkedin user cancelled login';            
    }

    public  function linkedIn_callback() {
    
         $linkedin_config = array(
                     'appKey'       => '1ky0pyoc0rpe',
                     'appSecret'    => '7WIPfrEkya3QT3LR',
                     'callbackUrl'  => 'http://srprog-spr13-01.aul.fiu.edu/senior-projects/user/linkedIn_callback'
                 );
                
        $this->load->library('linkedin', $linkedin_config);
        $this->linkedin->setResponseFormat(LINKEDIN::_RESPONSE_JSON);
         
        $oauth_token = $this->session->flashdata('oauth_request_token');
        $oauth_token_secret = $this->session->flashdata('oauth_request_token_secret');

        $oauth_verifier = $this->input->get('oauth_verifier');

        $response = $this->linkedin->retrieveTokenAccess($oauth_token, $oauth_token_secret, $oauth_verifier);
        
        // ok if we are good then proceed to retrieve the data from Linkedin
        if($response['success'] === TRUE) {
            
        // From this part onward it is up to you on how you want to store/manipulate the data 
        $oauth_expires_in = $response['linkedin']['oauth_expires_in'];
        $oauth_authorization_expires_in = $response['linkedin']['oauth_authorization_expires_in'];
        
        $response = $this->linkedin->setTokenAccess($response['linkedin']);
        $profile = $this->linkedin->profile('~:(id,first-name,last-name,picture-url)');
        $profile_connections = $this->linkedin->profile('~/connections:(id,first-name,last-name,picture-url,industry)');
        $user = json_decode($profile['linkedin']);
        $user_array = array('linkedin_id' => $user->id , 'second_name' => $user->lastName , 'profile_picture' => $user->pictureUrl , 'first_name' => $user->firstName);
        
        // For example, print out user data
        echo 'User data:';
        print '<pre>';
        print_r($user_array);
        print '</pre>';

        echo '<br><br>';
            
        // Example of company data
        $company = $this->linkedin->company('1337:(id,name,ticker,description,logo-url,locations:(address,is-headquarters))');
        echo 'Company data:';
        print '<pre>';
        print_r($company);
        print '</pre>';
        
        echo '<br><br>';
        
        echo 'Logout';
        echo '<form id="linkedin_connect_form" action="../logout" method="post">';
        echo '<input type="submit" value="Logout from LinkedIn" />';
        echo '</form>';
        
        } else {
          // bad token request, display diagnostic information
          echo "Request token retrieval failed:<br /><br />RESPONSE:<br /><br />" . print_r($response, TRUE);
        }                  
    }
}