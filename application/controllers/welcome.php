<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller 
{
	
	public function __construct()
	{
        parent::__construct();
        
		$this->load->model('Person_Model');
	}
	
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->view('home_index');
	}
	
	public function browse()
	{
		$this->load->view('home_browse');
	}
	
	public function login()
	{
		//publish using this
		//D:\Dropbox\Sites>xcopy /E /Y senior_project\* \\192.168.87.135\camilin\site1\
	
		$person_model = new Person_Model;
		$person_model->first_name = 'eric';
		$person_model->last_name = 'cartman';
		$person_model->age = 15;
		
		$data['model'] = $person_model;
		
		$data['first_name'] = 'loliting';
		
		$this->load->view('home_login', $data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */