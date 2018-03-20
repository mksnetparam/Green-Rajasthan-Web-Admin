<form class="form" id="change-password-form" method="post" action="<?php echo base_url('do-change-password') ?>">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label" for="password">New Password</label>
                <?php
                    echo form_password(['class' => 'form-control', 'id' => 'password', 'name' => 'password', 'placeholder' => 'New Password']);
                ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label" for="confirm-password">Confirm Password</label>
                <?php
                    echo form_password(['class' => 'form-control', 'id' => 'confirm-password', 'name' => 'confirm-password', 'placeholder' => 'Confirm Password']);
                ?>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <?php echo form_submit(['class'=>'btn btn-primary','id'=>'sub','name'=>'sub'],'Change Password') ?>
            </div>
        </div>
    </div>
    
</form>