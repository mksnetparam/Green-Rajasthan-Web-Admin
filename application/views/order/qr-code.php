<div class="container">
    <br>
    <div class="row">
        <div class="col-md-12 text-right">
            <a href="javascript:void(0)" class="btn btn-info hidden-print"  onclick="window.print()">Print QRCode</a>
        </div>
    </div>
    <br>
    <table class="table">
        <tr>
            <?php
            $i = 0;
            foreach ($result as $row) {
                ?>
                <th>
            <div class="thumbnail">
                <img src="<?php echo base_url('Qrcode/' . $row['qr_code'] . '.png'); ?>">
                <div class="caption">
                    <h3 class="text-center"><?php echo $row['plant_name'].'#'.$row['id']; ?></h3>
                    <p class="text-center h4"><?php echo ucfirst($row['nominee_name']) ?></p>
                </div>
            </div>
            </th>    
            <?php
            $i++;
            if ($i % 3 == 0) {
                echo '</tr><tr>';
            }
        }
        ?>
        </tr>
    </table>
</div>
