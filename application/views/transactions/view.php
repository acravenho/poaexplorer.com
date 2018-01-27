
<div class="row">
	<div class="col-md-12">
		<h2>Recent Transactions</h2>
		<hr>
		<?php
			echo $links;
			if(!empty($transactions)) {
				echo '<table class="table table-striped transaction_table">';
					echo '<tr><th>Block</th><th>Time</th><th>TXID</th><th>From</th><th></th><th>To</th><th>Value</th></tr>';
					foreach($transactions as $t) {
						echo '<tr>';
							echo '<td><a href="'.base_url().'blocks/block/'.$t->blockNumber.'">'.$t->blockNumber.'</a></td>';
							echo '<td>'.(!empty($t->time) ? _ago($t->time). ' ago' : '-').'</td>';
							echo '<td><a href="'.base_url().'txid/search/'.$t->txid.'">'.substr($t->txid, 0, 21).'...</a></td>';
							echo '<td>'.($t->from_contract == 1 ? '<i class="fa fa-file-text-o" rel="tooltip" data-placement="bottom" title="" data-original-title="Contract"></i>' : '').'<a href="'.base_url().'address/search/'.$t->from.'">'.substr($t->from, 0, 21).'...</a></td>';
							
							
							echo '<td><i class="fa fa-arrow-right"></i></td>';
							
							if(empty($t->to))
							{
								echo '<td><i class="fa fa-file-text" rel="tooltip" data-placement="bottom" title="" data-original-title="Contract Creation"></i> <a href="'.base_url().'txid/search/'.$t->txid.'">Contract Creation</a></td>';
							}
							else
							{
								echo '<td>'.($t->to_contract == 1 ? '<i class="fa fa-file-text-o" rel="tooltip" data-placement="bottom" title="" data-original-title="Contract"></i>' : '').' <a href="'.base_url().'address/search/'.$t->to.'">'.substr($t->to, 0, 21).'...</a></td>';
							}
							
							

							echo '<td style="text-align:right;">'.convertFloat($t->transactionValue).' POA</td>';
						echo '</tr>';
					}
				
				echo '</table>';
				echo $links;
			} else {
				echo '<p class="lead">Sorry, no transactions could be found!</p>';
			}
		?>
		
		
	
	</div>
</div>
	
	
	
	

	
	