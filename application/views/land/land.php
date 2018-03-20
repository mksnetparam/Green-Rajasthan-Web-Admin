<!--<div class="row">
    <div class="col-md-12  text-right">
        <a href="<?php echo base_url('land/add-land') ?>" class="btn btn-primary btn-sm">Add New Land</a>
    </div>
</div>-->
<br>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-6">Land List</div>
                    <div class="col-md-6 text-right">
                        <div class="btn-group" role="group" aria-label="...">
                            <a href="<?php echo base_url('land/get-lands')?>" class="btn btn-default btn-primary btn-sm filter-land">Request for Approval</a>
                            <a href="<?php echo base_url('land/get-lands/Soil_Processing')?>" class="btn btn-default btn-sm filter-land">Approved</a>
                            <a href="<?php echo base_url('land/get-lands/Rejected')?>" class="btn btn-default btn-sm filter-land">Rejected</a>
                        </div>
                    </div>
                </div>
            </div>  
            <div class="panel-body">
                <div id="list-wrapper" data-url="<?php echo base_url('land/get-lands'); ?>"></div>
            </div>
        </div>
    </div>
</div>