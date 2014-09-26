<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function __construct() {
	        parent::__construct();
	        if (! $this->session->userdata('logged_in')) :
	            $this->session->set_userdata('url', uri_string());
	            redirect(base_url().'login');
	        endif;
	}
	
	public function index( $template_name = 'default' ) {
		if ( is_profile_complete() ):
			if ( $template_name == 'default' ) $template_name = get_current_theme();
			$template_name = set_theme( $template_name );
			$this->db->where( 'name', $template_name );
			$query = $this->db->get('templates', 1);
			if ( $query->num_rows() > 0 ):
				$data['template'] =  $query->row();
				$this->db->where('template_id', $data['template']->id);
				$this->db->order_by('order', 'ASC');
				$query = $this->db->get('template_images');
				if ( $query->num_rows() > 0 ):
					$data['main_images'] = $query;
				else:
					$data['main_images'] = FALSE;
				endif;
				
				$this->db->where('template_id', $data['template']->id);
				$this->db->order_by('order', 'ASC');
				$query = $this->db->get('template_videos');
				if ( $query->num_rows() > 0 ):
					$data['videos'] = $query;
				else:
					$data['videos'] = FALSE;
				endif;
			else:
				$this->not_found();
				return;
			endif;
			$this->load->view( get_client().'/header', array('jqueryui' => TRUE, 'layout'=> $data['template']->layout, 'color_scheme' => $data['template']->color_scheme) );
			$this->load->view( get_client().'/' . $data['template']->layout, $data );
			$this->load->view( get_client().'/footer', array('jqueryui' => TRUE) );
		else:
			$this->session->set_userdata('url', uri_string());
			 redirect(base_url('profile'));
		endif;
	}
	
	function not_found(){
		$this->load->view( get_client().'/header' );
		$this->load->view( get_client().'/not_found' );
		$this->load->view( get_client().'/footer' );
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */