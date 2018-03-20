<?php
$pages = ceil($total_rows / ROWS_PER_PAGE);
?>
<div class="row">
    <div class="col-md-6">
        <label class="control-label">Action</label>
        <?php echo form_dropdown(['name' => 'action', 'id' => 'action', 'disabled' => 'disabled', 'data-url' => base_url('master/change-all-country-status/' . $pg), 'class' => 'textbox'], ['' => 'None', 'Yes' => 'Yes', 'No' => 'No']); ?>
    </div>
    <div class="col-md-6 text-right">
        Showing <?php echo form_dropdown(['name' => 'page', 'id' => 'page', 'data-url' => base_url('master/get-countries'), 'class' => 'textbox'], range(1, $pages), $pg); ?> page of <?php echo $pages ?> pages.
    </div>
</div>
<br>
<?php echo form_open('master/filter-country', ['id' => 'filter-country-form']); ?>
<table class="table table-bordered table-condensed table-hover table-responsive table-striped">
    <tr>
        <th><?php echo form_checkbox(['name' => 'select-all', 'id' => 'select-all']) ?></th>
        <th>S.No.</th>
        <th>Country Name</th>
        <th>Added Date</th>
        <th>Modified Date</th>
        <th>Is Active</th>
        <th style="width: 60px;">Action</th>

    </tr>
    <tr>
        <td><i class="fa fa-search"></i></td>
        <td>#</td>
        <td>
            <div class="form--group form-group-sm">
                <?php echo form_input(['name' => 'country', 'id' => 'country', 'class' => 'form-control', 'placeholder' => 'Country Name'], set_value('country', $this->input->post('country') ? $this->input->post('country') : ''));
                ?>
            </div>
        </td>
        <td></td>
        <td></td>
        <td><div class="form-group form-group-sm"><?php echo form_dropdown(['name' => 'status', 'id' => 'status', 'class' => 'form-control'], ['' => '---Status---', 'Yes' => 'Yes', 'No' => 'No'], set_value('status', $this->input->post('status') ? $this->input->post('status') : ''))
                ?></div></td>
        <td>
            <?php echo form_button(['type' => 'submit', 'name' => 'sub', 'id' => 'filter-country-sub-btn', 'class' => 'filter btn btn-success btn-xs','title' => 'Search'], '<i class="fa fa-search"></i>'); ?>
            <a href="<?php echo base_url('master/country'); ?>" class="btn btn-danger btn-xs" title="Refresh/Reload"><i class="fa fa-rotate-right"></i></a>
        </td>
    </tr>
    <div id="content-list-wrapper">

    </div>
    <?php
    $i = $pg * ROWS_PER_PAGE + 1;
    foreach ($countries as $country) {
        echo '<tr>';
        echo '<td>' . form_checkbox(['name' => 'countries[]', 'class' => 'select'], $country['country_id']) . '</td>';
        echo '<td>' . $i++ . '</td>';
        echo '<td>' . $country['country_name'] . '</td>';
        echo '<td>' . date('d-m-Y h:i:s a', strtotime($country['added_date'])) . '</td>';
        echo '<td>' . date('d-m-Y h:i:s a', strtotime($country['modified_date'])) . '</td>';
        echo '<td>' . $country['is_active'] . '</td>';
        ?>
        <td>
            <a href="<?php echo base_url('master/get-country/' . $country['country_id']); ?>" class="edit-country" title="Edit"><i class="fa fa-edit"></i></a>
            <a href="<?php echo base_url('master/delete-country/' . $pg . '/' . $country['country_id']) ?>" class="delete-country" title="Delete"><i class="fa fa-trash"></i></a>
            <?php if ($country['is_active'] === 'Yes') {
                ?>
            <a href="<?php echo base_url('master/change-country-status/' . $pg . '/' . $country['country_id'] . '/No') ?>" class="status-btn" title="Inactive"><i class="fa fa-thumbs-down"></i></a>
                <?php
            } else {
                ?>
            <a href="<?php echo base_url('master/change-country-status/' . $pg . '/' . $country['country_id'] . '/Yes') ?>" class="status-btn" title="Active"><i class="fa fa-thumbs-up"></i></a>
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
            <a href="<?php echo base_url('master/get-countries/' . ($pg - 1)) ?>" aria-label="Previous" class="pg">
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
            <li <?php echo (($i - 1) == $pg) ? 'class="active"' : '' ?>><a href="<?php echo base_url('master/get-countries/' . ($i - 1)); ?>" class="pg"><?php echo $i; ?></a></li>
            <?php
        }
        ?>
        <li <?php
        if ($pg == $pages - 1) {
            echo 'class = "disabled"';
        }
        ?>>
            <a href="<?php echo base_url('master/get-countries/' . ($pg + 1)) ?>" aria-label="Next" class="pg">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    </ul>
</nav>