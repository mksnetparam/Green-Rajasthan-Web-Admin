<div class="row">
	<div class="col-md-8">
		<div class="panel panel-default">
			<div class="panel-heading">User Lands</div>
			<div class="panel-body">
				<table class="table table-striped">
					<tr>
						<th>S.No</th>
						<th>Land Image</th>
						<th>Land Area</th>
						<th>Plant Capacity</th>
						<th>Land Address</th>
					</tr>
					<?php $i = 1;
					foreach ($lands as $land) {
						?>
						<tr>
						<td><?php echo $i++; ?></td>
						<td>
						<?php if(!empty($land['land_image_url'])){ ?>
						<img src="<?php echo base_url('uploads/land/'.$land['land_image_url']) ?>" width="70px">
						<?php }else{
							?><img src="<?php echo base_url('uploads/no_image.jpg') ?>" width="70px"><?php 
							} ?>
						</td>
						<td><?php echo $land['land_area'].' '.$land['land_area_unit'] ?></td>
						<td><?php echo $land['plant_capacity_qty'] ?></td>
						<td><?php echo $land['land_address'].' '.$land['district'].' '.$land['tehsil']; ?></td>
						</tr>
						<?php
					} ?>
				</table>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading">User Detail</div>
			<div class="panel-body">
				<table class="table table-striped">
					<tr>
						<th>Name</th><td><?php echo $user['name'] ?></td>
					</tr>
					<tr>
						<th>Mobile</th><td><?php echo $user['mobile'] ?></td>
					</tr>
					<tr>
						<th>Email</th><td><?php echo $user['email'] ?></td>
					</tr>
					<tr>
						<th>Address</th><td><?php echo $user['address'] ?></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>