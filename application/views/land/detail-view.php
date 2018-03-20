<div class="row">
    <div class="col-md-12 text-right">
        <a href="<?php echo base_url('land'); ?>" class="btn btn-primary btn-sm">View Lands</a>
    </div>
</div>
<br>
<div class="row">
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">Land Detail</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <fieldset>
                            <legend>Land Info</legend>
                            <table class="table table-striped table-responsive table-condensed">
                                <tr>
                                    <th>Land Area</th>
                                    <td><?php echo $land['land_area'] . ' ' . $land['land_area_unit'] ?></td>
                                </tr>
                                <tr>
                                    <th>Plant Capactiy</th>
                                    <td><?php echo $land['plant_capacity_qty']; ?></td>
                                </tr>
                                <tr>
                                    <th>Soil Type</th>
                                    <td><?php
                                        if (isset($soil_name)) {
                                            echo $soil_name;
                                        } else {
                                            echo 'N/A';
                                        }
                                        ?></td>
                                </tr>
                                <tr>
                                    <th>Land Address</th>
                                    <td><?php echo $land['land_address']; ?></td>
                                </tr>
                                <tr>
                                    <th>Associated Organization</th>
                                    <td><?php echo $land['organization_name'] ?></td>
                                </tr>
                            </table>
                        </fieldset>
                    </div>
                    <div class="col-md-6">
                        <fieldset>
                            <legend>Verification Detail</legend>
                            <table class="table table-striped table-responsive table-condensed">
                                <tr>
                                    <th>Land Verified By</th>
                                    <td><?php echo empty($land['land_verified_by']) ? '--' : $employees[$land['land_verified_by']]; ?></td>
                                </tr>
                                <tr>
                                    <th>Land Verification Comment</th>
                                    <td><?php echo $land['land_verification_comments']; ?></td>
                                </tr>
                                <tr>
                                    <th>Soil Tested By</th>
                                    <td><?php echo empty($land['soil_verified_by']) ? '--' : $employees[$land['soil_verified_by']]; ?></td>
                                </tr>
                                <tr>
                                    <th>Soil Testing Comments</th>
                                    <td><?php echo $land['soil_verification_comments']; ?></td>
                                </tr>
                            </table>
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <fieldset>
                            <legend>Supported Docs</legend>
                            <?php
                            if (!empty($docs)) {
                                $i = 1;
                                foreach ($docs as $doc) {
                                    ?>
                                    <?php if (file_exists(APPPATH . '/../uploads/land/docs/' . $doc['doc'])) { ?>
                                        <div class="col-md-3">
                                            <p>File-<?php echo $i; ?> <a href="<?php echo base_url('uploads/land/docs/' . $doc['doc']); ?>" download>Download</a></p>
                                        </div>
                                        <?php
                                        $i++;
                                    }
                                }
                            }
                            ?>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="thumbnail">
            <img src="<?php
            if ($land['land_image_url']) {
                echo base_url('uploads/land/' . $land['land_image_url']);
            } else {
                echo base_url('uploads/no_image.jpg');
            }
            ?>">
        </div>
        <div class="panel panel-info">
            <div class="panel-heading">User Detail</div>
            <div class="panel-body">
                <table class="table table-condensed table-striped">
                    <tr>
                        <th>Username</th>
                        <td><?php echo $land['username'] ?></td>
                    </tr>
                    <tr>
                        <th>Contact Person Name</th>
                        <td><?php echo $land['contact_person']; ?></td>
                    </tr>
                    <tr>
                        <th>Contact Person Mobile</th>
                        <td><?php echo $land['contact_person_mobile'] ?></td>
                    </tr>
                    <tr>
                        <th>Relationship With User</th>
                        <td><?php echo $land['contact_person_relation'] ?></td>
                    </tr>
                </table>                    
            </div>
        </div>
        <div class="panel panel-info">
            <div class="panel-heading">Plant Suggestion</div>
            <div class="panel-body">
                <table class="table table-condensed table-responsive table-striped">
                    <tr>
                        <th>Plant Name</th>
                        <th>Season</th>
                    </tr>
                    <?php
                    if (!empty($plants)) {
                        foreach ($plants as $plant) {
                            ?>
                            <tr>
                                <td><?php echo $plant['plant_name']; ?></td>      
                                <td><?php echo $plant['season']; ?></td>      
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
</div>