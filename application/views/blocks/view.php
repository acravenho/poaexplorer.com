<div class="row">
	<div class="col-md-12">
		<h2>POA Network Blocks</h2>
		<p class="lead">Information on the last 100 blocks with completed transactions.</p>
		<table id="blockTable" class="table table-striped">
			<thead>
				<tr>
					<td>Block Number</td>
					<td>Transactions</td>
					
					
				</tr>
			</thead>
			<tbody>
				
				<?php 
					if(!empty($blocks)) {
						foreach($blocks as $block) {
							echo '<tr><td><a href="/blocks/block/'.$block->blockNumber.'">'.$block->blockNumber.'</a></td><td>'.$block->trans.'</td></tr>';
						}
					}
				?>
			</tbody>
		</table>
	</div>
	
</div>