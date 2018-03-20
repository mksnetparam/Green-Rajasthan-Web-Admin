<div class="row">
    <div class="col-md-9">
        <div class="panel panel-default">
            <div class="panel-heading">Payment Receiving Info</div>
            <div class="panel-body">
                <?php echo form_open(base_url('order/do-update-payment-info'), ['id' => 'offline-payment-form'],['id' => $payment['id'],'order_id' => $payment['order_id']]); ?>
                <div class="form-group">
                    <?php
                    echo form_label('Payment Status', 'payment_status', ['class' => 'control-label']);
                    echo form_dropdown(['name' => 'payment_status', 'id' => 'payment_status', 'class' => 'form-control'], ['' => '---Select Status---', 'Received' => 'Received', 'Not Received' => 'Not Received']);
                    ?>
                </div>
                <div class="form-group">
                    <?php
                    echo form_label('Message', 'message', ['class' => 'control-label']);
                    echo form_textarea(['name' => 'message', 'id' => 'message', 'placeholder' => 'Type Your Message or Comments', 'class' => 'form-control']);
                    ?>
                </div>
                <div class="form-group">
                    <?php
                    echo form_submit(['name' => 'sub', 'id' => 'payment-sub-btn', 'class' => 'btn btn-primary'], 'Confirm Payment');
                    ?>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-info">
            <div class="panel-heading">Payment Info</div>
            <div class="panel-body">
                <table class="table table-striped table-hover">
                    <tr>
                        <th>Payment Type</th>
                        <td><?php echo $payment['payment_type'] ?></td>
                    </tr>
                    <tr>
                        <th>Username</th>
                        <td><?php echo $payment['name'] ?></td>
                    </tr>
                    <tr>
                        <th>Amount</th>
                        <td><?php echo '<i class="fa fa-rupee"></i> '.$payment['amount'] ?></td>
                    </tr>
                    <tr>
                        <th>Receipt Number</th>
                        <td><?php echo $payment['payment_id'] ?></td>
                    </tr>
                    <tr>
                        <th>Payment Date</th>
                        <td><?php echo date('d-m-Y',  strtotime($payment['payment_date'])) ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>