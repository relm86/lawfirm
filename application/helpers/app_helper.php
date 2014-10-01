<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CodeIgniter AppCommon  Helpers
 *
 * @package		AppCommon
 * @subpackage	Helpers
 * @category	Helpers
 * @author		asep@wordpress-services.com
 * @link		asep@wordpress-services.com
 */

// ------------------------------------------------------------------------

if ( ! function_exists('create_thumb'))
{
	function create_thumb($source, $width, $height) {
		$CI = get_instance();
		$config['image_library'] = 'gd2';
		$config['source_image']	= $source;
		$ext = pathinfo($source, PATHINFO_EXTENSION);
		$config['new_image']	= str_replace('.'.$ext, '_thumb_' . $width . 'x' . $height . '.'. $ext, $source);
		$config['create_thumb'] = FALSE;
		$config['maintain_ratio'] = TRUE;
		$config['width']	= $width;
		$config['height']	= $height;
		
		if ( file_exists( $config['new_image'] ) ) return $config['new_image'];

		$CI->load->library('image_lib'); 
		$CI->image_lib->clear(); 
		$CI->image_lib->initialize($config);
		
		if ( ! $CI->image_lib->resize()):
			echo $CI->image_lib->display_errors();
			return FALSE;
		else:
			return $config['new_image'];
		endif;
	}
}

if ( ! function_exists('is_profile_complete'))
{
	function is_profile_complete() {
		$row = get_user_detail();
		//if ( ! $row || ! $row->email_address || ! $row->password || ! $row->level || ($row->first_name . $row->last_name == '') || ! $row->phone_number || ! $row->zip_code ):
		if ( ! $row || ! $row->level || ($row->first_name . $row->last_name == '') ):
			return FALSE;
		endif;
		
		return TRUE;
	}
}

if ( ! function_exists('get_user_detail'))
{
	function get_user_detail( $userid = FALSE ) {
		$CI = get_instance();
		if ( ! $userid )$userid = $CI->session->userdata('id');
		
		$CI->db->where('id', $userid);
		$query = $CI->db->get('users', 1);
		if ( $query->num_rows() > 0 ):
			return  $query->row();
		else:
			return FALSE;
		endif;
		
		return FALSE;
	}
}

if ( ! function_exists('get_user_fullname'))
{
	function get_user_fullname( $userid = FALSE ) {
		$CI = get_instance();
		if ( ! $userid )$userid = $CI->session->userdata('id');
		
		$CI->db->where('id', $userid);
		$query = $CI->db->get('users', 1);
		if ( $query->num_rows() > 0 ):
			$row =  $query->row();
			return $row->first_name . ' ' . $row->last_name;
		else:
			return FALSE;
		endif;
		
		return FALSE;
	}
}

if ( ! function_exists('get_user_picture'))
{
	function get_user_picture( $userid = FALSE ) {
		$CI = get_instance();
		if ( ! $userid )$userid = $CI->session->userdata('id');
		
		$CI->db->where('id', $userid);
		$query = $CI->db->get('users', 1);
		if ( $query->num_rows() > 0 ):
			$row =  $query->row();
			if ( ! $row->picture ) 
				return base_url() . 'img/img100.png';
			return base_url() . str_replace('./', '', $row->picture );
		else:
			return FALSE;
		endif;
		
		return FALSE;
	}
}

if ( ! function_exists('get_user_picture_thumb'))
{
	function get_user_picture_thumb( $userid = FALSE, $width = 100, $height = 100 ) {
		$CI = get_instance();
		if ( ! $userid )$userid = $CI->session->userdata('id');
		
		$CI->db->where('id', $userid);
		$query = $CI->db->get('users', 1);
		if ( $query->num_rows() > 0 ):
			$row =  $query->row();
			if ( ! $row->picture ) 
				$row->picture = './img/img100.png';
			return base_url() . str_replace('./', '',  create_thumb($row->picture, $width, $height) );
		else:
			return FALSE;
		endif;
		
		return FALSE;
	}
}

if ( ! function_exists('isPhone'))
{
	function isPhone( $phone ) {
		//if(preg_match("/\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/", $phone))
		if(preg_match("/^(\d[\s-]?)?[\(\[\s-]{0,2}?\d{3}[\)\]\s-]{0,2}?\d{3}[\s-]?\d{4}$/i", $phone))
			return TRUE;
		elseif( ctype_digit($phone) )
			return TRUE;
		else
			return FALSE;
	}
}

if ( ! function_exists('IsZipCode'))
{
	function IsZipCode( $zipcode ) {
		if(preg_match("/^\d{5}(?:[\s-]\d{4})?$/", $zipcode))
			return TRUE;
		else
			return FALSE;
	}
}

if ( ! function_exists('get_themes'))
{
	function get_themes() {
		$CI = get_instance();
		$CI->db->select('name');
		$query = $CI->db->get('templates');
		if ( $query->num_rows() > 0 ):
			$themes = array();
			foreach ( $query->result() as $row ):
				$themes[] = $row->name;
			endforeach;
			return $themes;
		else:
			return FALSE;
		endif;
	}
}

if ( ! function_exists('get_client'))
{
	function get_client() {
		$CI = get_instance();
		return $CI->config->item('client');
	}
}

if ( ! function_exists('get_default_theme'))
{
	function get_default_theme() {
		return 'default';
	}
}

if ( ! function_exists('get_current_theme'))
{
	function get_current_theme() {
		$CI = get_instance();
		return $CI->session->userdata('theme');
	}
}

if ( ! function_exists('set_theme'))
{
	function set_theme( $theme ) {
		$CI = get_instance();
		$themes = get_themes();
		$default_theme = get_default_theme();
		
		if ( ! is_array($themes) ) return FALSE;
		
		if ( in_array($theme, $themes) ):
			$result = TRUE;
		else:
			$theme = $default_theme;
			$result = FALSE;
		endif;
		
		//set theme
		$userid = $CI->session->userdata('id');
		$data = array('theme' => $theme );
		$CI->db->where('id', $userid);
		$CI->db->update('users', $data); 
		$CI->session->set_userdata($data);
		
		return $theme;
	}
}

if ( ! function_exists('valid_widget_id')){
	function valid_widget_id( $widget_id ) {
		$widget_id = preg_replace("/[^0-9]/","",$widget_id);
		$CI = get_instance();
		$CI->db->where('id', $widget_id); 
		$query = $CI->db->get('widgets',1); 
		if ( $query->num_rows() > 0 )
			return TRUE;
		else
			return FALSE;
	}
}

if ( ! function_exists('draw_widgets')){
	function draw_widgets( $template_id, $position, $preview = FALSE ){
		$CI = get_instance();
		$CI->db->where('id', $template_id);
		$query = $CI->db->get('templates',1);
		if ( $query->num_rows() > 0 ):
			$row = $query->row();
			$widgets =unserialize($row->widgets);
			
			if ( ! isset($widgets[$position]) )
				return FALSE;
			$widgets[$position] = explode(',', $widgets[$position]);
			foreach( $widgets[$position] as $widget ):
				draw_widget($widget, $position, $preview);
			endforeach;
		else:
			return FALSE;
		endif;
	}
}

if ( ! function_exists('draw_widget')){
	function draw_widget( $widget_id, $position = FALSE, $preview = FALSE ){
		$CI = get_instance();
		$id = preg_replace("/[^0-9]/","",$widget_id);
		$CI->db->where('id', $id);
		$query = $CI->db->get('widgets',1);
		if ( $query->num_rows() > 0 ):
			$widget = $query->row();
			if ( $widget->widget_type == 'greeting' ):
				draw_widget_greeting($widget, $position, $preview);
			elseif ( $widget->widget_type == 'testimonials' ):
				draw_widget_testimonials($widget, $position, $preview);
			elseif ( $widget->widget_type == 'stories' ):
				draw_widget_stories($widget, $position, $preview);
			elseif ( $widget->widget_type == 'links' ):
				draw_widget_links($widget, $position, $preview);
			elseif ( $widget->widget_type == 'contact' ):
				draw_widget_contact($widget, $position, $preview);
			elseif ( $widget->widget_type == 'twitter' ):
				draw_widget_twitter($widget, $position, $preview);
			elseif ( $widget->widget_type == 'faq' ):
				draw_widget_faq($widget, $position, $preview);
			elseif ( $widget->widget_type == 'text' ):
				draw_widget_text($widget, $position, $preview);
			endif;
		else:
			return FALSE;
		endif;
	}
}

if ( ! function_exists('draw_modals')){
	function draw_modals( $template_id ){
		$CI = get_instance();
		$id = preg_replace("/[^0-9]/","",$template_id);
		
		//image modal
		$CI->db->where('template_id', $id);
		$CI->db->order_by('order', 'ASC');
		$query = $CI->db->get('template_images');
		if ( $query->num_rows() > 0 ):
			$main_images = $query;
		else:
			$main_images = FALSE;
		endif;
		draw_main_image_modal($main_images);
			
		//video modal
		$CI->db->where('template_id', $id);
		$CI->db->order_by('order', 'ASC');
		$query = $CI->db->get('template_videos');
		if ( $query->num_rows() > 0 ):
			$videos = $query;
		else:
			$videos = FALSE;
		endif;
		draw_video_modal($videos);
		
		//widget modal
		$CI->db->where('template_id', $id);
		$query = $CI->db->get('widgets');
		if ( $query->num_rows() > 0 ):
			foreach($query->result() as $widget ):
				if ( $widget->widget_type == 'greeting' ):
					draw_modal_greeting($widget);
				elseif ( $widget->widget_type == 'testimonials' ):
					draw_modal_testimonials($widget);
				elseif ( $widget->widget_type == 'stories' ):
					draw_modal_stories($widget);
				elseif ( $widget->widget_type == 'links' ):
					draw_modal_links($widget);
				elseif ( $widget->widget_type == 'contact' ):
					draw_modal_contact($widget);
				elseif ( $widget->widget_type == 'twitter' ):
					draw_modal_twitter($widget);
				elseif ( $widget->widget_type == 'faq' ):
					draw_modal_faq($widget);
				elseif ( $widget->widget_type == 'text' ):
					draw_modal_text($widget);
				endif;
			endforeach;
		else:
			return FALSE;
		endif;
	}
}

if ( ! function_exists('draw_modal')){
	function draw_modal( $widget_id ){
		$CI = get_instance();
		$id = preg_replace("/[^0-9]/","",$widget_id);
		
		$CI->db->where('id', $id);
		$query = $CI->db->get('widgets', 1);
		if ( $query->num_rows() > 0 ):
			$widget = $query->row();
			if ( $widget->widget_type == 'greeting' ):
				draw_modal_greeting($widget);
			elseif ( $widget->widget_type == 'testimonials' ):
				draw_modal_testimonials($widget);
			elseif ( $widget->widget_type == 'stories' ):
				draw_modal_stories($widget);
			elseif ( $widget->widget_type == 'links' ):
				draw_modal_links($widget);
			elseif ( $widget->widget_type == 'contact' ):
				draw_modal_contact($widget);
			elseif ( $widget->widget_type == 'twitter' ):
				draw_modal_twitter($widget);
			elseif ( $widget->widget_type == 'faq' ):
				draw_modal_faq($widget);
			elseif ( $widget->widget_type == 'text' ):
				draw_modal_text($widget);
			endif;
		else:
			return FALSE;
		endif;
	}
}

if ( ! function_exists('draw_widget_greeting')){
	function draw_widget_greeting($widget, $position = FALSE, $preview = FALSE ){
		if ( ! is_object($widget) ) return FALSE;
		
		$CI = get_instance();
		
		$text = unserialize($widget->widget_data);
		
		if ( ! isset($text['title']) ) $text['title'] = 'Hi [first-name],';
		if ( ! isset($text['content']) ) $text['content'] = "<p>We've put together this page to provide customized information just for you.</p>";
		$first_name = '';
		$last_name = '';
		if ( $CI->session->userdata('first_name') ) $first_name = $CI->session->userdata('first_name');
		if ( $CI->session->userdata('last_name') ) $last_name = $CI->session->userdata('last_name');
		$search = array('[first-name]','[last-name]');
		$replace = array($first_name, $last_name);
		$text['title'] = str_replace($search, $replace, $text['title']);
		$text['content'] = str_replace($search, $replace, $text['content']);
		
		$col = '';
		$preview_col = '';
		
		if ( $position == 'footer' && $preview ) $preview_col =' col-md-2';
		elseif ( $position == 'footer' )$col = ' col-md-2';
		
		if ( $preview ):
?>

<div id="widget-<?=$widget->widget_type . '-' . $widget->id;?>" class="widget<?=$preview_col;?>" data-type="<?=$widget->widget_type;?>">
	<div class="widget-top">
		<div class="widget-title"><h4>Welcome Message</h4></div>
	</div>

	<div class="widget-inside">
		<button type="button" class="btn btn-warning btn-sm edit-widget" data-toggle="modal" data-target="#widget-<?=$widget->widget_type . '-' . $widget->id;?>-modal">Edit</button>
		<button type="button" class="btn btn-danger btn-sm delete-widget pull-right">Delete</button>
<?php
		endif;
?>
		<div class="widget text greeting<?=$col;?>">
			<div class="pull-left">
			     <div id="user_picture" style="max-width: 100px;"><img src="<?php echo get_user_picture_thumb(FALSE, 100, 100); ?>" alt="<?=$first_name;?> Picture" class="img-rounded"></div>
			    <div class="clearfix"></div>
                        </div>
		        <div class="media-body">
				<h4 class="media-heading"><?=$text['title'];?></h4>
				<?=$text['content'];?>
			</div>
			<div class="clearfix"></div>
		</div>
<?php
		if ( $preview ):
?>
	</div>
	<div class="widget-description">User picture and welcome words.</div>
</div>
<?php
		endif;
	}
}

if ( ! function_exists('draw_widget_testimonials')){
	function draw_widget_testimonials($widget, $position = FALSE, $preview = FALSE ){
		if ( ! is_object($widget) ) return FALSE;
		
		$testimonials = unserialize($widget->widget_data);
		
		if ( ! isset($testimonials['title']) ) $testimonials['title'] = 'Testimonials';
		if ( ! isset($testimonials['content']) && $preview) 
			$testimonials['content'] = '<button type="button" class="btn btn-warning edit-widget center-block" data-toggle="modal" data-target="#widget-testimonials-'.$widget->id.'-modal">Add Testimonials (Reviews)</button>';
		elseif ( ! isset($testimonials['content']) )
			$testimonials['content'] = '';
		
		$col = '';
		$preview_col = '';
		
		if ( $position == 'footer' && $preview ) $preview_col =' col-md-2';
		elseif ( $position == 'footer' )$col = ' col-md-2';
		
		if ( $preview ):
?>

<div id="widget-<?=$widget->widget_type . '-' . $widget->id;?>" class="widget<?=$preview_col;?>" data-type="<?=$widget->widget_type;?>">
	<div class="widget-top">
		<div class="widget-title"><h4>Testimonials (Reviews)<span class="in-widget-title"></span></h4></div>
	</div>

	<div class="widget-inside">
		<button type="button" class="btn btn-warning btn-sm edit-widget" data-toggle="modal" data-target="#widget-<?=$widget->widget_type . '-' . $widget->id;?>-modal">Edit</button>
		<button type="button" class="btn btn-danger btn-sm delete-widget pull-right">Delete</button>
<?php
		endif;
?>
		<div class="widget testimonials<?=$col;?>">
			<div class="panel panel-default widget-testimonials-full">
			        <div class="panel-heading">
			          <h3 class="panel-title"><?=$testimonials['title'];?></h3>
			        </div>
			        <div class="panel-body">
			          <?=$testimonials['content'];?>
			        </div>
			</div>
		</div>
<?php
		if ( $preview ):
?>
	</div>

	<div class="widget-description">Add Testimonials</div>
</div>
<?php
		endif;
	}
}

if ( ! function_exists('draw_widget_stories')){
	function draw_widget_stories($widget, $position = FALSE, $preview = FALSE ){
		if ( ! is_object($widget) ) return FALSE;
		
		$stories = unserialize($widget->widget_data);
		
		if ( ! isset($stories['title']) ) $stories['title'] = 'Client Stories';
		if ( ! isset($stories['content']) && $preview ) 
			$stories['content'] = '<div class="blank-widget text"><button type="button" class="btn btn-warning edit-widget center-block" data-toggle="modal" data-target="#widget-stories-'.$widget->id.'-modal">Add Client Stories</button></div>';
		elseif ( ! isset($stories['content']) )
			$stories['content'] = '';
		
		$col = '';
		$preview_col = '';
		
		if ( $position == 'footer' && $preview ) $preview_col =' col-md-2';
		elseif ( $position == 'footer' )$col = ' col-md-2';
		
		if ( $preview ):
?>

<div id="widget-<?=$widget->widget_type . '-' . $widget->id;?>" class="widget<?=$preview_col;?>" data-type="<?=$widget->widget_type;?>">
	<div class="widget-top">
		<div class="widget-title"><h4>Stories<span class="in-widget-title"></span></h4></div>
	</div>

	<div class="widget-inside">
		<button type="button" class="btn btn-warning btn-sm edit-widget" data-toggle="modal" data-target="#widget-<?=$widget->widget_type . '-' . $widget->id;?>-modal">Edit</button>
		<button type="button" class="btn btn-danger btn-sm delete-widget pull-right">Delete</button>
<?php
		endif;
?>
		<div class="widget stories<?=$col;?>">
			<h3 class="title"><?=$stories['title'];?></h3>
		        <?=$stories['content']; ?>
		</div>
<?php
		if ( $preview ):
?>
	</div>

	<div class="widget-description">Client stories link</div>
</div>
<?php
		endif;
	}
}

if ( ! function_exists('draw_widget_contact')){
	function draw_widget_contact($widget, $position = FALSE, $preview = FALSE ){
		if ( ! is_object($widget) ) return FALSE;
		
		$col = '';
		$preview_col = '';
		
		if ( $position == 'footer' && $preview ) $preview_col =' col-md-2';
		elseif ( $position == 'footer' )$col = ' col-md-2';
		
		if ( $preview ):
?>

<div id="widget-<?=$widget->widget_type . '-' . $widget->id;?>" class="widget<?=$preview_col;?>" data-type="<?=$widget->widget_type;?>">
	<div class="widget-top">
		<div class="widget-title"><h4>Contact<span class="in-widget-title"></span></h4></div>
	</div>

	<div class="widget-inside">
		<button type="button" class="btn btn-warning btn-sm edit-widget" data-toggle="modal" data-target="#widget-dummy-99-modal">Edit</button>
<?php
		endif;
?>
		<div class="widget contact<?=$col;?>">
			<img src="<?php echo base_url(); ?>img/img-appointment.png" width="175" height="156" alt="" class="pull-left"/>
			<div class="media-body">
				<div class="widget-box-text">Get a free review of your potential case.</div>
				<a href="http://www.siegfriedandjensen.com/free-case-review" class="btn btn-warning widget-box-btn">Click Here</a>
			</div>
			<div class="clearfix"></div>
		</div>
<?php
		if ( $preview ):
?>
	</div>

	<div class="widget-description">Add contact widget</div>
</div>
<?php
		endif;
	}
}

if ( ! function_exists('draw_widget_twitter')){
	function draw_widget_twitter($widget, $position = FALSE, $preview = FALSE ){
		if ( ! is_object($widget) ) return FALSE;
		
		$CI = get_instance();
		$CI->load->library('twitteroauth');
		$CI->config->load('twitter');
		$consumer_token  = $CI->config->item('twitter_consumer_key');
		$consumer_secret = $CI->config->item('twitter_consumer_secret');
		// check if user and pass are set
		if( !isset($consumer_secret) || !isset($consumer_token) || !$consumer_secret || !$consumer_token )
		{
			die('ERROR: Twitter secret and token is not defined.'.PHP_EOL);
		}
		if($widget->widget_data=='') {
			$widget->widget_data = 'a:1:{i:1;a:2:{s:5:"title";s:12:"Twitter Feed";s:7:"hashtag";s:12:"autoaccident";}}';
		}
		
		if ( $preview ):
?>
<div id="widget-<?=$widget->widget_type . '-' . $widget->id;?>" class="widget" data-type="<?=$widget->widget_type;?>">
	<div class="widget-top">
		<div class="widget-title"><h4>Twitter Feed<span class="in-widget-title"></span></h4></div>
	</div>

	<div class="widget-inside">
		<button type="button" class="btn btn-warning btn-sm edit-widget" data-toggle="modal" data-target="#widget-<?=$widget->widget_type . '-' . $widget->id;?>-modal">Edit</button><button type="button" class="btn btn-danger btn-sm delete-widget pull-right">Delete</button>
<?php
		endif;
		
		$twitters = unserialize($widget->widget_data);
		//i don't what is the plan, do you plan to support multiple hashtag?
		//for now I assume it store in wrong way
		if ( is_array($twitters) && count($twitters) > 0 ):
		
			$twitters = $twitters[1]; //-> remove this after fix store data function
		
?>
		<div class="widget twitter">
			<div class="panel panel-default widget-testimonials-full">
				<div class="panel-heading">
					<h3 class="panel-title"><?=$twitters['title'];?></h3>
				</div>
				<div class="panel-body">
<?php
		
			$twitter_connection = $CI->twitteroauth->create($consumer_token, $consumer_secret);
			
			if($twitters['hashtag']=='') {
				$twitters['hashtag'] = 'autoaccident';
			}
			
			$query = array(
			  "q" => "#".$twitters['hashtag']
			);
				
			$results = $twitter_connection->get('search/tweets', $query);
			
			$i = 0;
			foreach ($results->statuses as $result):
				$i++;
				if($i<=4):
?>
					   <div class="panel-review pull-left"> <img src="<?=$result->user->profile_image_url;?>" width="64" height="64" alt="" class="img64 pull-left"/>
						<div class="media-body">
						  <h4 class="media-heading"><?=$result->user->screen_name;?></h4>
						  <a href="#"><?=$result->text;?></a> </div>
					  </div>
<?php	
				endif;
			endforeach;
		endif; //if ( is_array($twitters) && count($twitters) > 0 ):
?>
				</div>
			</div>
		</div>
<?php 
		if ( $preview ): 
?>		
	</div>
	<div class="widget-description">Add Twitter Feeds</div>
</div>
<?php 
		endif;
	}
}

if ( ! function_exists('draw_modal_twitter')){
	function draw_modal_twitter( $widget ){
		if ( ! is_object($widget) ) return FALSE;
		$twitters = unserialize($widget->widget_data);
?>
<div class="modal fade twitter_modal" id="widget-<?=$widget->widget_type . '-' . $widget->id;?>-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Twitter</h4>
			</div>
			<div class="modal-body">
				<div id="widget-<?=$widget->id;?>-sort" class="twitter_sort">
				<?php
		$i = 1;
		if ( is_array($twitters) && count($twitters) > 0 ):
			foreach ( $twitters as $twitter ):
				?>
					<div id="widget-<?=$widget->widget_type . '-' . $widget->id.'-modal-'.$i;?>" class="twitter_form form-inline">
						<div class="form-group">
							<input type="text" name="twitter-title[<?=$i;?>]" value="<?=$twitter['title'];?>" class="form-control" placeholder="Title"/>
						</div>
						<div class="form-group">
							<div class="input-group">
								<div class="input-group-addon">#</div>
								<input type="text" name="twitter-hashtag[<?=$i;?>]" value="<?=$twitter['hashtag'];?>" class="form-control" placeholder="hashtag without #"/>
							</div>
						</div>
						<div class="form-group action-button">
							<span class="spinner"></span>
						</div>
					</div>
				<?php
			endforeach;
		else:
				?>
					<div id="widget-<?=$widget->widget_type . '-' . $widget->id.'-modal-'.$i;?>" class="twitter_form form-inline">
						<div class="form-group">
							<input type="text" name="twitter-title[<?=$i;?>]" value="Twitter Feed" class="form-control" placeholder="Title"/>
						</div>
						<div class="form-group">
							<div class="input-group">
								<div class="input-group-addon">#</div>
								<input type="text" name="twitter-hashtag[<?=$i;?>]" value="autoaccident" class="form-control" placeholder="hashtag without #"/>
							</div>
						</div>
						<div class="form-group action-button">
							<span class="spinner"></span>
						</div>
					</div>
				<?php
		endif;
				?>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-dismiss="modal">Save</button>
				<span class="spinner"></span>
			</div>
		</div>
	</div>
</div>
<?php
	}
}

if ( ! function_exists('draw_widget_faq')){
	function draw_widget_faq($widget, $position = FALSE, $preview = FALSE ){
		if ( ! is_object($widget) ) return FALSE;
		
		$faq = unserialize($widget->widget_data);
		
		if ( ! isset($faq['title']) ) $faq['title'] = 'FAQ';
		if ( ! isset($faq['content']) ) $faq['content'] = '';
		
		$col = '';
		$preview_col = '';
		
		if ( $position == 'footer' && $preview ) $preview_col =' col-md-2';
		elseif ( $position == 'footer' )$col = ' col-md-2';
		
		if ( $preview ):
?>

<div id="widget-<?=$widget->widget_type . '-' . $widget->id;?>" class="widget<?=$preview_col;?>" data-type="<?=$widget->widget_type;?>">
	<div class="widget-top">
		<div class="widget-title"><h4>FAQ<span class="in-widget-title"></span></h4></div>
	</div>

	<div class="widget-inside">
		<button type="button" class="btn btn-warning btn-sm edit-widget" data-toggle="modal" data-target="#widget-<?=$widget->widget_type . '-' . $widget->id;?>-modal">Edit</button><button type="button" class="btn btn-danger btn-sm delete-widget pull-right">Delete</button>
<?php
		endif;
?>
		<div class="widget faq<?=$col;?>">
			<h3 class="title"><?=$faq['title'];?></h3>
			<?php
				if ( $faq['content'] != '' ):
					echo$faq['content'];
				elseif($preview):
					echo '<div class="blank-widget faqs"><button type="button" class="btn btn-warning edit-widget center-block" data-toggle="modal" data-target="#widget-'.$widget->widget_type . '-' . $widget->id.'-modal">Add FAQ</button></div>';
				endif;
			?>
		</div>
<?php
		if ( $preview ):
?>
	</div>
	
	<div class="widget-description">Add FAQ</div>
</div>
<?php
		endif;
	}
}

if ( ! function_exists('draw_widget_links')){
	function draw_widget_links($widget, $position = FALSE, $preview = FALSE ){
		if ( ! is_object($widget) ) return FALSE;
		$links = unserialize($widget->widget_data);
		
		if ( ! isset($links['title']) ) $links['title'] = 'Links';
		
		$col = '';
		$preview_col = '';
		
		if ( $position == 'footer' && $preview ) $preview_col =' col-md-2';
		elseif ( $position == 'footer' )$col = ' col-md-2';
		
		if ( $preview ):
?>

<div id="widget-<?=$widget->widget_type . '-' . $widget->id;?>" class="widget<?=$preview_col;?>" data-type="<?=$widget->widget_type;?>">
	<div class="widget-top">
		<div class="widget-title"><h4><?=$links['title'];?><span class="in-widget-title"></span></h4></div>
	</div>

	<div class="widget-inside">
		<button type="button" class="btn btn-warning btn-sm edit-widget" data-toggle="modal" data-target="#widget-<?=$widget->widget_type . '-' . $widget->id;?>-modal">Edit</button><button type="button" class="btn btn-danger btn-sm delete-widget pull-right">Delete</button>
<?php
		endif;
?>
		<div class="widget links<?=$col;?>">
			<h3 class="title"><?=$links['title'];?></h3>
			<?php
				if ( isset($links['links']) && is_array($links['links']) && count($links['links']) > 0 ):
					echo '<ul>';
					foreach ( $links['links'] as $link ):
						echo '<li><a href="'.$link['url'].'">'.$link['title'].'</a></li>';
					endforeach;
					echo '</ul>';
				elseif ( $preview ):
					echo '<div class="blank-widget faqs"><button type="button" class="btn btn-warning edit-widget center-block" data-toggle="modal" data-target="#widget-'.$widget->widget_type . '-' . $widget->id.'-modal">Add Link</button></div>';
				endif;
			?>
		</div>
<?php
		if ( $preview ):
?>
	</div>
	
	<div class="widget-description">Add Link</div>
</div>
<?php
		endif;
	}
}

if ( ! function_exists('draw_widget_text')){
	function draw_widget_text($widget, $position = FALSE, $preview = FALSE ){
		if ( ! is_object($widget) ) return FALSE;
		$text = unserialize($widget->widget_data);
		$box_style = 'style="';
		$title_style = 'style="';
		if ( isset($text['border-color']) && $text['border-color']  != '') $box_style .= 'border: 1px solid ' . $text['border-color'] . ';';
		if ( isset($text['background-color']) && $text['background-color']  != '') $box_style .= ' background-color: ' . $text['background-color'] . ';';
		if ( isset($text['title-color']) && $text['title-color']  != '') $title_style .= 'color: ' . $text['title-color'] . '; border-color: ' . $text['title-color'] . ';';
		if ( isset($text['text-color']) && $text['text-color']  != '') $box_style .= ' color: ' . $text['text-color'] . ';';
		$box_style .= '"';
		$title_style .= '"';
		
		if ( ! isset($text['title']) ) $text['title'] = '';
		
		$col = '';
		$preview_col = '';
		
		if ( $position == 'footer' && $preview ) $preview_col =' col-md-2';
		elseif ( $position == 'footer' )$col = ' col-md-2';
		
		if ( $preview ):
?>

<div id="widget-<?=$widget->widget_type . '-' . $widget->id;?>" class="widget<?=$preview_col;?>" data-type="<?=$widget->widget_type;?>">
	<div class="widget-top">
		<div class="widget-title"><h4><?=$text['title'];?><span class="in-widget-title"></span></h4></div>
	</div>

	<div class="widget-inside">
		<button type="button" class="btn btn-warning btn-sm edit-widget" data-toggle="modal" data-target="#widget-<?=$widget->widget_type . '-' . $widget->id;?>-modal">Edit</button><button type="button" class="btn btn-danger btn-sm delete-widget pull-right">Delete</button>
<?php
		endif;
?>
		<div class="widget text<?=$col;?>" <?=$box_style;?>>
			<?php if ( $text['title'] != '' ): ?>
			<h3 class="title" <?=$title_style;?>><?=$text['title'];?></h3>
			<?php endif; ?>
			<?php
				if ( isset($text['content']) && $text['content'] != ''  ):
					echo $text['content'];
				elseif( $preview ):
					echo '<div class="blank-widget text"><button type="button" class="btn btn-warning edit-widget center-block" data-toggle="modal" data-target="#widget-'.$widget->widget_type . '-' . $widget->id.'-modal">Add Text</button></div>';
				endif;
			?>
		</div>
<?php
		if ( $preview ):
?>
	</div>
	
	<div class="widget-description">Add Text</div>
</div>
<?php
		endif;
	}
}
	
if ( ! function_exists('draw_main_image_modal')){
	function draw_main_image_modal( $main_images ){
		
?>
<div class="modal fade" id="main-image-slider" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Featured Image</h4>
      </div>
      <div class="modal-body" id="main_images_upload">
		<div id="main_image_sort">
			<?php
			if ( isset($main_images)  && $main_images): 
				foreach($main_images->result() as $image):
			?>
			<div id="slider-image-<?=$image->id;?>" class="slider-image-form form-inline" data-img-url="<?=base_url() . str_replace('./', '',  create_thumb($image->path, 770, 366) );?>"  role="form">
				<div class="image-wrapper form-group"><img src="<?=base_url() . str_replace('./', '',  create_thumb($image->path, 100, 100) );?>" width="100" height="100" alt="<?=$image->title;?>"/></div>
				<div class="image-title form-group">
					<input type="text" name="image-title[<?=$image->id;?>]" value="<?=$image->title;?>" class="form-control" placeholder="Title"/>
					<span class="image-control">
						<button type="button" class="btn btn-primary btn-sm save-image">Save</button>
						<button type="button" class="btn btn-danger btn-sm delete-image">Delete</button>
						<span class="spinner"></span>
					</span>
				</div>
				<div class="image-desc form-group"><textarea name="image-desc[<?=$image->id;?>]" class="form-control" placeholder="Short Description"><?=$image->description;?></textarea></div>
				
			</div>
			<?php
				endforeach;
			endif;	
			?>
		</div>
      </div>
      <div class="modal-footer">
      	<a id="upload_main_image" href="#" class="btn btn-primary btn-sm active pull-left" role="button">Add New Slide</a>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <span class="spinner"></span>
        <p class="clearfix" style="text-align: left; margin-top: 10px;"><b>Note: </b>Max image file size 10MB, width 1024px and height 768px. Only jpg/jpeg/png allowed. Best Dimension 770x366px.</p>
      </div>
    </div>
  </div>
</div>
<?php
		
	}
}

if ( ! function_exists('draw_video_modal')){
	function draw_video_modal( $videos ){
?>
<div class="modal fade" id="videos-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Video</h4>
      </div>
      <div class="modal-body" id="videos-sort-container">
		<div id="videos-sort">
			<?php
			if ( isset($videos) && $videos ):
				foreach($videos->result() as $video):
			?>
			<div id="video-thumb-<?=$video->id;?>" class="video-form form-inline" data-img-url="<?=base_url() . str_replace('./', '',  create_thumb($video->thumb, 746, 439) );?>"  role="form">
				<div class="image-wrapper form-group"><img src="<?=base_url() . str_replace('./', '',  create_thumb($video->thumb, 211, 126) );?>" width="211" height="126" alt=""/></div>
				<div class="image-title form-group">
					<input type="text" name="video-url[<?=$video->id;?>]" value="<?=$video->url;?>" class="form-control" placeholder="Youtube URL"/>
					<span class="image-control">
						<button type="button" class="btn btn-primary btn-sm save-video">Save</button>
						<button type="button" class="btn btn-danger btn-sm delete-video">Delete</button>
						<span class="spinner"></span>
					</span>
				</div>
			</div>
			<?php
				endforeach;
			endif;	
			?>
		</div>
      </div>
      <div class="modal-footer">
      	<a id="upload_video_thumb" href="#" class="btn btn-primary btn-sm active pull-left" role="button">Add Video</a>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <span class="spinner"></span>
        <p class="clearfix" style="text-align: left; margin-top: 10px;">Press Add Video button to select your video thumbnail and then place Youtube URL.<br /><b>Note:</b> Maximum video thumbnail size is 10MB, width 1024px and height 768px. Only jpg/jpeg/png allowed. Best Dimension 746x439px.</p>
      </div>
    </div>
  </div>
</div>
<?php
	}
}

if ( ! function_exists('draw_modal_faq')){
	function draw_modal_faq( $widget ){
		if ( ! is_object($widget) ) return FALSE;
		$faq = unserialize($widget->widget_data);
		
		if ( ! isset($faq['title']) ) $faq['title'] = '';
		if ( ! isset($faq['content']) ) $faq['content'] = '';
?>
<div class="modal fade faq_modal" id="widget-<?=$widget->widget_type . '-' . $widget->id;?>-modal" tabindex="-1" data-widget-type="faq" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">FAQ</h4>
			</div>
			<div class="modal-body">
				<input type="text" name="text-title" value="<?=$faq['title'];?>" class="form-control" placeholder="Title"/>
				<textarea id="widget-<?=$widget->widget_type . '-' . $widget->id;?>-content" name="text-content" class="form-control tinymce" style="width:100%; height:300px"><?=$faq['content'];?></textarea>
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">Save</button>
				<span class="spinner"></span>
			</div>
		</div>
	</div>
</div>
<?php
	}
}

if ( ! function_exists('draw_modal_links')){
	function draw_modal_links( $widget ){
		if ( ! is_object($widget) ) return FALSE;
		$links = unserialize($widget->widget_data);
		
		if ( ! isset($links['title']) ) $links['title'] = 'Links';
?>
<div class="modal fade links_modal" id="widget-<?=$widget->widget_type . '-' . $widget->id;?>-modal" tabindex="-1" data-widget-type="links" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Link</h4>
			</div>
			<div class="modal-body">
				<a href="#" class="btn btn-primary btn-sm active add-link" role="button">Add Link</a>
				
				<p>Link url must lead with http:// or https://.</p>
				
				<input type="text" name="links-title" value="<?=$links['title'];?>" class="form-control" placeholder="Links Title"/>
				
				<div id="widget-<?=$widget->widget_type . '-' . $widget->id;?>-sort" class="link_sort">
				
				<?php
				if ( isset($links['links']) && is_array($links['links']) && count($links['links']) > 0 ):
					$i = 1;
					foreach ( $links['links'] as $link ):
				?>
					<div id="widget-<?=$widget->widget_type . '-' . $widget->id.'-modal-'.$i;?>" class="link_form form-inline">
						<div class="form-group">
							<input type="text" name="link-title[<?=$i;?>]" value="<?=$link['title'];?>" class="form-control" placeholder="Title"/>
						</div>
						<div class="form-group">
							<input type="text" name="link-url[<?=$i;?>]" value="<?=$link['url'];?>" class="form-control form_url" placeholder="External Link"/>
						</div>
						<div class="form-group action-button">
							<button type="button" class="btn btn-danger btn-sm delete-link">Delete</button>
							<span class="ui-icon ui-icon-arrowthick-2-n-s sort-handle"></span>
							<span class="spinner"></span>
						</div>
					</div>
				<?php
						$i++;
					endforeach;
				endif;
				?>
				</div>
			</div>
			<div class="modal-footer">
				<p>Close this to save your data!</p>
				<span class="spinner"></span>
			</div>
		</div>
	</div>
</div>
<?php
	}
}

if ( ! function_exists('draw_modal_text')){
	function draw_modal_text( $widget ){
		if ( ! is_object($widget) ) return FALSE;
		$text = unserialize($widget->widget_data);
		
		if ( ! isset($text['title']) ) $text['title'] = '';
		if ( ! isset($text['content']) ) $text['content'] = '';
		if ( ! isset($text['border-color']) ) $text['border-color'] = '';
		if ( ! isset($text['background-color']) ) $text['background-color'] = '';
		if ( ! isset($text['title-color']) ) $text['title-color'] = '';
		if ( ! isset($text['text-color']) ) $text['text-color'] = '';
?>
<div class="modal fade text_modal" id="widget-<?=$widget->widget_type . '-' . $widget->id;?>-modal" tabindex="-1" data-widget-type="text" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Text</h4>
			</div>
			<div class="modal-body">
				
				<input type="text" name="text-title" value="<?=$text['title'];?>" class="form-control" placeholder="Title"/>
				<textarea id="widget-<?=$widget->widget_type . '-' . $widget->id;?>-content" name="text-content" class="form-control tinymce" style="width:100%; height:300px"><?=$text['content'];?></textarea>

				<div class="row" style="margin-top: 20px;">
					<div class="col-md-3">
						<div class="form-group">
							<label for="border-color">Border Color</label>
							<div class="input-group color-picker">
								<input type="text" class="form-control" name="border-color" value="<?=$text['border-color'];?>">
								<span class="input-group-addon"><i></i></span>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="background-color">Background Color</label>
							<div class="input-group color-picker">
								<input type="text" class="form-control" name="background-color" value="<?=$text['background-color'];?>">
								<span class="input-group-addon"><i></i></span>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="title-color">Title Color</label>
							<div class="input-group color-picker">
								<input type="text" class="form-control" name="title-color" value="<?=$text['title-color'];?>">
								<span class="input-group-addon"><i></i></span>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="text-color">Text Color</label>
							<div class="input-group color-picker">
								<input type="text" class="form-control" name="text-color" value="<?=$text['text-color'];?>">
								<span class="input-group-addon"><i></i></span>
							</div>
						</div>
					</div>
				</div>
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">Save</button>
				<span class="spinner"></span>
			</div>
		</div>
	</div>
</div>
<?php
	}
}

if ( ! function_exists('draw_modal_testimonials')){
	function draw_modal_testimonials( $widget ){
		if ( ! is_object($widget) ) return FALSE;
		$text = unserialize($widget->widget_data);
		
		if ( ! isset($text['title']) ) $text['title'] = '';
		if ( ! isset($text['content']) ) $text['content'] = '';
?>
<div class="modal fade text_modal" id="widget-<?=$widget->widget_type . '-' . $widget->id;?>-modal" data-widget-type="testimonials" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Testimonial</h4>
			</div>
			<div class="modal-body">
				
				<input type="text" name="text-title" value="<?=$text['title'];?>" class="form-control" placeholder="Title"/>
				<textarea id="widget-<?=$widget->widget_type . '-' . $widget->id;?>-content" name="text-content" class="form-control tinymce" style="width:100%; height:300px"><?=$text['content'];?></textarea>
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">Save</button>
				<span class="spinner"></span>
			</div>
		</div>
	</div>
</div>
<?php
	}
}

if ( ! function_exists('draw_modal_stories')){
	function draw_modal_stories( $widget ){
		if ( ! is_object($widget) ) return FALSE;
		$text = unserialize($widget->widget_data);
		
		if ( ! isset($text['title']) ) $text['title'] = '';
		if ( ! isset($text['content']) ) $text['content'] = '';
?>
<div class="modal fade text_modal" id="widget-<?=$widget->widget_type . '-' . $widget->id;?>-modal" data-widget-type="stories" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Client Stories</h4>
			</div>
			<div class="modal-body">
				
				<input type="text" name="text-title" value="<?=$text['title'];?>" class="form-control" placeholder="Title"/>
				<textarea id="widget-<?=$widget->widget_type . '-' . $widget->id;?>-content" name="text-content" class="form-control tinymce" style="width:100%; height:300px"><?=$text['content'];?></textarea>
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">Save</button>
				<span class="spinner"></span>
			</div>
		</div>
	</div>
</div>
<?php
	}
}

if ( ! function_exists('draw_modal_contact')){
	function draw_modal_contact( $widget ){
		if ( ! is_object($widget) ) return FALSE;
		$text = unserialize($widget->widget_data);
		
		if ( ! isset($text['title']) ) $text['title'] = '';
		if ( ! isset($text['content']) ) $text['content'] = '';
?>
<div class="modal fade contact_modal" id="widget-<?=$widget->widget_type . '-' . $widget->id;?>-modal" data-widget-type="contact" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Contact</h4>
			</div>
			<div class="modal-body">
				
				<input type="text" name="text-title" value="<?=$text['title'];?>" class="form-control" placeholder="Title"/>
				<textarea id="widget-<?=$widget->widget_type . '-' . $widget->id;?>-content" name="text-content" class="form-control tinymce" style="width:100%; height:300px"><?=$text['content'];?></textarea>
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">Save</button>
				<span class="spinner"></span>
			</div>
		</div>
	</div>
</div>
<?php
	}
}

if ( ! function_exists('draw_modal_greeting')){
	function draw_modal_greeting( $widget ){
		if ( ! is_object($widget) ) return FALSE;
		$text = unserialize($widget->widget_data);
		
		if ( ! isset($text['title']) ) $text['title'] = '';
		if ( ! isset($text['content']) ) $text['content'] = '';
?>
<div class="modal fade text_modal" id="widget-<?=$widget->widget_type . '-' . $widget->id;?>-modal" data-widget-type="greeting" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Welcome widget</h4>
			</div>
			<div class="modal-body">
				
				<input type="text" name="text-title" value="<?=$text['title'];?>" class="form-control" placeholder="Title"/>
				<textarea id="widget-<?=$widget->widget_type . '-' . $widget->id;?>-content" name="text-content" class="form-control tinymce" style="width:100%; height:100px"><?=$text['content'];?></textarea>
				<p>You can use [first-name] or [last-name] to view user First Name or Last Name.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">Save</button>
				<span class="spinner"></span>
			</div>
		</div>
	</div>
</div>
<?php
	}
}

if ( ! function_exists('the_widgets')){
	function the_widgets( ){
?>
<div id="widget-greeting-__i__" class="widget" data-type="greeting">
	<div class="widget-top">
		<div class="widget-title"><h4>Welcome<span class="in-widget-title"></span></h4></div>
	</div>

	<div class="widget-inside">
		<button type="button" class="btn btn-warning btn-sm edit-widget" data-toggle="modal" data-target="#widget-dummy-99-modal">Edit</button>
		<button type="button" class="btn btn-danger btn-sm delete-widget pull-right">Delete</button>
		<div class="widget text greeting">
			<div class="pull-left">
			     <div id="user_picture" style="max-width: 100px;"><img src="<?php echo get_user_picture_thumb(FALSE, 100, 100); ?>" alt="<?php if ( isset($user->first_name) ) echo $user->first_name; ?> Picture" class="img-rounded"></div>
			    <div class="clearfix"></div>
                        </div>
		        <div class="media-body">
				<h4 class="media-heading"><?php echo get_user_fullname(); ?></h4>
				<p>We've put together this page to provide customized information just for you.</p>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>

	<div class="widget-description">User picture and welcome words.</div>
</div>

<div id="widget-testimonials-__i__" class="widget"  data-type="testimonials">
	<div class="widget-top">
		<div class="widget-title"><h4>Testimonials (Reviews)<span class="in-widget-title"></span></h4></div>
	</div>

	<div class="widget-inside">
		<button type="button" class="btn btn-warning btn-sm edit-widget" data-toggle="modal" data-target="#widget-testimonials-__i__-modal">Edit</button>
		<button type="button" class="btn btn-danger btn-sm delete-widget pull-right">Delete</button>
		<div class="widget testimonials">
			<div class="panel panel-default widget-testimonials-full">
			        <div class="panel-heading">
			          <h3 class="panel-title">Testimonials (Reviews)</h3>
			        </div>
			        <div class="panel-body">
			          <button type="button" class="btn btn-warning edit-widget center-block" data-toggle="modal" data-target="#widget-testimonials-__i__-modal">Add Testimonials (Reviews)</button>
			        </div>
			</div>
		</div>
	</div>

	<div class="widget-description">Add Testimonials</div>
</div>

<div id="widget-stories-__i__" class="widget"  data-type="stories">
	<div class="widget-top">
		<div class="widget-title"><h4>Client Stories<span class="in-widget-title"></span></h4></div>
	</div>

	<div class="widget-inside">
		<button type="button" class="btn btn-warning btn-sm edit-widget" data-toggle="modal" data-target="#widget-stories-__i__-modal">Edit</button>
		<button type="button" class="btn btn-danger btn-sm delete-widget pull-right">Delete</button>
		<div class="widget stories">
			<h3 class="title">Client Stories</h3>
			<div class="blank-widget text"><button type="button" class="btn btn-warning edit-widget center-block" data-toggle="modal" data-target="#widget-stories-__i__-modal">Add Client Stories</button></div>
		</div>
	</div>

	<div class="widget-description">Add Client Stories</div>
</div>
				
<div id="widget-twitter-feed-__i__" class="widget" data-type="twitter">
	<div class="widget-top">
		<div class="widget-title"><h4>Twitter Feed<span class="in-widget-title"></span></h4></div>
	</div>

	<div class="widget-inside">
		<button type="button" class="btn btn-warning btn-sm edit-widget" data-toggle="modal" data-target="#widget-twitter-60-modal">Edit</button><button type="button" class="btn btn-danger btn-sm delete-widget pull-right">Delete</button>

		<div class="widget twitter">
			<div class="panel panel-default widget-testimonials-full">
				<div class="panel-heading">
				  <h3 class="panel-title">Twitter Feed</h3>
				</div>
				<div class="panel-body">
				  
				</div>
			</div>
		</div>
	</div>
	<div class="widget-description">Add Twitter Feeds</div>

</div>

<div id="widget-faq-__i__" class="widget" data-type="faq">
	<div class="widget-top">
		<div class="widget-title"><h4>FAQ<span class="in-widget-title"></span></h4></div>
	</div>

	<div class="widget-inside">
		<button type="button" class="btn btn-warning btn-sm edit-widget" data-toggle="modal" data-target="#widget-faq-__i__-modal">Edit</button><button type="button" class="btn btn-danger btn-sm delete-widget pull-right">Delete</button>
		<div class="widget faq">
			<h3 class="title">FAQ</h3>
			<div class="blank-widget faqs"><button type="button" class="btn btn-warning edit-widget center-block" data-toggle="modal" data-target="#widget-faq-__i__-modal">Add FAQ</button></div>
		</div>
	</div>

	<div class="widget-description">Add FAQ</div>
</div>

<div id="widget-text-__i__" class="widget" data-type="text">
	<div class="widget-top">
		<div class="widget-title"><h4>Text<span class="in-widget-title"></span></h4></div>
	</div>

	<div class="widget-inside">
		<button type="button" class="btn btn-warning btn-sm edit-widget" data-toggle="modal" data-target="#widget-text-__i__-modal">Edit</button><button type="button" class="btn btn-danger btn-sm delete-widget pull-right">Delete</button>
		<div class="widget text">
			<h3 class="title">Text</h3>
			<div class="blank-widget text"><button type="button" class="btn btn-warning edit-widget center-block" data-toggle="modal" data-target="#widget-text-__i__-modal">Add Text</button></div>
		</div>
	</div>

	<div class="widget-description">Add Text</div>
</div>
<?php
	}
}