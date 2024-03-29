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

					//geo ip
					$loc = get_location_info();
					if ( is_object($loc) && isset($loc->ip) ):
						$data['login_ip'] = $loc->ip;
						if ( $row->city == '' ) $data['city'] = $loc->city;
						if ( $row->state == '' ) $data['state'] = $loc->region_name;
						if ( $row->country == '' ) $data['country'] = $loc->country_name;
						if ( $row->latitude == '' ) $data['latitude'] = $loc->latitude;
						if ( $row->longitude == '' ) $data['longitude'] = $loc->longitude;
					endif;

					$this->db->where('id', $row->id);
					$this->db->update('users', $data);
					$data = array_merge($data, (array) $row);
					$data['logged_in'] = TRUE;
					$data['password_verified'] = TRUE;
					$this->session->set_userdata($data);
					redirect(base_url('/dashboard/'));
				endif;

			endif;
		endif;

		$this->load->view( 'dashboard/header', array('title' => 'Login', 'page_preview' => TRUE) );
		$this->load->view( get_client().'/login-dashboard', $data );
		$this->load->view( 'dashboard/footer' );
		//var_dump($this->session->all_userdata()); login issue need to fix, clue is somehow ci regenerate the session
		//check this https://github.com/EllisLab/CodeIgniter/wiki/Native-session
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

	public function new_user( $data = array() ){

		if ($this->input->post()):
			$this->form_validation->set_rules('first_name', 'First Name', "trim|required");
			$this->form_validation->set_rules('last_name', 'Last Name', "trim|required");
			$this->form_validation->set_rules('g_email', 'Email', "trim|required|valid_email");
			if ($this->form_validation->run() == TRUE):
				$insert['email_address'] = $this->input->post('g_email');
				$insert['first_name'] = $this->input->post('first_name');
				$insert['last_name'] = $this->input->post('last_name');
				$insert['email_address'] = $this->input->post('g_email');
				$insert['phone_number'] = $this->input->post('phone_number');
				$insert['zip_code'] = $this->input->post('zip_code');
				$insert['gender'] = $this->input->post('gender');

				$this->db->insert('users', $insert);
				$template_id = $this->db->insert_id();

				if ( $template_id ){
					$this->index($template_id);
					return;
				} else {
					$data = array_merge($data, $insert);
				}
			else:
				$add['email_address'] = $this->input->post('g_email');
				$add['first_name'] = $this->input->post('first_name');
				$add['last_name'] = $this->input->post('last_name');
				$add['g_email'] = $this->input->post('g_email');
				$add['phone_number'] = $this->input->post('phone_number');
				$add['zip_code'] = $this->input->post('zip_code');
				$add['gender'] = $this->input->post('gender');
				$data = array_merge($data, $add);
			endif;
		endif;

		$this->load->view( 'dashboard/header' );
		$this->load->view( 'dashboard/top-nav' );
		$this->load->view( 'dashboard/user/new', $data);
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
				$insert['is_male'] = $this->input->post('male');
				$insert['is_female'] = $this->input->post('female');
				$insert['is_both'] = $this->input->post('both');
				$insert['state'] = $this->input->post('state');
				$insert['city'] = $this->input->post('city');
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
		$data['usStates'] = array(
				    "AL" => "Alabama",
				    "AK" => "Alaska",
				    "AZ" => "Arizona",
				    "AR" => "Arkansas",
				    "CA" => "California",
				    "CO" => "Colorado",
				    "CT" => "Connecticut",
				    "DE" => "Delaware",
				    "FL" => "Florida",
				    "GA" => "Georgia",
				    "HI" => "Hawaii",
				    "ID" => "Idaho",
				    "IL" => "Illinois",
				    "IN" => "Indiana",
				    "IA" => "Iowa",
				    "KS" => "Kansas",
				    "KY" => "Kentucky",
				    "LA" => "Louisiana",
				    "ME" => "Maine",
				    "MD" => "Maryland",
				    "MA" => "Massachusetts",
				    "MI" => "Michigan",
				    "MN" => "Minnesota",
				    "MS" => "Mississippi",
				    "MO" => "Missouri",
				    "MT" => "Montana",
				    "NE" => "Nebraska",
				    "NV" => "Nevada",
				    "NH" => "New Hampshire",
				    "NJ" => "New Jersey",
				    "NM" => "New Mexico",
				    "NY" => "New York",
				    "NC" => "North Carolina",
				    "ND" => "North Dakota",
				    "OH" => "Ohio",
				    "OK" => "Oklahoma",
				    "OR" => "Oregon",
				    "PA" => "Pennsylvania",
				    "RI" => "Rhode Island",
				    "SC" => "South Carolina",
				    "SD" => "South Dakota",
				    "TN" => "Tennessee",
				    "TX" => "Texas",
				    "UT" => "Utah",
				    "VT" => "Vermont",
				    "VA" => "Virginia",
				    "WA" => "Washington",
				    "WV" => "West Virginia",
				    "WI" => "Wisconsin",
				    "WY" => "Wyoming"
				    );
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

		$this->load->view( 'dashboard/header', array('jqueryui' => TRUE, 'template_id'=> $template_id, 'sticky' => TRUE, 'page_preview' => TRUE, 'layout'=> $data['template']->layout, 'color_scheme' => $data['template']->color_scheme) );
		$this->load->view(  get_client() . '/preview_' . $data['template']->layout, $data );
		$this->load->view( 'dashboard/footer', array('jqueryui' => TRUE, 'sticky' => TRUE) );
	}

	public function template_preview2( $template_id = NULL){
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

		$this->load->view( get_client().'/header', array('jqueryui' => TRUE, 'layout'=> $data['template']->layout, 'color_scheme' => $data['template']->color_scheme) );
		$this->load->view(  get_client().'/'.$data['template']->layout, $data );
		$this->load->view( get_client().'/footer', array('jqueryui' => TRUE) );
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
		elseif ( $action == 'delete_widget')
			$this->_delete_widget();
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
		elseif ( $action == 'yelp_search')
			$this->_yelp_search();
	}

	function _save_widget(){

		$response = array(
	            'success' 	=> FALSE,
	            'error'		=> ''
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

				    ob_start();
					draw_modal($widget_id);
					$response = array(
				            'success' => TRUE,
				            'widget_modal' => ob_get_contents(),
				            'widget_id' =>  'widget-' . $insert['widget_type'] . '-' . $widget_id
				        );
				    ob_end_clean();

				elseif( valid_widget_id($this->input->post('widget_id')) ):
					//update widget
					$widget_id = preg_replace("/[^0-9]/","", $this->input->post('widget_id'));
					$update['widget_data'] = $this->_get_widget_data();

					if ( $update['widget_data'] ):
						$this->db->where('id', $widget_id);
						$this->db->where('template_id', $this->input->post('template_id'));
						$this->db->update('widgets', $update);
						if ( $this->db->affected_rows() > 0 ):
							ob_start();
							draw_widget($widget_id);
							$response = array(
						            'success' => TRUE,
						            'widget_html' => ob_get_contents(),
						            'widget_id' =>  'widget-' . $this->input->post('widget_type') . '-' . $widget_id
						        );
						    ob_end_clean();
						else:
							$response['error'] = 'Nothing change';
						endif;
					else:
						$response['error'] = 'Invalid widget data.';
					endif;
				endif;
			endif;
		endif;

		echo json_encode($response);
		exit;
	}

	function _delete_widget(){

		$response = array(
	            'success' 	=> FALSE,
	            'error'		=> ''
	        );

		if ($this->input->post()) :
			$this->form_validation->set_rules('widget_id', 'Widget ID', "trim|required");
			$this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
			$this->form_validation->set_rules('template_id', 'Template ID', 'trim|required');
			if ($this->form_validation->run() == TRUE):
				if ( ! valid_widget_id($this->input->post('widget_id')) ):

					$response = array(
				            'success' => TRUE,
				            'error' => 'Widget not found!'
				        );

				elseif( valid_widget_id($this->input->post('widget_id')) ):
					$widget_id = preg_replace("/[^0-9]/","", $this->input->post('widget_id'));

					$this->db->where('id', $widget_id);
					$this->db->where('template_id', $this->input->post('template_id'));
					$this->db->delete('widgets');
					if ( $this->db->affected_rows() > 0 ):
						$response = array(
					            'success' => TRUE,
					            'widget_id' => $widget_id
					        );
					else:
						$response['error'] = 'Fail delete widget from database!';
					endif;
				else:
					$response['error'] = 'Fail delete widget. Invalid widget data.';
				endif;
			endif;
		endif;

		echo json_encode($response);
		exit;
	}

	function _get_widget_data(){
		if ( 'faq' == $this->input->post('widget_type') && $this->input->post('text-title') ):
			$title = $this->input->post('text-title');
			$content = $this->input->post('text-content');
			if ( $content ):
				$this->load->library('htmlfixer');
				$content = $this->htmlfixer->getFixedHtml($this->input->post('text-content'));
			endif;
			$return['title'] = $title;
			$return['content'] = $content;
			return serialize($return);

		elseif ( 'links' == $this->input->post('widget_type') && $this->input->post('link-title')):
			$link_title = $this->input->post('link-title');
			$link_url = $this->input->post('link-url');
			$i = 1;

			$links['title'] =$this->input->post('links-title');

			if ( is_array($link_title) && count($link_title) > 0):
				foreach($link_title as $title):
					if (filter_var($link_url[$i], FILTER_VALIDATE_URL) !== false)
						$url = $link_url[$i];
					else
						$url = '#';
					$links['links'][$i]['title'] = $title;
					$links['links'][$i]['url'] = strtolower($url);
					$i++;
				endforeach;
			endif;

			if ( isset($links) && count($links) > 0 ):
				return serialize($links);
			endif;

		elseif ( 'text' == $this->input->post('widget_type') ||  'download' == $this->input->post('widget_type') ||  'coupon' == $this->input->post('widget_type') ||  'products' == $this->input->post('widget_type') ||  'services' == $this->input->post('widget_type') ||  'contact' == $this->input->post('widget_type') ):
			$this->load->library('security');
			$title = $this->input->post('text-title');
			$content = $this->input->post('text-content');
			$bordercolor = $this->input->post('border-color');
			$backgroundcolor = $this->input->post('background-color');
			$titlecolor = $this->input->post('title-color');
			$textcolor = $this->input->post('text-color');

			if ( ! $title && ! $content ) return FALSE; //don't save empty title & content
			/*
			if ( $content ):
				$this->load->library('htmlfixer');
				$content = $this->htmlfixer->getFixedHtml($this->input->post('text-content'));
			endif;
			*/
			$return['title'] = $title;
			$return['content'] = $content;

			if ( $bordercolor && strpos($bordercolor, '#') !== FALSE ) $return['border-color'] = $bordercolor;
			if ( $backgroundcolor && strpos( $backgroundcolor, '#') !== FALSE ) $return['background-color'] = $backgroundcolor;
			if ( $titlecolor && strpos( $titlecolor, '#') !== FALSE ) $return['title-color'] = $titlecolor;
			if ( $textcolor && strpos( $textcolor, '#') !== FALSE ) $return['text-color'] = $textcolor;

			return serialize($return);

		elseif ( 'forumfeed' == $this->input->post('widget_type') ):
			$title = $this->input->post('feed-title');
			$url = $this->input->post('feed-url');
			$number = (int) $this->input->post('feed-number');
			$bordercolor = $this->input->post('border-color');
			$backgroundcolor = $this->input->post('background-color');
			$titlecolor = $this->input->post('title-color');
			$textcolor = $this->input->post('text-color');

			if ( ! $title && ! $url ) return FALSE; //don't save empty title & content

			$return['title'] = $title;
			$return['feed_url'] = $url;
			$return['feed_number'] = $number;

			if ( $bordercolor && strpos($bordercolor, '#') !== FALSE ) $return['border-color'] = $bordercolor;
			if ( $backgroundcolor && strpos( $backgroundcolor, '#') !== FALSE ) $return['background-color'] = $backgroundcolor;
			if ( $titlecolor && strpos( $titlecolor, '#') !== FALSE ) $return['title-color'] = $titlecolor;
			if ( $textcolor && strpos( $textcolor, '#') !== FALSE ) $return['text-color'] = $textcolor;

			return serialize($return);

		elseif ( 'gmap' == $this->input->post('widget_type') ):
			$location = $this->input->post('gmap-location');
			$content = $this->input->post('gmap-content');
			$bordercolor = $this->input->post('border-color');
			$backgroundcolor = $this->input->post('background-color');
			$textcolor = $this->input->post('text-color');

			if ( ! $location && ! $content ) return FALSE; //don't save empty title & content

			$return['location'] = $location;
			$return['content'] = $content;

			if ( $bordercolor && strpos($bordercolor, '#') !== FALSE ) $return['border-color'] = $bordercolor;
			if ( $backgroundcolor && strpos( $backgroundcolor, '#') !== FALSE ) $return['background-color'] = $backgroundcolor;
			if ( $textcolor && strpos( $textcolor, '#') !== FALSE ) $return['text-color'] = $textcolor;

			return serialize($return);

		elseif ( 'greeting' == $this->input->post('widget_type') && $this->input->post('text-title')):
			$title = $this->input->post('text-title');
			$content = $this->input->post('text-content');
			if ( $content ):
				$this->load->library('htmlfixer');
				$content = $this->htmlfixer->getFixedHtml($this->input->post('text-content'));
			endif;
			$return['title'] = $title;
			$return['content'] = $content;
			return serialize($return);

		elseif ( 'testimonials' == $this->input->post('widget_type') && $this->input->post('text-title')):
			$title = $this->input->post('text-title');
			$content = $this->input->post('text-content');
			//turn off for now since find bug when element have more class name
			/*if ( $content ):
				$this->load->library('htmlfixer');
				$content = $this->htmlfixer->getFixedHtml($this->input->post('text-content'));
			endif;*/
			$return['title'] = $title;
			$return['content'] = $content;
			return serialize($return);

		elseif ( 'stories' == $this->input->post('widget_type') && $this->input->post('text-title')):
			$title = $this->input->post('text-title');
			$content = $this->input->post('text-content');
			if ( $content ):
				$this->load->library('htmlfixer');
				$content = $this->htmlfixer->getFixedHtml($this->input->post('text-content'));
			endif;
			$return['title'] = $title;
			$return['content'] = $content;
			return serialize($return);

		elseif ( 'yreview' == $this->input->post('widget_type') && $this->input->post('business_id')):
			$business_id = $this->input->post('business_id');
			$return['business_id'] = $business_id;
			return serialize($return);

		elseif ( 'twitter' == $this->input->post('widget_type')):
			$twitter_title = $this->input->post('twitter-title');
			$twitter_hashtag = $this->input->post('twitter-hashtag');
			$i = 1;
			if(strlen($twitter_title[1])==0) {
					$twitter_title[1] = 'Twitter Feed';
					$twitter_title[1] = 'autoaccident';
			} else {
				foreach($twitter_title as $title):
					$twitter[$i]['title'] = $title;
					$twitter[$i]['hashtag'] = $twitter_hashtag[$i];
					$i++;
				endforeach;
			}

			if ( isset($twitter) && count($twitter) > 0 ):
				return serialize($twitter);
			endif;

		//require_once(APPPATH.'widgets/foursquare/dashboard_get_widget_data.php');
		elseif ( 'foursquare' == $this->input->post('widget_type')):
			$foursquare_title = $this->input->post('foursquare-title');
			$foursquare_hashtag = $this->input->post('foursquare-hashtag');
			$i = 1;
			if(strlen($foursquare_title[1])==0) {
					$foursquare_title[1] = 'Twitter Feed';
					$foursquare_title[1] = 'autoaccident';
			} else {
				foreach($foursquare_title as $title):
					$foursquare[$i]['title'] = $title;
					$foursquare[$i]['hashtag'] = $foursquare_hashtag[$i];
					$i++;
				endforeach;
			}
			//print_r($foursquare);
			if ( isset($foursquare) && count($foursquare) > 0 ):
				return serialize($foursquare);
			endif;

		//require_once(APPPATH.'widgets/reviews/dashboard_get_widget_data.php');
		elseif ( 'reviews' == $this->input->post('widget_type')):
			$reviews_gender = $this->input->post('reviews-gender');
			$reviews_state  = $this->input->post('reviews-state');
			$reviews_city   = $this->input->post('reviews-city');
			$i = 1;
			if(strlen($reviews_gender[1])==0) {
					$reviews_gender[1] = 'M';
			} else {
				foreach($reviews_gender as $title):
					$reviews[$i]['gender'] = $title;
					$reviews[$i]['state'] = $reviews_state[$i];
					$reviews[$i]['city'] = $reviews_city[$i];
					$i++;
				endforeach;
			}
			//print_r($reviews);
			if ( isset($reviews) && count($reviews) > 0 ):
				return serialize($reviews);
			endif;

		endif;
		return FALSE;
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
				$footer1 = $this->input->post('footer1');
				$footer2 = $this->input->post('footer2');
				$footer3 = $this->input->post('footer3');
				$footer4 = $this->input->post('footer4');
				$footer5 = $this->input->post('footer5');
				$footer6 = $this->input->post('footer6');
				$widgets = array(
										'sidebar' => $sidebar,
										'left' => $left,
										'right' => $right,
										'footer' => $footer,
										'footer1' => $footer1,
										'footer2' => $footer2,
										'footer3' => $footer3,
										'footer4' => $footer4,
										'footer5' => $footer5,
										'footer6' => $footer6
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

	function _yelp_search(){

		$response = array(
	            'success' => FALSE
	        );

		if ($this->input->post()) :
			$this->form_validation->set_rules('find', 'Find', 'trim|required');
			$this->form_validation->set_rules('near', 'Location', 'trim|required');
			if ($this->form_validation->run() == TRUE):
				$this->load->library('yelpoauth');
				$this->config->load('yelp');

				$result = $this->yelpoauth->query_api($this->input->post('find'), $this->input->post('near'));

				if ( $result ):
					$content = '<p>Move cursor to one of business below then press select button.</p>';
					$i = 0;

					ob_start();
					foreach( $result->businesses as $business):
						$i++;
						$business_detail = json_decode($this->yelpoauth-> get_business($business->id));
						?>
<div class="search-result natural-search-result biz-listing-large clearfix" data-key="<?=$i;?>" data-component-bound="true" id="<?=$business->id;?>">
	<div class="main-attributes">
		<div class="media-block media-block-large">
			<div class="media-avatar">
				<div class="photo-box pb-90s">
					<?php if ( isset($business->image_url) ): ?>
					<img alt="<?=$business->name; ?>" class="photo-box-img" height="90" src="<?=$business->image_url;?>" width="90">
					<?php endif; ?>
				</div>
			</div>
			<div class="media-story">
				<h3 class="search-result-title"><?=$business->name;?></h3>
				<div class="biz-rating biz-rating-large clearfix">
					<div class="rating-large">
						<i class="star-img stars_4" title="4.0 star rating">
							<img alt="4.0 star rating" class="offscreen" height="30" src="<?=$business->rating_img_url_large;?>" width="166">
						</i>
					</div>
					<span class="review-count rating-qualifier">
						<?=$business->review_count;?> reviews
					</span>
				</div>
				<div class="price-category">
					<span class="category-str-list">
						<?=implode(', ', $business->categories[0]);?>
					</span>
				</div>
				<ul class="tags">
				</ul>
			</div>
		</div>
	</div>

	<div class="secondary-attributes">
		<span class="neighborhood-str-list">
			<?=implode(', ', $business->location->neighborhoods);?>
		</span>
		<address>
			<?=implode(', ', $business->location->display_address);?>
		</address>
		<span class="offscreen">
			Phone number
		</span>
		<?php if ( isset($business->display_phone) ): ?>
		<span class="biz-phone"><?=$business->display_phone;?></span>
		<?php endif; ?>
	</div>

	<div class="snippet-block review-snippet">
		<div class="media-block">
			<div class="media-avatar">
				<div class="photo-box pb-30s">
						<img alt="<?=$business_detail->reviews[0]->user->name;?>" class="photo-box-img" height="30" src="<?=$business_detail->reviews[0]->user->image_url;?>" width="30">
					</a>
				</div>
			</div>
			<div class="media-story">
				<p class="snippet"><?=$business_detail->reviews[0]->excerpt;?></p>
			</div>
		</div>
	</div>

	<div class="select_yelp_business_container"><button type="button" class="btn btn-primary btn-sm select_yelp_business" data-dismiss="modal">Select</button></div>
</div>
<?php
					endforeach;
					$content .= ob_get_contents();
					ob_end_clean();
					$response = array('success' => TRUE, 'content' => $content);
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
        //$data['required'] = array('first_name', 'last_name', 'email_address', 'phone_number', 'zip_code');
        $data['required'] = array();

        if ($this->input->post())
        {
            //$this->form_validation->set_rules('first_name', 'Name', "trim");
            //$this->form_validation->set_rules('last_name', 'Name', "trim");
            //$this->form_validation->set_rules('password', 'Password', "trim|required|callback__check_password");
            //$this->form_validation->set_rules('email_address', 'Email', "trim");
            //$this->form_validation->set_rules('phone_number', 'Phone Number', "trim|required|callback__isValidPhone");
            //$this->form_validation->set_rules('zip_code', 'Zip Code', "trim|required|callback__isValidZipCode");

            /*if ($this->form_validation->run() == TRUE)
            {*/
                $data = $this->input->post();

                if (!$data['password'] || $data['password'] == '') {
                    unset($data['password']);
                } else {
                    $data['password'] = md5($data['password']);
                }

                foreach ($data as $key => $value)
                {
                    if ($value == '')
                    {
                        unset($data[$key]);
                    }
                }

                $this->db->where('id', $id);
                $this->db->update('users', $data);
                redirect(base_url('dashboard'));
            /*}*/
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

    function template_delete($id)
    {
        $this->db->where('id', $id);
        $this->db->where('is_default !=', '1');//don't delete default template
        $query = $this->db->delete('templates');

        //delete widget
        if ( $this->db->affected_rows() > 0):
        	$this->db->where('template_id', $id);
        	$this->db->delete('widgets');
        endif;

        redirect(base_url('dashboard'));
    }

    function set_default($id)
    {
	$update['is_default'] = 0;
	$this->db->where('id !=', $id);
	$query = $this->db->update('templates', $update);

        $update['is_default'] = 1;
	$this->db->where('id', $id);
	$query = $this->db->update('templates', $update);

	redirect(base_url('dashboard'));
    }

    function save_template_option()
    {
    	if ($this->input->post()):
		$this->form_validation->set_rules('template_id', 'ID', "trim|required");
		$this->form_validation->set_rules('color_scheme', 'Color Scheme', "trim|required");
		$this->form_validation->set_rules('layout', 'Template Layout', "trim|required");
		if ($this->form_validation->run() == TRUE):
			$update['color_scheme'] = $this->input->post('color_scheme');
			$update['layout'] = $this->input->post('layout');
			$update['is_male'] = $this->input->post('male');
			$update['is_female'] = $this->input->post('female');
			$update['is_both'] = $this->input->post('both');
			$update['state'] = $this->input->post('state');
			$update['city'] = $this->input->post('city');
			$this->db->where('id', $this->input->post('template_id'));
			$this->db->update('templates', $update);
		endif;
	endif;

	redirect( base_url( 'dashboard/template_preview/' .  $this->input->post('template_id') ) );
    }


}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */