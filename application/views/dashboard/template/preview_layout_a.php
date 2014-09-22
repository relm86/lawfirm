<div class="container page_preview">
	<div class="row">
		<div class="col-md-2" id="widgets-container">
			<div id="widgets" class="cardbox">
				<h4 class="page-header">Widgets</h4>
				<div id="widget-greeting-__i__" class="widget" data-type="greeting">
					<div class="widget-top">
						<div class="widget-title"><h4>Welcome<span class="in-widget-title"></span></h4></div>
					</div>

					<div class="widget-inside">
						<button type="button" class="btn btn-warning edit-widget">Edit</button>
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

					<div class="widget-description">User picture and welcome words.</div>
				</div>
				
				<div id="widget-testimonials-__i__" class="widget"  data-type="testimonials">
					<div class="widget-top">
						<div class="widget-title"><h4>Testimonials (Reviews)<span class="in-widget-title"></span></h4></div>
					</div>

					<div class="widget-inside">
						<button type="button" class="btn btn-warning edit-widget">Edit</button>
						<div class="widget reviews">
							<div class="panel panel-default widget-testimonials-full">
							        <div class="panel-heading">
							          <h3 class="panel-title">Testimonials</h3>
							        </div>
							        <div class="panel-body">
							          <p class="pull-left">Other people in your area who we've helped:</p>
							          <div class="clearfix"></div>
							           <div class="panel-review pull-left"> <img src="<?php echo base_url(); ?>img/barbara-canon.jpg" width="64" height="64" alt="" class="img64 pull-left"/>
							            <div class="media-body">
							              <h4 class="media-heading">Barbara Cannon</h4>
							              <a href="http://www.siegfriedandjensen.com/barbara-cannon">Barbara Cannon was enjoying ...</a> </div>
							          </div>
							           <div class="panel-review pull-left"> <img src="<?php echo base_url(); ?>img/ashley.jpg" width="64" height="64" alt="" class="img64 pull-left"/>
							            <div class="media-body">
							              <h4 class="media-heading">Ashley Merrill </h4>
							              <a href="http://www.siegfriedandjensen.com/our-clients/ashley-merrill">We need to make sure...</a> </div>
							          </div>
							           <div class="panel-review pull-left"> <img src="<?php echo base_url(); ?>img/bill.jpg" width="64" height="64" alt="" class="img64 pull-left"/>
							            <div class="media-body">
							              <h4 class="media-heading">Bill Thompson</h4>
							              <a href="http://www.siegfriedandjensen.com/our-clients/bill-thompson">A Bill learned firsthand...</a> </div>
							          </div>
							          <div class="panel-review pull-left"> <img src="<?php echo base_url(); ?>img/sorenson.jpg" width="64" height="64" alt="" class="img64 pull-left"/>
							            <div class="media-body">
							              <h4 class="media-heading">Lisa Holcombe</h4>
							              <a href="http://www.siegfriedandjensen.com/our-clients/lisa-holcombe">Lisa is a vivacious, energetic...</a> </div>
							          </div>
							        </div>
							</div>
						</div>
					</div>

					<div class="widget-description">Add Testimonials</div>
				</div>
				
				
								
				<div id="widget-twitter-feed-__i__" class="widget" data-type="twitter">
					<div class="widget-top">
						<div class="widget-title"><h4>Twitter Feed<span class="in-widget-title"></span></h4></div>
					</div>

					<div class="widget-inside">
						<button type="button" class="btn btn-warning edit-widget">Edit</button>
						<div class="widget twitter">
							<h3 class="title">Twitter Feed</h3>
	        					<a class="twitter-timeline" href="https://twitter.com/hashtag/autoaccident" data-widget-id="509464469521461248" data-chrome="noheader  noborders transparent" >#autoaccident Tweets</a>
	<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
						</div>
					</div>

					<div class="widget-description">Add Twitter Feed</div>
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
				
				<div id="widget-links-__i__" class="widget" data-type="links">
					<div class="widget-top">
						<div class="widget-title"><h4>Links</h4></div>
					</div>

					<div class="widget-inside">
						<button type="button" class="btn btn-warning btn-sm edit-widget" data-toggle="modal" data-target="#widget-links-__i__">Edit</button><button type="button" class="btn btn-danger btn-sm delete-widget pull-right">Delete</button>
						<div class="widget links">
							<h3 class="title">Links</h3>
							<div class="blank-widget links"><button type="button" class="btn btn-warning edit-widget center-block" data-toggle="modal" data-target="#widget-links-__i__">Add Links</button></div>
							<div id="widget-links-__i__-sort" class="links_sort"></div>
						</div>
					</div>
					
					<div class="widget-description">Add Links</div>
				</div>
				
			</div>
			
			
		</div>
		
		<div class="col-md-10" id="the-page">
			<div class="col-md-12" id="header">
				<!-- header -->
				 <div class="widget header">
					<div class="logo pull-left">
						<a href="javascript:void()"><img src="<?php echo base_url(); ?>img/logo-siegfriedJensen.png" alt=""/></a>
					</div>
					<div class="pull-right">
						<div class="header-nav"><a href="javascript:void()">Contact an Associate</a> | <a href="<?php echo base_url('logout'); ?>">Logout</a></div>
						<div class="header-chat"><a href="javascript:void()"><img src="<?php echo base_url(); ?>img/chat-widget.png" width="217" height="86" alt=""/></a></div>
					</div>
				</div>
				<!-- header end -->
			</div>
			<div class="col-md-4 widget-container" id="left-sidebar">
				<!-- left sidebar -->
				<?php echo draw_widgets($template->id, 'sidebar', TRUE); ?>
				<!-- left sidebar end -->
			</div>
			<div class="col-md-8 " id="main-content">
				<!-- main content -->
				<button type="button" class="btn btn-warning edit-widget" data-toggle="modal" data-target="#main-image-slider">Edit</button>
				<div class="widget image-slider">
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
				
				<button type="button" class="btn btn-warning edit-widget" data-toggle="modal" data-target="#videos-modal">Edit</button>
				<div class="widget videos">
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
				<div class="widget external-site">Visit our website <a href="http://siegfriedandjensen.com" target="_blank">http://siegfriedandjensen.com</a></div>
			</div>
			<div class="col-md-12 widget-container" id="footer">
				<!-- footer -->
				<?php echo draw_widgets($template->id, 'footer', TRUE); ?>
				<!-- footer end -->
			</div>
			<div class="col-md-12 copyright">
				<img src="<?=base_url('img/logo-footer.png'); ?>" class="pull-right logo-footer" /><hr class="pull-left" />
				<div class="clearfix"></div>
				<p>Siegfried &amp; Jensen &copy; 2014 | <a href="javascript:void()">Disclaimer</a></p>
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