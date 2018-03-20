<div class="container-fluid">
    <div class="row">
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Organization</label>
                        <?php echo form_dropdown(['name' => 'organization_id', 'id' => 'organization_id', 'class' => 'form-control','data-url' => base_url('order/get-organization-land')], $organizations , $order['organization_id']); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <label>Land</label>
                    <?php echo form_dropdown(['name' => 'land_id', 'id' => 'land_id', 'class' => 'form-control'], $lands); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <fieldset>
                        <legend>Plant Suggestions</legend>
                        
                    </fieldset>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel panel-info">
                <div class="panel-heading">Land Info</div>
                <div class="panel-body">
                    <table class="table table-striped">
                        <tr>
                            <th>Max Capacity</th>
                            <td>100</td>
                        </tr>
                        <tr>
                            <th>Available Capacity</th>
                            <td>50</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>