<table class="table table-bordered table-condensed table-hover table-responsive table-striped" id="sortable">
    <thead>
    <tr>
        <th>S.No.</th>
        <th>Order Id</th>
        <th>User Name</th>
        <th>No. Of plants</th>
        <th>No. Of Allocated plants</th>
        <th>Payment Mode</th>
        <th>Plant Cost</th>
        <th>Organization</th>
        <th>Order Date</th>
        <th>Last Payment Done</th>
        <th>Due Date</th>
        <th>Payment Type</th>
        <th>Payment Status</th>
        <th>Order Status</th>
        <th>Order Date</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $i = 1;
    foreach ($orders as $order) {
        ?>
        <tr>
            <td><?php echo $i++ ?></td>
            <td><?php echo $order['order_id'] ?></td>
            <td><a href="<?php echo base_url('user/view-detail/' . $order['user_id']) ?>"
                   target="_blank"><?php echo $order['name'] ?></a></td>
            <td><?php echo $order['number_of_plants'] ?></td>
            <td><?php echo array_key_exists($order['order_id'], $allocated_plants) ? $allocated_plants[$order['order_id']] : '0'; ?></td>
            <td><?php echo $order['payment_mode'] ?></td>
            <td><?php echo PLANT_COST ?></td>
            <td><a href="<?php echo base_url('master/view-detail/' . $order['organization_id']) ?>"
                   target="_blank"><?php echo $order['organization_name'] ?></a></td>
            <td><?php echo date('d/m/Y h:i:s', strtotime($order['order_date'])); ?></td>
            <td><?php
                if ($order['payment_status'] === 'Not Received') {
                    echo 'N/A';
                } else {
                    echo $order['last_payment_date'];
                }
                ?></td>
            <td
                <?php
                if ($order['payment_status'] === 'Received' && $order['due_date'] !== 'One Time Paid') {
                    $strDate = explode("/", $order['due_date']);
                    $dueTime = mktime(0, 0, 0, $strDate[1], $strDate[0], $strDate[2]);
                    if ($dueTime < time()) {
                        echo 'class="bg-danger"';
                    } else {
                        echo 'class="bg-success"';
                    }
                } ?>
            >
                <?php
                if ($order['payment_status'] === 'Received') {
                    if ($order['due_date'] === 'One Time Paid') {
                        echo 'N/A';
                    } else {
                        echo $order['due_date'];
                    }
                } else {
                    echo 'NP';
                }
                ?></td>
            <td><?php
                if ($order['payment_type'] === 'Offline' or $order['payment_type'] === 'Paytm') {
                    if ($order['payment_status'] !== 'Received') {
                        echo '<a href="' . base_url('order/update-payment-info/' . $order['payment_id']) . '">' . $order['payment_type'] . '</a>';
                    } else {
                        echo $order['payment_type'];
                    }
                } else {
                    echo $order['payment_type'];
                }
                ?></td>
            <td><?php echo $order['payment_status'] ?></td>

            <td>
                <?php
                if (!empty($order['payment_status']) and $order['payment_status'] === 'Received') {
                    echo form_dropdown(['name' => 'order_status', 'id' => 'order_status', 'class' => 'order-status', 'data-url' => base_url('order/change-order-status/' . $order['order_id']), 'data-plants' => ($order['number_of_plants'] - (array_key_exists($order['order_id'], $allocated_plants) ? $allocated_plants[$order['order_id']] : '0'))], ['Not Processed' => 'Not Processed', 'Processing' => 'Processing', 'Processed' => 'Planted'], $order['order_status']);
                }
                ?>
            </td>
            <td><?php echo date('d-m-Y', strtotime($order['order_date'])); ?></td>
            <td>
                <?php
                if (!empty($order['payment_status']) and $order['payment_status'] === 'Received') {
                    if ($order['order_status'] === 'Processing') {
                        ?>
                        <a href="<?php echo base_url('order/allocate/' . $order['order_id'] . '/' . $order['user_id']); ?>"
                           title="allocate"><i class="fa fa-plus-square"></i></a>
                        <a href="<?php echo base_url('order/get-order-plants/' . $order['order_id']); ?>"
                           title="View Plant Growth" target="_blank"><i class="fa fa-bar-chart"></i></a>
                        <a href="<?php echo base_url('order/print-qrcode/' . $order['order_id']); ?>" title="Print QR"
                           target="_blank"><i class="fa fa-print"></i></a>
                        <a class="delete-allocation"
                           href="<?php echo base_url('order/delete-allocation/' . $order['order_id']); ?>"
                           title="Delete Allocation"><i class="fa fa-trash"></i></a>
                    <?php } else if ($order['order_status'] === 'Processed') { ?>
                        <a href="<?php echo base_url('order/get-order-plants/' . $order['order_id']); ?>"
                           title="View Plant Growths" target="_blank"><i class="fa fa-bar-chart"></i></a>
                        <a href="<?php echo base_url('order/print-qrcode/' . $order['order_id']); ?>" title="Print QR"
                           target="_blank"><i class="fa fa-print"></i></a>
                    <?php } ?>
                    <a href="<?php echo base_url('order/view-payments/' . $order['order_id']); ?>" title="View Payment"><i
                                class="fa fa-eye"></i></a>
                    <?php
                } else {
                    ?>
                    <a href="<?php echo base_url('order/view-payments/' . $order['order_id']); ?>" title="View Payment"><i
                                class="fa fa-eye"></i></a>
                    <?php
//                            echo 'NP';
                }
                ?>
                <a href="#" title="Send Notification" data-toggle="modal" data-target="#fcmModal"
                   data-name="<?php echo $order['name'] ?>" data-fcmid="<?php echo $order['fcm_id'] ?>"><i
                            class="fa fa-envelope-o"></i></a>
            </td>
        </tr>
    <?php }
    ?>
    </tbody>
</table>