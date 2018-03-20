<div class="row">
    <?php foreach ($images as $image) { ?>
        <div class="col-md-3">
            <div class="thumbnail">
                <img src="<?php echo base_url('uploads/plant-growth/' . $image['image']); ?>">
                <div class="caption text-center"><h4><?php echo date('d-m-Y h:i',  strtotime($image['capture_date']))?></h4></div>
            </div>
        </div>
    <?php } ?>
</div>