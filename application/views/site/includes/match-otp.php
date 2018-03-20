<div id="login-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-lg-offset-4">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h3 id="login-title"></h3>
                        <?php echo form_open(base_url('site/match-otp'), ['id' => 'match-otp-form']) ?>
                        <div class="form-group">
                            <label class="sr-only" for="otp"></label>
                            <?php echo form_input(['id' => 'otp', 'name' => 'otp', 'class' => 'form-control', 'placeholder' => 'Entery OTP']) ?>
                        </div>
                        <div class="form-group">
                            <?php echo form_submit(['name' => 'sub', 'value' => 'Match OTP', 'class' => 'btn btn-block btn-success', 'id' => 'match-otp-btn']); ?>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                    <div class="panel-footer">
                        <h4><a href="<?php echo base_url('resend-otp'); ?>" id="resend-otp-link">Resend OTP</a></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>