<?php
if (empty($lands)) {
    echo '<p>No Land Available in this section.</p>';
} else {
    ?>
    <table class="table table-bordered table-condensed table-hover table-responsive table-striped" id="sortable">
        <thead>
            <tr>
                <th>Land Id</th>
                <th>Image</th>
                <th>Land Area</th>
                <th>Contact Person</th>
                <th>Registered User</th>
                <th>Capacity</th>
                <th>Land Address</th>
                <th>Land Verified By</th>
                <th>Soil Tested By</th>
                <th>Assigned To</th>
                <th>Updated Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            foreach ($lands as $land) {
                ?>
                <tr>
                    <td><?php echo $land['land_id']; ?></td>
                    <td><img src="<?php
                        if (!empty($land['land_image_url'])) {
                            echo base_url('uploads/land/' . $land['land_image_url']);
                        } else {
                            echo base_url('uploads/no_image.jpg');
                        }
                        ?>" width="70px"></td>
                    <td><?php echo $land['land_area'] . ' ' . $land['land_area_unit']; ?></td>
                    <td><?php echo $land['contact_person'] . '<br>' . $land['contact_person_mobile']; ?></td>
                    <td><?php echo '<a href="' . base_url('user/view-detail/' . $land['user_id']) . '" target="_blank">'.$land['name'].' - '. $land['user_mobile'] . ' ' . $land['user_email'] . '</a>'; ?></td>
                    <td><?php echo $land['plant_capacity_qty'] . ' Plants'; ?></td>
                    <td><?php echo $land['land_address']; ?></td>
                    <td><?php echo empty($land['land_verified_by']) ? '--' : $employees[$land['land_verified_by']]; ?></td>
                    <td><?php echo empty($land['soil_verified_by']) ? '--' : $employees[$land['soil_verified_by']]; ?></td>
                    <td>
                        <?php
                        if ($land_type === 3) {
                            echo 'Rejected';
                        } else if ((empty($land['land_verified_by']) || empty($land['soil_verified_by']))) {
                            echo form_dropdown(['name' => 'land_verified_assigned_to', 'class' => 'form-control land-verified-assigned-to', 'data-url' => $user_assign_url . '/' . $land['land_id']], $users, array_key_exists($land['land_id'], $verfiy_requested_lands) ? $verfiy_requested_lands[$land['land_id']] : '');
                        } else {
                            echo 'Verified';
                        }
                        ?>
                    </td>
                    <td><?php echo date('d-m-Y',strtotime($land['added_date'])); ?></td>
                    <td>
                        <a href="<?php echo base_url('land/view-detail/' . $land['land_id']); ?>" title="View Detail"><i class="fa fa-eye"></i></a> 
                        <?php
                        if ($land_type !== 3 and array_key_exists($land['land_id'], $verfiy_requested_lands)) {
                            ?>
                            <a href="<?php echo base_url($verify_url . '/' . $land['land_id']); ?>" title="Edit"><i class="fa fa-edit"></i></a>
                            <?php
                        } else if ($land_type === 3) {
                            ?>
                            <a href="<?php echo base_url('land/reapply/' . $land['land_id']) ?>" title="Re Apply" class="reapply-land"><i class="fa fa-reply"></i></a>
                            <?php
                        }
                        if (!in_array($land['land_id'], $allocations)) {
                            ?>
                            <a href="<?php echo base_url('land/delete/' . $land['land_id']) ?>" class="delete-land" title="Delete"><i class="fa fa-trash"></i></a>
                            <?php } ?>
                    </td>
                </tr>    
                <?php
                $i++;
            }
            ?>  
        </tbody>
    </table>
<?php } ?>