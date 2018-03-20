<div class="container-fluid">
    <div class="alert" id="error_msg"></div>
    <div class="row">
        <div class="col-md-12 text-right">
            <a href="<?php echo base_url('order/allocate/'.$order_id.'/'.$user_id); ?>" class="btn btn-primary">Back</a>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-2">Allocation</div>
                        <div class="col-md-10 text-right"> <?php echo 'No. of Plant to allocated:<span id="number_of_plants">' . $order['number_of_plants'] . '</span> | No. of Allocated Plants:<span id="allocated_plants">' . $no_of_allocated_plants . '</span> | Remaining Plants:<span id="remaining_order_capacity">' . ($order['number_of_plants'] - $no_of_allocated_plants).'</span>'; ?></div>
                    </div>
                </div>
                <div class="panel-body">
                    <?php echo $suggestions ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="panel panel-warning">
                <div class="panel-heading">Land Detail</div>
                <div class="panel-body">
                    <table class="table table-condensed table-hover table-striped">
                        <tr>
                            <th>Land Area</th>
                            <td><?php echo $land['land_area'] . ' ' . $land['land_area_unit']; ?></td>
                        </tr>
                        <tr>
                            <th>Max Plant capacity</th>
                            <td id="plant_capacity"><?php echo $land['plant_capacity_qty']; ?></td>
                        </tr>
                        <tr>
                            <th>Allocated Plants</th>
                            <td><?php echo $land_allocated_plants?></td>
                        </tr>
                        <tr>
                            <th>Remaining Capacity</th>
                            <td id="remaining_capacity"><?php echo $land['plant_capacity_qty'] - $land_allocated_plants; ?></td>
                        </tr>
                        <tr>
                            <th>Land Address</th>
                            <td><?php echo $land['land_address'] ?></td>
                        </tr>
                        <tr>
                            <th>District</th>
                            <td><?php echo $land['district'] ?></td>
                        </tr>
                        <tr>
                            <th>Tehsil</th>
                            <td><?php echo $land['tehsil'] ?></td>
                        </tr>
                        <tr>
                            <th>City</th>
                            <td><?php echo $land['city_name'] ?></td>
                        </tr>
                        <tr>
                            <th>State</th>
                            <td><?php echo $land['state_name'] ?></td>
                        </tr>
                        <tr>
                            <th>Country</th>
                            <td><?php echo $land['country_name'] ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>