<div class="container">
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Profile</h3>
			</div>
			<div class="panel-body">
				<?php echo form_open('profile', array('class' => 'form-horizontal', 'id' => 'update_profile') ); ?>
		  		<div class="form-group">
		  			<label class="col-lg-3 control-label"></label>
			  		<div class="col-lg-5">
			  			<?php
					  		if ( isset($success_msg) ) echo '<div class="alert alert-success" role="alert">' . $success_msg . '</div>';
					  		if ( isset($error_msg) ) echo '<div class="alert alert-danger" role="alert">' . $error_msg . '</div>';
					  	?>
			  		</div>
			  	</div>
		  		
				<div class="form-group">
		                        <label class="col-lg-3 control-label">Picture</label>
		                        <div class="col-lg-5"  id="upload_foto">
		                            <?php echo form_error('picture'); ?>
		                            <div id="user_picture"><img src="<?php echo get_user_picture_thumb(FALSE, 100, 100); ?>" alt="<?php if ( isset($user->first_name) ) echo $user->first_name; ?> Picture" class="img-rounded"></div>
		                            <p><a id="upload_foto_button" href="#" class="btn btn-primary btn-sm active" role="button">Upload Picture</a></p>
		                            <span id="console"></span>
		                            <p><i>Picture format jpg/jpeg or png. Maximum size 1MB, 500x500px.</i></p>
		                        </div>
		                    </div>
		                    
				<div class="form-group">
		                        <label class="col-lg-3 control-label">First Name</label>
		                        <div class="col-lg-5">
		                            <?php echo form_error('first_name'); ?>
		                            <input type="text" class="form-control display-inline" name="first_name" value="<?php if ( isset($user->first_name) ) echo $user->first_name; ?>" />
		                        </div>
		                    </div>
		                    
		                    <div class="form-group">
		                        <label class="col-lg-3 control-label">Last Name</label>
		                        <div class="col-lg-5">
		                            <?php echo form_error('last_name'); ?>
		                            <input type="text" class="form-control" name="last_name" value="<?php if ( isset($user->last_name) )  echo $user->last_name; ?>"  />
		                        </div>
		                    </div>

		                    <div class="form-group">
		                        <label class="col-lg-3 control-label">Email address</label>
		                        <div class="col-lg-5">
		                            <?php echo form_error('email'); ?>
		                            <input type="text" class="form-control" name="email" value="<?php if ( isset($user->email_address) ) echo $user->email_address; ?>"  />
		                        </div>
		                    </div>

		                    <div class="form-group">
		                        <label class="col-lg-3 control-label">Password</label>
		                        <div class="col-lg-5">
		                            <?php echo form_error('password'); ?>
		                            <input type="password" class="form-control" name="password" />
		                        </div>
		                    </div>

		                    <div class="form-group">
		                        <label class="col-lg-3 control-label">Gender</label>
		                        <?php echo form_error('gender'); ?>
		                        <div class="col-lg-5">
		                            <div class="radio">
		                                <label>
		                                    <input type="radio" name="gender" value="m" <?php if ( isset($user->gender) && $user->gender == 'm') echo '  checked="checked"'; ?> /> Male
		                                </label>
		                            </div>
		                            <div class="radio">
		                                <label>
		                                    <input type="radio" name="gender" value="f" <?php if ( isset($user->gender) && $user->gender == 'f' ) echo '  checked="checked"'; ?> /> Female
		                                </label>
		                            </div>
		                        </div>
		                    </div>

		                    <div class="form-group">
		                        <label class="col-lg-3 control-label">Phone</label>
		                        <div class="col-lg-5">
		                          <?php echo form_error('phone'); ?>
		                          <input type="text" class="form-control" name="phone" value="<?php  if ( isset($user->phone_number) ) echo $user->phone_number; ?>"  />
		                        </div>
		                    </div>
		                    
		                     <div class="form-group">
		                        <label class="col-lg-3 control-label">Zip Code</label>
		                        <div class="col-lg-5">
		                          <?php echo form_error('zipcode'); ?>
		                          <input type="text" class="form-control" name="zipcode" value="<?php if ( isset($user->zip_code) ) echo $user->zip_code; ?>"  />
		                        </div>
		                    </div>

		                    <div class="form-group">
		                        <div class="col-lg-9 col-lg-offset-3">
		                            <button type="submit" class="btn btn-default">Save</button>
		                        </div>
		                    </div>
				
			<?php echo form_close(); ?>
			</div>
		</div>
	</div>
  </div>
  </div>