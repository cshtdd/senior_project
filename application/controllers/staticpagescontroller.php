<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class StaticPagesController extends CI_Controller 
{
    
    public function __construct()
    {
        parent::__construct();
        //$this->output->cache(60);
    }
    
    
    public function about()
    {
        // $this->load->view('template_header');
        // $this->load->view('static_pages_about');
        // $this->load->view('template_footer');

        if (is_test($this))
        {
            $data['title'] = 'TEST';
        }
        else
        {
            $data['title'] = 'About';
        }

        $this->load->view('staticpages_about', $data);
    }
    
    
}