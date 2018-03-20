<div class="row">
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">User Orders</div>
            <div class="panel-body">
                <table class="table table-striped">
                    <tr>
                        <th>S.No</th>
                        <th>Order Id</th>
                        <th>Number of plants</th>
                        <th>Nominee Name</th>
                        <th>Order Date</th>
                        <th>Last Payment Date</th>
                        <th>Order Status</th>
                    </tr>
                    <?php
                    $i = 1;
                    foreach ($orders as $order) {
                        ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo $order['order_id'] ?></td>
                            <td><?php echo $order['number_of_plants'] ?></td>
                            <td><?php echo $order['nominee_name'] ?></td>
                            <td><?php echo date('d/m/Y', strtotime($order['order_date'])) ?></td>
                            <td><?php echo $order['last_payment_date'] ?></td>
                            <td><?php echo $order['order_status'] ?></td>
                        </tr>
                        <?php }
                    ?>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">User Detail</div>
            <div class="panel-body">
                <table class="table table-striped">
                    <tr>
                        <th>Name</th><td><?php echo $user['name'] ?></td>
                    </tr>
                    <tr>
                        <th>Mobile</th><td><?php echo $user['mobile'] ?></td>
                    </tr>
                    <tr>
                        <th>Email</th><td><?php echo $user['email'] ?></td>
                    </tr>
                    <tr>
                        <th>Address</th><td><?php echo $user['address'] ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>