<div class="panel panel-primary">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-6">Payment Details</div>
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
                <th>Order Id</th>
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
                    <td><?php echo $detail['payment_type'] ?></td>
                    <td><?php echo $detail['payment_id'] ?></td>
                    <td>
                        <a href="<?php echo base_url('order/' . $detail['order_id']) ?>" target="_blank"><?php echo $detail['order_id'] ?></a>
                    </td>
                    <td><?php echo $detail['payment_date'] ?></td>
                </tr>
            <?php }
            ?>
        </table>
    </div>
</div>