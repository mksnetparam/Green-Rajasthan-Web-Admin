<table class="table table-condensed table-hover table-striped table-responsive">
    <tr>
        <th>S.No.</th><th>Name</th><th>Mobile</th><th>Email</th><th>Role</th><th>Status</th><th>Action</th>
    </tr>
    <?php
    if (empty($users)) {
        echo '<tr><td colspan="8">No Record Found</td></tr>';
    } else {
        $i = 1;
        foreach ($users as $user) {
            ?>
            <tr>
                <td><?php echo $i++; ?></td>
                <td><?php echo $user['firstname'].' '.$user['lastname'] ?></td>
                <td><?php echo $user['phone'] ?></td>   
                <td><?php echo $user['email'] ?></td>   
                <td><?php echo $user['role_name'];?></td>   
                <td><?php echo $user['is_active'] ?></td>
                <td>
                    <a href="<?php echo base_url('employee/edit/' . $user['id']) ?>" title="edit"><i class="fa fa-edit"></i></a> 
                    <!--<a href="<?php // echo base_url('employee/delete/' . $user['id']); ?>" class="delete-user" title="delete"><i class="fa fa-trash"></i></a>-->
                    <?php
                    if ($user['is_active'] === 'Yes') {
                        ?>
                    <a href="<?php echo base_url('employee/change-status/' . $user['id']) ?>" class="change-status" data-status="No" title="Inactive"><i class="fa fa-thumbs-down"></i></a>
                        <?php
                    } else {
                        ?>
                    <a href="<?php echo base_url('employee/change-status/' . $user['id']) ?>" class="change-status" data-status="Yes" title="Active"><i class="fa fa-thumbs-up"></i></a>
                            <?php
                        }
                        ?>
                </td>
            </tr>
            <?php
        }
    }
    ?>
</table>