<input type="hidden" id="user_id" value="<?=$this->session->userdata('id');?>" />
<input type="hidden" id="template_id" value="<?=$template->id;?>" />
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
				
				<div id="widget-stories-__i__" class="widget" data-type="stories">
					<div class="widget-top">
						<div class="widget-title"><h4>Stories<span class="in-widget-title"></span></h4></div>
					</div>

					<div class="widget-inside">
						<button type="button" class="btn btn-warning edit-widget">Edit</button>
						<div class="widget stories">
							<h3 class="title">Client Stories</h3>
						        <ul>
						          <li><a href="http://www.siegfriedandjensen.com/tuesday-sorenson">Tuesday Sorenson</a></li>
						          <li><a href="http://www.siegfriedandjensen.com/barbara-cannon">Barbara Cannon</a></li>
						          <li><a href="http://www.siegfriedandjensen.com/our-clients/ashley-merrill">Ashley Merrill - Auto accident, wrongful death</a></li>
						          <li><a href="http://www.siegfriedandjensen.com/our-clients/bill-thompson">Bill Thompson - Auto accident, catastrophic injury</a></li>
						          <li><a href="http://www.siegfriedandjensen.com/our-clients/carl-fisher">Carl Fisher - Auto accident</a></li>
						          <li><a href="http://www.siegfriedandjensen.com/our-clients/lisa-holcombe">Lisa Holcombe - Catastrophic injury, spinal cord injury, paralysis</a></li>
						        </ul>
						</div>
					</div>

					<div class="widget-description">Client stories link</div>
				</div>
				
				<div id="widget-contact-__i__" class="widget" data_type="contact">
					<div class="widget-top">
						<div class="widget-title"><h4>Contact<span class="in-widget-title"></span></h4></div>
					</div>

					<div class="widget-inside">
						<button type="button" class="btn btn-warning edit-widget">Edit</button>
						<div class="widget contact">
							<img src="<?php echo base_url(); ?>img/img-appointment.png" width="175" height="156" alt="" class="pull-left"/>
							<div class="media-body">
								<div class="widget-box-text">Get a free review of your potential case.</div>
								<a href="http://www.siegfriedandjensen.com/free-case-review" class="btn btn-warning widget-box-btn">Click Here</a>
							</div>
							<div class="clearfix"></div>
						</div>
					</div>

					<div class="widget-description">Add contact widget</div>
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
						<button type="button" class="btn btn-warning edit-widget">Edit</button>
						<div class="widget faq">
							<h3 class="title">FAQ</h3>
							<ul>
								<li><a href="http://www.siegfriedandjensen.com/faqs/what-to-do-after-an-accident">What to do After an Accident</a></li>
							        <li><a href="http://www.siegfriedandjensen.com/faqs/settlements">Settlements (They&apos;re A Good Thing)</a></li>
							        <li><a href="http://www.siegfriedandjensen.com/faqs/contingency-fees">Contingency Fees Demystified</a></li>
							        <li><a href="http://www.siegfriedandjensen.com/faqs/3-keys-infographic">Ever Wondered If You Have a Valid Injury Claim?</a></li>
							</ul>
						</div>
					</div>

					<div class="widget-description">Add FAQ</div>
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
			<div class="col-md-12 widget-container" id="footer">
				<!-- footer -->
				<button type="button" class="btn btn-warning edit-widget">Edit</button>
				<div class="widget external-site">Visit our website <a href="http://siegfriedandjensen.com" target="_blank">http://siegfriedandjensen.com</a></div>
				<div class="col-md-2 widget links">
				      <h5>Practice Areas</h5>
				      <ul>
				      	<li><a href="http://www.siegfriedandjensen.com/practice-areas/auto-accidents">Automobile Accidents</a></li>
				      	<li><a href="http://www.siegfriedandjensen.com/practice-areas/utah-semi-truck-accident-lawyer">Semi-Truck Accidents</a></li>
				      	<li><a href="http://www.siegfriedandjensen.com/practice-areas/motorcycle-accidents/faqs-motorcycle-accidents">Motorcycle, Bicycle, Pedestrian Accidents</a></li>
				      	<li><a href="http://www.siegfriedandjensen.com/practice-areas/medical-malpractice">Medical Malpractice</a></li>
				      	<li><a href="http://www.siegfriedandjensen.com/practice-areas/birth-injury">Birth Injuries, Cerebral Palsy</a></li>
				      	<li><a href="http://www.siegfriedandjensen.com/practice-areas/brain-head-injury">Brain/Head Injury</a></li>
				      	<li><a href="http://www.siegfriedandjensen.com/drug-blog/dangerous-drugs">Dangerous Drugs</a></li>
				      	<li><a href="http://www.siegfriedandjensen.com/defective-hip-implants">Defective Hip Implants</a></li>
				      	<li><a href="http://www.siegfriedandjensen.com/practice-areas/animal-attacks-dog-bites">Dog Bite</a></li>
				      	<li><a href="http://www.siegfriedandjensen.com/practice-areas/slip-fall">Slip and Fall</a></li>
				      	<li><a href="http://www.siegfriedandjensen.com/catastrophic-injuries">Catastrophic Injuries</a></li>
				      	<li><a href="http://www.siegfriedandjensen.com/practice-areas/utah-accidental-death">Accidental Death</a></li>
				      	<li><a href="http://www.siegfriedandjensen.com/practice-areas">Other Injury Cases</a></li>
				      	<li></li>
				      </ul>
				    </div>
				    <div class="col-md-2 widget links">
				      <h5>Attorneys</h5>
				      <ul>
				        <li><a href="http://www.siegfriedandjensen.com/our-team-of-attorneys/ned-p-siegfried">Ned P. Siegfried</a></li>
				        <li><a href="http://www.siegfriedandjensen.com/our-team-of-attorneys/mitch-jensen">Mitch Jensen</a></li>
				        <li><a href="http://www.siegfriedandjensen.com/our-team-of-attorneys/joseph-w-steele-v">Joseph W. Steele, V &mdash; Of Counsel</a></li>
				        <li><a href="http://www.siegfriedandjensen.com/our-team-of-attorneys/bradley-parker">Bradley Parker &mdash; Of Counsel</a></li>
				        <li><a href="http://www.siegfriedandjensen.com/our-team-of-attorneys/james-w-mcconkie">James W. McConkie &mdash; Of Counsel</a></li>
				        <li><a href="http://www.siegfriedandjensen.com/our-team-of-attorneys/michael-katz">Michael Katz</a></li>
				        <li><a href="http://www.siegfriedandjensen.com/our-team-of-attorneys/brad-l-anderson">Brad L. Anderson</a></li>
				        <li><a href="http://www.siegfriedandjensen.com/our-team-of-attorneys/randal-g-payne">Randal G. Payne</a></li>
				        <li><a href="http://www.siegfriedandjensen.com/our-team-of-attorneys/kenneth-lougee">Kenneth Lougee</a></li>
				        <li><a href="http://www.siegfriedandjensen.com/our-team-of-attorneys/mark-taylor">Mark Taylor</a></li>
				        <li><a href="http://www.siegfriedandjensen.com/our-team-of-attorneys/steven-k-jensen">Steven K. Jensen</a></li>
				        <li><a href="http://www.siegfriedandjensen.com/our-team-of-attorneys/brian-stewart">Brian Stewart</a></li>
				        <li><a href="http://www.siegfriedandjensen.com/our-team-of-attorneys/steve-johnson">Steve Johnson</a></li>
				        <li><a href="http://www.siegfriedandjensen.com/our-team-of-attorneys/c-ryan-christensen">C. Ryan Christensen</a></li>
				        <li><a href="http://www.siegfriedandjensen.com/our-team-of-attorneys/w-alexander-evans">W. Alexander Evans &mdash; Of Counsel</a></li>
				        <li><a href="http://www.siegfriedandjensen.com/our-team-of-attorneys/todd-bradford">Todd Bradford</a></li>
				        <li><a href="http://www.siegfriedandjensen.com/our-team-of-attorneys/lauren-channell">Lauren Channell</a></li>
				      </ul>
				    </div>
				    <div class="col-md-2 widget links">
				      <h5>Clients</h5>
				      <ul>
				        <li><a href="http://www.siegfriedandjensen.com/tuesday-sorenson">Tuesday Sorenson</a></li>
				        <li><a href="http://www.siegfriedandjensen.com/barbara-cannon">Barbara Cannon</a></li>
				        <li><a href="http://www.siegfriedandjensen.com/our-clients/ashley-merrill">Ashley Merrill</a></li>
				        <li><a href="http://www.siegfriedandjensen.com/our-clients/bill-thompson">Bill Thompson</a></li>
				        <li><a href="http://www.siegfriedandjensen.com/our-clients/carl-fisher">Carl Fisher</a></li>
				        <li><a href="http://www.siegfriedandjensen.com/our-clients/lisa-holcombe">Lisa Holcombe</a></li>
				      </ul>
				    </div>
				    <div class="col-md-2 widget links">
				      <h5>Community</h5>
				      <ul>
				        <li><a href="http://www.siegfriedandjensen.com/our-community-involvement/utah-food-bank">Utah Food Bank</a></li>
				        <li><a href="http://www.siegfriedandjensen.com/our-community-involvement/special-olympics-utah">Special Olympics</a></li>
				        <li><a href="http://www.siegfriedandjensen.com/our-community-involvement/utah-open-golf-tournament">Utah Open</a></li>
				        <li><a href="http://www.siegfriedandjensen.com/our-community-involvement/utah-drug-prevention-campaign">Utah Drug Prevention Campaign</a></li>
				        <li><a href="http://www.siegfriedandjensen.com/our-community-involvement/utah-council-for-crime-prevention">Utah Council for Crime Prevention</a></li>
				        <li><a href="http://www.siegfriedandjensen.com/our-community-involvement/utah-human-race">Utah Human Race</a></li>
				        <li><a href="http://www.siegfriedandjensen.com/our-community-involvement/scouting-for-food">Scouting for Food</a></li>
				      </ul>
				    </div>
				    <div class="col-md-2 widget links">
				      <h5>FAQ</h5>
				      <ul>
				        <li><a href="http://www.siegfriedandjensen.com/faqs/what-to-do-after-an-accident">What to do After an Accident</a></li>
				        <li><a href="http://www.siegfriedandjensen.com/faqs/settlements">Settlements (They&apos;re A Good Thing)</a></li>
				        <li><a href="http://www.siegfriedandjensen.com/faqs/contingency-fees">Contingency Fees Demystified</a></li>
				        <li><a href="http://www.siegfriedandjensen.com/faqs/3-keys-infographic">Ever Wondered If You Have a Valid Injury Claim?</a></li>
				      </ul>
				    </div>
				    <div class="col-md-2 widget links">
				      <h5>Blog</h5>
				      <ul>
				        <li><a href="http://www.siegfriedandjensen.com/blog/siegfried-jensen-employees-support-habitat-for-humanity">Siegfried & Jensen Employees Support Habitat for Humanity</a></li>
				        <li><a href="http://www.siegfriedandjensen.com/blog/deadly-rollover-accidents-on-utah-roads">Deadly Rollover Accidents on Utah Roads</a></li>
				        <li><a href="http://www.siegfriedandjensen.com/blog/ktm-enduro-motorcycles-recall-risk-of-crash-and-fire-hazard">KTM Enduro Motorcycles &mdash; Recall &mdash; Risk of Crash and Fire Hazard</a></li>
				        <li><a href="http://www.siegfriedandjensen.com/blog/utah-2012-traffic-deaths-lowest-since-1959">Utah 2012 Traffic Deaths Lowest Since 1959</a></li>
				      </ul>
				    </div>
				    <div class="clearfix"></div>
				    <div class="copyright">
				    	<img src="<?=base_url('img/logo-footer.png'); ?>" class="pull-right logo-footer" /><hr class="pull-left" />
				    	<div class="clearfix"></div>
				    	<p>Siegfried &amp; Jensen &copy; 2014 | <a href="javascript:void()">Disclaimer</a></p>
				    </div>
				<!-- footer end -->
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="main-image-slider" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Featured Image</h4>
      </div>
      <div class="modal-body" id="main_images_upload">
		<a id="upload_main_image" href="#" class="btn btn-primary btn-sm active" role="button">Upload Image</a>
		<p>Note: Max image file size 10MB, width 1024px and height 768px. Only jpg/jpeg/png allowed. Best Dimension 770x366px.</p>
		<div id="main_image_sort">
			<?php
			if ( isset($main_images) && $main_images): 
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
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <span class="spinner"></span>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="videos-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Video</h4>
      </div>
      <div class="modal-body" id="videos-sort-container">
		<a id="upload_video_thumb" href="#" class="btn btn-primary btn-sm active" role="button">Upload Video Thumb</a>
		<p>Note: Max image file size 10MB, width 1024px and height 768px. Only jpg/jpeg/png allowed. Best Dimension 746x439px.</p>
		<div id="videos-sort">
			<?php
			if ( isset($videos) && $videos ):
				foreach($videos->result() as $video):
			?>
			<div id="video-thumb-<?=$video->id;?>" class="video-form form-inline" data-img-url="<?=base_url() . str_replace('./', '',  create_thumb($video->thumb, 746, 439) );?>"  role="form">
				<div class="image-wrapper form-group"><img src="<?=base_url() . str_replace('./', '',  create_thumb($video->thumb, 211, 126) );?>" width="211" height="126" alt="<?=$image->title;?>"/></div>
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
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <span class="spinner"></span>
      </div>
    </div>
  </div>
</div>