<form action="<?php echo base_url('order/do-allocate'); ?>" method="post" id="plant-allocation-form">
    <?php
    echo form_hidden([
        'land_id' => $land_id,
        'user_id' => $user_id,
        'land_user_id' => $land_user_id,
        'order_id' => $order['order_id'],
        'organization_id' => $order['organization_id']
    ]);
    ?>
    <table class="table table-condensed table-striped table-striped">
        <tr>
            <th>Plant list</th>
            <th>Season</th>
            <th>No. of Plants to Allocate</th>
        </tr>

        <?php
        foreach ($result as $row) {
            ?>
            <tr>
                <td><?php echo $row['plant_name'] ?></td>
                <td><?php echo $row['season'] ?></td>
                <td><input type="number" name="plants[]" class="form-control plant" min="0" placeholder="0"></td>
            <input type="hidden" name="plant_id[]" value="<?php echo $row['plant_id']; ?>">
            </tr>
        <?php } ?>
    </table>
    <div class="row">
        <div class="col-md-12 text-center">
            <input type="submit" name="submit" value="Allocate" class="btn btn-primary"/>
        </div>
    </div>
</form>
