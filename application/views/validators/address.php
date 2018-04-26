
<div class="row">
	<div class="col-md-12">
		<h2>Validator Information</h2>
		<p class="lead">Validator Address: <?php echo $this->uri->segment(3); ?></p>
		<hr />
	</div>
</div>
<div class="row">
	<div class="col-md-4">
		<img src="<?php echo (!empty($validator->photo) ? $validator->photo : ''); ?>" style="margin-bottom: 30px; max-width:320px;" />
	
	
		<ul class="list-group">
			<li class="list-group-item d-flex justify-content-between align-items-center">Full Name: <span class="badge badge-primary badge-pill"><?php echo (!empty($validator->name) ? $validator->name : '-'); ?></span></li>
			<li class="list-group-item d-flex justify-content-between align-items-center">Street Address: <span class="badge badge-primary badge-pill"><?php echo (!empty($validator->street) ? $validator->street : '-' ); ?></span></li>
			<li class="list-group-item d-flex justify-content-between align-items-center">City: <span class="badge badge-primary badge-pill"><?php echo (!empty($validator->city) ? $validator->city : '-' ); ?></span></li>
			<li class="list-group-item d-flex justify-content-between align-items-center">State: <span class="badge badge-primary badge-pill"><?php echo (!empty($validator->state) ? $validator->state : '-' ); ?></span></li>
			
			
		
		<li class="list-group-item d-flex justify-content-between align-items-center">Zip Code: <span class="badge badge-primary badge-pill"><?php echo (!empty($validator->zip) ? $validator->zip : '-' ); ?></span></li>
			<li class="list-group-item d-flex justify-content-between align-items-center">License: <span class="badge badge-primary badge-pill"><?php echo (!empty($validator->license) ? $validator->license : '-' ); ?></span></li>
			<li class="list-group-item d-flex justify-content-between align-items-center">Expiration: <span class="badge badge-primary badge-pill"><?php echo (!empty($validator->street) ? $validator->expiration : '-' ); ?></span></li>
			<li class="list-group-item d-flex justify-content-between align-items-center">Validator Creation: <span class="badge badge-primary badge-pill"><?php echo (!empty($validator->creation) ? $validator->creation : '-'); ?></span></li>
		</ul>
	</div>

	<div class="col-md-8">
		<h3>Blocks Validated</h3>
		<hr />
		<?php
			if(!empty($blocks))
			{
				
				echo '<table class="table table-striped transaction_table">';
				echo '<tr><th>Block</th><th>Time</th><th>Transactions</th><th>Reward</th></tr>';
				foreach($blocks as $block) 
				{
					echo '<tr>';
						echo '<td><a href="'.base_url().'blocks/block/'.$block->blocknum.'">'.$block->blocknum.'</a></td>';
						echo '<td>'._ago($block->timestamp).'</td>';
						echo '<td>'.$block->transactions.'</td>';
						echo '<td>Coming soon.</td>';
					echo '</tr>';
				}
				echo '</table>';
			}
			else
			{
				echo 'Sorry no blocks have been validated by this validator!';
			}
			
			if(!empty($links))
			{
				echo $links;
			}
			
		?>	
		
				
	</div>
	
</div>

