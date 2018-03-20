<div class="panel panel-primary">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-6">Donation Payment Details</div>
            <div class="col-md-6">
                <a href="<?php echo base_url('order/payments'); ?>" class="btn btn-danger pull-right">Back</a>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <table class="table table-bordered table-condensed table-striped table-responsive">
            <tr>
                <th>S.No.</th>
                <th>Name</th>
                <th>Amount</th>
                <th>Payment Type</th>
                <th>Receipt No.</th>
                <th>User Id</th>
                <th>Payment Date</th>
            </tr>
            <?php
            $i = 1;
            foreach ($details as $detail) {
                ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo $detail['name'] ?></td>
                    <td><?php echo $detail['amount'] ?></td>
                    <td>
                    <?php
                        if ($detail['payment_type'] === 'Offline' or $detail['payment_type'] === 'Paytm') {
                        if ($detail['payment_status'] !== 'Received') {
                            echo '<a href="' . base_url('user/update-payment-status/'.$detail['id']) . '">' . $detail['payment_type'] . '</a>';
                        } else {
                            echo $detail['payment_type'];
                        }
                    } else {
                        echo $detail['payment_type'];
                    }
                     ?>
                    </td>
                    <td><?php echo $detail['payment_id'] ?></td>
                    <td><a href="<?php echo base_url('user/view-detail/'.$detail['user_id']) ?>" target="_blank"><?php echo $detail['user_name'] ?></a></td>
                    <td><?php echo $detail['payment_date'] ?></td>
                </tr>    
                <?php }
            ?>
        </table>
    </div>
</div>