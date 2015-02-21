<div class="container">
<div class="row login-form-container center-block">
	<div class="col-sm-12">
		<div class="row">
			<div class="col-sm-6">
				<div class="login-logo">
					<img src="<?php echo base_url(); ?>/img/audi-logo.png" alt="Audi North Scottsdale Logo" width="181" height="103"/>
				</div>
			</div>
			
			<div class="col-sm-6">
				<h1>Audi North Scottsdale</h1>
			</div>
		</div>
		
		
		<div class="row">
			<div class="col-sm-6">
				<p>Audi North Scottsdale takes great pride in the product we represent. We have a strong and committed sales staff with many years of experience satisfying our customers' needs. We invite you to experience for yourself the advantage of doing business with an award-winning Audi dealership. Audi North Scottsdale is your premier Audi dealer in Phoenix and the state of Arizona.</p>
			</div>
			<div class="col-sm-6">
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
						<div class="form-group"> <?php echo form_error('email'); ?><input type="text" name="email" id="email" value="<?php if (isset($email)) echo $email; ?>" placeholder="Email address" class="form-control" /></div>
						<?php if ( isset($states) && is_array($states) && isset($show_state) && $show_state ): ?>
						<div class="form-group">
							<?php echo form_error('state'); ?>
							<select name="state" class="form-control">
							<?php foreach( $states as $state_id => $state_name ): ?>
								<option value="<?=$state_name;?>"><?=$state_name;?></option>
							<?php endforeach; ?>
							</select>
						</div>
						<?php endif; ?>
						
						<?php if (isset($show_gender) && $show_gender ): ?>
						<div class="form-group">
							<select name="gender" class="form-control">
								<option value="m">Male</option>
								<option value="f">Female</option>
							</select>
						</div>
						<?php endif; ?>
						
						<button type="button" class="btn btn-login" onclick="validate_login();">Sign In</button>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="login-form-footer center-block"></div>
</div>