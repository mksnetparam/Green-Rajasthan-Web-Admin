<div class="row">
    <div class="col-md-12 text-right">
        <a href="<?php echo base_url('user') ?>" class="btn btn-primary">View All Users</a>
    </div>
</div>
<br>
<div class="panel panel-default">
    <div class="panel-heading"><?php
        if (isset($user)) {
            echo 'Update User Detail';
        } else {
            echo 'Add New User';
        }
        ?></div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <?php echo form_open(isset($user) ? base_url('user/do-update-user') : base_url('user/do-add-user'), ['id' => 'add-user-form']); ?>
                <?php
                if (isset($user)) {
                    echo form_hidden('user_id', $user['user_id']);
                }
                ?>
                <div class="row">
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-12">
                                <?php
                                echo '<div class="form-group">';
                                echo form_label('Name', '', ['for' => 'name', 'class' => 'control-label']);
                                echo form_input(['name' => 'name', 'id' => 'name', 'class' => 'form-control', 'placeholder' => 'Name'], isset($user) ? $user['name'] : '');
                                echo '</div>';
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <?php
                                echo '<div class="form-group">';
                                echo form_label('Mobile', '', ['for' => 'mobile', 'class' => 'control-label']);
                                echo form_input(['name' => 'mobile', 'id' => 'mobile', 'class' => 'form-control', 'placeholder' => 'Mobile'], isset($user) ? $user['mobile'] : '');
                                echo '</div>';
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <?php
                                echo '<div class="form-group">';
                                echo form_label('Email', '', ['for' => 'email', 'class' => 'control-label']);
                                echo form_input(['name' => 'email', 'id' => 'email', 'class' => 'form-control', 'placeholder' => 'Email'], isset($user) ? $user['email'] : '');
                                echo '</div>';
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <?php
                                echo '<div class="form-group">';
                                echo form_label('Password', '', ['for' => 'password', 'class' => 'control-label']);
                                echo form_password(['name' => 'password', 'id' => 'password', 'class' => 'form-control', 'placeholder' => 'Password','readonly' => isset($user) ? "readonly" : ''], isset($user) ? $user['password'] : '');
                                echo '</div>';
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <?php
                                echo '<div class="form-group">';
                                echo form_label('Address', '', ['for' => 'address', 'class' => 'control-label']);
                                echo form_textarea(['name' => 'address', 'id' => 'address', 'class' => 'form-control', 'placeholder' => 'Address', 'rows' => '4'], isset($user) ? $user['address'] : '');
                                echo '</div>';
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <?php
                                echo '<div class="form-group">';
                                echo form_label('Country', '', ['for' => 'country_id', 'class' => 'control-label']);
                                echo form_dropdown(['name' => 'country_id', 'id' => 'country_id', 'class' => 'form-control', 'data-url' => base_url('master/get-states-by-country-json')], $countries, isset($user) ? $user['country_id'] : '');
                                echo '</div>';
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <?php
                                echo '<div class="form-group">';
                                echo form_label('State', '', ['for' => 'state_id', 'class' => 'control-label']);
                                $state_attr = ['name' => 'state_id', 'id' => 'state_id', 'class' => 'form-control', 'data-url' => base_url('master/get-city-by-state-json')];
                                if (!isset($user)) {
                                    $state_attr['disabled'] = 'disabled';
                                }
                                echo form_dropdown($state_attr, $states, set_value('state_id', isset($user) ? $user['state_id'] : ''));
                                echo '</div>';
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <?php
                                echo '<div class="form-group">';
                                echo form_label('City', '', ['for' => 'city_id', 'class' => 'control-label']);
                                $city_attr = ['name' => 'city_id', 'id' => 'city_id', 'class' => 'form-control'];

                                if (!isset($user)) {
                                    $city_attr['disabled'] = 'disabled';
                                }

                                echo form_dropdown($city_attr, $cities, set_value('city_id', isset($user) ? $user['city_id'] : ''));
                                echo '</div>';
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
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
                                echo form_submit(['name' => 'sub', 'id' => 'user-add-btn', 'class' => 'btn btn-primary'], isset($user) ? 'Update User' : 'Add User');
                                echo '</div>';
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-md-offset-2">
                        <?php
                        if (isset($user)) {
                            ?>
                            <div class="thumbnail" id="organization-thumbnail">
                                <img src="<?php
                                if (!empty($user['image'])) {
                                    echo base_url('uploads/user/' . $user['image']);
                                } else {
                                    echo base_url('uploads/no_image.jpg');
                                }
                                ?>" width="120px">
                                <p class="text-center"><a href="javascript:void(0)" id="change-user-picture">Change Picture</a></p>
                            </div>    
                             <?php }
                             ?>
                        <div id="person-photo" class="dropzone <?php
                             if (isset($user)) {
                                 echo 'hidden';
                             }
                             ?>">Max Allowed file size is 5 MB.</div>
                    </div>
                </div>
<?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
