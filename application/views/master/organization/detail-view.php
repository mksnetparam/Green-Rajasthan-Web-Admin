<div class="row">
    <div class="col-md-12 text-right">
        <a href="<?php echo base_url('master/organization') ?>" class="btn btn-sm btn-primary">View All Organization</a>
    </div>
</div>
<br>
<div class="panel panel-default">
    <div class="panel-heading">Organization Detail</div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-9">
                <table class="table table-striped table-condensed">
                    <tr>
                        <th>Organization Enroll Id</th>
                        <td><?php echo $org['organization_enroll_id']; ?></td>
                    </tr>
                    <tr>
                        <th>Organization Name</th>
                        <td><?php echo $org['organization_name'] ?></td>
                    </tr>
                    <tr>
                        <th>Contact Person</th>
                        <td><?php echo $org['contact_person'] ?></td>
                    </tr>
                    <tr>
                        <th>Ownership</th>
                        <td><?php echo $org['ownership'] ?></td>
                    </tr>
                    <tr>
                        <td>Mobile</td>
                        <td><?php echo $org['mobile'];?></td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td><?php echo $org['email'];?></td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td><?php echo $org['address_line1'].', '.$org['address_line2'].', '.$org['city_name'].', '.$org['state_name'].', '.$org['country_name'].' - '.$org['pincode'] ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td><?php echo $org['is_active'] ?></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-3">
                <div class="thumbnail">
                    <img src="<?php if(!empty($org['logo'])){ echo base_url('uploads/org/' . $org['logo']);}else {echo base_url('uploads/no_image.jpg');} ?>" title="<?php echo $org['organization_name']; ?>">
                </div>
            </div>
        </div>
    </div>
</div>
