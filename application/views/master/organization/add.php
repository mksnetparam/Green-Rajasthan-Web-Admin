<div class="row">
    <div class="col-md-12 text-right">
        <?php echo anchor(base_url('master/organization'), 'View All Organization', ['class' => 'btn btn-primary btn-sm']); ?>
    </div>
</div>
<br>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">Add New Organization</div>
            <div class="panel-body">
                <fieldset>
                    <legend>Basic Details</legend>
                    <div class="row">
                        <?php echo form_open_multipart(isset($organization) ? base_url('master/do-update-organization') : base_url('master/do-add-organization'), ['id' => 'add-organization-form']); ?>
                        <?php
                        if (isset($organization)) {
                            echo form_hidden('organization_id', $organization['organization_id']);
                        }
                        ?>
                        <div class="col-md-4">
                            <?php
                            echo '<div class="form-group">';
                            echo form_label('Entroll Id', '', ['for' => 'organization_entroll_id', 'class' => 'control-label']);
                            echo form_input(['name' => 'organization_enroll_id', 'id' => 'organization_entroll_id', 'placeholder' => 'Enroll Id', 'class' => 'form-control', 'readonly' => 'readonly'], set_value('organization_enroll_id', isset($organization) ? $organization['organization_enroll_id'] : ''));
                            echo '</div>';
                            ?>
                        </div>
                        <div class="col-md-4 col-md-offset-2">
                            <?php
                            if (isset($organization)) {
                                ?>
                                <div class="thumbnail" id="organization-thumbnail">
                                    <img src="<?php
                                    if (!empty($organization['logo'])) {
                                        echo base_url('uploads/org/' . $organization['logo']);
                                    } else {
                                        echo base_url('uploads/no_image.jpg');
                                    }
                                    ?>" width="120px">
                                    <p class="text-center"><a href="javascript:void(0)" id="change-organization-picture">Change Picture</a></p>
                                </div>    
                            <?php }?>
                            <div id="organization-photo" class="dropzone <?php if(isset($organization)){echo 'hidden';}?>">Max Allowed file size is 5 MB.</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <?php
                            echo '<div class="form-group">';
                            echo form_label('Organization Name', '', ['for' => 'organization_name', 'class' => 'control-label']);
                            echo form_input(['name' => 'organization_name', 'id' => 'organization_name', 'class' => 'form-control', 'placeholder' => 'Organization'], set_value('organization_name', isset($organization) ? $organization['organization_name'] : ''));
                            echo '</div>';
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <?php
                            echo '<div class="form-group">';
                            echo form_label('Contact Person', '', ['for' => 'contact_person', 'class' => 'control-label']);
                            echo form_input(['name' => 'contact_person', 'id' => 'contact_person', 'class' => 'form-control', 'placeholder' => 'Contact Person'], set_value('contact_person', isset($organization) ? $organization['contact_person'] : ''));
                            echo '</div>';
                            ?>
                        </div>
                        <div class="col-md-4 col-md-offset-2">
                            <?php
                            echo '<div class="form-group">';
                            echo form_label('Ownership', '', ['for' => 'ownership']);
                            echo form_input(['name' => 'ownership', 'id' => 'ownership', 'class' => 'form-control', 'placeholder' => 'Ownership'], set_value('ownership', isset($organization) ? $organization['ownership'] : ''));
                            echo '</div>';
                            ?>
                        </div>
                    </div>
                </fieldset>
                <fieldset>
                    <legend>Contact Details</legend>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-4">
                                    <?php
                                    echo '<div class="form-group">';
                                    echo form_label('Mobile', '', ['for' => 'phone', 'class' => 'control-label']);
                                    echo form_input(['name' => 'mobile', 'id' => 'mobile', 'placeholder' => 'Mobile', 'class' => 'form-control'], set_value('mobile', isset($organization) ? $organization['mobile'] : ''));
                                    echo '</div>';

                                    echo '<div class="form-group">';
                                    echo form_label('Email', '', ['for' => 'email', 'class' => 'control-label']);
                                    echo form_input(['name' => 'email', 'id' => 'email', 'placeholder' => 'Email', 'class' => 'form-control'], set_value('email', isset($organization) ? $organization['email'] : ''));
                                    echo '</div>';

                                    echo '<div class="form-group">';
                                    echo form_label('Address Line1', '', ['for' => 'address_line1', 'class' => 'control-label']);
                                    echo form_input(['name' => 'address_line1', 'id' => 'address_line1', 'placeholder' => 'Address Line1', 'class' => 'form-control'], set_value('address_line1', isset($organization) ? $organization['address_line1'] : ''));
                                    echo '</div>';

                                    echo '<div class="form-group">';
                                    echo form_label('Address Line2', '', ['for' => 'address_line2', 'class' => 'control-label']);
                                    echo form_input(['name' => 'address_line2', 'id' => 'address_line2', 'placeholder' => 'Address Line2', 'class' => 'form-control'], set_value('address_line2', isset($organization) ? $organization['address_line2'] : ''));
                                    echo '</div>';
                                    ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <?php
                                    echo '<div class="form-group">';
                                    echo form_label('Country', '', ['for' => 'country', 'class' => 'control-label']);
                                    echo form_dropdown(['name' => 'country_id', 'id' => 'country_id', 'class' => 'form-control', 'data-url' => base_url('master/get-states-by-country-json')], $countries, set_value('country_id', isset($organization) ? $organization['country_id'] : ''));
                                    echo '</div>';
                                    ?>
                                </div>
                                <div class="col-md-4 col-md-offset-2">
                                    <?php
                                    echo '<div class="form-group">';
                                    echo form_label('State', '', ['for' => 'state', 'class' => 'control-label']);
                                    $state_attr = ['name' => 'state_id', 'id' => 'state_id', 'class' => 'form-control', 'data-url' => base_url('master/get-city-by-state-json')];
                                    if (!isset($organization)) {
                                        $state_attr['disabled'] = 'disabled';
                                    }
                                    echo form_dropdown($state_attr, $states, set_value('state_id', isset($organization) ? $organization['state_id'] : ''));
                                    echo '</div>';
                                    ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <?php
                                    echo '<div class="form-group">';
                                    echo form_label('City', '', ['for' => 'city', 'class' => 'control-label']);
                                    $city_attr = ['name' => 'city_id', 'id' => 'city_id', 'class' => 'form-control'];

                                    if (!isset($organization)) {
                                        $city_attr['disabled'] = 'disabled';
                                    }

                                    echo form_dropdown($city_attr, $cities, set_value('city_id', isset($organization) ? $organization['city_id'] : ''));
                                    echo '</div>';
                                    ?>
                                </div>
                                <div class="col-md-4 col-md-offset-2">
                                    <?php
                                    echo '<div class="form-group">';
                                    echo form_label('Pincode', '', ['for' => 'pincode', 'class' => 'control-label']);
                                    echo form_input(['name' => 'pincode', 'id' => 'pincode', 'class' => 'form-control', 'placeholder' => 'Pincode'], set_value('pincode', isset($organization) ? $organization['pincode'] : ''));
                                    echo '</div>';
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <?php
                        echo form_label('Status', '', ['for' => 'status', 'class' => 'control-label']);
                        echo form_dropdown(['name' => 'status', 'id' => 'status', 'class' => 'form-control'], ['No' => 'No', 'Yes' => 'Yes'], set_value('status', isset($organization) ? $organization['is_active'] : ''));
                        ?>
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-md-12">
                        <?php
                        echo form_submit(['name' => 'sub', 'id' => 'add-organization-btn', 'class' => 'btn btn-success'], isset($organization) ? 'Update' : 'Save');
                        echo form_reset(['name' => 'reset', 'id' => 'reset', 'class' => 'btn btn-danger'], 'Cancel');
                        ?>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>