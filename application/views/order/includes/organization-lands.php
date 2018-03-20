<table class="table table-striped table-hover">
    <tr>
        <th>Land Id</th>
        <th>Land Area</th>
        <th>Plant Capacity</th>
        <th>Land Address</th>
        <th>District</th>
        <th>Tehsil</th>
        <th>Allocation</th>
    </tr>
    <?php 
    if (empty($lands)) {
        echo '<tr><td colspan="6">No Record Found</td></tr>';
    } else {
        $i = 1;
        foreach ($lands as $land) {
            ?>
            <tr>
                <td><?php echo $land['land_id']; ?></td>
                <td><?php echo $land['land_area'] . ' ' . $land['land_area_unit']; ?></td>
                <td><?php echo $land['plant_capacity_qty']; ?></td>
                <td><?php echo $land['land_address']; ?></td>
                <td><?php echo $land['district']; ?></td>
                <td><?php echo $land['tehsil']; ?></td>
                <?php if ((array_key_exists($land['land_id'], $allocated_plants_land) ? $land['plant_capacity_qty'] - $allocated_plants_land[$land['land_id']] : $land['plant_capacity_qty']) > 0) { ?>
                <td><a href="<?php echo base_url('order/allocation-plant-to-land/' . $order_id . '/' . $land['land_id'] . '/' . $land['user_id'] . '/' . $user_id) ?>" title="Allocate"><i class="fa fa-plus-square"></i></a></td>
                        <?php } ?>
            </tr>    
            <?php
        }
    }
    ?>
</table>