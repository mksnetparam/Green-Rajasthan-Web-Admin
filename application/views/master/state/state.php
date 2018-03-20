<div class="row">
    <div class="col-md-9">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-6">State List</div>
                    <div class="col-md-6 text-right"><a href="<?php echo base_url('master/state') ?>" class="btn btn-sm btn-danger hidden" id="add-state-panel-btn">Add State</a></div>
                </div>  
            </div>
            <div class="panel-body">
                <div id="list-wrapper" data-url="<?php echo base_url('master/get-states'); ?>"></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-info">
            <div class="panel-heading">Add State</div>
            <div class="panel-body">
                <?php
                echo form_open('master/do-add-state', ['class' => '', 'id' => 'state-form']);
                echo '<div class="form-group">';
                echo form_label('State Name', '', ['for' => 'state_name', 'class' => 'control-label']);
                echo form_input(['name' => 'state_name', 'id' => 'state_name', 'class' => 'form-control', 'placeholder' => 'State Name']);
                echo '</div>';

                echo '<div class="form-group">';
                echo form_label('Country', '', ['for' => 'country_id']);
                echo form_dropdown(['name' => 'country_id', 'id' => 'country_id', 'class' => 'form-control'], $countries);
                echo '</div>';

                echo '<div class="form-group">';
                echo form_label('Is Active', '', ['for' => 'active']);
                echo form_dropdown(['name' => 'is_active', 'id' => 'is_active', 'class' => 'form-control'], ['Yes' => 'Yes', 'No' => 'No']);
                echo '</div>';
                echo '<div class="form-group">';
                echo form_submit(['name' => 'sub', 'id' => 'state_sub_btn', 'class' => 'btn btn-info'], 'Add State');
                echo '</div>';
                echo form_close();
                ?>
            </div>
        </div>
    </div>
</div>