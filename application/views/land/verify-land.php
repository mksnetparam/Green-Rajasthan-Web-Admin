<div class="row">
    <div class="col-md-12 text-right">
        <?php echo anchor(base_url('land'), 'View All Land', ['class' => 'btn btn-primary btn-sm']); ?>
    </div>
</div>
<br>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">Verify Land</div>
            <div class="panel-body">
                <?php
                echo form_open_multipart(base_url('land/do-verify-land'), ['id' => 'verify-land-form'], ['land_id' => $land['land_id']]);
                echo form_hidden('employee_id', $land['employee_id']);
                ?>
                <fieldset>
                    <legend>Basic Details</legend>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-12">
                                    <?php
                                    echo '<div class="form-group">';
                                    echo form_label('Organization Name', '', ['for' => 'organization_name', 'class' => 'control-label']);
                                    echo form_dropdown(['name' => 'organization_id', 'id' => 'organization_id', 'class' => 'form-control', 'placeholder' => 'Organization'], $organizations, $land['organization_id']);
                                    echo '</div>';
                                    ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <?php
                                    echo '<div class="form-group">';
                                    echo form_label('Land Area', '', ['for' => 'land_area', 'class' => 'control-label']);
                                    echo form_input(['name' => 'land_area', 'id' => 'land_area', 'class' => 'form-control', 'placeholder' => 'Land Area','maxlength' => '4'], set_value('land_area', $land['land_area']));
                                    echo '</div>';
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-md-offset-2"> 
                            <?php
                            if (isset($land)) {
                                ?>
                                <div class="thumbnail">
                                    <img src="<?php
                                    if (!empty($land['land_image_url'])) {
                                        echo base_url('uploads/land/' . $land['land_image_url']);
                                    } else {
                                        echo base_url('uploads/no_image.jpg');
                                    }
                                    ?>" width="120px">
                                    <p class="text-center"><a href="javascript:void(0)" id="change-land-picture">Change Picture</a></p>
                                </div>    
                                <?php }
                            ?>
                            <div id="organization-photo" class="dropzone <?php
                            if (isset($land)) {
                                echo 'hidden';
                            }
                            ?>">Max Allowed file size is 5 MB and allowed type jpg/png.</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <?php
                            echo '<div class="form-group">';
                            echo form_label('Land Area Measurement Unit', '', ['for' => 'land_area_unit']);
                            echo form_dropdown(['name' => 'land_area_unit', 'id' => 'land_area_unit', 'class' => 'form-control', 'placeholder' => 'Land Area Measurement Unit'], $units, set_value('land_area_unit', $land['land_area_unit']));
                            echo '</div>';
                            ?>
                        </div>
                        <div class="col-md-4 col-md-offset-2">
                            <?php
                            echo '<div class="form-group">';
                            echo form_label('Plant Capacity', '', ['for' => 'plant_capacity_qty']);
                            echo form_input(['name' => 'plant_capacity_qty', 'id' => 'plant_capacity_qty', 'class' => 'form-control', 'placeholder' => 'Plant Capacity','maxlength'=>'4'], set_value('plant_capacity_qty', $land['plant_capacity_qty']));
                            echo '</div>';
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <?php
                            echo '<div class="form-group">';
                            echo form_label('Land Address', '', ['for' => 'land_address']);
                            echo form_textarea(['name' => 'land_address', 'id' => 'land_address', 'class' => 'form-control', 'placeholder' => 'Land Address', 'rows' => 4], set_value('land_address', $land['land_address']));
                            echo '</div>';
                            ?>
                        </div>
                    </div>
                </fieldset>
                <fieldset>
                    <legend>Contact Details</legend>
                    <div class="row">
                        <div class="col-md-4">
                            <?php
                            echo '<div class="form-group">';
                            echo form_label('User', '', ['for' => 'user']);
                            echo form_dropdown(['name' => 'user_id', 'id' => 'user_id', 'class' => 'form-control', 'placeholder' => 'Choose User','readonly' => 'readonly'], $users, set_value('user_id', $land['user_id']));
                            echo '</div>';
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <?php
                            echo '<div class="form-group">';
                            echo form_label('Contact Person', '', ['for' => 'contact_person']);
                            echo form_input(['name' => 'contact_person', 'id' => 'contact_person', 'class' => 'form-control', 'placeholder' => 'Contact Person Name'], set_value('contact_person', $land['contact_person']));
                            echo '</div>';
                            ?>
                        </div>
                        <div class="col-md-4">
                            <?php
                            echo '<div class="form-group">';
                            echo form_label('Contact Person Relation', '', ['for' => 'contact_person_relation']);
                            echo form_input(['name' => 'contact_person_relation', 'id' => 'contact_person_relation', 'class' => 'form-control', 'placeholder' => 'Relationship, Ex: Owner'], set_value('contact_person_relation', $land['contact_person_relation']));
                            echo '</div>';
                            ?>
                        </div>
                        <div class="col-md-4">
                            <?php
                            echo '<div class="form-group">';
                            echo form_label('Contact Person Mobile', '', ['for' => 'contact_person_mobile']);
                            echo form_input(['name' => 'contact_person_mobile', 'id' => 'contact_person_mobile', 'class' => 'form-control', 'placeholder' => 'Contact Person Mobile'], set_value('contact_person_mobile', $land['contact_person_mobile']));
                            echo '</div>';
                            ?>
                        </div>
                    </div>
                </fieldset>
                <hr>
                <fieldset>
                    <legend>Verification Detail</legend>
                    <div class="row">
                        <div class="col-md-4">
                            <p class="h4 text-primary">Reviewer Name : <?php echo $land['firstname'] . ' ' . $land['lastname'] ?></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <?php
                            echo '<div class="form-group">';
                            echo form_label('Action', '', ['for' => 'is_verified', 'class' => 'control-label']);
                            echo form_dropdown(['name' => 'is_verified', 'id' => 'is_verified', 'class' => 'form-control'], ['Approved' => 'Approved', 'Rejected' => 'Rejected'], set_value('is_verified', $land['is_verified']));
                            echo '</div>';

                            echo '<div class="form-group">';
                            echo form_label('Reason', '', ['for' => 'reason', 'class' => 'control-label']);
                            echo form_textarea(['name' => 'reason', 'id' => 'reason', 'class' => 'form-control', 'placeholder' => 'Reason', 'rows' => 4], set_value('is_verified', $land['reason']));
                            echo '</div>';
                            ?>
                        </div>
                        <div class="col-md-4 col-md-offset-2">
                            <div id="verify-docs" class="dropzone">Max Allowed file size is 5 MB and allowed type pdf/doc.</div>
                        </div>
                    </div>
                </fieldset>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        echo form_submit(['name' => 'sub', 'id' => 'verify-land-btn', 'class' => 'btn btn-success'], 'Save Details');
                        echo anchor(base_url('land'), 'Cancel', ['class' => 'btn btn-danger']);
                        ?>
                    </div>
                </div>
<?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>