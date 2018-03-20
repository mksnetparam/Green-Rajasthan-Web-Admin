<div class="row">
    <div class="col-md-12 text-right">
        <a href="<?php echo base_url('user'); ?>" class="bth btn-primary btn-sm">View All User</a>
    </div>
</div>
<br>
<div class="panel panel-default">
    <div class="panel-heading">User Detail</div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-9">
                <table class="table table-striped table-condensed">
                    <tr>
                        <th>Name</th>
                        <td><?php echo $user['name']; ?></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><?php echo $user['email'] ?></td>
                    </tr>
                    <tr>
                        <th>Mobile</th>
                        <td><?php echo $user['mobile'] ?></td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td><?php echo $user['address'] . ', ' . $cities[$user['city_id']] . ', ' . $states[$user['state_id']] . ', ' . $countries[$user['country_id']] ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td><?php echo $user['is_active'] ?></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-3">
                <div class="thumbnail">
                    <img src="<?php
                    if (substr($user['image'], 0, 4) === 'http') {
                        echo $user['image'];
                    } else {
                        echo base_url('uploads/user/' . $user['image']);
                    }
                    ?>" title="<?php echo $user['name']; ?>">

                </div>
            </div>
        </div>
    </div>
</div>
