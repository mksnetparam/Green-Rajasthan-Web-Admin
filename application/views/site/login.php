<div id="logo-wrapper"></div>
<h2 class="text-success text-center" id="logo-title">Green Rajasthan</h2>
<div id="login-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-lg-offset-4">
                <div class="alert alert-danger" id="error_msg">
                    Invalid username/password
                </div>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h3 id="login-title">Administrator Login</h3>
                        <?php echo form_open(base_url('site/do-login'), ['id' => 'emplogin']) ?>
                        <div class="form-group">
                            <label class="sr-only" for="email"></label>
                            <?php echo form_input(['id' => 'email', 'name' => 'email', 'class' => 'form-control', 'placeholder' => 'Username', 'type' => 'email']) ?>
                        </div>
                        <div class="form-group">
                            <label class="sr-only" for="password"></label>
                            <?php echo form_password(['name' => 'password', 'id' => 'password', 'placeholder' => 'Password', 'class' => 'form-control']) ?>
                        </div>
                        <div class="form-group">
                            <?php echo form_submit(['name' => 'sub', 'value' => 'Login', 'class' => 'btn btn-block btn-success', 'id' => 'login-btn']); ?>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                    <div class="panel-footer">
                        <h4><a href="<?php echo base_url('forgot-password');?>">Forgot Password?</a>  </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>