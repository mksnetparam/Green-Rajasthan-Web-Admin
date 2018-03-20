<h1 class="page-header">
    Dashboard <small>Statistics Overview</small>
</h1>
<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-bolt fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php echo $total_lands ?></div>
                        <div>Total Land Registered</div>
                    </div>
                </div>
            </div>
            <a href="<?php echo base_url('land'); ?>">
                <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-green">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-check-square fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php echo $total_plants_allocated; ?></div>
                        <div>Total Allocated Plants</div>
                    </div>
                </div>
            </div>
            <a href="<?php echo base_url('order/index/Processed'); ?>">
                <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-yellow">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-rupee fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php echo $total_orders ?></div>
                        <div>Total Order</div>
                    </div>
                </div>
            </div>
            <a href="<?php echo base_url('order'); ?>">
                <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-red">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-user fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php echo $users_count; ?></div>
                        <div>Registered Users</div>
                    </div>
                </div>
            </div>
            <a href="<?php echo base_url('user'); ?>">
                <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
</div>
<!-- /.row -->

<div class="row">    
    <div class="col-lg-12 col-md-12">
        <div class="panel panel-info">
            <div class="panel-heading">New Orders</div>
            <div class="panel-body">
                <table class="table table-responsive table-condensed table-striped table-bordered table-hover">
                    <!--<tr>-->
<!--                        <th>S.No.</th>
                        <th>Nominee Name</th>
                        <th>Number of Plants to Allocate</th>
                        <th>Payment Mode</th>
                        <th>Plant Cost</th>-->

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
                    <?php
                    $i = 1;
                    foreach ($new_orders as $order) {
                        ?>
                            <tr>
                <td><?php echo $i++ ?></td>
                <td><?php echo $order['order_id'] ?></td>
                <td><a href="<?php echo base_url('user/view-detail/' . $order['user_id']) ?>" target="_blank"><?php echo $order['name'] ?></a></td>
                <td><?php echo $order['number_of_plants'] ?></td>
                <td><?php echo array_key_exists($order['order_id'], $allocated_plants) ? $allocated_plants[$order['order_id']] : '0'; ?></td>
                <td><?php echo $order['payment_mode'] ?></td>
                <td><?php echo PLANT_COST ?></td>
                <td><a href="<?php echo base_url('master/view-detail/' . $order['organization_id']) ?>" target="_blank"><?php echo $order['organization_name'] ?></a></td>
                <td><?php echo date('d/m/Y h:i:s', strtotime($order['order_date'])); ?></td>
                <td><?php
                    if ($order['payment_status'] === 'Not Received') {
                        echo 'N/A';
                    } else {
                        echo $order['last_payment_date'];
                    }
                    ?></td>
                <td><?php
                    if($order['payment_status']==='Received') {
                        if ($order['due_date'] === 'One Time Paid') {
                            echo 'N/A';
                        } else {
                            echo $order['due_date'];
                        }
                    }else {
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
                <td><?php echo date('d-m-Y',strtotime($order['order_date'])); ?></td>
                <td>
                    <?php
                    if (!empty($order['payment_status']) and $order['payment_status'] === 'Received') {
                        if ($order['order_status'] === 'Processing') {
                            ?>
                            <a href="<?php echo base_url('order/allocate/' . $order['order_id'] . '/' . $order['user_id']); ?>" title="allocate"><i class="fa fa-plus-square"></i></a>
                            <a href="<?php echo base_url('order/get-order-plants/' . $order['order_id']); ?>" title="View Plant Growth" target="_blank"><i class="fa fa-bar-chart"></i></a>
                            <a href="<?php echo base_url('order/print-qrcode/' . $order['order_id']); ?>" title="Print QR" target="_blank"><i class="fa fa-print"></i></a>
                        <?php } else if ($order['order_status'] === 'Processed') {?>
                            <a href="<?php echo base_url('order/get-order-plants/' . $order['order_id']); ?>" title="View Plant Growth"><i class="fa fa-bar-chart"></i></a>
                            <a href="<?php echo base_url('order/print-qrcode/' . $order['order_id']); ?>" title="Print QR" target="_blank"><i class="fa fa-print"></i></a>    
                        <?php }?>
                        <a href="<?php echo base_url('order/view-payments/' . $order['order_id']); ?>" title="View Payment"><i class="fa fa-eye"></i></a>
                            <?php
                        } else {
                            ?>
                        <a href="<?php echo base_url('order/view-payments/' . $order['order_id']); ?>" title="View Payment"><i class="fa fa-eye"></i></a>
                            <?php
//                            echo 'NP';
                        }
                        ?>
                </td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
                <a href="<?php echo base_url('order'); ?>">View All</a>
            </div>
        </div>
    </div>
</div>