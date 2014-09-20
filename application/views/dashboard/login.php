<div class="container">
<div class="row login-form-container center-block">
	<div class="col-md-12">
			
		<div class="login-form">
			<a href="<?php echo base_url(); ?>" class="center-block"><img src="<?php echo base_url(); ?>img/siegfriedandjensen-small-logo.png" alt="Siegfried and Jensen Logo" width="250" height="34"/></a>
			<h4 class="page-header">Login</h4>
			<?php
		  		if ( isset($success_msg) ) echo '<div class="alert alert-success" role="alert">' . $success_msg . '</div>';
		  		if ( isset($error_msg) ) echo '<div class="alert alert-danger" role="alert">' . $error_msg . '</div>';
		  	?>
			<?php echo form_open('dashboard/login', array('class' => 'login_form', 'id' => 'login_form') ); ?>
				<div class="form-group"> <?php echo form_error('email'); ?><input type="text" name="email" id="email" value="<?php if (isset($email)) echo $email; ?>" placeholder="Email address" class="form-control" /></div>
				<div class="form-group"> <?php echo form_error('password'); ?><input type="password" name="password" id="password" placeholder="Password" class="form-control" /></div>
				<button type="button" class="btn btn-success" onclick="this.form.submit();">Sign In</button>
			<?php echo form_close(); ?>
		</div>

	</div>
</div>
</div>