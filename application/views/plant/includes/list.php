<table class="table table-bordered table-condensed table-hover table-responsive table-striped" id="sortable">
    <thead>
    <tr>
        <th>S.No.</th>
        <th>Image</th>
        <th>Plant Name</th>
        <th>Biological Name</th>
        <th>Maximum Height</th>
        <th>Plant Type</th>
        <th>Soil Type</th>
        <th>Tree Count</th>
        <th>Description</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $i = 1;
    foreach ($plants as $plant) {
        ?>
        <tr>
            <td><?php echo $i++ ?></td>
            <td><img src="<?php if(!empty($plant['image_url'])){ echo base_url('uploads/plant/' . $plant['image_url']);}else {echo base_url('uploads/no_image.jpg');} ?>" width="70px"></td>
            <td><?php echo $plant['plant_name'] ?></td>
            <td><?php echo $plant['biological_name'] ?></td>
            <td><?php echo $plant['max_height'] ?></td>
            <td><?php echo $plant['tree_type'] ?></td>
            <td><?php echo $plant['soil_name'] ?></td>
            <td><?php echo $plant['stock_qty'] ?></td>
            <td><?php echo $plant['description'] ?></td>
            <td><?php echo $plant['is_active'] ?></td>
            <td>
                <a href="<?php echo base_url('plant/edit/' . $plant['plant_id']) ?>" class="edit-plant" title="Edit"><i class="fa fa-edit"></i></a>
                <a href="<?php echo base_url('plant/delete/' . $plant['plant_id']) ?>" class="delete-plant" title="Delete"><i class="fa fa-trash"></i></a>
                <?php
                if ($plant['is_active'] === 'Yes') {
                    ?>
                <a href="<?php echo base_url('plant/change-status/' . $plant['plant_id']) ?>" class="change-status" data-status="No" title="Inactive"><i class="fa fa-thumbs-down"></i></a>
                    <?php
                } else {
                    ?>
                <a href="<?php echo base_url('plant/change-status/' . $plant['plant_id']) ?>" class="change-status" data-status="Yes" title="Active"><i class="fa fa-thumbs-up"></i></a>
                        <?php
                    }
                    ?>
            </td>
        </tr>
    <?php }
    ?>
    </tbody>
</table>