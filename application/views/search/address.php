
<div class="row">
	<div class="col-md-8">
		<h2>Transaction History</h2>
		<p class="lead">Address: <?php echo $this->uri->segment(3); ?></p>
		
		<?php
			if(!empty($transactions)) {
				echo '<table class="table table-striped transaction_table">';
					echo '<thead>';
					echo '<tr><th>Block</th><th>TXID</th><th>To</th><th>From</th><th>Value</th></tr>';
					echo '</thead>';
					echo '<tbody>';
					foreach($transactions as $t) {
						echo '<tr>';
							echo '<td><a href="/blocks/block/'.$t->blockNumber.'">'.$t->blockNumber.'</a></td>';
							echo '<td><a href="/txid/search/'.$t->txid.'">'.substr($t->txid, 0, 15).'...</a></td>';
							echo '<td>'.($t->to_contract == 1 ? '<i class="fa fa-file-text-o" rel="tooltip" data-placement="bottom" title="" data-original-title="Contract"></i>' : '').' <a '.($t->to == $this->uri->segment(3) ? ' style="color:#5653a1;"' : '').' href="/address/search/'.$t->to.'">'.substr($t->to, 0, 15).'...</a></td>';
							echo '<td>'.($t->from_contract == 1 ? '<i class="fa fa-file-text-o" rel="tooltip" data-placement="bottom" title="" data-original-title="Contract"></i>' : '').' <a '.($t->from == $this->uri->segment(3) ? ' style="color:#5653a1;"' : '').' href="/address/search/'.$t->from.'">'.substr($t->from, 0, 15).'...</a></td>';
							echo '<td>'.$t->transactionValue.'</td>';
						echo '</tr>';
					}
					echo '</tbody>';
				echo '</table>';
			} else {
				echo '<p class="lead">Sorry, no transactions could be found!</p>';
			}
		?>
		
		<?php 
			if($contract == 1)
			{
				echo '<div class="contract_code">';
				echo '<p class="lead">Contract Creation Code</p>';
				echo '<hr />';
				echo '<figure class="highlight contractCode">';
				echo '<pre>';
					
				echo '</pre>';		
				echo '</figure>';
				echo '</div>';
			}	
		?>
	
	</div>
	<div class="col-md-4">
		<ul class="list-group">
			<li class="list-group-item d-flex justify-content-between align-items-center">POA Balance: <span class="badge badge-primary badge-pill" id="address_balance"></span></li>
			<li class="list-group-item d-flex justify-content-between align-items-center">Transaction Count: <span class="badge badge-primary badge-pill" id="address_count"><?php echo (!empty($transactions) ? count($transactions) : 0 ); ?></span></li>
			<li class="list-group-item d-flex justify-content-between align-items-center">POA Value: <span class="badge badge-primary badge-pill" id="address_value">Coming soon.</span></li>
			
		</ul>
		<?php
			$ethprice = json_decode(file_get_contents('https://api.coinmarketcap.com/v1/ticker/ethereum/'));
			$price = $ethprice[0]->price_usd;
		?>
		
		
		<p class="small">The POA Value was calculated using the ICO purchase price of .00023 ETH per POA token. The current price of Ethereum is $<span class="eth_price"><?php echo $price; ?></span>.</p>
	</div>
	
	
	
	

	
	