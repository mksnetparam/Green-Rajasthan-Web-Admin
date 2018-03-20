<div class="panel panel-primary">
    <div class="panel-heading"><div class="row"><div class="col-md-6">Payment Info</div><div class="col-md-6 text-right"><a href="<?php echo base_url('order'); ?>" class="btn btn-danger">Back</a></div></div></div>
    <div class="panel-body">
        <table class="table table-striped table-hover table-bordered table-condensed">
            <tr>
                <th>S.No.</th>
                <th>Name</th>
                <th>Amount</th>
                <th>Transaction ID</th>
                <th>Payment ID</th>
                <th>Payment Status</th>
                <th>Message</th>
                <th>Payment Type</th>
                <th>Payment Date</th>
                <th>Payment Mode</th>
            </tr>
            <?php
            $i = 1;
            foreach ($payments as $payment) {
                ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo $payment['name']; ?></td>
                    <td><?php echo $payment['amount'] ?></td>
                    <td><?php echo $payment['transaction_id'] ?></td>
                    <td><?php echo $payment['payment_id'] ?></td>
                    <td><?php echo $payment['payment_status'] ?></td>
                    <td><?php echo $payment['message'] ?></td>
                    <td><?php echo $payment['payment_type'] ?></td>
                    <td><?php echo $payment['payment_date'] ?></td>
                    <td><?php echo $payment['payment_mode'] ?></td>
                </tr>    
                <?php }
            ?>
        </table>

    </div>
</div>
