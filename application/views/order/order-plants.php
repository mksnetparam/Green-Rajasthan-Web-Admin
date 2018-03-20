<div class="row">
    <?php foreach ($plants as $plant) { ?>
        <div class="col-md-3">
            <a href="<?php echo base_url('order/plant-growth/' . $plant['id']); ?>">
                <div class="thumbnail">
                    <?php if (!empty($images[$plant['id']])) { ?>
                        <img src="<?php echo base_url('uploads/plant-growth/' . $images[$plant['id']]); ?>">
                    <?php } else {
                        ?><img src="<?php echo base_url('public/image/blank.png'); ?>">
                    <?php }
                    ?>
                    <div class="caption text-center"><h4><?php echo $plant['plant_name'] . '#' . $plant['id'] ?></h4></div>
                </div>
            </a>
        </div>
    <?php } ?>
</div>