<table class="table table-bordered table-condensed table-striped table-responsive">
    <tr>
        <th>S.No</th>
        <th>Date</th>
        <th>Total Amount</th>
        <th>Action</th>
    </tr>
    <?php $i=1;
    foreach ($payments as $payment){
        ?>
    <tr>
        <td><?php echo $i++; ?></td>
        <td><?php echo date('d-m-Y',  strtotime($payment['payment_date'])); ?></td>
        <td><?php echo $payment['amount'] ?></td>
        <td><a href="<?php echo base_url('user/view-donation-payment-detail/'. rawurlencode(base64_encode($payment['payment_date'])).'/'.$payment['user_id']); ?>"><i class="fa fa-eye"></i></a></td>
    </tr>    
        <?php
    }
    ?>
</table>