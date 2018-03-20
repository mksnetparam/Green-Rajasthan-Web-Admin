<div class="row">
    <div class="col-md-9">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-6">Country List</div>
                    <div class="col-md-6 text-right"><a href="<?php echo base_url('master/country')?>" class="btn btn-sm btn-danger hidden" id="add-country-panel-btn">Add Country</a></div>
                </div>    
            </div>
            <div class="panel-body">
                <div id="list-wrapper" data-url="<?php echo base_url('master/get-countries'); ?>"></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-info">
            <div class="panel-heading">Add Country</div>
            <div class="panel-body">
                <?php
                echo form_open('master/do-add-country', ['method'=>'get','class' => '', 'id' => 'country-form']);
                echo '<div class="form-group">';
                echo form_label('Country Name', '', ['for' => 'country_name', 'class' => 'control-label']);
                echo form_input(['name' => 'country_name', 'id' => 'country_name', 'class' => 'form-control', 'placeholder' => 'Country Name']);
                echo '</div>';
                echo '<div class="form-group">';
                echo form_label('Is Active', '', ['for' => 'active']);
                echo form_dropdown(['name' => 'is_active', 'id' => 'is_active', 'class' => 'form-control'], ['Yes' => 'Yes', 'No' => 'No']);
                echo '</div>';
                echo '<div class="form-group">';
                echo form_submit(['name' => 'sub', 'id' => 'country_sub_btn', 'class' => 'btn btn-info'], 'Add Country');
                echo '</div>';
                echo form_close();
                ?>
            </div>
        </div>
    </div>
</div>