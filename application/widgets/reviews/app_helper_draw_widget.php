<?php
if ( ! function_exists('draw_widget_reviews')){
	function draw_widget_reviews($widget, $position = FALSE, $preview = FALSE ){
		if ( ! is_object($widget) ) return FALSE;
		if ($preview ):
?>
<div id="widget-<?=$widget->widget_type . '-' . $widget->id;?>" class="widget widget-wrapper" data-type="<?=$widget->widget_type;?>">
	<div class="widget-top">
		<div class="widget-title"><h4>Reviews Feed<span class="in-widget-title"></span></h4></div>
		<div class="widget-action">
			<span class="glyphicon glyphicon-move move-widget" title="Move"></span>
			<span class="glyphicon glyphicon-edit edit-widget" data-toggle="modal" data-target="#widget-<?=$widget->widget_type . '-' . $widget->id;?>-modal" title="Edit"></span>
			<span class="glyphicon glyphicon-remove delete-widget" title="Delete"></span>
		</div>
	</div>
	<div class="widget-inside">
<?php
		endif;
		$reviews = unserialize($widget->widget_data);
		//i don't what is the plan, do you plan to support multiple hashtag?
		//for now I assume it store in wrong way
		if ( is_array($reviews) && count($reviews) > 0 ):
			$reviews = $reviews[1]; //-> remove this after fix store data function
		endif;
?>
		<div class="widget reviews">
			<div class="panel panel-default widget-testimonials-full">
				<div class="panel-heading">
					<h3 class="panel-title">Reviews</h3>
				</div>
				<div class="panel-body">
<!--
					<?=$reviews['gender'];?>
					<?=$reviews['state'];?>
					<?=$reviews['city'];?>
-->
<?php

$CI = get_instance();

$user = $CI->session->all_userdata();

// init variables
if( isset($user['f_gender']) && $user['f_gender'] != '') {
	$gender = strtoupper($user['f_gender']);
} elseif ( isset($user['g_gender']) && $user['g_gender'] != '') {
	$gender = strtoupper($user['g_gender']);
} elseif ( isset($user['gender']) && $user['gender'] != '') {
	$gender = strtoupper($user['gender']);
} else {
	$gender = $reviews['gender'];
}

$state = $reviews['state'];
$city = $reviews['city'];

$CI->db->select('name, gender, city, state, review');
$CI->db->from('reviews');
$CI->db->where("gender", $gender);
if($state != '') {
	$CI->db->where("state", $state);
}
if($city != '') {
	$CI->db->where("city", $city);
}

$CI->db->order_by('RAND()');
$CI->db->limit(5);
$query = $CI->db->get();
//echo $CI->db->last_query();
foreach ($query->result_array() as $row) {
?>
					<div class="panel-review">
						<div class="media-body">
							<h4 class="media-heading"><?=$row['name'];?></h4>
							<?=$row['city'];?>, <?=$row['state'];?><br />
							<?=$row['review'];?>
						</div>
					</div>
<?php
}
?>
				</div>
			</div>
		</div>
<?php
		if ( $preview ):
?>
	</div>
	<div class="widget-description">Add Review Feeds</div>
</div>
<?php
		endif;
	}
}

if ( ! function_exists('draw_modal_reviews')){
	function draw_modal_reviews( $widget ){
		if ( ! is_object($widget) ) return FALSE;
		$reviews = unserialize($widget->widget_data);
?>
<div class="modal fade reviews_modal" id="widget-<?=$widget->widget_type . '-' . $widget->id;?>-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Reviews</h4>
			</div>
			<div class="modal-body">
				<div id="widget-<?=$widget->id;?>-sort" class="reviews_sort">
				<?php
		$i = 1;
		if ( is_array($reviews) && count($reviews) > 0 ):
			foreach ( $reviews as $reviews ):
				?>
					<div id="widget-<?=$widget->widget_type . '-' . $widget->id.'-modal-'.$i;?>" class="reviews_form form-inline">
						<div class="form-group">
							<div class="input-group">
								<div class="input-group-addon"><font color="red">Gender</font></div>
								<select name="reviews-gender[<?=$i;?>]" class="form-control" placeholder="Gender">
									<option <?php if($reviews['gender']=='M') echo 'selected'; ?> value="M">Male</option>
									<option <?php if($reviews['gender']=='F') echo 'selected'; ?> value="F">Female</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<div class="input-group">
<!--								<div class="input-group-addon">State</div> -->
								<select name="reviews-state[<?=$i;?>]" class="form-control" placeholder="State" size="1">
									<option value="">State...</option>
<?php
									$CI = get_instance();
									$us_states = $CI->config->item('us_states');
									foreach ($us_states as $key => $value) {
										$selected = '';
										if($key==$reviews['state']) $selected = 'selected';
										echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
									}
?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<div class="input-group">
								<input type="text" name="reviews-city[<?=$i;?>]" value="<?php echo $reviews['city'];?>" class="form-control" placeholder="City"/>
							</div>
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
					<div id="widget-<?=$widget->widget_type . '-' . $widget->id.'-modal-'.$i;?>" class="reviews_form form-inline">
						<div class="form-group">
							<div class="input-group">
								<div class="input-group-addon"><font color="red">Gender</font></div>
								<select name="reviews-gender[<?=$i;?>]" class="form-control" placeholder="Gender">
									<option value="M">Male</option>
									<option value="F">Female</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<div class="input-group">
								<select name="reviews-state[<?=$i;?>]" class="form-control" placeholder="State" size="1">
									<option value="">State...</option>
<?php
									$CI = get_instance();
									$us_states = $CI->config->item('us_states');
									foreach ($us_states as $key => $value) {
										echo '<option value="'.$key.'">'.$value.'</option>';
									}
?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<div class="input-group">
								<input type="text" name="reviews-city[<?=$i;?>]" value="" class="form-control" placeholder="City"/>
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