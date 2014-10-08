<div class="container">
<div class="row login-form-container center-block">
	<div class="col-md-12">
		<div class="row">
			<div class="col-md-6">
				<div class="login-logo">
					<img src="<?php echo base_url(); ?>/img/hero-partners-footer-logo.png" alt="Hero Partners Logo" width="68" height="67" class="pull-left"/>
					<div class="media-body">
						<h4 class="media-heading">Hero Global</h4>
						<p><a href="http://heropartners.com/">A Division of Hero Partners</a></p>
					</div>
					<div class="clearfix"></div>
				</div>
				<p>Accelerating companies with our proven strategy and influence.</p>
				<p>We know entrepreneurs are the lifeblood of economic growth and cultural innovation. Hero Global is designed to accelerate the growth of high-potential entrepreneurs - and their companies - by giving access to the Hero Partners Methodology - a method that has built multiple billion dollar and centi-million dollar organizations. Simply put, we teach you our proprietary strategic approach and give you access to our influential network.</p>
			</div>
			<div class="col-md-6">
				<ul class="socbtns" style="display: none;">
					<?php if ( $this->config->item('linkedin_login') ): ?>
					<li><button class="btn btn-linkedin" onclick="linkedinLogin();"><i class="fa fa-linkedin"></i> | Sign In with LinkedIn</button></li>
					<?php endif; ?>
					<?php if ( $this->config->item('twitter_login') ):  ?>
					<li><a class="btn btn-twitter" href="<?php echo base_url('/login/twitter'); ?>"><i class="fa fa-twitter"></i> | Sign In with Twitter</a></li>
					<?php endif; ?>
					<?php if ( $this->config->item('facebook_login') ): ?>
					<li><button class="btn btn-facebook" onclick="fb_login();"><i class="fa fa-facebook"></i> | Sign In with Facebook</button></li>
					<?php endif; ?>
					<?php if ( $this->config->item('google_login') ): ?>
					<li><button class="btn btn-google-plus" id="googleSignInBtn"><i class="fa fa-google-plus"></i> | Sign In with Google</button></li>
					<?php endif; ?>
					<?php if ( $this->config->item('twitter_login') || $this->config->item('fb_app_id') || $this->config->item('google_login') || $this->config->item('linkedin_login')  ): ?>
					<li><div id="status"></div><span class="or">OR</span></li>
					<?php endif; ?>
				</ul>
				<?php if ( $this->config->item('linkedin_login') ): ?>
				<script type="text/javascript" src="http://platform.linkedin.com/in.js">
				api_key: <?php echo $this->config->item('linkedin_api_key')."\n";?> 
				scope:r_basicprofile,r_emailaddress,r_network,r_fullprofile
				authorize: false
				</script>
				<div id="linkedin" style="display: none;"><script type="IN/Login" data-onAuth="onLinkedInAuth"></script></div>
				<?php endif; ?>
				<div class="login-form">
					<?php
				  		if ( isset($error_msg) ) echo '<div class="alert alert-danger" role="alert">' . $error_msg . '</div>';
				  	?>
					<?php echo form_open('login', array('class' => 'login_form', 'id' => 'login_form') ); ?>
						<div class="form-group"> <?php echo form_error('name'); ?><input type="text" name="name" id="name" value="<?php if (isset($name)) echo $name; ?>" placeholder="Name" class="form-control" /></div>
						<div class="form-group"> <?php echo form_error('business'); ?><input type="text" name="business" id="business" value="<?php if (isset($business)) echo $business ?>" placeholder="Business Name" class="form-control" /></div>
						<div class="form-group"> <?php echo form_error('email'); ?><input type="text" name="email" id="email" value="<?php if (isset($email)) echo $email; ?>" placeholder="Email address" class="form-control" /></div>
						<button type="button" class="btn btn-success" onclick="validate_login();">Get Started</button>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</div>
</div>
</div>