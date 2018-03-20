<!-- Sidebar -->
<div id="sidebar-wrapper">
    <ul class="sidebar-nav">
        <li><a href="<?php echo base_url('/dashboard') ?>" class="<?php
            if (isset($active) && $active === 'dashboard') {
                echo 'active';
            } else {
                echo '';
            }
            ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="<?php echo base_url('/employee') ?>" class="<?php
            if (isset($active) && $active === 'employee') {
                echo 'active';
            } else {
                echo '';
            }
            ?>"><i class="fa fa-user-secret"></i> Admin Users</a></li>
        <li><a href="<?php echo base_url('/user') ?>" class="<?php
            if (isset($active) && $active === 'user') {
                echo 'active';
            } else {
                echo '';
            }
            ?>"><i class="fa fa-users"></i> Users</a></li>
        <li><a href="javascript:;" data-toggle="collapse" data-target="#demo">
                <i class="fa fa-cogs"></i> Master Settings<i class="fa fa-caret-down"></i></a>
            <ul id="demo" class="collapse<?php
            if (isset($menu_id) && $menu_id === 'master') {
                echo 'in';
            } else {
                echo '';
            }
            ?>">
                <li class="<?php
                if (isset($active) && $active === 'country') {
                    echo 'menu-active';
                } else {
                    echo '';
                }
                ?>"><a href="<?php echo base_url('master/country'); ?>"><i class="fa fa-map-o"></i> Country</a></li>
                <li class="<?php
                if (isset($active) && $active === 'state') {
                    echo 'menu-active';
                } else {
                    echo '';
                }
                ?>"><a href="<?php echo base_url('master/state'); ?>"><i class="fa fa-map-signs"></i> State</a></li>
                <li class="<?php
                if (isset($active) && $active === 'city') {
                    echo 'menu-active';
                } else {
                    echo '';
                }
                ?>"><a href="<?php echo base_url('master/city'); ?>"><i class="fa fa-map-marker"></i> City</a></li>
                <li class="<?php
                if (isset($active) && $active === 'organization') {
                    echo 'active';
                } else {
                    echo '';
                }
                ?>"><a href="<?php echo base_url('master/organization'); ?>"><i class="fa fa-building"></i> Organization</a>
                </li>
            </ul>
        </li>
        <li><a href="<?php echo base_url('land') ?>" class="<?php
            if (isset($active) && $active === 'land') {
                echo 'active';
            } else {
                echo '';
            }
            ?>"><i class="fa fa-list"></i> Lands</a>
        </li>

        <li><a href="javascript:void(0);" data-toggle="collapse" data-target="#order">
                <i class="fa fa-dot-circle-o"></i> Order Management<i class="fa fa-caret-down"></i></a>
            <ul id="order" class="collapse <?php
            if (isset($menu_id) && $menu_id === 'order') {
                echo 'in';
            } else {
                echo '';
            }
            ?>">
                <li class="<?php
                if (isset($active) && $active === 'view-order') {
                    echo 'menu-active';
                } else {
                    echo '';
                }
                ?>"><a href="<?php echo base_url('order') ?>"><i class="fa fa-list"></i> View Order</a></li>
                <li class="<?php
                if (isset($active) && $active === 'view-land-order') {
                    echo 'menu-active';
                } else {
                    echo '';
                }
                ?>"><a href="<?php echo base_url('order/order-by-land'); ?>"><i class="fa fa-list-ul"></i> View Order by
                        Land</a></li>
            </ul>
        </li>
        <li><a href="javascript:void(0);" data-toggle="collapse" data-target="#plant">
                <i class="fa fa-dot-circle-o"></i> Plant Management<i class="fa fa-caret-down"></i></a>
            <ul id="plant" class="collapse <?php
            if (isset($menu_id) && $menu_id === 'plant') {
                echo 'in';
            } else {
                echo '';
            }
            ?>">
                <li class="<?php
                if (isset($active) && $active === 'add-plant') {
                    echo 'menu-active';
                } else {
                    echo '';
                }
                ?>"><a href="<?php echo base_url('plant/add-plant'); ?>"><i class="fa fa-plus-square"></i> Add New Plant</a>
                </li>
                <li class="<?php
                if (isset($active) && $active === 'plant') {
                    echo 'menu-active';
                } else {
                    echo '';
                }
                ?>"><a href="<?php echo base_url('plant') ?>"><i class="fa fa-list"></i> View Plant</a></li>
            </ul>
        </li>
        <li>
            <a href="<?php echo base_url('order/payments') ?>" class="<?php
            if (isset($active) && $active === 'payments') {
                echo 'active';
            } else {
                echo '';
            }
            ?>"><i class="fa fa-list"></i> Payments</a>
        </li>
        <li>
            <a href="<?php echo base_url('user/donation-payments') ?>" class="<?php
            if (isset($active) && $active === 'donation-payments') {
                echo 'active';
            } else {
                echo '';
            }
            ?>"><i class="fa fa-list"></i> Donation Payments</a>
        </li>
        <li>
            <a href="<?php echo base_url('user/tree-friend') ?>" class="<?php
            if (isset($active) && $active === 'tree-friend') {
                echo 'active';
            } else {
                echo '';
            }
            ?>"><i class="fa fa-leaf"></i> Plant Friend</a>
        </li>
    </ul>
</div>
<!-- /#sidebar-wrapper -->