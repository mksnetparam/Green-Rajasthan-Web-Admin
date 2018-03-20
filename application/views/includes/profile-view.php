<form class="form" id="profile-form" method="post" action="<?php echo base_url('update-profile'); ?>">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label" for="firstname">Firstname</label>
                <?php
                    echo form_input(['class' => 'form-control', 'id' => 'firstname', 'name' => 'firstname', 'placeholder' => 'Firstname'],  set_value('firstname',$user_info['firstname']));
                ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label" for="lastname">Lastname</label>
                <?php
                    echo form_input(['class' => 'form-control', 'id' => 'lastname', 'name' => 'lastname', 'placeholder' => 'Lastname'],set_value('lastname',$user_info['lastname']));
                ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label" for="mobile">Mobile</label>
                <?php
                    echo form_input(['class' => 'form-control', 'id' => 'mobile', 'name' => 'mobile', 'placeholder' => 'Mobile'],set_value('mobile',$user_info['phone']));
                ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <?php echo form_submit(['class'=>'btn btn-primary','id'=>'sub','name'=>'sub'],'Update Profile') ?>
            </div>
        </div>
    </div>
    
</form>