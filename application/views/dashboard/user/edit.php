<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><strong><span>Edit User: </span></strong><?php echo $row->first_name . ' ' . $row->last_name; ?></h3>
                </div>
                <div class="panel-body">
                    <div id="edituser_error" class="alert alert-danger">
                        <div><strong>Following errors occured:</strong></div>
                    </div>
                    <?php echo form_open('dashboard/edituser/'.$row->id, array('class' => 'edituser_form', 'id' => 'edituser_form') ); ?>
                        <div id="edituser_button_top">
							<div id="edituser_button_right">
                            <a href="<?php echo site_url().'/dashboard/'; ?>" class="btn btn-default">Cancel</a> &nbsp; <button type="submit" class="btn btn-success">Save</button>
							</div>
                        </div>
                        <?php foreach ($row as $key => $value): ?>
                            <?php if ($key == 'picture'): ?>
                                <?php continue; ?>
                            <?php endif; ?>
                            <?php if ($key != 'id'): ?>
                                <label for="<?php echo $key; ?>"><?php echo str_replace(array("T ", 'G ', 'L ', 'F '), array('Twitter ', 'Google+ ', 'Linkedin ', 'Facebook '), ucwords(str_replace('_', ' ', $key))); ?><?php echo in_array($key, $required) ? '*' : '' ?></label>
                            <?php endif; ?>
                            <?php if (in_array($key, $selects)): ?>
                                <select class="form-control" name="<?php echo $key; ?>" id="<?php echo $key; ?>">
                                    <?php foreach($select_values[$key] as $k => $v): ?>
                                        <option value="<?php echo $k; ?>" <?php echo $k == $value ? 'selected' : '' ?>><?php echo $v; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            <?php else: ?>
                                <?php if ($key == 'password'): ?>
                                    <input id="password" type="password" class="form-control" name="password" placeholder="Password" />
                                    <label for="retype_password">Re-type Password</label>
                                    <input id="retype_password" type="password" class="form-control" name="password" placeholder="Re-type Password" />
                                <?php else: ?>
                                    <input id="<?php echo $key; ?>" type="<?php echo $key == 'id' ? 'hidden' : 'text' ?>" <?php echo in_array($key, $required) ? 'required=""' : '' ?> class="form-control" name="<?php echo $key; ?>" value="<?php echo $value; ?>" placeholder="<?php echo str_replace(array("T ", 'G ', 'L ', 'F '), array('Twitter ', 'Google+ ', 'Linkedin ', 'Facebook '), ucwords(str_replace('_', ' ', $key))); ?>" />
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php if ($key == 'theme'): ?>
                                <?php break; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <div id="edituser_button_right">
							<a href="<?php echo site_url().'/dashboard/'; ?>" class="btn btn-default">Cancel</a> &nbsp; <button type="submit" class="btn btn-success">Save</button>
						</div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>