<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered table-striped table-responsive table-hover">
            <tr>
                <th>Tree Name</th>
                <th>Season</th>
                <th>Action</th>
            </tr>
            <?php foreach ($result as $row) {
                ?>
                <tr>
                    <td><?php echo $row['plant_name'] ?></td>
                    <td><?php echo $row['season'] ?></td>
                    <td><a href="<?php echo base_url('land/delete-tree-suggestion/'.$row['id'].'/'.$row['land_id']); ?>" class="delete"><i class="fa fa-trash"></i></a></td>
                        <?php }
                        ?>
            <tr>
        </table>
    </div>
</div>