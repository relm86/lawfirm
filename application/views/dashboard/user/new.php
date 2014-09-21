<div class="container">
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Create User</h3>
			</div>
			<div class="panel-body">
				<?php echo form_open('dashboard/new_user', array('class' => 'form-horizontal', 'id' => 'new_page') ); ?>
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
					<label class="col-lg-3 control-label">First Name</label>
					<div class="col-lg-5">
	                       	<?php echo form_error('first_name'); ?>
						<div class="input-group">							
							<input type="text" class="form-control display-inline" name="first_name" value="<?php if(isset($first_name)) echo $first_name; ?>"/>
						</div>
		              </div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label">Last Name</label>
					<div class="col-lg-5">
	                       	<?php echo form_error('last_name'); ?>
						<div class="input-group">							
							<input type="text" class="form-control display-inline" name="last_name"  value="<?php if(isset($last_name)) echo $last_name; ?>"/>
						</div>
		              </div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label">Gender</label>
					<div class="col-lg-5">
	                       	<?php echo form_error('gender'); ?>
						<div class="input-group">							
							<input type="text" class="form-control display-inline" name="gender"  value="<?php if(isset($gender)) echo $gender; ?>"/>
						</div>
		              </div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label">Email</label>
					<div class="col-lg-5">
	                       	<?php echo form_error('g_email'); ?>
						<div class="input-group">							
							<input type="text" class="form-control display-inline" name="g_email" value="<?php if(isset($g_email)) echo $g_email; ?>"/>
						</div>
		              </div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label">Phone Number</label>
					<div class="col-lg-5">
						<div class="input-group">							
							<input type="text" class="form-control display-inline" name="phone_number" value="<?php if(isset($phone_number)) echo $phone_number; ?>"/>
						</div>
		              </div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label">Zip Code</label>
					<div class="col-lg-5">
						<div class="input-group">							
							<input type="text" class="form-control display-inline" name="zip_code" value="<?php if(isset($zip_code)) echo $zip_code; ?>"/>
						</div>
		              </div>
				</div>
				<div class="form-group">
					<div class="col-lg-9 col-lg-offset-3">
						<button type="submit" class="btn btn-default">Generate</button>
					</div>
				</div>
				
			<?php echo form_close(); ?>
			</div>
		</div>
	</div>
  </div>
  </div>