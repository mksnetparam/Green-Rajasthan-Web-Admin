<table class="table table-bordered table-striped table-responsive">
    <tr>
        <th>S.No</th>
        <th>Image</th>
        <th>Name</th>
        <th>Referral Code</th>
        <th>Referral Plants</th>
        <th>Total Amount</th>
        <th>Paid Amount</th>
        <th>Due Amount</th>
        <th>Area</th>
        <th>Action</th>
    </tr>
    <?php
    $i = 1;
    foreach ($copartners as $copartner) { ?>
        <tr>
            <td><?php echo $i++; ?></td>
            <td><img src="<?php
                if (!empty($copartner['image'])) {
                    echo base_url('uploads/user/' . $copartner['image']);
                } else {
                    echo base_url('public/image/blank.png');
                } ?>" width="100px">

            </td>
            <td><?php echo $copartner['name']; ?></td>
            <td><?php echo $copartner['referal_code'] ?></td>
            <td><?php echo $copartner['number_of_referal_plants'] ?></td>
            <td><?php echo $amount = $copartner['number_of_referal_plants'] * REFERAL_AMOUNT;?></td>
            <td><?php echo $payment = isset($payments[$copartner['user_id']]) ? $payments[$copartner['user_id']] : 0; ?></td>
            <td><?php echo $due = $amount - $payment; ?></td>
            <td>
                <?php
                    if(!empty($copartner['city_id'])) {
                        $arr = explode(',',$copartner['city_id']);
                        $out = [];
                        foreach($arr as $item) {
                            $out[] = $cities[$item];
                        }
                        echo implode(', ',$out);
                    }

                    elseif(!empty($copartner['state_id'])) {
                        echo $states[$copartner['state_id']];
                    }

                    elseif(!empty($copartner['country_id'])) {
                        echo $countries[$copartner['country_id']];
                    }
                ?>
            </td>

            <td>
                <a href="<?php echo base_url('user/allocate-area-modal/'.$copartner['role_id']) ?>" class="allocate-area" title="Allocate Area"><i class="fa fa-map-marker"></i></a>
                <a href="<?php echo base_url('user/payment-modal/'.$copartner['user_id'].'/'.$due) ?>" class="user-payment-btn" title="Payment"><i class="fa fa-rupee"></i></a>
            </td>
        </tr>
    <?php } ?>
</table>