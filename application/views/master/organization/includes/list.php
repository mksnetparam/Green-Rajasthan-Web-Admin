<table class="table table-bordered table-condensed table-hover table-responsive table-striped">
    <tr>
        <th>S.No.</th>
        <th>Enroll Id.</th>
        <th>Organization Name</th>
        <th>Logo</th>
        <th>Contact</th>
        <th>Ownership</th>
        <th>Mobile</th>
        <th>Address</th>
        <th>Status</th>
        <th>Action</th>
    </tr>

    <?php
    $i = 1;
    foreach ($organizations as $organization) {
        ?>
        <tr>
            <td><?php echo $i; ?></td>
            <td><?php echo $organization['organization_enroll_id']; ?></td>
            <td><?php echo $organization['organization_name']; ?></td>
            <td><img src="<?php if(!empty($organization['logo'])){ echo base_url('uploads/org/' . $organization['logo']);}else {echo base_url('uploads/no_image.jpg');} ?>" width="70px"></td>
            <td><?php echo $organization['contact_person']; ?></td>
            <td><?php echo $organization['ownership']; ?></td>
            <td><?php echo $organization['mobile']; ?></td>
            <td><?php echo $organization['address_line1'] . ', ' . $organization['address_line2'] . ', ' . $organization['city_name'] . ', ' . $organization['state_name'] . ', ' . $organization['country_name'] . ', ' . $organization['pincode']; ?></td>
            <td><?php echo $organization['is_active']; ?></td>
            <td>
                <a href="<?php echo base_url('master/view-detail/'.$organization['organization_id'])?>" title="View Detail"><i class="fa fa-eye"></i></a>
                <a href="<?php echo base_url('master/edit-organization/'.$organization['organization_id'])?>" title="Edit"><i class="fa fa-edit"></i></a>
                <a href="<?php echo base_url('master/delete/' . $organization['organization_id']) ?>" class="delete-organization" title="Delete"><i class="fa fa-trash"></i></a>
                <?php
                if ($organization['is_active'] === 'Yes') {
                    ?>
                    <a href="<?php echo base_url('master/change-status/' . $organization['organization_id']) ?>" class="change-status" data-status="No" title="Inactive"><i class="fa fa-thumbs-down"></i></a>
                    <?php
                } else {
                    ?>
                    <a href="<?php echo base_url('master/change-status/' . $organization['organization_id']) ?>" class="change-status" data-status="Yes"><i class="fa fa-thumbs-up" title="Active"></i></a>
                        <?php
                    }
                    ?>
            </td>
        </tr>    
        <?php
        $i++;
    }
    ?>  
</table>