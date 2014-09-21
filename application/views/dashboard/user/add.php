<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><strong><span>Add New User</span></strong></h3>
                </div>
                <div class="panel-body">
                    <div id="edituser_error" class="alert alert-danger">
                        <div><strong>Following errors occured:</strong></div>
                    </div>
                    <?php echo form_open('dashboard/adduser', array('class' => 'adduser_form', 'id' => 'adduser_form') ); ?>
                        <div id="edituser_button_top">
                            &nbsp;<button type="submit" class="btn btn-success">Add New User</button>
                        </div>
                        <label for="email_address">Email Address*</label>
                        <input id="email_address" type="text" required="" class="form-control" name="email_address" value="" placeholder="Email Address">
                        <label for="password">Password*</label>
                        <input id="password" type="password" required="" class="form-control" name="password" placeholder="Password">
                        <label for="retype_password">Re-type Password*</label>
                        <input id="retype_password" type="password" required="" class="form-control" name="password" placeholder="Re-type Password">
                        <label for="level">Level</label>
                        <select class="form-control" name="level" id="level">
                            <option value="1" selected="">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                        </select>
                        <label for="first_name">First Name*</label>
                        <input id="first_name" type="text" required="" class="form-control" name="first_name" value="" placeholder="First Name">
                        <label for="last_name">Last Name*</label>
                        <input id="last_name" type="text" required="" class="form-control" name="last_name" value="" placeholder="Last Name">
                        <label for="gender">Gender</label>
                        <select class="form-control" name="gender" id="gender">
                            <option value="m" selected="">male</option>
                            <option value="f">female</option>
                        </select>
                        <label for="city">City</label>
                        <input id="city" type="text" class="form-control" name="city" value="" placeholder="City">
                        <label for="state">State</label>
                        <input id="state" type="text" class="form-control" name="state" value="" placeholder="State">
                        <label for="country">Country</label>
                        <input id="country" type="text" class="form-control" name="country" value="" placeholder="Country">
                        <label for="phone_number">Phone Number*</label>
                        <input id="phone_number" type="text" required="" class="form-control" name="phone_number" value="" placeholder="Phone Number">
                        <label for="zip_code">Zip Code*</label>
                        <input id="zip_code" type="text" required="" class="form-control" name="zip_code" value="" placeholder="Zip Code">
                        <label for="t_id">Twitter Id</label>
                        <input id="t_id" type="text" class="form-control" name="t_id" value="" placeholder="Twitter Id">
                        <label for="t_name">Twitter Name</label>
                        <input id="t_name" type="text" class="form-control" name="t_name" value="" placeholder="Twitter Name">
                        <label for="t_screen_name">Twitter Screen Name</label>
                        <input id="t_screen_name" type="text" class="form-control" name="t_screen_name" value="" placeholder="Twitter Screen Name">
                        <label for="t_location">Twitter Location</label>
                        <input id="t_location" type="text" class="form-control" name="t_location" value="" placeholder="Twitter Location">
                        <label for="t_image">Twitter Image</label>
                        <input id="t_image" type="text" class="form-control" name="t_image" value="" placeholder="Twitter Image">
                        <label for="t_image_https">Twitter Image Https</label>
                        <input id="t_image_https" type="text" class="form-control" name="t_image_https" value="" placeholder="Twitter Image Https">
                        <label for="g_id">Google+ Id</label>
                        <input id="g_id" type="text" class="form-control" name="g_id" value="" placeholder="Google+ Id">
                        <label for="g_email">Google+ Email</label>
                        <input id="g_email" type="text" class="form-control" name="g_email" value="" placeholder="Google+ Email">
                        <label for="g_first_name">Google+ First Name</label>
                        <input id="g_first_name" type="text" class="form-control" name="g_first_name" value="" placeholder="Google+ First Name">
                        <label for="g_last_name">Google+ Last Name</label>
                        <input id="g_last_name" type="text" class="form-control" name="g_last_name" value="" placeholder="Google+ Last Name">
                        <label for="g_gender">Google+ Gender</label>
                        <input id="g_gender" type="text" class="form-control" name="g_gender" value="" placeholder="Google+ Gender">
                        <label for="g_link">Google+ Link</label>
                        <input id="g_link" type="text" class="form-control" name="g_link" value="" placeholder="Google+ Link">
                        <label for="f_id">Facebook Id</label>
                        <input id="f_id" type="text" class="form-control" name="f_id" value="" placeholder="Facebook Id">
                        <label for="f_email">Facebook Email</label>
                        <input id="f_email" type="text" class="form-control" name="f_email" value="" placeholder="Facebook Email">
                        <label for="f_first_name">Facebook First Name</label>
                        <input id="f_first_name" type="text" class="form-control" name="f_first_name" value="" placeholder="Facebook First Name">
                        <label for="f_last_name">Facebook Last Name</label>
                        <input id="f_last_name" type="text" class="form-control" name="f_last_name" value="" placeholder="Facebook Last Name">
                        <label for="f_name">Facebook Name</label>
                        <input id="f_name" type="text" class="form-control" name="f_name" value="" placeholder="Facebook Name">
                        <label for="f_gender">Facebook Gender</label>
                        <input id="f_gender" type="text" class="form-control" name="f_gender" value="" placeholder="Facebook Gender">
                        <label for="f_link">Facebook Link</label>
                        <input id="f_link" type="text" class="form-control" name="f_link" value="" placeholder="Facebook Link">
                        <label for="l_id">Linkedin Id</label>
                        <input id="l_id" type="text" class="form-control" name="l_id" value="" placeholder="Linkedin Id">
                        <label for="l_email">Linkedin Email</label>
                        <input id="l_email" type="text" class="form-control" name="l_email" value="" placeholder="Linkedin Email">
                        <label for="l_first_name">Linkedin First Name</label>
                        <input id="l_first_name" type="text" class="form-control" name="l_first_name" value="" placeholder="Linkedin First Name">
                        <label for="l_last_name">Linkedin Last Name</label>
                        <input id="l_last_name" type="text" class="form-control" name="l_last_name" value="" placeholder="Linkedin Last Name">
                        <label for="l_picture">Linkedin Picture</label>
                        <input id="l_picture" type="text" class="form-control" name="l_picture" value="" placeholder="Linkedin Picture">
                        <label for="theme">Theme</label>
                        <input id="theme" type="text" class="form-control" name="theme" value="" placeholder="Theme">
                        <button type="submit" class="btn btn-success">Add New User</button>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>