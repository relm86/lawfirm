<div class="container page_preview">
	<div class="row">
		<div class="col-md-2" id="widgets-container">
			<div id="widgets" class="cardbox">
				<h4 class="page-header">Widgets</h4>
				<?php the_widgets(); ?>
			</div>
		</div>
		
		<div class="col-md-10" id="the-page">
			<div class="col-md-12" id="header">
				<!-- header -->
				<div class="col-md-6">
					 <div class="widget header">
						<div class="logo pull-left">
							<a href="javascript:void()"><img src="<?php echo base_url(); ?>img/hero-partners-logo.png" alt=""/></a>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="widget photo" id="upload_foto">
						<?php echo form_open(); ?>
						<div class="pull-left">
						     <div id="user_picture" style="max-width: 100px;"><img src="<?php echo get_user_picture_thumb(FALSE, 100, 100); ?>" alt="<?php if ( isset($user->first_name) ) echo $user->first_name; ?> Picture" class="img-rounded"></div>
						    <div class="clearfix"></div>
			                        </div>
					        <div class="media-body">
							<h4 class="media-heading"><?php echo get_user_fullname(); ?></h4>
							<p>We've put together this page to provide customized information just for you.</p>
						</div>
						<div class="clearfix"></div>
						<?php echo form_close(); ?>
					</div>
				</div>
				<div class="col-md-3">
					<div class="pull-right">
						<div class="header-nav"><a href="javascript:void()">Contact an Associate</a> | <a href="<?php echo base_url('logout'); ?>">Logout</a></div>
						<div class="header-chat"><a href="javascript:void()"><img src="<?php echo base_url(); ?>img/chat-widget.png" width="217" height="86" alt=""/></a></div>
					</div>
				</div>
				<!-- header end -->
			</div>
			
			<div class="col-md-12">
				<div class="widget image-slider">
					<button type="button" class="btn btn-warning edit-widget btn-sm" data-toggle="modal" data-target="#main-image-slider">Edit</button>
					<div id="carousel-main-image" class="carousel slide" data-ride="carousel">
					<?php
					if ( isset($main_images) && $main_images ):
					?>
						<div class="carousel-inner">
						<?php
						$i = 0;
						foreach($main_images->result() as $image):
							$active = '';
							if ( $i== 0 ) $active = ' active';
							$i++;
						?>
							<div class="item<?=$active;?>">
								<img src="<?=base_url() . str_replace('./', '',  create_thumb($image->path, 770, 366) );?>" width="770" height="366" alt=""/>
								<div class="carousel-caption">
									<h3><?=$image->title;?></h3>
									<p><?=$image->description;?></p>
								</div>
							</div>
						<?php
						endforeach;
						?>
						</div>
						<a class="left carousel-control" href="#carousel-main-image" role="button" data-slide="prev"> <span class="glyphicon glyphicon-chevron-left"></span> </a> <a class="right carousel-control" href="#carousel-main-image" role="button" data-slide="next"> <span class="glyphicon glyphicon-chevron-right"></span> </a> 
				<?php
				else:
				?>
						<div class="blank-image"><button type="button" class="btn btn-warning edit-widget center-block" data-toggle="modal" data-target="#main-image-slider">Add Image Slider</button></div>
				<?php
				endif;
				?>
					</div>
				</div>
				
				<div class="widget share-this pull-right">
					<div class="icon-share"></div>
					<div class="icon-facebook icon-block"><a href="http://www.facebook.com/sharer/sharer.php?u=<?=base_url();?>"></a></div>
					<div class="icon-twitter icon-block"><a href="javascript:void()"></a></div>
					<div class="icon-email icon-block"><a href="javascript:void()"></a></div>
					<div class="icon-linkedin icon-block"><a href="javascript:void()"></a></div>
					<div class="icon-plus icon-block"><a href="javascript:void()"></a></div>
				</div>
				
				<div class="clearfix"></div>
			</div>
			
			<div class="col-md-4 widget-container" id="left-sidebar">
				<!-- left sidebar -->
				<?php echo draw_widgets($template->id, 'sidebar', TRUE); ?>
				<!-- left sidebar end -->
			</div>
			<div class="col-md-8 " id="main-content">
				<!-- main content -->
				<div class="widget videos">
					<button type="button" class="btn btn-warning edit-widget btn-sm" data-toggle="modal" data-target="#videos-modal">Edit</button>
					<?php
					if ( isset($videos) && $videos ):
						$i = 0;
						foreach($videos->result() as $video):
							if ( $i == 0 ):
								echo '<div class="embed-responsive embed-responsive-16by9">';
								echo '<div onclick="play($(this));" id="youtube-main" data-youtubeurl="'.$video->url.'"><img src="'.base_url() . str_replace('./', '',  create_thumb($video->thumb, 746, 439) ).'"/></div>';
								echo '</div>';
								echo '<div class="video-nav">';
								echo '<div id="carousel-video" class="carousel video" data-ride="carousel">';
								echo '<div class="carousel-inner video">';
								echo '<div class="item active">';
							endif;
							
							if ( $i % 3 == 0 && $i != 0 ):
								echo '</div>';
								echo '<div class="item">';
							endif;
							
							echo '<div class="widget-video-thumb"><img src="'.base_url() . str_replace('./', '',  create_thumb($video->thumb, 211, 126) ).'" alt=""  data-youtubeurl="'.$video->url.'" onclick="play($(this));"/></div>';
							
							$i++;
						endforeach;
						
						echo '</div>'; //<div class="item">
						echo '</div>'; //<div class="carousel-inner video">
						echo '<a class="left carousel-control" href="#carousel-video" role="button" data-slide="prev"> <span class="glyphicon glyphicon-chevron-left"></span> </a> <a class="right carousel-control" href="#carousel-video" role="button" data-slide="next"> <span class="glyphicon glyphicon-chevron-right"></span> </a>';
						echo '</div>'; //<div id="carousel-video" class="carousel video" data-ride="carousel">
						echo '</div>'; //<div class="video-nav">
						
					else:
						echo '<div class="blank-video"><button type="button" class="btn btn-warning edit-widget center-block" data-toggle="modal" data-target="#videos-modal">Add Video</button></div>';
					endif;
					?>
				</div><!-- end .widget.videos -->
				<div class="row">
					<!-- main content left -->
					<div class="col-md-6 widget-container" id="main-content-left">
						<?php echo draw_widgets($template->id, 'left', TRUE); ?>
					</div>
					<!-- main content left end-->
					<!-- main content right -->
					<div class="col-md-6 widget-container" id="main-content-right">
						<?php echo draw_widgets($template->id, 'right', TRUE); ?>
					</div>
					<!-- main content right end -->
				</div>
				<!-- main content end -->
			</div>
			<div class="col-md-12" id="footer-fullwidth">
				<div class="widget external-site">Visit our website <a href="http://heropartners.com/" target="_blank">http://heropartners.com/</a></div>
			</div>
			<div class="col-md-12 widget-container" id="footer">
				<!-- footer -->
				<?php echo draw_widgets($template->id, 'footer', TRUE); ?>
				<!-- footer end -->
			</div>
			<div class="col-md-12 copyright">
				<div class="col-md-4 center-block">
					<img src="<?=base_url('img/hero-partners-footer-logo.png');?>" class="pull-left"/>
					<p><a href="mailto:info@heropartners.com">heropartners.com</a></p>
					<p>560 E 500 S</p>
					<p>Salt Lake City, UT 84102</p>
				</div>
				<div class="clearfix"></div>
				<hr />
				<p>Hero Partners &copy; 2014 | <a href="javascript:void()">Disclaimer</a></p>
			</div>
		</div>
	</div>
</div>

<?php echo draw_modals($template->id);?>

<div class="modal fade dummy_modal" id="widget-dummy-99-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
<!--				<h4 class="modal-title" id="myModalLabel">FAQ</h4> -->
			</div>
			<div class="modal-body">
				<input type="text" name="dummy_data" value="" class="form-control" placeholder=""/>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary btn-sm save-video">Save</button>
				<span class="spinner"></span>
			</div>
		</div>
	</div>
</div>

<?=form_open();?>
<input type="hidden" id="user_id" value="<?=$this->session->userdata('id');?>" />
<input type="hidden" id="template_id" value="<?=$template->id;?>" />
<?=form_close();?>