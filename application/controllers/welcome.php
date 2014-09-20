<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function __construct() {
	        parent::__construct();
	        if (! $this->session->userdata('logged_in')) :
	            $this->session->set_userdata('url', uri_string());
	            redirect(base_url().'login');
	        endif;
	        $themes = get_themes();
	        if ( $this->input->get('theme') && in_array($this->input->get('theme'), $themes) )
	        	set_theme( $this->input->get('theme') );
	}
	
	public function index() {
		if ( is_profile_complete() ):
			$data['user'] = new stdClass();
			$data['user']->id = $this->session->userdata('id');
			$data['picture_upload'] =TRUE;
			$this->load->view(get_current_theme() . '/header');
			$this->load->view(get_current_theme() . '/home');
			$this->load->view(get_current_theme() . '/footer', $data);
		else:
			$this->session->set_userdata('url', uri_string());
			 redirect(base_url('profile'));
		endif;
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */