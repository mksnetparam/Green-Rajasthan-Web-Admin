<div id="login-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-lg-offset-4">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h3 id="login-title"></h3>
                        <?php echo form_open(base_url('do-change-forgot-password'), ['id' => 'change-password-form']) ?>
                        <div class="form-group">
                            <label class="sr-only" for="password"></label>
                            <?php echo form_password(['class' => 'form-control', 'id' => 'password', 'name' => 'password', 'placeholder' => 'New Password']); ?>
                        </div>
                        <div class="form-group">
                            <label class="sr-only" for="confirm-password"></label>
                            <?php echo form_password(['class' => 'form-control', 'id' => 'confirm-password', 'name' => 'confirm-password', 'placeholder' => 'Confirm Password']);?>  
                        </div>
                        <div class="form-group">
                            <?php echo form_submit(['name' => 'sub', 'value' => 'Change Password', 'class' => 'btn btn-block btn-success', 'id' => 'change-password-btn']); ?>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                    <div class="panel-footer">
                        <h4><a href="<?php echo base_url(); ?>">Login Here</a>  </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>