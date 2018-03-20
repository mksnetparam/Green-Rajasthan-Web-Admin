<table class="table table-bordered table-responsive table-striped">
    <tr>
        <th>S.No.</th>
        <th>Image</th>
        <th>Name</th>
        <th>Refer Code</th>
        <th>Referral Plants</th>
        <th>Total Amount</th>
        <th>Paid Amount</th>
        <th>Due Amount</th>
        <th>Action</th>
    </tr>
    <?php
        $i = 1;
        foreach ($tfriends as $friend) {
            ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><img src="<?php
                    if(!empty($friend['image'])) {
                        echo base_url('uploads/user/' . $friend['image']);
                    }else{
                        echo base_url('public/image/blank.png');
                    }
                    ?>" width="50px"></td>
                <td><?php echo $friend['name'] ?></td>
                <td><?php echo $friend['referal_code'] ?></td>
                <td><?php echo $friend['number_of_referal_plants'] ?></td>
                <td><?php echo $amount = $friend['number_of_referal_plants'] * REFERAL_AMOUNT;?></td>
                <td><?php echo $payment = isset($payments[$friend['user_id']]) ? $payments[$friend['user_id']] : 0; ?></td>
                <td><?php echo $due = $amount - $payment; ?></td>
                <td>
                    <a href="<?php echo base_url('user/payment-modal/'.$friend['user_id'].'/'.$due) ?>" class="user-payment-btn"><i class="fa fa-rupee"></i></a>
                </td>
            </tr>
            <?php
            $i++;
        }
    ?>
</table>