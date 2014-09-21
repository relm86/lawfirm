<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct() {
	        parent::__construct();


	        if (! $this->session->userdata('logged_in') && $this->uri->segment(2) != 'login' ) :
			$this->session->set_userdata('url', uri_string());
			redirect(base_url('dashboard/login') );
		elseif ( $this->session->userdata('level') < 2 && $this->uri->segment(2) != 'login'):
			redirect(base_url('welcome'));
		elseif ( ! is_profile_complete()  && $this->uri->segment(2) != 'login' ):
			$this->session->set_userdata('url', uri_string());
			redirect(base_url('profile'));
	        endif;
	        
	        $themes = get_themes();
	        if ( $this->input->get('theme') && in_array($this->input->get('theme'), $themes) )
	        	set_theme( $this->input->get('theme') );
	        
	        $this->form_validation->set_error_delimiters('<div class="alert alert-danger" role="alert">','</div>');
	        
	}
	
	public function index() {
		if ( ! $this->session->userdata('password_verified') ):
			$this->session->set_userdata('url', uri_string());
			$this->verify_password();
			return;
		endif;

		$this->load->view( 'dashboard/header' );
		$this->load->view( 'dashboard/top-nav' );
		
		//super admin
		if ( $this->session->userdata('level') == 3 ):
			$this->db->where('id !=', 1);
			$this->db->where('id !=', 2);
			$this->db->where('id !=', 3);
			$this->db->where('id !=', 4);
			$data['users'] = $this->db->get('users');
			$this->load->view( 'dashboard/user/list', $data );
		endif;
		
		$this->db->select('*');
		$this->db->from('templates');
		$data['templates'] = $this->db->get();
		$this->load->view( 'dashboard/template/list', $data );
		
		$this->load->view( 'dashboard/footer' );
		//var_dump($this->session->all_userdata());
	}
	
	public function login(){
		$data = array();
		if ($this->input->post()) :
			$this->form_validation->set_rules('email', 'Email', "trim|required|valid_email");
			$this->form_validation->set_rules('password', 'Password', "trim|required");
			
			if ($this->form_validation->run() == TRUE):
				$data['login_from'] = 'form';
				$data['login_ip'] = $this->session->userdata('ip_address');
				$data['user_agent'] = $this->session->userdata('user_agent');
				$data['last_login'] = $this->session->userdata('last_login');
				
				$this->db->where('email_address', $this->input->post('email'));
				$this->db->where('password', md5($this->input->post('password')) );
                $this->db->where('suspend', 0 );
				$query = $this->db->get('users', 1);
				if ( $query->num_rows() < 1 ):
					$data['error_msg'] = 'Wrong email address or password, please try again!';
				else:
					//update login info
					$row = $query->row(); 
					unset($row->password);

					$this->db->where('id', $row->id);
					$this->db->update('users', $data); 
					$data = array_merge($data, (array) $row);
					$data['logged_in'] = TRUE;
					$data['password_verified'] = TRUE;
					$this->session->set_userdata($data);
                    sleep(2);
					redirect(base_url('/dashboard/'));
				endif;
				
			endif;
		endif;
		
		$this->load->view( 'dashboard/header', array('title' => 'Login', 'page_preview' => TRUE) );
		$this->load->view( 'dashboard/login', $data );
		$this->load->view( 'dashboard/footer' );
		//var_dump($this->session->all_userdata());
	}
	
	public function verify_password(){
		$data = array();
		
		if ( $this->input->post() ):
			$this->form_validation->set_rules('verify_password', 'Password', "trim|required");
			if ($this->form_validation->run() == TRUE):
				$this->db->where('id', $this->session->userdata('id'));
				$this->db->where('password', md5($this->input->post('verify_password')));
				$query = $this->db->get('users', 1);
				if ( $query->num_rows() > 0 ):
					$this->session->set_userdata('password_verified', TRUE);
					$this->index();
					return;
				else:
					$data['error_msg'] = 'Wrong password, please try again!';
				endif;
			endif;
		endif;
		
		$this->load->view( 'dashboard/header' );
		$this->load->view( 'dashboard/top-nav' );
		$this->load->view( 'dashboard/verify-password', $data );
		$this->load->view( 'dashboard/footer' );
	}
	
	public function new_template( $data = array() ){
				
		if ($this->input->post()):
			$this->form_validation->set_rules('name', 'Template URL', "trim|required|callback__unique_template_name");
			$this->form_validation->set_rules('color_scheme', 'Color Scheme', "trim|required");
			$this->form_validation->set_rules('layout', 'Template Layout', "trim|required");
			if ($this->form_validation->run() == TRUE):
				$insert['name'] = $this->input->post('name');
				$insert['color_scheme'] = $this->input->post('color_scheme');
				$insert['layout'] = $this->input->post('layout');
				$insert['view_count'] = 0;
				$insert['owner'] = $this->session->userdata('id');
				$this->db->insert('templates', $insert); 
				$template_id = $this->db->insert_id();
				
				if ( $template_id ){
					$this->template_preview($template_id);
					return;
				} else {
					$data = array_merge($data, $insert);
				}
			endif;
		endif;
		
		$this->load->view( 'dashboard/header' );
		$this->load->view( 'dashboard/top-nav' );
		$this->load->view( 'dashboard/template/new', $data );
		$this->load->view( 'dashboard/footer' );
	} 
	
	public function template_preview( $template_id = NULL){
		$template_id = (int) $template_id;
		$this->db->where('id', $template_id );
		$query = $this->db->get('templates', 1);
		if ( $query->num_rows() > 0 ):
			$data['template'] =  $query->row();
		else:
			$this->new_template(array('error_msg' => 'Template not found. You may try to create new template or hit back to go to previous page!'));
			return;
		endif;
		
		$this->load->view( 'dashboard/header', array('jqueryui' => TRUE, 'page_preview' => TRUE, 'layout'=> $data['template']->layout, 'color_scheme' => $data['template']->color_scheme) );
		$this->load->view( 'dashboard/template/preview_' . $data['template']->layout, $data );
		$this->load->view( 'dashboard/footer', array('jqueryui' => TRUE) );
	}
	
	public function dev_template_preview( $template_id = NULL){
		$template_id = (int) $template_id;
		$this->db->where('id', $template_id );
		$query = $this->db->get('templates', 1);
		if ( $query->num_rows() > 0 ):
			$data['template'] =  $query->row();
			
			$this->db->where('template_id', $template_id);
			$this->db->order_by('order', 'ASC');
			$query = $this->db->get('template_images');
			if ( $query->num_rows() > 0 ):
				$data['main_images'] = $query;
			else:
				$data['main_images'] = FALSE;
			endif;
			
			$this->db->where('template_id', $template_id);
			$this->db->order_by('order', 'ASC');
			$query = $this->db->get('template_videos');
			if ( $query->num_rows() > 0 ):
				$data['videos'] = $query;
			else:
				$data['videos'] = FALSE;
			endif;
		else:
			$this->new_template(array('error_msg' => 'Template not found. You may try to create new template or hit back to go to previous page!'));
			return;
		endif;
		
		$this->load->view( 'dashboard/header', array('jqueryui' => TRUE, 'page_preview' => TRUE, 'layout'=> $data['template']->layout, 'color_scheme' => $data['template']->color_scheme) );
		$this->load->view( 'dashboard/template/dev_preview_' . $data['template']->layout, $data );
		$this->load->view( 'dashboard/footer', array('jqueryui' => TRUE) );
	}
	
	function _unique_template_name(){
		
		$name = $this->input->post('name');
		$this->db->where('name', $name);
		$result = $this->db->get('templates');
		if ( $result->num_rows() > 0 ):
			$this->form_validation->set_message('_unique_template_name', 'Template name is exist. Please enter another template name!');
			return FALSE;
		else:
			return TRUE;
		endif;
	}
	
	function ajax( $action ){
		if ( $action == 'save_widget' )
			$this->_save_widget();
		elseif ( $action == 'save_layout')
			$this->_save_layout();
		elseif ( $action == 'upload_main_image')
			$this->_upload_main_image();
		elseif ( $action == 'save_image_order')
			$this->_save_image_order();
		elseif ( $action == 'save_image_title')
			$this->_save_image_title();
		elseif ( $action == 'delete_image')
			$this->_delete_image();
		elseif ( $action == 'upload_video_thumb')
			$this->_upload_video_thumb();
		elseif ( $action == 'save_video_order')
			$this->_save_video_order();
		elseif ( $action == 'save_video_url')
			$this->_save_video_url();
		elseif ( $action == 'delete_video')
			$this->_delete_video();
	}

	function _save_widget(){
		
		$response = array(
	            'success' => FALSE
	        );
			
		if ($this->input->post()) :
			$this->form_validation->set_rules('widget_type', 'Widget Type', "trim|required");
			$this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
			$this->form_validation->set_rules('template_id', 'Template ID', 'trim|required');
			if ($this->form_validation->run() == TRUE):
				if ( ! $this->input->post('widget_id') || ! valid_widget_id($this->input->post('widget_id')) ):
					//save widget 
					$insert['template_id'] = (int) $this->input->post('template_id');
					$insert['widget_type'] = $this->input->post('widget_type');
					
					$this->db->insert('widgets', $insert);
					$widget_id = $this->db->insert_id();
					
					$response = array(
				            'success' => TRUE,
				            'widget_id' => 'widget_' . $insert['widget_type'] . '_' . $widget_id
				        );

				else:
				endif;
			endif;
		endif;
		
		echo json_encode($response);
		exit;
	}
	
	function _save_layout(){
		
		$response = array(
	            'success' => TRUE
	        );
			
		if ($this->input->post()) :
			$this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
			$this->form_validation->set_rules('template_id', 'Template ID', 'trim|required');
			if ($this->form_validation->run() == TRUE):
				//save layout 
				$template_id = (int) $this->input->post('template_id');
				$sidebar = $this->input->post('sidebar');
				$left = $this->input->post('left');
				$right = $this->input->post('right');
				$footer = $this->input->post('footer');
				$widgets = array(
										'sidebar' => $sidebar,
										'left' => $left,
										'right' => $right,
										'footer' => $footer
									);
				$update['widgets'] = serialize($widgets);
				$this->db->where('id', $template_id);
				$this->db->update('templates', $update);
			endif;
		endif;
		
		echo json_encode($response);
		exit;
	}
	
	function _upload_main_image(){
		
		$response = array(
	            'success' => FALSE
	        );
			
		if ($this->input->post()) :
			$this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
			$this->form_validation->set_rules('template_id', 'Template ID', 'trim|required');
			if ($this->form_validation->run() == TRUE):
				$config['upload_path'] = './img/slider/';
				$config['allowed_types'] = 'jpeg|jpg|png';
				$config['max_size']	= '10240';
				$config['max_width']  = '1024';
				$config['max_height']  = '768';
				$config['overwrite']  = FALSE;

				$this->load->library('upload', $config);

				if ( ! $this->upload->do_upload('file')):
					$error = $this->upload->display_errors();
					//var_dump($error);
					$result = FALSE;
					$img_url = '';
					$img_thumb_url = '';
					$image_id = '';
				else:
					$data = $this->upload->data();
					
					//insert data
					$insert['template_id'] = $this->input->post('template_id');
					$insert['path'] = './img/slider/' . $data['raw_name'] . $data['file_ext'];
					$this->db->insert('template_images', $insert); 
					$image_id = $this->db->insert_id();
					
					$result = TRUE;
					$img_url = base_url() . str_replace('./', '',  create_thumb($insert['path'],  770, 366) );
					$img_thumb_url = base_url() . str_replace('./', '',  create_thumb($insert['path'], 100, 100) );
					$error = '';
					
				endif;
				
				$response = array(
			            'success' => $result,
			            'image_id' => $image_id,
			            'img_url' => $img_url,
			            'img_thumb_url' => $img_thumb_url,
				    'error'   => strip_tags($error)
			        );
			endif;
		endif;
		
		echo json_encode($response);
		exit;
	}
	
	function _save_image_order(){
		
		$response = array(
	            'success' => FALSE
	        );
			
		if ($this->input->post()) :
			$this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
			$this->form_validation->set_rules('template_id', 'Template ID', 'trim|required');
			$this->form_validation->set_rules('image_order', 'Image Order', 'trim|required');
			if ($this->form_validation->run() == TRUE):
				$image_order = explode(',', $this->input->post('image_order'));
				if ( is_array($image_order) && count($image_order) > 0 ){
					$order = 0;
					$image_order_updated = 0;
					foreach ( $image_order as $image ):
						$image_id = preg_replace("/[^0-9]/","", $image);
						$update['order'] = $order;
						$this->db->where('id', $image_id);
						$this->db->update('template_images', $update);
						$order++;
						if ( $this->db->affected_rows() > 0 ) $image_order_updated++;
					endforeach;
					if ( $image_order_updated > 0 ){
						$response = array(
					            'success' => TRUE
					        );
					}
				}
			endif;
		endif;
		
		echo json_encode($response);
		exit;
	}
	
	function _save_image_title(){
		
		$response = array(
	            'success' => FALSE
	        );
			
		if ($this->input->post()) :
			$this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
			$this->form_validation->set_rules('template_id', 'Template ID', 'trim|required');
			$this->form_validation->set_rules('id', 'Image ID', 'trim|required');
			if ($this->form_validation->run() == TRUE):
				$image_id = preg_replace("/[^0-9]/","",$this->input->post('id'));
				$update['title'] = strip_tags($this->input->post('title'));
				$update['description'] = strip_tags($this->input->post('desc'), '<p><a><b><i><strong><em><u>');
				$this->db->where('id', $image_id);
				$this->db->where('template_id', (int) $this->input->post('template_id'));
				$this->db->update('template_images', $update);
				if ( $this->db->affected_rows() > 0 ) {
					$response = array(
				            'success' => TRUE
				        );
				}
			endif;
		endif;
		
		echo json_encode($response);
		exit;
	}
	
	function _delete_image(){
		
		$response = array(
	            'success' => FALSE
	        );
			
		if ($this->input->post()) :
			$this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
			$this->form_validation->set_rules('template_id', 'Template ID', 'trim|required');
			$this->form_validation->set_rules('id', 'Image ID', 'trim|required');
			if ($this->form_validation->run() == TRUE):
				$image_id = preg_replace("/[^0-9]/","",$this->input->post('id'));
				$this->db->where('id', $image_id);
				$this->db->where('template_id', (int) $this->input->post('template_id'));
				$query = $this->db->get('template_images');
				if ( $query->num_rows() > 0 ):
					$row = $query->row();
					$this->db->where('id', $image_id);
					$this->db->where('template_id', (int) $this->input->post('template_id'));
					$this->db->delete('template_images');
					$response = array(
				            'success' => TRUE
				        );
				        unlink(create_thumb($row->path, 100, 100));
				        unlink(create_thumb($row->path,  770, 366));
				        unlink($row->path);
				endif;
			endif;
		endif;
		
		echo json_encode($response);
		exit;
	}
	
	function _upload_video_thumb(){
		
		$response = array(
	            'success' => FALSE
	        );
			
		if ($this->input->post()) :
			$this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
			$this->form_validation->set_rules('template_id', 'Template ID', 'trim|required');
			if ($this->form_validation->run() == TRUE):
				$config['upload_path'] = './img/video_thumb/';
				$config['allowed_types'] = 'jpeg|jpg|png';
				$config['max_size']	= '10240';
				$config['max_width']  = '1024';
				$config['max_height']  = '768';
				$config['overwrite']  = FALSE;

				$this->load->library('upload', $config);

				if ( ! $this->upload->do_upload('file')):
					$error = $this->upload->display_errors();
					//var_dump($error);
					$result = FALSE;
					$img_url = '';
					$img_thumb_url = '';
					$image_id = '';
				else:
					$data = $this->upload->data();
					
					//insert data
					$insert['template_id'] = $this->input->post('template_id');
					$insert['thumb'] = './img/video_thumb/' . $data['raw_name'] . $data['file_ext'];
					$this->db->insert('template_videos', $insert); 
					$image_id = $this->db->insert_id();
					
					$result = TRUE;
					$img_url = base_url() . str_replace('./', '',  create_thumb($insert['thumb'],  746, 439) );
					$img_thumb_url = base_url() . str_replace('./', '',  create_thumb($insert['thumb'], 211, 126) );
					$error = '';
					
				endif;
				
				$response = array(
			            'success' => $result,
			            'video_id' => $image_id,
			            'img_url' => $img_url,
			            'img_thumb_url' => $img_thumb_url,
				    'error'   => strip_tags($error)
			        );
			endif;
		endif;
		
		echo json_encode($response);
		exit;
	}
	
	function _save_video_order(){
		
		$response = array(
	            'success' => FALSE
	        );
			
		if ($this->input->post()) :
			$this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
			$this->form_validation->set_rules('template_id', 'Template ID', 'trim|required');
			$this->form_validation->set_rules('video_order', 'Image Order', 'trim|required');
			if ($this->form_validation->run() == TRUE):
				$video_order = explode(',', $this->input->post('video_order'));
				if ( is_array($video_order) && count($video_order) > 0 ){
					$order = 0;
					$video_order_updated = 0;
					foreach ( $video_order as $video ):
						$video_id = preg_replace("/[^0-9]/","", $video);
						$update['order'] = $order;
						$this->db->where('id', $video_id);
						$this->db->update('template_videos', $update);
						$order++;
						if ( $this->db->affected_rows() > 0 ) $video_order_updated++;
					endforeach;
					if ( $video_order_updated > 0 ){
						$response = array(
					            'success' => TRUE
					        );
					}
				}
			endif;
		endif;
		
		echo json_encode($response);
		exit;
	}
	
	function _save_video_url(){
		
		$response = array(
	            'success' => FALSE
	        );
			
		if ($this->input->post()) :
			$this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
			$this->form_validation->set_rules('template_id', 'Template ID', 'trim|required');
			$this->form_validation->set_rules('id', 'Video ID', 'trim|required');
			$this->form_validation->set_rules('url', 'Video URL', 'trim|required');
			if ($this->form_validation->run() == TRUE):
				$video_id = preg_replace("/[^0-9]/","",$this->input->post('id'));
				$update['url'] = strip_tags($this->input->post('url'));
				$this->db->where('id', $video_id);
				$this->db->where('template_id', (int) $this->input->post('template_id'));
				$this->db->update('template_videos', $update);
				if ( $this->db->affected_rows() > 0 ) {
					$response = array(
				            'success' => TRUE
				        );
				}
			endif;
		endif;
		
		echo json_encode($response);
		exit;
	}
	
	function _delete_video(){
		
		$response = array(
	            'success' => FALSE
	        );
			
		if ($this->input->post()) :
			$this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
			$this->form_validation->set_rules('template_id', 'Template ID', 'trim|required');
			$this->form_validation->set_rules('id', 'Video ID', 'trim|required');
			if ($this->form_validation->run() == TRUE):
				$video_id = preg_replace("/[^0-9]/","",$this->input->post('id'));
				$this->db->where('id', $video_id);
				$this->db->where('template_id', (int) $this->input->post('template_id'));
				$query = $this->db->get('template_videos');
				if ( $query->num_rows() > 0 ):
					$row = $query->row();
					$this->db->where('id', $video_id);
					$this->db->where('template_id', (int) $this->input->post('template_id'));
					$this->db->delete('template_videos');
					$response = array(
				            'success' => TRUE
				        );
				        unlink(create_thumb($row->thumb, 211, 126));
				        unlink(create_thumb($row->thumb,  746, 439));
				        unlink($row->thumb);
				endif;
			endif;
		endif;
		
		echo json_encode($response);
		exit;
	}

    function adduser()
    {
        if (!$this->input->post())
        {
            $this->load->view( 'dashboard/header' );
            $this->load->view( 'dashboard/top-nav' );
            $this->load->view( 'dashboard/user/add' );
            $this->load->view( 'dashboard/footer' );
        }
        else
        {
            $this->form_validation->set_rules('first_name', 'Name', "trim|required");
            $this->form_validation->set_rules('last_name', 'Name', "trim|required");
            $this->form_validation->set_rules('password', 'Password', "trim|required|callback__check_password");
            $this->form_validation->set_rules('email_address', 'Email', "trim|required|valid_email");
            $this->form_validation->set_rules('phone_number', 'Phone Number', "trim|required|callback__isValidPhone");
            $this->form_validation->set_rules('zip_code', 'Zip Code', "trim|required|callback__isValidZipCode");

            if ($this->form_validation->run() == TRUE)
            {
                $data['login_from'] = 'form';
                $data['login_ip'] = $this->session->userdata('ip_address');
                $data['user_agent'] = $this->session->userdata('user_agent');
                $data['last_login'] = $this->session->userdata('last_login');

                $this->db->where('email_address', $this->input->post('email_address'));
                $query = $this->db->get('users', 1);
                if ( $query->num_rows() < 1 ):
                    $data['level'] = 1;
                    $data['last_name'] = $this->input->post('last_name');
                    $data['first_name'] =  $this->input->post('first_name');
                    $data['phone_number'] =  $this->input->post('phone_number');
                    $data['zip_code'] =  $this->input->post('zip_code');
                    $data['email_address'] =  $this->input->post('email_address');
                    $data['password'] =  md5($this->input->post('password'));

                    $this->db->insert('users', $data);
                    $data['id'] = $this->db->insert_id();
                else:
                    //update login info
                    $row = $query->row();
                    unset($row->password);
                    $data['last_name'] = $this->input->post('last_name');
                    $data['first_name'] =  $this->input->post('first_name');
                    $data['password'] = md5($this->input->post('password'));
                    $data['phone_number'] =  $this->input->post('phone_number');
                    $data['zip_code'] =  $this->input->post('zip_code');
                    $this->db->where('id', $row->id);
                    $this->db->update('users', $data);
                    $data = array_merge($data, (array) $row);
                endif;
                redirect(base_url('dashboard'));
            }
        }
    }

    function edituser($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('users', 1);
        $data['row'] = $query->row();
        $data['selects'] = array('level', 'gender');
        $data['select_values'] = array(
            'level'  => array(1 => '1', 2 => 2, 3 => '3', 4 => '4'),
            'gender' => array('m' => 'male', 'f' => 'female')
        );
        $data['required'] = array('first_name', 'last_name', 'email_address', 'phone_number', 'zip_code');

        if ($this->input->post())
        {
            $this->form_validation->set_rules('first_name', 'Name', "trim|required");
            $this->form_validation->set_rules('last_name', 'Name', "trim|required");
            //$this->form_validation->set_rules('password', 'Password', "trim|required|callback__check_password");
            $this->form_validation->set_rules('email_address', 'Email', "trim|required|valid_email");
            $this->form_validation->set_rules('phone_number', 'Phone Number', "trim|required|callback__isValidPhone");
            $this->form_validation->set_rules('zip_code', 'Zip Code', "trim|required|callback__isValidZipCode");

            if ($this->form_validation->run() == TRUE)
            {
                $data = $this->input->post();

                if (!$data['password'] || $data['password'] == '') {
                    unset($data['password']);
                } else {
                    $data['password'] = md5($data['password']);
                }
                $this->db->where('id', $id);
                $this->db->update('users', $data);
                redirect(base_url('dashboard'));
            }
            //var_dump(validation_errors()); die;
        }

        $this->load->view( 'dashboard/header' );
        $this->load->view( 'dashboard/top-nav' );
        $this->load->view( 'dashboard/user/edit', $data );
        $this->load->view( 'dashboard/footer' );
    }

    function loginas($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('users', 1);

        $data['login_from'] = 'form';
        $data['login_ip'] = $this->session->userdata('ip_address');
        $data['user_agent'] = $this->session->userdata('user_agent');
        $data['last_login'] = $this->session->userdata('last_login');

        //update login info
        $row = $query->row();
        unset($row->password);

        $this->db->where('id', $row->id);
        $this->db->update('users', $data);
        $data = array_merge($data, (array) $row);
        $data['logged_in'] = TRUE;
        $data['password_verified'] = TRUE;
        $this->session->set_userdata($data);
        sleep(2);
        redirect(base_url());
    }

    function suspend($id, $suspend)
    {
        $data['suspend'] = $suspend == 0 ? 1 : 0;
        $this->db->where('id', $id);
        $query = $this->db->update('users', $data);
        redirect(base_url('dashboard'));
    }

    function deleteuser($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->delete('users');
        redirect(base_url('dashboard'));
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */