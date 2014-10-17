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
			$CI->image_lib->clear(); 
			return FALSE;
		else:
			$CI->image_lib->clear(); 
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
				$row->picture = './img/default-profile-photo.jpg';
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
			elseif ( $widget->widget_type == 'foursquare' ):
				draw_widget_foursquare($widget, $position, $preview);
			elseif ( $widget->widget_type == 'faq' ):
				draw_widget_faq($widget, $position, $preview);
			elseif ( $widget->widget_type == 'text' ):
				draw_widget_text($widget, $position, $preview);
			elseif ( $widget->widget_type == 'download' ):
				draw_widget_text($widget, $position, $preview);
			elseif ( $widget->widget_type == 'coupon' ):
				draw_widget_text($widget, $position, $preview);
			elseif ( $widget->widget_type == 'products' ):
				draw_widget_text($widget, $position, $preview);
			elseif ( $widget->widget_type == 'services' ):
				draw_widget_text($widget, $position, $preview);
			elseif ( $widget->widget_type == 'gmap' ):
				draw_widget_gmap($widget, $position, $preview);
			elseif ( $widget->widget_type == 'yreview' ):
				draw_widget_yreview($widget, $position, $preview);
			elseif ( $widget->widget_type == 'forumfeed' ):
				draw_widget_feed($widget, $position, $preview);
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
				elseif ( $widget->widget_type == 'foursquare' ):
					draw_modal_foursquare($widget);
				elseif ( $widget->widget_type == 'faq' ):
					draw_modal_faq($widget);
				elseif ( $widget->widget_type == 'text' ):
					draw_modal_text($widget);
				elseif ( $widget->widget_type == 'yreview' ):
					draw_modal_yreview($widget);
				elseif ( $widget->widget_type == 'download' ):
					draw_modal_text($widget);
				elseif ( $widget->widget_type == 'coupon' ):
					draw_modal_text($widget);
				elseif ( $widget->widget_type == 'products' ):
					draw_modal_text($widget);
				elseif ( $widget->widget_type == 'services' ):
					draw_modal_text($widget);
				elseif ( $widget->widget_type == 'forumfeed' ):
					draw_modal_feed($widget);
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
			elseif ( $widget->widget_type == 'foursquare' ):
				draw_modal_foursquare($widget);
			elseif ( $widget->widget_type == 'faq' ):
				draw_modal_faq($widget);
			elseif ( $widget->widget_type == 'text' ):
				draw_modal_text($widget);
			elseif ( $widget->widget_type == 'yreview' ):
				draw_modal_yreview($widget);
			elseif ( $widget->widget_type == 'download' ):
				draw_modal_text($widget);
			elseif ( $widget->widget_type == 'coupon' ):
				draw_modal_text($widget);
			elseif ( $widget->widget_type == 'products' ):
				draw_modal_text($widget);
			elseif ( $widget->widget_type == 'services' ):
				draw_modal_text($widget);
			elseif ( $widget->widget_type == 'forumfeed' ):
				draw_modal_feed($widget);
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
		if ( (! isset($text['content']) || $text['content'] == '') && NULL != $CI->config->item('greeting_widget_text') && $CI->config->item('greeting_widget_text') != '' ) $text['content'] = $CI->config->item('greeting_widget_text');
		if ( ! isset($text['content']) ) $text['content'] = "<p>We've put together this page to provide customized information just for you.</p>";
		$first_name = '';
		$last_name = '';
		$city = '';
		if ( $CI->session->userdata('first_name') ) $first_name = $CI->session->userdata('first_name');
		if ( $CI->session->userdata('last_name') ) $last_name = $CI->session->userdata('last_name');
		if ( $CI->session->userdata('city') ) $city = $CI->session->userdata('city');
		$search = array('[first-name]','[last-name]','[city]');
		$replace = array($first_name, $last_name, $city);
		$text['title'] = str_replace($search, $replace, $text['title']);
		$text['content'] = str_replace($search, $replace, $text['content']);
		
		if ( $preview ):
?>

<div id="widget-<?=$widget->widget_type . '-' . $widget->id;?>" class="widget widget-wrapper" data-type="<?=$widget->widget_type;?>">
	<div class="widget-top">
		<div class="widget-title"><h4>Welcome Message</h4></div>
		<div class="widget-action">
			<span class="glyphicon glyphicon-move move-widget" title="Move"></span>
			<span class="glyphicon glyphicon-edit edit-widget" data-toggle="modal" data-target="#widget-<?=$widget->widget_type . '-' . $widget->id;?>-modal" title="Edit"></span>
			<span class="glyphicon glyphicon-remove delete-widget" title="Delete"></span>
		</div>
	</div>

	<div class="widget-inside">
<?php
		endif;
?>
		<div class="widget text greeting">
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
		
		if ( $preview ):
?>

<div id="widget-<?=$widget->widget_type . '-' . $widget->id;?>" class="widget widget-wrapper" data-type="<?=$widget->widget_type;?>">
	<div class="widget-top">
		<div class="widget-title"><h4>Testimonials (Reviews)<span class="in-widget-title"></span></h4></div>
		<div class="widget-action">
			<span class="glyphicon glyphicon-move move-widget" title="Move"></span>
			<span class="glyphicon glyphicon-edit edit-widget" data-toggle="modal" data-target="#widget-<?=$widget->widget_type . '-' . $widget->id;?>-modal" title="Edit"></span>
			<span class="glyphicon glyphicon-remove delete-widget" title="Delete"></span>
		</div>
	</div>

	<div class="widget-inside">
<?php
		endif;
?>
		<div class="widget testimonials">
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
		
		if ( $preview ):
?>

<div id="widget-<?=$widget->widget_type . '-' . $widget->id;?>" class="widget widget-wrapper" data-type="<?=$widget->widget_type;?>">
	<div class="widget-top">
		<div class="widget-title"><h4>Stories<span class="in-widget-title"></span></h4></div>
		<div class="widget-action">
			<span class="glyphicon glyphicon-move move-widget" title="Move"></span>
			<span class="glyphicon glyphicon-edit edit-widget" data-toggle="modal" data-target="#widget-<?=$widget->widget_type . '-' . $widget->id;?>-modal" title="Edit"></span>
			<span class="glyphicon glyphicon-remove delete-widget" title="Delete"></span>
		</div>
	</div>

	<div class="widget-inside">
<?php
		endif;
?>
		<div class="widget stories">
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
		
		if ( $preview ):
?>

<div id="widget-<?=$widget->widget_type . '-' . $widget->id;?>" class="widget widget-wrapper" data-type="<?=$widget->widget_type;?>">
	<div class="widget-top">
		<div class="widget-title"><h4>Contact<span class="in-widget-title"></span></h4></div>
		
		<div class="widget-action">
			<span class="glyphicon glyphicon-move move-widget" title="Move"></span>
			<span class="glyphicon glyphicon-edit edit-widget"></span>
			<span class="glyphicon glyphicon-remove delete-widget" title="Delete"></span>
		</div>
	</div>

	<div class="widget-inside">
<?php
		endif;
?>
		<div class="widget contact"style="background: url(<?php echo base_url(); ?>img/learning-outdoors.jpg) no-repeat center center; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; background-size: cover;">
			<div class="aloha-editable">
				<h3 class="title">Learning in the great outdoors.</h3>
				<p>Meet Rob Ryan and learn about the Sunflower Grown Companies system.</p>
				<p><a class="btn btn-default btn-lg active" href="http://www.heropartners.com/retreats">Sign Up</a></p>
			</div>
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

if ( ! function_exists('draw_widget_gmap')){
	function draw_widget_gmap($widget, $position = FALSE, $preview = FALSE ){
		if ( ! is_object($widget) ) return FALSE;
		
		if ( $preview ):
?>

<div id="widget-<?=$widget->widget_type . '-' . $widget->id;?>" class="widget widget-wrapper" data-type="<?=$widget->widget_type;?>">
	<div class="widget-top">
		<div class="widget-title"><h4>Google Map<span class="in-widget-title"></span></h4></div>
		<div class="widget-action">
			<span class="glyphicon glyphicon-move move-widget" title="Move"></span>
			<span class="glyphicon glyphicon-edit edit-widget" data-toggle="modal" data-target="#widget-<?=$widget->widget_type . '-' . $widget->id;?>-modal" title="Edit"></span>
			<span class="glyphicon glyphicon-remove delete-widget" title="Delete"></span>
		</div>
	</div>

	<div class="widget-inside">
<?php
		endif;
?>
		<div class="widget gmap">
			<div class="col-md-6 gmap-container"><iframe width="374" height="260" frameborder="0" style="border:0"src="https://www.google.com/maps/embed/v1/place?q=560+E+500+S,+Salt+Lake+City,+UT+84102,+United+States&key=AIzaSyDJl-y_I_6stRCFmDvJbZMmojGjQdXbX2s"></iframe></div>
			<div class="contact-info">
				<h3 class="title">Contact Us</h3>
				<div class="aloha-editable">
					<p>560 E 500 S Salt Lake City, UT 84102</p>
					<p>801-935-4928</p>
					<p>info@heropartners.com</p>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
<?php
		if ( $preview ):
?>
	</div>

	<div class="widget-description">Add Google Map widget</div>
</div>
<?php
		endif;
	}
}

if ( ! function_exists('draw_widget_yreview')){
	function draw_widget_yreview($widget, $position = FALSE, $preview = FALSE ){
		if ( ! is_object($widget) ) return FALSE;
		
		$yelp = unserialize($widget->widget_data);
		
		if ( ! isset($yelp['business_id']) ) $yelp['business_id'] = '';
		
		if ( $yelp['business_id'] != '' ):
			$CI = get_instance();
			$CI->load->library('yelpoauth');
			$CI->config->load('yelp');
			$business_detail = json_decode($CI->yelpoauth->get_business( $yelp['business_id'] ));
		endif;
		
		if ( $preview ):
?>

<div id="widget-<?=$widget->widget_type . '-' . $widget->id;?>" class="widget widget-wrapper" data-type="<?=$widget->widget_type;?>">
	<div class="widget-top">
		<div class="widget-title"><h4>Yelp Reviews<span class="in-widget-title"></span></h4></div>
		<div class="widget-action">
			<span class="glyphicon glyphicon-move move-widget" title="Move"></span>
			<span class="glyphicon glyphicon-edit edit-widget" data-toggle="modal" data-target="#widget-<?=$widget->widget_type . '-' . $widget->id;?>-modal" title="Edit"></span>
			<span class="glyphicon glyphicon-remove delete-widget" title="Delete"></span>
		</div>
	</div>

	<div class="widget-inside">
<?php
		endif;
?>
		<div class="widget yreview">
			<?php if ( isset($business_detail) ): ?>
				<div class="search-result natural-search-result biz-listing-large clearfix" id="<?php echo $business_detail->id; ?>">
					<div class="main-attributes">
						<div class="media-block media-block-large">
							<div class="media-avatar">
								<div class="photo-box pb-90s">
									<img alt="<?php echo $business_detail->name; ?>" class="photo-box-img" height="90" src="<?php echo $business_detail->image_url; ?>" width="90">
								</div>
							</div>
							<div class="media-story">
								<h3 class="search-result-title"><a href="<?php echo $business_detail->url; ?>" target="_blank"><?php echo $business_detail->name; ?></a></h3>
								<div class="biz-rating biz-rating-large clearfix">
									<div class="rating-large">
										<i class="star-img stars_4" title="4.0 star rating">
											<img alt="4.0 star rating" class="offscreen" height="30" src="<?php echo $business_detail->rating_img_url_large; ?>" width="166">
										</i>
									</div>
									<span class="review-count rating-qualifier"><?php echo $business_detail->review_count;?> reviews</span>
								</div>
								<div class="price-category">
									<span class="category-str-list"><?php echo implode(', ', $business_detail->categories[0]);?></span>
								</div>
								<ul class="tags"></ul>
							</div>
						</div>
					</div>

					<div class="secondary-attributes">
						<span class="neighborhood-str-list"><?php echo implode(', ', $business_detail->location->neighborhoods); ?></span>
						<address><?php echo implode(', ', $business_detail->location->display_address); ?></address>
						<span class="offscreen">Phone number</span>
						<span class="biz-phone"><?php if ( isset($business_detail->display_phone) )echo $business_detail->display_phone;?></span>
					</div>

					<div class="snippet-block review-snippet">
						<div class="media-block">
							<div class="media-avatar">
								<div class="photo-box pb-30s">
										<img alt="<?php echo $business_detail->reviews[0]->user->name;?>" class="photo-box-img" height="30" src="<?php echo $business_detail->reviews[0]->user->image_url;?>" width="30">
									</a>
								</div>
							</div>
							<div class="media-story">
								<p class="snippet"><?php echo $business_detail->reviews[0]->excerpt; ?></p>
							</div>
						</div>
					</div>
					
					<div class="yelp-copyright"><a href="<?php echo $business_detail->url; ?>" target="_blank"><img alt="<?php echo $business_detail->name; ?>" src="http://s3-media3.fl.yelpcdn.com/assets/2/www/img/3049d7633b6e/developers/reviewsFromYelpRED.gif" width="115" height="25"/></a></div>
					
				</div>
			<?php else: ?>
				<h3 class="title">Yelp Reviews</h3>
				<?php if ( $preview ): ?>
				<div class="blank-widget text yreview"><button type="button" class="btn btn-warning edit-widget center-block" data-toggle="modal" data-target="#widget-<?=$widget->widget_type . '-' . $widget->id;?>-modal">Add Yelp Reviews</button></div>
				<?php endif; ?>
			<?php endif; ?>
			<div class="clearfix"></div>
		</div>
<?php
		if ( $preview ):
?>
	</div>

	<div class="widget-description">Add Yelp Reviews widget</div>
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
<div id="widget-<?=$widget->widget_type . '-' . $widget->id;?>" class="widget widget-wrapper" data-type="<?=$widget->widget_type;?>">
	<div class="widget-top">
		<div class="widget-title"><h4>Twitter Feed<span class="in-widget-title"></span></h4></div>
		<div class="widget-action">
			<span class="glyphicon glyphicon-move move-widget" title="Move"></span>
			<span class="glyphicon glyphicon-edit edit-widget" data-toggle="modal" data-target="#widget-<?=$widget->widget_type . '-' . $widget->id;?>-modal" title="Edit"></span>
			<span class="glyphicon glyphicon-remove delete-widget" title="Delete"></span>
		</div>
	</div>

	<div class="widget-inside">
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

require_once(APPPATH.'widgets/foursquare/app_helper_draw_widget.php');

if ( ! function_exists('draw_widget_faq')){
	function draw_widget_faq($widget, $position = FALSE, $preview = FALSE ){
		if ( ! is_object($widget) ) return FALSE;
		
		$faq = unserialize($widget->widget_data);
		
		if ( ! isset($faq['title']) ) $faq['title'] = 'FAQ';
		if ( ! isset($faq['content']) ) $faq['content'] = '';
		
		if ( $preview ):
?>

<div id="widget-<?=$widget->widget_type . '-' . $widget->id;?>" class="widget widget-wrapper" data-type="<?=$widget->widget_type;?>">
	<div class="widget-top">
		<div class="widget-title"><h4>FAQ<span class="in-widget-title"></span></h4></div>
		<div class="widget-action">
			<span class="glyphicon glyphicon-move move-widget" title="Move"></span>
			<span class="glyphicon glyphicon-edit edit-widget" data-toggle="modal" data-target="#widget-<?=$widget->widget_type . '-' . $widget->id;?>-modal" title="Edit"></span>
			<span class="glyphicon glyphicon-remove delete-widget" title="Delete"></span>
		</div>
	</div>

	<div class="widget-inside">
<?php
		endif;
?>
		<div class="widget faq">
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
		
		if ( $preview ):
?>

<div id="widget-<?=$widget->widget_type . '-' . $widget->id;?>" class="widget widget-wrapper" data-type="<?=$widget->widget_type;?>">
	<div class="widget-top">
		<div class="widget-title"><h4><?=$links['title'];?><span class="in-widget-title"></span></h4></div>
		<div class="widget-action">
			<span class="glyphicon glyphicon-move move-widget" title="Move"></span>
			<span class="glyphicon glyphicon-edit edit-widget" data-toggle="modal" data-target="#widget-<?=$widget->widget_type . '-' . $widget->id;?>-modal" title="Edit"></span>
			<span class="glyphicon glyphicon-remove delete-widget" title="Delete"></span>
		</div>
	</div>

	<div class="widget-inside">
<?php
		endif;
?>
		<div class="widget links">
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
		
		if ( $preview ):
?>

<div id="widget-<?=$widget->widget_type . '-' . $widget->id;?>" class="widget widget-wrapper" data-type="<?=$widget->widget_type;?>">
	<div class="widget-top">
		<div class="widget-title"><h4><?=$text['title'];?><span class="in-widget-title"></span></h4></div>
		<div class="widget-action">
			<span class="glyphicon glyphicon-move move-widget" title="Move"></span>
			<span class="glyphicon glyphicon-edit edit-widget" data-toggle="modal" data-target="#widget-<?=$widget->widget_type . '-' . $widget->id;?>-modal" title="Edit"></span>
			<span class="glyphicon glyphicon-remove delete-widget" title="Delete"></span>
		</div>
	</div>

	<div class="widget-inside">
<?php
		endif;
?>
		<div class="widget text <?=$widget->widget_type;?>"<?=$box_style;?>>
			<?php if ( $text['title'] != '' ): ?>
			<h3 class="title" <?=$title_style;?>><?=$text['title'];?></h3>
			<?php endif; ?>
			<?php
				if ( isset($text['content']) && $text['content'] != ''  ):
					echo $text['content'];
				elseif( $preview ):
					echo '<div class="blank-widget text ' . $widget->widget_type . '"><button type="button" class="btn btn-warning edit-widget center-block" data-toggle="modal" data-target="#widget-'.$widget->widget_type . '-' . $widget->id.'-modal">Add '. ucwords($widget->widget_type) . '</button></div>';
				endif;
			?>
		</div>
<?php
		if ( $preview ):
?>
	</div>
	
	<div class="widget-description">Add <?=ucwords($widget->widget_type);?></div>
</div>
<?php
		endif;
	}
}

if ( ! function_exists('draw_widget_feed')){
	function draw_widget_feed($widget, $position = FALSE, $preview = FALSE ){
		if ( ! is_object($widget) ) return FALSE;
		$feed = unserialize($widget->widget_data);
		$box_style = 'style="';
		$title_style = 'style="';
		if ( isset($feed['border-color']) && $feed['border-color']  != '') $box_style .= 'border: 1px solid ' . $feed['border-color'] . ';';
		if ( isset($feed['background-color']) && $feed['background-color']  != '') $box_style .= ' background-color: ' . $feed['background-color'] . ';';
		if ( isset($feed['title-color']) && $feed['title-color']  != '') $title_style .= 'color: ' . $feed['title-color'] . '; border-color: ' . $feed['title-color'] . ';';
		if ( isset($feed['text-color']) && $feed['text-color']  != '') $box_style .= ' color: ' . $feed['text-color'] . ';';
		$box_style .= '"';
		$title_style .= '"';
		
		if ( ! isset($feed['title']) ) $feed['title'] = '';
		if ( ! isset($feed['feed_number']) || $feed['feed_number'] < 1 ) $feed['feed_number'] = 5;
		if ( ! isset($feed['feed_url']) ) $feed['feed_url'] = '';
		
		if ( $preview ):
?>

<div id="widget-<?=$widget->widget_type . '-' . $widget->id;?>" class="widget widget-wrapper" data-type="<?=$widget->widget_type;?>">
	<div class="widget-top">
		<div class="widget-title"><h4><?=$feed['title'];?><span class="in-widget-title"></span></h4></div>
		<div class="widget-action">
			<span class="glyphicon glyphicon-move move-widget" title="Move"></span>
			<span class="glyphicon glyphicon-edit edit-widget" data-toggle="modal" data-target="#widget-<?=$widget->widget_type . '-' . $widget->id;?>-modal" title="Edit"></span>
			<span class="glyphicon glyphicon-remove delete-widget" title="Delete"></span>
		</div>
	</div>

	<div class="widget-inside">
<?php
		endif;
?>
		<div class="widget text <?=$widget->widget_type;?>"<?=$box_style;?>>
			<?php if ( $feed['title'] != '' ): ?>
			<h3 class="title" <?=$title_style;?>><?=$feed['title'];?></h3>
			<?php endif; ?>
			<?php
				if ( isset($feed['feed_url']) && $feed['feed_url'] != ''  ):
					//Load the shiny new rssparse
					$CI = get_instance();
					$CI->load->library('RSSParser', array('url' =>$feed['feed_url'], 'life' => 2));
					//Get six items from the feed
					$data = $CI->rssparser->getFeed($feed['feed_number']);
					
					if ( is_array($data) && count($data) > 0 ):
						echo '<ul>';
						foreach ($data as $item) :
							// do stuff with $item['title'], $item['description'], etc.
							echo '<li><a href="' . $item['link'] . '" target="_blank">' . $item['title'] . '</a></li>';
						endforeach;
						echo '</ul>';
					endif;
					
				elseif( $preview ):
					echo '<div class="blank-widget feed ' . $widget->widget_type . '"><button type="button" class="btn btn-warning edit-widget center-block" data-toggle="modal" data-target="#widget-'.$widget->widget_type . '-' . $widget->id.'-modal">Add Feed</button></div>';
				endif;
			?>
		</div>
<?php
		if ( $preview ):
?>
	</div>
	
	<div class="widget-description">Add Feed</div>
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
<div class="modal fade text_modal <?=$widget->widget_type;?>" id="widget-<?=$widget->widget_type . '-' . $widget->id;?>-modal" tabindex="-1" data-widget-type="<?=$widget->widget_type;?>" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel"><?=ucwords($widget->widget_type);?></h4>
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

if ( ! function_exists('draw_modal_feed')){
	function draw_modal_feed( $widget ){
		if ( ! is_object($widget) ) return FALSE;
		$feed = unserialize($widget->widget_data);
		
		if ( ! isset($feed['title']) ) $feed['title'] = '';
		if ( ! isset($feed['feed_url']) ) $feed['feed_url'] = '';
		if ( ! isset($feed['feed_number']) ) $feed['feed_number'] = '';
		if ( ! isset($feed['border-color']) ) $feed['border-color'] = '';
		if ( ! isset($feed['background-color']) ) $feed['background-color'] = '';
		if ( ! isset($feed['title-color']) ) $feed['title-color'] = '';
		if ( ! isset($feed['text-color']) ) $feed['text-color'] = '';
?>
<div class="modal fade feed_modal <?=$widget->widget_type;?>" id="widget-<?=$widget->widget_type . '-' . $widget->id;?>-modal" tabindex="-1" data-widget-type="<?=$widget->widget_type;?>" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Feed</h4>
			</div>
			<div class="modal-body">
				
				<input type="text" name="feed-title" value="<?=$feed['title'];?>" class="form-control" placeholder="Title"/>
				<input type="text" name="feed-url" value="<?=$feed['feed_url'];?>" class="form-control" placeholder="Feed URL"/>
				<input type="text" name="feed-number" value="<?=$feed['feed_number'];?>" class="form-control" placeholder="Number of Feed"/>

				<div class="row" style="margin-top: 20px;">
					<div class="col-md-3">
						<div class="form-group">
							<label for="border-color">Border Color</label>
							<div class="input-group color-picker">
								<input type="text" class="form-control" name="border-color" value="<?=$feed['border-color'];?>">
								<span class="input-group-addon"><i></i></span>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="background-color">Background Color</label>
							<div class="input-group color-picker">
								<input type="text" class="form-control" name="background-color" value="<?=$feed['background-color'];?>">
								<span class="input-group-addon"><i></i></span>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="title-color">Title Color</label>
							<div class="input-group color-picker">
								<input type="text" class="form-control" name="title-color" value="<?=$feed['title-color'];?>">
								<span class="input-group-addon"><i></i></span>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="text-color">Text Color</label>
							<div class="input-group color-picker">
								<input type="text" class="form-control" name="text-color" value="<?=$feed['text-color'];?>">
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
				<p>You can use [first-name] or [last-name] or [city] to view user First Name or Last Name or City.</p>
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

if ( ! function_exists('draw_modal_yreview')){
	function draw_modal_yreview( $widget ){
		if ( ! is_object($widget) ) return FALSE;
		$yelp = unserialize($widget->widget_data);
		
		if ( ! isset($yelp['business_id']) ) $yelp['business_id'] = '';
?>
<div class="modal fade yelp_modal" id="widget-<?=$widget->widget_type . '-' . $widget->id;?>-modal" data-widget-type="yreview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Yelp! Business & Review</h4>
			</div>
			<div class="modal-body">
				
				<div class="yelp_search  form-inline">
					<div class="form-group" style="max-width: 235px;">
						<div class="input-group">
							<div class="input-group-addon">Find</div>
							<input type="text" name="find" value="" class="yelp-find form-control" placeholder="tacos, cheap dinner, Max’s"/>
						</div>
					</div>
					<div class="form-group">
						<div class="input-group">
							<div class="input-group-addon">Near</div>
							<input type="text" name="find" value="" class="yelp-near form-control" placeholder="address, neighborhood, city, state or zip"/>
						</div>
					</div>
					<div class="form-group"><button type="button" class="btn btn-primary btn-sm yelp-search">Search</button></div>
					
					<div class="search-result clearfix"></div>
				</div>
			</div>
			<div class="modal-footer">
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
<div id="widget-greeting-__i__" class="widget widget-wrapper gradient gradient-blue" data-type="greeting">
	<div class="widget-top">
		<div class="widget-title"><h4>Welcome<span class="glyphicon glyphicon-move move-widget" title="Move"></span></h4></div>
		<div class="widget-action">
			<span class="glyphicon glyphicon-move move-widget" title="Move"></span>
			<span class="glyphicon glyphicon-edit edit-widget" data-toggle="modal" data-target="#widget-dummy-99-modal"></span>
			<span class="glyphicon glyphicon-remove delete-widget" title="Delete"></span>
		</div>
	</div>

	<div class="widget-inside">
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

	<div class="widget-description">User picture and welcome words.<p>Grab and move into place.</p></div>
</div>

<div id="widget-testimonials-__i__" class="widget widget-wrapper gradient gradient-blue"  data-type="testimonials">
	<div class="widget-top">
		<div class="widget-title"><h4>Testimonials (Reviews)<span class="glyphicon glyphicon-move move-widget" title="Move"></span></h4></div>
		<div class="widget-action">
			<span class="glyphicon glyphicon-move move-widget" title="Move"></span>
			<span class="glyphicon glyphicon-edit edit-widget" data-toggle="modal" data-target="#widget-testimonials-__i__-modal"></span>
			<span class="glyphicon glyphicon-remove delete-widget" title="Delete"></span>
		</div>
	</div>

	<div class="widget-inside">
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

	<div class="widget-description">Add Testimonials<p>Grab and move into place.</p></div>
</div>

<div id="widget-stories-__i__" class="widget widget-wrapper gradient gradient-blue"  data-type="stories">
	<div class="widget-top">
		<div class="widget-title"><h4>Client Stories<span class="glyphicon glyphicon-move move-widget" title="Move"></span></h4></div>
		<div class="widget-action">
			<span class="glyphicon glyphicon-move move-widget" title="Move"></span>
			<span class="glyphicon glyphicon-edit edit-widget" data-toggle="modal" data-target="#widget-stories-__i__-modal"></span>
			<span class="glyphicon glyphicon-remove delete-widget" title="Delete"></span>
		</div>
	</div>

	<div class="widget-inside">
		<div class="widget stories">
			<h3 class="title">Client Stories</h3>
			<div class="blank-widget text"><button type="button" class="btn btn-warning edit-widget center-block" data-toggle="modal" data-target="#widget-stories-__i__-modal">Add Client Stories</button></div>
		</div>
	</div>

	<div class="widget-description">Add Client Stories<p>Grab and move into place.</p></div>
</div>
				
<div id="widget-twitter-feed-__i__" class="widget widget-wrapper gradient gradient-blue" data-type="twitter">
	<div class="widget-top">
		<div class="widget-title"><h4>Twitter Feed<span class="glyphicon glyphicon-move move-widget" title="Move"></span></h4></div>
		<div class="widget-action">
			<span class="glyphicon glyphicon-move move-widget" title="Move"></span>
			<span class="glyphicon glyphicon-edit edit-widget" data-toggle="modal" data-target="#widget-twitter-60-modal"></span>
			<span class="glyphicon glyphicon-remove delete-widget" title="Delete"></span>
		</div>
	</div>

	<div class="widget-inside">
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
	<div class="widget-description">Add Twitter Feed<p>Grab and move into place.</p></div>

</div>

<div id="widget-faq-__i__" class="widget widget-wrapper gradient gradient-blue" data-type="faq">
	<div class="widget-top">
		<div class="widget-title"><h4>FAQ<span class="glyphicon glyphicon-move move-widget" title="Move"></span></h4></div>
		<div class="widget-action">
			<span class="glyphicon glyphicon-move move-widget" title="Move"></span>
			<span class="glyphicon glyphicon-edit edit-widget" data-toggle="modal" data-target="#widget-faq-modal"></span>
			<span class="glyphicon glyphicon-remove delete-widget" title="Delete"></span>
		</div>
	</div>

	<div class="widget-inside">
		<div class="widget faq">
			<h3 class="title">FAQ</h3>
			<div class="blank-widget faqs"><button type="button" class="btn btn-warning edit-widget center-block" data-toggle="modal" data-target="#widget-faq-__i__-modal">Add FAQ</button></div>
		</div>
	</div>

	<div class="widget-description">Add FAQ<p>Grab and move into place.</p></div>
</div>

<div id="widget-text-__i__" class="widget widget-wrapper gradient gradient-blue" data-type="text">
	<div class="widget-top">
		<div class="widget-title"><h4>Text<span class="glyphicon glyphicon-move move-widget" title="Move"></span></h4></div>
		<div class="widget-action">
			<span class="glyphicon glyphicon-move move-widget" title="Move"></span>
			<span class="glyphicon glyphicon-edit edit-widget" data-toggle="modal" data-target="#widget-text-modal"></span>
			<span class="glyphicon glyphicon-remove delete-widget" title="Delete"></span>
		</div>
	</div>

	<div class="widget-inside">
		<div class="widget text">
			<h3 class="title">Text</h3>
			<div class="blank-widget text"><button type="button" class="btn btn-warning edit-widget center-block" data-toggle="modal" data-target="#widget-text-__i__-modal">Add Text</button></div>
		</div>
	</div>

	<div class="widget-description">Add Text<p>Grab and move into place.</p></div>
</div>

<div id="widget-gmap-__i__" class="widget widget-wrapper gradient gradient-blue" data-type="gmap">
	<div class="widget-top">
		<div class="widget-title"><h4>Google Map<span class="glyphicon glyphicon-move move-widget" title="Move"></span></h4></div>
		<div class="widget-action">
			<span class="glyphicon glyphicon-move move-widget" title="Move"></span>
			<span class="glyphicon glyphicon-edit edit-widget" data-toggle="modal" data-target="#widget-gmap-modal"></span>
			<span class="glyphicon glyphicon-remove delete-widget" title="Delete"></span>
		</div>
	</div>

	<div class="widget-inside">
		<div class="widget gmap">
			<div class="col-md-6 gmap-container"><iframe width="374" height="260" frameborder="0" style="border:0"src="https://www.google.com/maps/embed/v1/place?q=560+E+500+S,+Salt+Lake+City,+UT+84102,+United+States&key=AIzaSyDJl-y_I_6stRCFmDvJbZMmojGjQdXbX2s"></iframe></div>
			<div class="contact-info">
				<h3 class="title">Contact Us</h3>
				<div class="aloha-editable">
					<p>560 E 500 S Salt Lake City, UT 84102</p>
					<p>801-935-4928</p>
					<p>info@heropartners.com</p>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>

	<div class="widget-description">Add Google Map<p>Grab and move into place.</p></div>
</div>

<div id="widget-contact-__i__" class="widget widget-wrapper gradient gradient-blue" data-type="contact">
	<div class="widget-top">
		<div class="widget-title"><h4>Contact<span class="glyphicon glyphicon-move move-widget" title="Move"></span></h4></div>
		<div class="widget-action">
			<span class="glyphicon glyphicon-move move-widget" title="Move"></span>
			<span class="glyphicon glyphicon-edit edit-widget" data-toggle="modal" data-target="#widget-contact-modal"></span>
			<span class="glyphicon glyphicon-remove delete-widget" title="Delete"></span>
		</div>
	</div>

	<div class="widget-inside">
		<div class="widget contact" style="background: url(<?php echo base_url(); ?>img/learning-outdoors.jpg) no-repeat center center; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; background-size: cover;">
			<div class="media-body">
				<div class="aloha-editable">
					<h3 class="title">Learning in the great outdoors.</h3>
					<p>Meet Rob Ryan and learn about the Sunflower Grown Companies system.</p>
					<p><a class="btn btn-default btn-lg active" href="http://www.heropartners.com/retreats">Sign Up</a></p>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>

	<div class="widget-description">Add Contact<p>Grab and move into place.</p></div>
</div>

<div id="widget-download-__i__" class="widget widget-wrapper gradient gradient-blue" data-type="download">
	<div class="widget-top">
		<div class="widget-title"><h4>Download<span class="glyphicon glyphicon-move move-widget" title="Move"></span></h4></div>
		<div class="widget-action">
			<span class="glyphicon glyphicon-move move-widget" title="Move"></span>
			<span class="glyphicon glyphicon-edit edit-widget" data-toggle="modal" data-target="#widget-dummy-99-modal"></span>
			<span class="glyphicon glyphicon-remove delete-widget" title="Delete"></span>
		</div>
	</div>

	<div class="widget-inside">
		<div class="widget text download">
			<h3 class="title">Download</h3>
			<div class="blank-widget text download"><button type="button" class="btn btn-warning edit-widget center-block" data-toggle="modal" data-target="#widget-download-__i__-modal">Add Download</button></div>
		</div>
	</div>

	<div class="widget-description">Add download link. eBook, Whitepaper, PDF, etc.<p>Grab and move into place.</p></div>
</div>

<div id="widget-coupon-__i__" class="widget widget-wrapper gradient gradient-blue" data-type="coupon">
	<div class="widget-top">
		<div class="widget-title"><h4>Coupon<span class="glyphicon glyphicon-move move-widget" title="Move"></span></h4></div>
		<div class="widget-action">
			<span class="glyphicon glyphicon-move move-widget" title="Move"></span>
			<span class="glyphicon glyphicon-edit edit-widget" data-toggle="modal" data-target="#widget-dummy-99-modal"></span>
			<span class="glyphicon glyphicon-remove delete-widget" title="Delete"></span>
		</div>
	</div>

	<div class="widget-inside">
		<div class="widget text coupon">
			<h3 class="title">Coupon</h3>
			<div class="blank-widget text coupon"><button type="button" class="btn btn-warning edit-widget center-block" data-toggle="modal" data-target="#widget-coupon-__i__-modal">Add Coupon</button></div>
		</div>
	</div>

	<div class="widget-description">Add printable image.<p>Grab and move into place.</p></div>
</div>

<div id="widget-products-__i__" class="widget widget-wrapper gradient gradient-blue" data-type="products">
	<div class="widget-top">
		<div class="widget-title"><h4>Products<span class="glyphicon glyphicon-move move-widget" title="Move"></span></h4></div>
		<div class="widget-action">
			<span class="glyphicon glyphicon-move move-widget" title="Move"></span>
			<span class="glyphicon glyphicon-edit edit-widget" data-toggle="modal" data-target="#widget-dummy-99-modal"></span>
			<span class="glyphicon glyphicon-remove delete-widget" title="Delete"></span>
		</div>
	</div>

	<div class="widget-inside">
		<div class="widget text products">
			<h3 class="title">Products</h3>
			<div class="blank-widget text products"><button type="button" class="btn btn-warning edit-widget center-block" data-toggle="modal" data-target="#widget-products-__i__-modal">Add Products</button></div>
		</div>
	</div>

	<div class="widget-description">Add Products.<p>Grab and move into place.</p></div>
</div>

<div id="widget-services-__i__" class="widget widget-wrapper gradient gradient-blue" data-type="services">
	<div class="widget-top">
		<div class="widget-title"><h4>Services<span class="glyphicon glyphicon-move move-widget" title="Move"></span></h4></div>
		<div class="widget-action">
			<span class="glyphicon glyphicon-move move-widget" title="Move"></span>
			<span class="glyphicon glyphicon-edit edit-widget" data-toggle="modal" data-target="#widget-dummy-99-modal"></span>
			<span class="glyphicon glyphicon-remove delete-widget" title="Delete"></span>
		</div>
	</div>

	<div class="widget-inside">
		<div class="widget text services">
			<h3 class="title">Services</h3>
			<div class="blank-widget text services"><button type="button" class="btn btn-warning edit-widget center-block" data-toggle="modal" data-target="#widget-services-__i__-modal">Add Services</button></div>
		</div>
	</div>

	<div class="widget-description">Add Services.<p>Grab and move into place.</p></div>
</div>

<div id="widget-videos-__i__" class="widget widget-wrapper gradient gradient-blue" data-type="videos">
	<div class="widget-top">
		<div class="widget-title"><h4>Videos<span class="glyphicon glyphicon-move move-widget" title="Move"></span></h4></div>
		<div class="widget-action">
			<span class="glyphicon glyphicon-move move-widget" title="Move"></span>
			<span class="glyphicon glyphicon-edit edit-widget" data-toggle="modal" data-target="#widget-dummy-99-modal"></span>
			<span class="glyphicon glyphicon-remove delete-widget" title="Delete"></span>
		</div>
	</div>

	<div class="widget-inside">
		<div class="widget text videos">
			<h3 class="title">Videos</h3>
			<div class="blank-widget text videos"><button type="button" class="btn btn-warning edit-widget center-block" data-toggle="modal" data-target="#widget-videos-__i__-modal">Add Videos</button></div>
		</div>
	</div>

	<div class="widget-description">Add Videos.<p>Grab and move into place.</p></div>
</div>

<div id="widget-greview-__i__" class="widget widget-wrapper gradient gradient-blue" data-type="greview">
	<div class="widget-top">
		<div class="widget-title"><h4>Google Reviews<span class="glyphicon glyphicon-move move-widget" title="Move"></span></h4></div>
		<div class="widget-action">
			<span class="glyphicon glyphicon-move move-widget" title="Move"></span>
			<span class="glyphicon glyphicon-edit edit-widget" data-toggle="modal" data-target="#widget-dummy-99-modal"></span>
			<span class="glyphicon glyphicon-remove delete-widget" title="Delete"></span>
		</div>
	</div>

	<div class="widget-inside">
		<div class="widget text greview">
			<h3 class="title">Google Reviews</h3>
			<div class="blank-widget text greview"><button type="button" class="btn btn-warning edit-widget center-block" data-toggle="modal" data-target="#widget-greview-__i__-modal">Add Google Reviews</button></div>
		</div>
	</div>

	<div class="widget-description">Add Google Reviews.<p>Grab and move into place.</p></div>
</div>

<div id="widget-yreview-__i__" class="widget widget-wrapper gradient gradient-blue" data-type="yreview">
	<div class="widget-top">
		<div class="widget-title"><h4>Yelp Reviews<span class="glyphicon glyphicon-move move-widget" title="Move"></span></h4></div>
		<div class="widget-action">
			<span class="glyphicon glyphicon-move move-widget" title="Move"></span>
			<span class="glyphicon glyphicon-edit edit-widget" data-toggle="modal" data-target="#widget-dummy-99-modal"></span>
			<span class="glyphicon glyphicon-remove delete-widget" title="Delete"></span>
		</div>
	</div>

	<div class="widget-inside">
		<div class="widget text yreview">
			<h3 class="title">Yelp Reviews</h3>
			<div class="blank-widget text yreview"><button type="button" class="btn btn-warning edit-widget center-block" data-toggle="modal" data-target="#widget-yreview-__i__-modal">Add Yelp Reviews</button></div>
		</div>
	</div>

	<div class="widget-description">Add Yelp Reviews.<p>Grab and move into place.</p></div>
</div>

<div id="widget-forumfeed-__i__" class="widget widget-wrapper gradient gradient-blue" data-type="forumfeed">
	<div class="widget-top">
		<div class="widget-title"><h4>Our Community<span class="glyphicon glyphicon-move move-widget" title="Move"></span></h4></div>
		<div class="widget-action">
			<span class="glyphicon glyphicon-move move-widget" title="Move"></span>
			<span class="glyphicon glyphicon-edit edit-widget" data-toggle="modal" data-target="#widget-dummy-99-modal"></span>
			<span class="glyphicon glyphicon-remove delete-widget" title="Delete"></span>
		</div>
	</div>

	<div class="widget-inside">
		<div class="widget text forumfeed">
			<h3 class="title">Our Community</h3>
			<div class="blank-widget text forumfeed"><button type="button" class="btn btn-warning edit-widget center-block" data-toggle="modal" data-target="#widget-forumfeed-__i__-modal">Add Our Community</button></div>
		</div>
	</div>

	<div class="widget-description">Add Our Community.<p>Grab and move into place.</p></div>
</div>
<?php
		require_once(APPPATH.'widgets/foursquare/app_helper.php');
	}
}

if ( ! function_exists('get_client_ip')){
	// Function to get the client ip address
	function get_client_ip() {
	    $ipaddress = '';
	    if ( isset($_SERVER['HTTP_CLIENT_IP']) )
	        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	    else if( isset($_SERVER['HTTP_X_FORWARDED_FOR']) )
	        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	    else if( isset($_SERVER['HTTP_X_FORWARDED']) )
	        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
	    else if( isset($_SERVER['HTTP_FORWARDED_FOR']) )
	        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
	    else if( isset($_SERVER['HTTP_FORWARDED']) )
	        $ipaddress = $_SERVER['HTTP_FORWARDED'];
	    else if( isset($_SERVER['REMOTE_ADDR']))
	        $ipaddress = $_SERVER['REMOTE_ADDR'];
	    else
	        $ipaddress = 'UNKNOWN';
	 
	    return $ipaddress;
	}
}

if ( ! function_exists('get_location_info')){
	//function to get geo/location data by ip
	// output object
	// ip, country_code, country_name, region_code, region_name, Yogyakarta, city, zipcode, latitude, longitude, metro_code, areacode
	function get_location_info(){
		$ip = get_client_ip();
		if ( $ip == 'UNKNOWN')
			return FALSE;
		$url = "http://freegeoip.net/json/$ip";
		$ch  = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		$data = curl_exec($ch);
		curl_close($ch);
		//var_dump($data);
		if ($data) {
			$location = json_decode($data);
			return $location;
		}
	}
}