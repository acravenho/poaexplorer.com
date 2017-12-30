
<div class="row">
	<div class="col-md-12">
		<h2>Recent Transactions</h2>
		<hr>
		<?php
			if(!empty($transactions)) {
				echo '<table class="table table-striped">';
					echo '<tr><th>Block</th><th>Time</th><th>TXID</th><th>To</th><th>From</th><th>Value</th></tr>';
					foreach($transactions as $t) {
						echo '<tr>';
							echo '<td><a href="/blocks/block/'.$t->blockNumber.'">'.$t->blockNumber.'</a></td>';
							echo '<td>'.(!empty($t->time) ? _ago($t->time). ' ago' : '-').'</td>';
							echo '<td><a href="/txid/search/'.$t->txid.'">'.substr($t->txid, 0, 15).'...</a></td>';
							echo '<td><a href="/address/search/'.$t->to.'">'.substr($t->to, 0, 15).'...</a></td>';
							echo '<td><a href="/address/search/'.$t->from.'">'.substr($t->from, 0, 15).'...</a></td>';
							echo '<td>'.$t->transactionValue.'</td>';
						echo '</tr>';
					}
				
				echo '</table>';
			} else {
				echo '<p class="lead">Sorry, no transactions could be found!</p>';
			}
		?>
		
		
	
	</div>
</div>
	
	
	
	

	
	