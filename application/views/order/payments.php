<fieldset>
    <legend><h3>View Payments</h3></legend>
    <form action="#" id="view-payments-form">
        <div class="row">
            <div class="col-md-4">
                <!--<label class="control-label">Date From</label>-->
                <input type="text" name="datefrom" id="datefrom" class="form-control datefrom" placeholder="Date From" value="<?php echo date('d/m/Y') ?>">
            </div>
            <div class="col-md-4">
                <!--<label class="control-label">Date To</label>-->
                <input type="text" name="dateto" id="dateto" class="form-control dateto" placeholder="Date To" value="<?php echo date('d/m/Y') ?>">
            </div>
            <div class="col-md-4">
                <input type="submit" name="submit" id="view-payments-btn" value="View" class="btn btn-primary">
            </div>
        </div>
    </form>
</fieldset>
<br>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">Payments</div>
            <div class="panel-body">
                <div id="list-wrapper" data-url="<?php echo base_url('order/get-payments'); ?>"></div>
            </div>
        </div>
    </div>
</div>