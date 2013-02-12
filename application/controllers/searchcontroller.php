<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SearchController extends CI_Controller 
{
	/*
	public function __construct()
	{
        parent::__construct();
		$this->output->cache(60);
	}
	*/
	
	public function search($search_param='')
	{
		if (isset($search_param) && strlen($search_param) > 0)
		{
			$search_query = urldecode($search_param);
		
			//$this->output->set_output("new search query: '$search_query'");
			$data['no_results'] = false;
		}
		else
		{
			//$this->output->set_output('new no search query');
			$data['no_results'] = true;
		}

		$this->load->view('search_controller_search', $data);
	}
	
	
}