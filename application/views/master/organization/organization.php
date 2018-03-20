<div class="row">
    <div class="col-md-12  text-right">
        <a href="<?php echo base_url('master/add-organization')?>" class="btn btn-primary btn-sm">Add New Organization</a>
    </div>
</div>
<br>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
            <div class="row">
                    <div class="col-md-6">Organization List</div>
                    <div class="col-md-6 text-right"><a href="<?php echo base_url('master/organization') ?>" class="btn btn-sm btn-danger hidden" id="add-city-panel-btn">Add Organization</a></div>
                </div>  
            </div>
            <div class="panel-body">
                <div id="list-wrapper" data-url="<?php echo base_url('master/get-organizations'); ?>"></div>
            </div>
        </div>
    </div>
</div>