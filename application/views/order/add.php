<div class="row">
    <div class="col-md-12 text-right">
        <?php echo anchor(base_url('plant'), 'View All Plant', ['class' => 'btn btn-primary btn-sm']); ?>
    </div>
</div>
<br>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading"><?php echo isset($plant) ? 'Update New Plant' : 'Add New Plant' ?></div>
            <div class="panel-body">
                <?php echo form_open_multipart(isset($plant) ? base_url('plant/do-update-plant') : base_url('plant/do-add-plant'), ['id' => isset($plant) ? 'update-plant-form' : 'add-plant-form']); ?>
                <?php echo isset($plant) ? form_hidden('plant_id', $plant['plant_id']) : '' ?>
                <div class="row">
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-12">
                                <?php
                                echo '<div class="form-group">';
                                echo form_label('Plant Name', '', ['for' => 'plant_name', 'class' => 'control-label']);
                                echo form_input(['name' => 'plant_name', 'id' => 'plant_name', 'class' => 'form-control', 'placeholder' => 'Plant Name'], (isset($plant) && isset($plant['plant_name'])) ? set_value('plant_name', $plant['plant_name']) : '');
                                echo '</div>';
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <?php
                                echo '<div class="form-group">';
                                echo form_label('Biological Name', '', ['for' => 'biological_name', 'class' => 'control-label']);
                                echo form_input(['name' => 'biological_name', 'id' => 'biological_name', 'class' => 'form-control', 'placeholder' => 'Biological Name'], (isset($plant) && isset($plant['biological_name'])) ? set_value('biological_name', $plant['biological_name']) : '');
                                echo '</div>';
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <?php
                                echo '<div class="form-group">';
                                echo form_label('Maximum Height (In Feets)', '', ['for' => 'max_height']);
                                echo form_input(['type'=>'number','min'=>'1','name' => 'max_height', 'id' => 'max_height', 'class' => 'form-control', 'placeholder' => 'Maximum Height'], (isset($plant) && isset($plant['max_height'])) ? set_value('max_height', $plant['max_height']) : '');
                                echo '</div>';
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <?php
                                echo '<div class="form-group">';
                                echo form_label('Plant Type', '', ['for' => 'tree_type']);
                                echo form_dropdown(['name' => 'tree_type', 'id' => 'tree_type', 'class' => 'form-control', 'placeholder' => 'Plant Type'], ['' => '--Select--', 'Exterior' => 'Exterior', 'Interior' => 'Interior'], (isset($plant) && isset($plant['tree_type'])) ? set_value('tree_type', $plant['tree_type']) : '');
                                echo '</div>';
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <?php
                                echo '<div class="form-group">';
                                echo form_label('Soil Type', '', ['for' => 'soil_type']);
                                echo form_dropdown(['name' => 'soil_type', 'id' => 'soil_type', 'class' => 'form-control', 'placeholder' => 'Soil Type'], $soil, (isset($plant) && isset($plant['soil_type_id'])) ? set_value('soil_type', $plant['soil_type_id']) : '');
                                echo '</div>';
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <?php
                                echo '<div class="form-group">';
                                echo form_label('In Stock', '', ['for' => 'in_stock']);
                                echo form_input(['type'=>'number','min'=>'1','name' => 'in_stock', 'id' => 'in_stock', 'class' => 'form-control', 'placeholder' => 'In Stock'], (isset($plant) && isset($plant['stock_qty']) ? set_value('in_stock', $plant['stock_qty']) : ''));
                                echo '</div>';
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <?php
                                echo '<div class="form-group">';
                                echo form_label('Plant Description', '', ['for' => 'plant_description']);
                                echo form_textarea(['name' => 'plant_description', 'id' => 'plant_description', 'class' => 'form-control', 'placeholder' => 'Plant Description', 'rows' => 4], (isset($plant) && isset($plant['description'])) ? set_value('plant_description', $plant['description']) : '');
                                echo '</div>';
                                ?>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <?php
                                echo form_label('Status', '', ['for' => 'status', 'class' => 'control-label']);
                                echo form_dropdown(['name' => 'status', 'id' => 'status', 'class' => 'form-control'], ['Yes' => 'Yes', 'No' => 'No'], (isset($plant) && isset($plant['is_active'])) ? set_value('status', $plant['is_active']) : '');
                                ?>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div class="col-md-4 col-md-offset-2">
                        <div class="row">
                            <div class="col-md-12">
                                <?php
                                if (isset($plant)) {
                                    ?>
                                    <div class="thumbnail" id="organization-thumbnail">
                                        <img src="<?php
                                        if (!empty($plant['image_url'])) {
                                            echo base_url('uploads/plant/' . $plant['image_url']);
                                        } else {
                                            echo base_url('uploads/no_image.jpg');
                                        }
                                        ?>" width="120px">
                                        <p class="text-center"><a href="javascript:void(0)" id="change-plant-picture">Change Picture</a></p>
                                    </div>    
                                <?php } ?>
                                <div id="organization-photo" class="dropzone <?php
                                if (isset($plant)) {
                                    echo 'hidden';
                                }
                                ?>">Max Allowed file size is 5 MB.</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        echo form_submit(['name' => 'sub', 'id' => 'add-plant-btn', 'class' => 'btn btn-success'], 'Save');
                        echo form_reset(['name' => 'reset', 'id' => 'reset', 'class' => 'btn btn-danger'], 'Cancel');
                        ?>
                    </div>
                </div>
<?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>