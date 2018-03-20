<form action="<?php echo base_url('user/allocate-area/'.$role_id) ?>" id="allocate-area-form" method="post">
    <div class="form-group">
        <?php
        echo form_label('Country');
        echo form_dropdown(['name'=>'country_id','id'=>'country_id','class'=>'form-control','data-url'=>base_url('user/get-states-by-country-json')],$countries) ?>
    </div>
    <div class="form-group">
        <?php
        echo form_label('State');
        echo form_dropdown(['name'=>'state_id','id'=>'state_id','class'=>'form-control','data-url'=>base_url('user/get-city-by-state-json')]) ?>
    </div>
    <div class="form-group">
        <?php
        echo form_label('City');
        echo form_dropdown(['name'=>'city_id','id'=>'city_id','class'=>'form-control']) ?>
    </div>
    <div class="form-group">
        <?php
        echo form_submit(['name'=>'sub','id'=>'sub','class'=>'btn btn-primary'],'Allocate') ?>
    </div>
</form>