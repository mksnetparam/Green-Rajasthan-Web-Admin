<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12 text-right">
                    <a href="<?php echo base_url('order'); ?>" class="btn btn-primary">Back</a>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Select Organization</label>
                        <?php echo form_dropdown(['name' => 'organization_id', 'id' => 'organization_id', 'class' => 'form-control', 'data-url' => base_url('order/get-organization-land'), 'data-userid' => $user_id, 'data-orderid' => $order_id], $organizations, $order['organization_id']); ?>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>No. of Plants to Allocate</label>
                        <?php echo form_input(['name' => 'number_of_plants', 'id' => 'number_of_plants', 'readonly' => 'readonly', 'class' => 'form-control'], $order['number_of_plants']); ?>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>No. of Allocated Plants</label>
                        <?php echo form_input(['name' => 'number_of_plants', 'id' => 'number_of_plants', 'readonly' => 'readonly', 'class' => 'form-control'], $no_of_allocated_plants); ?>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Remaining Plants to allocate</label>
                        <?php echo form_input(['name' => 'number_of_plants', 'id' => 'number_of_plants', 'readonly' => 'readonly', 'class' => 'form-control'], $order['number_of_plants'] - $no_of_allocated_plants); ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-info">
                        <div class="panel-heading">Organization Lands</div>
                        <div class="panel-body">
                            <div id="lands-list">
                                <table class="table table-striped table-hover" id="land-table">
                                    <tr>
                                        <th>Land Id</th>
                                        <th>Land Area</th>
                                        <th>Plant Capacity</th>
                                        <th>Allocated Plant</th>
                                        <th>Remaining Capacity</th>
                                        <th>Contact Person</th>
                                        <th>Registered User</th>
                                        <th>Land Address</th>
                                        <th>District</th>
                                        <th>Tehsil</th>
                                        <th>Allocation</th>
                                    </tr>
                                    
                                    <?php
                                    $sortable_lands = [];
                                    foreach($lands as $land) {
                                        $sortable_lands[array_key_exists($land['land_id'], $allocated_plants_land) ? $land['land_id'].'-'.($land['plant_capacity_qty'] - $allocated_plants_land[$land['land_id']]) : $land['land_id'].'-'.$land['plant_capacity_qty']] = [
                                            'land_id' => $land['land_id'],
                                            'land_area' => $land['land_area'],
                                            'land_area_unit' => $land['land_area_unit'],
                                            'land_address' => $land['land_address'],
                                            'district' => $land['district'],
                                            'tehsil' => $land['tehsil'],
                                            'plant_capacity_qty' => $land['plant_capacity_qty'],
                                            'user_id' => $land['user_id'],
                                            'contact_person' => $land['contact_person'],
                                            'contact_person_mobile' => $land['contact_person_mobile'],
                                            'user_name' => $land['name'],
                                            'user_mobile' => $land['mobile'],
                                            'user_email' => $land['email']
                                        ];
                                    }
                                    krsort($sortable_lands);
                                    $i = 1;
                                    foreach ($sortable_lands as $land) {
                                        ?>
                                        <tr>
                                            <td><?php echo $land['land_id'] ?></td>
                                            <td><?php echo $land['land_area'] . ' ' . $land['land_area_unit'] ?></td>
                                            <td><?php echo $land['plant_capacity_qty'] ?></td>
                                            <td><?php echo array_key_exists($land['land_id'], $allocated_plants_land) ? $allocated_plants_land[$land['land_id']] : 0 ?></td>
                                            <td><?php echo array_key_exists($land['land_id'], $allocated_plants_land) ? $land['plant_capacity_qty'] - $allocated_plants_land[$land['land_id']] : $land['plant_capacity_qty'] ?></td>
                                            <td><?php echo $land['contact_person'] . '<br>' . $land['contact_person_mobile']; ?></td>
                                            <td><?php echo '<a href="' . base_url('user/view-detail/' . $land['user_id']) . '" target="_blank">'.$land['user_name'].' - '. $land['user_mobile'] . ' ' . $land['user_email'] . '</a>'; ?></td>
                                            <td><?php echo $land['land_address'] ?></td>
                                            <td><?php echo $land['district'] ?></td>
                                            <td><?php echo $land['tehsil'] ?></td>
                                            <?php if ((array_key_exists($land['land_id'], $allocated_plants_land) ? $land['plant_capacity_qty'] - $allocated_plants_land[$land['land_id']] : $land['plant_capacity_qty']) > 0) { ?>
                                                <td><a href="<?php echo base_url('order/allocation-plant-to-land/' . $order_id . '/' . $land['land_id'] . '/' . $land['user_id'] . '/' . $user_id) ?>"><i class="fa fa-plus-square"></i></a></td>
                                                    <?php } ?>
                                        </tr>    
                                        <?php
                                    }
                                    ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>  