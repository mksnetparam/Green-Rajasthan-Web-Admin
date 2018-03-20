<div class="row">
    <div class="col-md-12  text-right">
        <a href="<?php echo base_url('plant/add-plant') ?>" class="btn btn-primary btn-sm">Add New Plant</a>
    </div>
</div>
<br>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">Plant List</div>
            <div class="panel-body">
                <div id="list-wrapper" data-url="<?php echo base_url('plant/get_plants'); ?>"></div>
            </div>
        </div>
    </div>
</div>
