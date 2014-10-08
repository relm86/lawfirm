<div class="container">
	
	<div class="row">
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
	</div>
	
	<div class="row">
		<div class="col-md-4">
			<div class="widget-container" id="left-sidebar">
				<!-- left sidebar -->
				<?php echo draw_widgets($template->id, 'sidebar', FALSE); ?>
				<!-- left sidebar end -->
			</div>
		</div>
		<div class="col-md-8 " id="main-content">
			<!-- main content -->
			<div class="row">
				<div class="col-md-12">
					<div class="widget-wrapper">
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
						          	<?php if ( $image->title != '' || $image->description != '' ):?>
						          	<div class="carousel-caption">
									<h3><?=$image->title;?></h3>
									<p><?=$image->description;?></p>
								</div>
								<?php endif; ?>
						          </div>
							<?php
								endforeach;
							?>
						        </div>
						        <a class="left carousel-control" href="#carousel-main-image" role="button" data-slide="prev"> <span class="glyphicon glyphicon-chevron-left"></span> </a> <a class="right carousel-control" href="#carousel-main-image" role="button" data-slide="next"> <span class="glyphicon glyphicon-chevron-right"></span> </a> 
						        <?php
						        endif;
						        ?>
						        </div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="widget col-md-12 share-this pull-right">
					<div class="icon-share"></div>
					<div class="icon-facebook icon-block"><a href="http://www.facebook.com/sharer/sharer.php?u=<?=base_url();?>"></a></div>
					<div class="icon-twitter icon-block"><a href="javascript:void()"></a></div>
					<div class="icon-email icon-block"><a href="javascript:void()"></a></div>
					<div class="icon-linkedin icon-block"><a href="javascript:void()"></a></div>
					<div class="icon-plus icon-block"><a href="javascript:void()"></a></div>
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-12">
					<div class="widget-wrapper">
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
								
							endif;
							?>
						</div><!-- end .widget.videos -->
					</div>
				</div>
			</div>
				
			<div class="row">
				<!-- main content left -->
				<div class="col-md-6">
					<div class="widget-container" id="main-content-left">
						<?php echo draw_widgets($template->id, 'left', FALSE); ?>
					</div>
				</div>
				<!-- main content left end-->
				<!-- main content right -->
				<div class="col-md-6">
					<div class="widget-container" id="main-content-right">
						<?php echo draw_widgets($template->id, 'right', FALSE); ?>
					</div>
				</div>
				<!-- main content right end -->
			</div>
			<!-- main content end -->
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-12" id="footer-fullwidth">
			<div class="widget external-site">Visit our website <a href="http://siegfriedandjensen.com" target="_blank">http://siegfriedandjensen.com</a></div>
		</div>
	</div>
	
	<div class="row" id="footer">
		<div class="col-md-2">
			<div class="widget-container" id="footer1">
				<?php echo draw_widgets($template->id, 'footer', FALSE); ?>
				<?php echo draw_widgets($template->id, 'footer1', FALSE); ?>
			</div>
		</div>
		<div class="col-md-2">
			<div class="widget-container" id="footer2">
				<?php echo draw_widgets($template->id, 'footer2', FALSE); ?>
			</div>
		</div>
		<div class="col-md-2">
			<div class="widget-container" id="footer3">
				<?php echo draw_widgets($template->id, 'footer3', FALSE); ?>
			</div>
		</div>
		<div class="col-md-2">
			<div class="widget-container" id="footer4">
				<?php echo draw_widgets($template->id, 'footer4', FALSE); ?>
			</div>
		</div>
		<div class="col-md-2">
			<div class="widget-container" id="footer5">
				<?php echo draw_widgets($template->id, 'footer5', FALSE); ?>
			</div>
		</div>
		<div class="col-md-2">
			<div class="widget-container" id="footer6">
				<?php echo draw_widgets($template->id, 'footer6', FALSE); ?>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-12 copyright">
			<img src="<?=base_url('img/logo-footer.png'); ?>" class="pull-right logo-footer" /><hr class="pull-left" />
			<div class="clearfix"></div>
			<p>Siegfried &amp; Jensen &copy; 2014 | <a href="javascript:void()">Disclaimer</a></p>
		</div>
	</div>
		
</div>