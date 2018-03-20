<div class="row">
    <div class="col-md-9">
        <div class="panel panel-default">
            <div class="panel-heading">
            <div class="row">
                    <div class="col-md-6">City List</div>
                    <div class="col-md-6 text-right"><a href="<?php echo base_url('master/city') ?>" class="btn btn-sm btn-danger hidden" id="add-city-panel-btn">Add City</a></div>
                </div>  
            </div>
            <div class="panel-body">
                <div id="list-wrapper" data-url="<?php echo base_url('master/get-cities'); ?>"></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-info">
            <div class="panel-heading">Add City</div>
            <div class="panel-body">
                <?php
                echo form_open('master/do-add-city', ['class' => '', 'id' => 'city-form']);
                echo '<div class="form-group">';
                echo form_label('City Name', '', ['for' => 'city_name', 'class' => 'control-label']);
                echo form_input(['name' => 'city_name', 'id' => 'city_name', 'class' => 'form-control', 'placeholder' => 'City Name']);
                echo '</div>';
                
                echo '<div class="form-group">';
                echo form_label('Country', '', ['for' => 'country_id']);
                echo form_dropdown(['name' => 'country_id', 'id' => 'country_id', 'class' => 'form-control','data-url'=>  base_url('master/get-states-by-country-json')],$countries);
                echo '</div>';
                
                echo '<div class="form-group">';
                echo form_label('State', '', ['for' => 'state_id']);
                echo form_dropdown(['name' => 'state_id', 'id' => 'state_id', 'class' => 'form-control','disabled'=>'disabled']);
                echo '</div>';
                
                echo '<div class="form-group">';
                echo form_label('Is Active', '', ['for' => 'active']);
                echo form_dropdown(['name' => 'is_active', 'id' => 'is_active', 'class' => 'form-control'], ['Yes'=>'Yes', 'No'=>'No']);
                echo '</div>';
                echo '<div class="form-group">';
                echo form_submit(['name' => 'sub', 'id' => 'city_sub_btn', 'class' => 'btn btn-info'], 'Add City');
                echo '</div>';
                echo form_close();
                ?>
            </div>
        </div>
    </div>
</div>