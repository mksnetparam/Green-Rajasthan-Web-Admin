<table class="table table-bordered table-condensed table-hover table-responsive table-striped">
    <tr>
        <th>S.No.</th>
        <th>Land Id</th>
        <th>Land Address</th>
        <th>No. Of Allocated plants</th>
        <th>No. Of Planted Plants</th>
        <th>Action</th>
    </tr>
    <?php
    $i = 1;
    foreach ($orders as $order) {
        ?>
        <tr>
            <td><?php echo $i++ ?></td>
            <td><?php echo $order['land_id']; ?></td>
            <td><?php echo $order['land_address'].' '.$order['district'].' '.$order['tehsil'].' '.$order['city_name'].' '.$order['state_name'].' '.$order['country_name']; ?></td>
            <td><?php echo array_key_exists($order['land_id'], $allocated)?$allocated[$order['land_id']]:0; ?></td>
            <td><?php echo array_key_exists($order['land_id'], $planted)?$planted[$order['land_id']]:0; ?></td>
            <td>
                <a href="<?php echo base_url('order/print-land-qrcode/'.$order['land_id']);  ?>" title="allocate" target="_blank"><i class="fa fa-print"></i></a>
            </td>
        </tr>
    <?php }
    ?>
</table>