<form action="<?php echo base_url('user/do-user-payment') ?>" id="payment-form" method="post">
    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
    <input type="hidden" name="due_amount" value="<?php echo $due_amount; ?>">
    <div class="form-group">
        <?php
            echo form_label('Amount');
            echo form_input(['name'=>'amount','id'=>'amount','class'=>'form-control','placeholder'=>'Amount']);
        ?>
    </div>
    <div class="form-group">
        <?php
            echo form_label('Payment Date');
            echo form_input(['name'=>'payment_date','id'=>'payment_date','class'=>'form-control','placeholder'=>'Payment Date','readonly'=>'readonly']);
        ?>
    </div>
    <div class="form-group">
        <?php
            echo form_label('Comments');
            echo form_textarea(['name'=>'comments','id'=>'comments','class'=>'form-control','placeholder'=>'comments','rows'=>3]);
        ?>
    </div>
    <div class="form-group">
        <?php
            echo form_submit(['name'=>'submit','id'=>'submit','class'=>'btn btn-primary'],'Add');
        ?>
    </div>
</form>