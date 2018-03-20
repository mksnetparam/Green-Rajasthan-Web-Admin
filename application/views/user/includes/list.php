<table class="table table-condensed table-hover table-striped table-responsive" id="sortable">
    <thead>
        <tr>
            <th>S.No.</th><th>User Id</th><th>Image</th><th>Name</th><th>Mobile</th><th>Email</th><th>Address</th><th>Status</th><th>Added Date</th><th>Lands</th><th>Orders</th><th>Role</th><th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (empty($users)) {
            echo '<tr><td colspan="8">No Record Found</td></tr>';
        } else {
            $i = 1;
            foreach ($users as $user) {
                ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo $user['user_id'] ?></td>
                    <td>
                        <img src="<?php
                        if (substr($user['image'], 0, 4) === 'http') {
                            echo $user['image'];
                        } else {
                            if (!empty($user['image'])) {
                                echo base_url('uploads/user/' . $user['image']);
                            } else {
                                echo base_url('uploads/no_image.jpg');
                            }
                        }
                        ?>" width="70px"></td>
                    <td><?php echo $user['name'] ?></td>
                    <td><?php echo $user['mobile'] ?></td>   
                    <td><?php echo $user['email'] ?></td>   
                    <td><?php echo $user['address'] . ', ' . $cities[$user['city_id']] . ', ' . $states[$user['state_id']] . ', ' . $countries[$user['country_id']]; ?></td>   
                    <td><?php echo $user['is_active'] ?></td>
                    <td><?php echo date('d-m-Y', strtotime($user['added_date'])); ?></td>
                    <td><?php
                        if ($this->user_model->has_land($user['user_id'])) {
                            echo '<a href="' . base_url('user/user-lands/' . $user['user_id']) . '" target="_blank"><i class="fa fa-external-link"></i></a>';
                        }
                        ?></td>
                    <td><?php
                        if ($this->user_model->has_order($user['user_id'])) {
                            echo '<a href="' . base_url('user/user-orders/' . $user['user_id']) . '" target="_blank"><i class="fa fa-external-link"></i></a>';
                        }
                        ?></td>
                    <td>
                        <?php
                        if (array_key_exists($user['user_id'], $role)) {
                            echo implode(', ', $role[$user['user_id']]);
                        } else {
                            echo '';
                        }
                        echo form_dropdown(['name' => 'user_role', 'id' => 'user_role', 'class' => 'form-control', 'data-url' => base_url('user/add-role/' . $user['user_id'])], $roles);
                        ?>
                    </td>
                    <td>
                        <a href="<?php echo base_url('user/view-detail/' . $user['user_id']); ?>" title="View Detail"><i class="fa fa-eye"></i></a>
                        <a href="<?php echo base_url('user/edit/' . $user['user_id']) ?>" title="edit" target="_blank"><i class="fa fa-edit"></i></a>
                        <!-- <a href="<?php echo base_url('user/delete/' . $user['user_id']); ?>" class="delete-user" title="Delete"><i class="fa fa-trash"></i></a> -->
                        <?php
                        if ($user['is_active'] === 'Yes') {
                            ?>
                            <a href="<?php echo base_url('user/change-status/' . $user['user_id']) ?>" class="change-status" data-status="No" title="Inactive"><i class="fa fa-thumbs-down"></i></a>
                            <?php
                        } else {
                            ?>
                            <a href="<?php echo base_url('user/change-status/' . $user['user_id']) ?>" class="change-status" data-status="Yes" title="Active"><i class="fa fa-thumbs-up"></i></a>
                                <?php
                            }
                            ?>
                    </td>
                </tr>
                <?php
            }
        }
        ?>
    </tbody>
</table>