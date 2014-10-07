<div class="container">
<div class="row login-form-container center-block">
	<div class="col-md-12">
		<div class="row">
			<div class="col-md-6">
				<a href="<?php echo base_url(); ?>"><img src="<?php echo base_url(); ?>/img/siegfriedandjensen-small-logo.png" alt="Siegfried and Jensen Logo" width="250" height="34"/></a>
				<h1>Welcome</h1>
				<p>We've created an area just for you, to help you with your questions.</p>
				<p>For quick and easy access, you can login using an existing account. All information will be kept confidential.</p>
			</div>
			<div class="col-md-6">
				<ul class="socbtns" style="display: none;">
					<?php if ( $this->config->item('linkedin_api_key') != '' ): ?>
					<li><button class="btn btn-linkedin" onclick="linkedinLogin();"><i class="fa fa-linkedin"></i> | Sign In with LinkedIn</button></li>
					<?php endif; ?>
					<?php 
					if ( $this->config->item('twitter_login') ): 
					?>
					<li><a class="btn btn-twitter" href="<?php echo base_url('/login/twitter'); ?>"><i class="fa fa-twitter"></i> | Sign In with Twitter</a></li>
					<?php endif; ?>
					<?php if ( $this->config->item('fb_app_id') != '' ): ?>
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
					<?php echo form_open('login', array('class' => 'login_form', 'id' => 'login_form') ); ?>
						<div class="form-group"> <?php echo form_error('name'); ?><input type="text" name="name" id="name" value="<?php if (isset($name)) echo $name; ?>" placeholder="Name" class="form-control" /></div>
						<div class="form-group"> <?php echo form_error('email'); ?><input type="text" name="email" id="email" value="<?php if (isset($email)) echo $email; ?>" placeholder="Email address" class="form-control" /></div>
						<div class="form-group"> <?php echo form_error('password'); ?><input type="password" name="password" id="password" value="<?php if (isset($password)) echo $password; ?>" placeholder="Password" class="form-control" /></div>
						<div class="form-group"> <?php echo form_error('phone'); ?><input type="text" name="phone" id="phone" value="<?php if (isset($phone)) echo $phone; ?>" placeholder="Phone number" class="form-control" /></div>
						<div class="form-group"> <?php echo form_error('zipcode'); ?><input type="text" name="zipcode" id="zipcode" value="<?php if (isset($zipcode)) echo $zipcode; ?>" placeholder="Zip Code" class="form-control" /></div>
						<button type="button" class="btn btn-success" onclick="validate_login();">Sign In</button>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</div>
</div>
</div>