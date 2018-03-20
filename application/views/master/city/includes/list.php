<?php
$pages = ceil($total_rows / ROWS_PER_PAGE);
?>
<div class="row">
    <div class="col-md-6">
        <label class="control-label">Action</label>
        <?php echo form_dropdown(['name' => 'action', 'id' => 'action', 'disabled' => 'disabled', 'data-url' => base_url('master/change-all-city-status/' . $pg), 'class' => 'textbox'], ['' => 'None', 'Yes' => 'Yes', 'No' => 'No']); ?>
    </div>
    <div class="col-md-6 text-right">
        Showing <?php echo form_dropdown(['name' => 'page', 'id' => 'page', 'data-url' => base_url('master/get-cities'), 'class' => 'textbox'], range(1, $pages), $pg); ?> page of <?php echo $pages ?> pages.
    </div>
</div>
<br>
<?php echo form_open('master/filter-city', ['id' => 'filter-city-form']); ?>

<table class="table table-bordered table-condensed table-hover table-responsive table-striped">
    <tr>
        <th><?php echo form_checkbox(['name' => 'select-all', 'id' => 'select-all']) ?></th>
        <th>S.No.</th>
        <th>City Name</th>
        <th>State</th>
        <th>Country</th>
        <th>Added Date</th>
        <th>Modified Date</th>
        <th>Is Active</th>
        <th style="width: 60px;">Action</th>
    </tr>
    <tr>
        <td><i class="fa fa-search"></i></td>
        <td>#</td>
        <td><div class="form-group form-group-sm"><?php echo form_input(['name' => 'city', 'id' => 'city', 'class' => 'form-control', 'placeholder' => 'City Name'],set_value('city', $this->input->post('city') ? $this->input->post('city') : '')) ?></div></td>
        <td><div class="form-group form-group-sm"><?php echo form_input(['name' => 'state', 'id' => 'state', 'class' => 'form-control', 'placeholder' => 'State Name'],set_value('state', $this->input->post('state') ? $this->input->post('state') : '')) ?></div></td>
        <td><div class="form-group form-group-sm"><?php echo form_input(['name' => 'country', 'id' => 'country', 'class' => 'form-control', 'placeholder' => 'Country Name'],set_value('country', $this->input->post('country') ? $this->input->post('country') : '')) ?></div></td>
        <td></td>
        <td></td>
        <td><div class="form-group form-group-sm"><?php echo form_dropdown(['name' => 'status', 'id' => 'status', 'class' => 'form-control'], ['' => '---Status---', 'Yes' => 'Yes', 'No' => 'No'],set_value('status', $this->input->post('status') ? $this->input->post('status') : '')) ?></div></td>
        <td>
            <?php echo form_button(['type'=>'submit','name' => 'sub', 'id' => 'filter-state-sub-btn', 'class' => 'filter btn btn-success btn-xs'], '<i class="fa fa-search"></i>'); ?>
            <a href="<?php echo base_url('master/city'); ?>" class="btn btn-danger btn-xs"><i class="fa fa-rotate-right"></i></a>
        </td>
    </tr>
    <?php
    $i = $pg * ROWS_PER_PAGE + 1;
    foreach ($cities as $city) {
        echo '<tr>';
        echo '<td>' . form_checkbox(['name' => 'cities[]', 'class' => 'select'], $city['city_id']) . '</td>';
        echo '<td>' . $i++ . '</td>';
        echo '<td>' . $city['city_name'] . '</td>';
        echo '<td>' . $city['state_name'] . '</td>';
        echo '<td>' . $city['country_name'] . '</td>';
        echo '<td>' . date('d-m-Y h:i:s a', strtotime($city['added_date'])) . '</td>';
        echo '<td>' . date('d-m-Y h:i:s a', strtotime($city['modified_date'])) . '</td>';
        echo '<td>' . $city['is_active'] . '</td>';
        ?>
        <td>
            <a href="<?php echo base_url('master/get-city/' . $city['city_id']); ?>" class="edit-city" title="Edit"><i class="fa fa-edit"></i></a>
            <a href="<?php echo base_url('master/delete-city/' . $pg . '/' . $city['city_id']) ?>" class="delete-city" title="Delete"><i class="fa fa-trash"></i></a>
            <?php if ($city['is_active'] === 'Yes') {
                ?>
            <a href="<?php echo base_url('master/change-city-status/' . $pg . '/' . $city['city_id'] . '/No') ?>" title="Inactive" class="status-btn"><i class="fa fa-thumbs-down"></i></a>
                <?php
            } else {
                ?>
            <a href="<?php echo base_url('master/change-city-status/' . $pg . '/' . $city['city_id'] . '/Yes') ?>" title="Active" class="status-btn"><i class="fa fa-thumbs-up"></i></a>
            <?php }
            ?>

        </td>
        <?php
        echo '</tr>';
    }
    ?>
</table>
<?php echo form_close(); ?>
<nav aria-label="Page navigation" class="pull-right">
    <ul class="pagination">
        <li <?php
        if ($pg == 0) {
            echo 'class = "disabled"';
        }
        ?>>
            <a href="<?php echo base_url('master/get-cities/' . ($pg - 1)) ?>" aria-label="Previous" class="pg">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
        <?php
        if($pg ==0) {
            $start = 1;
            $end = min($pages,$pg+LINKS_PER_PAGE);
        }else if($pg === $pages-1) {
            $start = max(1,$pages-LINKS_PER_PAGE);
            $end = $pages;
        }else {
            $start = max(1,$pg-LINKS_PER_PAGE/2);
            $end = min($pages,$pg + LINKS_PER_PAGE/2);
        }
        for ($i = $start; $i <= $end; $i++) {
            ?>
            <li <?php echo (($i - 1) == $pg) ? 'class="active"' : '' ?>><a href="<?php echo base_url('master/get-cities/' . ($i - 1)); ?>" class="pg"><?php echo $i; ?></a></li>
            <?php
        }
        ?>
        <li <?php
        if ($pg == $pages - 1) {
            echo 'class = "disabled"';
        }
        ?>>
            <a href="<?php echo base_url('master/get-cities/' . ($pg + 1)) ?>" aria-label="Next" class="pg">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    </ul>
</nav>