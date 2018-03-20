<div class="row">
    <div class="col-md-12 text-right">
        <?php echo anchor(base_url('land'), 'View All Land', ['class' => 'btn btn-primary btn-sm']); ?>
    </div>
</div>
<br>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">Add New Land</div>
            <div class="panel-body">
                <?php echo form_open_multipart(base_url('land/do-add-land'), ['id' => 'add-land-form']); ?>
                <fieldset>
                    <legend>Basic Details</legend>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-12">
                                    <?php
                                    echo '<div class="form-group">';
                                    echo form_label('Organization Name', '', ['for' => 'organization_name', 'class' => 'control-label']);
                                    echo form_dropdown(['name' => 'organization_id', 'id' => 'organization_id', 'class' => 'form-control', 'placeholder' => 'Organization'], $organizations);
                                    echo '</div>';
                                    ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <?php
                                    echo '<div class="form-group">';
                                    echo form_label('Land Area', '', ['for' => 'land_area', 'class' => 'control-label']);
                                    echo form_input(['name' => 'land_area', 'id' => 'land_area', 'class' => 'form-control', 'placeholder' => 'Land Area']);
                                    echo '</div>';
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-md-offset-2"> 
                            <div id="organization-photo" class="dropzone">Max Allowed file size is 5 MB.</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <?php
                            echo '<div class="form-group">';
                            echo form_label('Land Area Measurement Unit', '', ['for' => 'land_area_unit']);
                            echo form_dropdown(['name' => 'land_area_unit', 'id' => 'land_area_unit', 'class' => 'form-control', 'placeholder' => 'Land Area Measurement Unit'], $units);
                            echo '</div>';
                            ?>
                        </div>
                        <div class="col-md-4 col-md-offset-2">
                            <?php
                            echo '<div class="form-group">';
                            echo form_label('Plant Capacity', '', ['for' => 'plant_capacity_qty']);
                            echo form_input(['name' => 'plant_capacity_qty', 'id' => 'plant_capacity_qty', 'class' => 'form-control', 'placeholder' => 'Plant Capacity']);
                            echo '</div>';
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <?php
                            echo '<div class="form-group">';
                            echo form_label('Land Address', '', ['for' => 'land_address']);
                            echo form_textarea(['name' => 'land_address', 'id' => 'land_address', 'class' => 'form-control', 'placeholder' => 'Land Address', 'rows' => 4]);
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
                            echo form_dropdown(['name' => 'user_id', 'id' => 'user_id', 'class' => 'form-control', 'placeholder' => 'Choose User'],$users);
                            echo '</div>';
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <?php
                            echo '<div class="form-group">';
                            echo form_label('Contact Person', '', ['for' => 'contact_person']);
                            echo form_input(['name' => 'contact_person', 'id' => 'contact_person', 'class' => 'form-control', 'placeholder' => 'Contact Person Name']);
                            echo '</div>';
                            ?>
                        </div>
                        <div class="col-md-4">
                            <?php
                            echo '<div class="form-group">';
                            echo form_label('Contact Person Relation', '', ['for' => 'contact_person_relation']);
                            echo form_input(['name' => 'contact_person_relation', 'id' => 'contact_person_relation', 'class' => 'form-control', 'placeholder' => 'Relationship, Ex: Owner']);
                            echo '</div>';
                            ?>
                        </div>
                        <div class="col-md-4">
                            <?php
                            echo '<div class="form-group">';
                            echo form_label('Contact Person Mobile', '', ['for' => 'contact_person_mobile']);
                            echo form_input(['name' => 'contact_person_mobile', 'id' => 'contact_person_mobile', 'class' => 'form-control', 'placeholder' => 'Contact Person Mobile']);
                            echo '</div>';
                            ?>
                        </div>
                    </div>
                </fieldset>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        echo form_submit(['name' => 'sub', 'id' => 'add-land-btn', 'class' => 'btn btn-success'], 'Save');
                        echo form_reset(['name' => 'reset', 'id' => 'reset', 'class' => 'btn btn-danger'], 'Cancel');
                        ?>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>