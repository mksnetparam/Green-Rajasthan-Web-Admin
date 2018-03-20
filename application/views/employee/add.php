<div class="row">
    <div class="col-md-12 text-right">
        <a href="<?php echo base_url('employee') ?>" class="btn btn-primary">View All Employee</a>
    </div>
</div>
<br>
<div class="panel panel-default">
    <div class="panel-heading"><?php
        if (isset($user)) {
            echo 'Update Employee Detail';
        } else {
            echo 'Add New Employee';
        }
        ?></div>
    <div class="panel-body">
        <blockquote class="text-danger">
            All Fields are Mandatory.
        </blockquote>
        <div class="row">
            <div class="col-md-12">
                <?php echo form_open(isset($user) ? base_url('employee/do-update-user') : base_url('employee/do-add-user'), ['id' => 'add-user-form']); ?>
                <?php
                if (isset($user)) {
                    echo form_hidden('employee_id', $user['id']);
                }
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <?php
                                echo '<div class="form-group">';
                                echo form_label('First Name', '', ['for' => 'firstname', 'class' => 'control-label']);
                                echo form_input(['name' => 'firstname', 'id' => 'firstname', 'class' => 'form-control', 'placeholder' => 'First Name'], isset($user) ? $user['firstname'] : '');
                                echo '</div>';
                                ?>
                            </div>
                            <div class="col-md-6">
                                <?php
                                echo '<div class="form-group">';
                                echo form_label('Last Name', '', ['for' => 'lastname', 'class' => 'control-label']);
                                echo form_input(['name' => 'lastname', 'id' => 'lastname', 'class' => 'form-control', 'placeholder' => 'Last Name'], isset($user) ? $user['lastname'] : '');
                                echo '</div>';
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <?php
                                echo '<div class="form-group">';
                                echo form_label('Mobile', '', ['for' => 'mobile', 'class' => 'control-label']);
                                echo form_input(['name' => 'mobile', 'id' => 'mobile', 'class' => 'form-control', 'placeholder' => 'Mobile'], isset($user) ? $user['phone'] : '');
                                echo '</div>';
                                ?>
                            </div>
                            <div class="col-md-6">
                                <?php
                                echo '<div class="form-group">';
                                echo form_label('Email', '', ['for' => 'email', 'class' => 'control-label']);
                                echo form_input(['name' => 'email', 'id' => 'email', 'class' => 'form-control', 'placeholder' => 'Email'], isset($user) ? $user['email'] : '');
                                echo '</div>';
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <?php
                                echo '<div class="form-group">';
                                echo form_label('Password', '', ['for' => 'password', 'class' => 'control-label']);
                                echo form_password(['name' => 'password', 'id' => 'password', 'class' => 'form-control', 'placeholder' => 'Password',isset($user) ? "'disabled'=>'disabled'" : ''], isset($user) ? $user['password'] : '');
                                echo '</div>';
                                ?>
                            </div>
                            <div class="col-md-6">
                                <?php
                                echo '<div class="form-group">';
                                echo form_label('Confirm Password', '', ['for' => 'cpassword', 'class' => 'control-label']);
                                echo form_password(['name' => 'cpassword', 'id' => 'cpassword', 'class' => 'form-control', 'placeholder' => 'Confirm Password',isset($user) ? "'disabled'=>'disabled'" : ''], isset($user) ? $user['password'] : '');
                                echo '</div>';
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <?php
                                echo '<div class="form-group">';
                                echo form_label('Role', '', ['for' => 'role_id', 'class' => 'control-label']);
                                echo form_dropdown(['name' => 'role_id', 'id' => 'role_id', 'class' => 'form-control'], $roles, isset($user) ? $user['role_id'] : '');
                                echo '</div>';
                                ?>
                            </div>
                            <div class="col-md-6">
                                <?php
                                echo '<div class="form-group">';
                                echo form_label('Country', '', ['for' => 'country_id', 'class' => 'control-label']);
                                echo form_dropdown(['name' => 'country_id', 'id' => 'country_id', 'class' => 'form-control','data-url'=>  base_url('employee/get_states_by_country_json')], $countries, isset($user) ? $user['country_id'] : '');
                                echo '</div>';
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <?php
                                echo '<div class="form-group">';
                                echo form_label('State', '', ['for' => 'state_id', 'class' => 'control-label']);
                                echo form_dropdown(['name' => 'state_id', 'id' => 'state_id', 'class' => 'form-control','data-url'=>  base_url('employee/get_city_by_state_json')], $states, isset($user) ? $user['state_id'] : '');
                                echo '</div>';
                                ?>
                            </div>
                            <div class="col-md-6">
                                <?php
                                echo '<div class="form-group">';
                                echo form_label('City', '', ['for' => 'city_id', 'class' => 'control-label']);
                                echo form_dropdown(['name' => 'city_id', 'id' => 'city_id', 'class' => 'form-control'], $cities, isset($user) ? $user['city_id'] : '');
                                echo '</div>';
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <?php
                                echo '<div class="form-group">';
                                echo form_label('Is Active', '', ['for' => 'is_active', 'class' => 'control-label']);
                                echo form_dropdown(['name' => 'is_active', 'id' => 'is_active', 'class' => 'form-control'], ['Yes' => 'Yes', 'No' => 'No'], isset($user) ? $user['is_active'] : 'Yes');
                                echo '</div>';
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <?php
                                echo '<div class="form-group">';
                                echo form_submit(['name' => 'sub', 'id' => 'user_info-add-btn', 'class' => 'btn btn-primary'], isset($user) ? 'Update Employee' : 'Add Employees');
                                echo '</div>';
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
