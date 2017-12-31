
<div class="row">
	<div class="col-md-12">
		<h2>Transaction History</h2>
		<p class="lead">Address: <?php echo $this->uri->segment(3); ?></p>
		<hr />
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<ul class="list-group">
			<li class="list-group-item d-flex justify-content-between align-items-center">POA Balance: <span class="badge badge-primary badge-pill" id="address_balance"></span></li>
			<li class="list-group-item d-flex justify-content-between align-items-center">Transaction Count: <span class="badge badge-primary badge-pill" id="address_count"><?php echo (!empty($transactions) ? count($transactions) : 0 ); ?></span></li>
			<li class="list-group-item d-flex justify-content-between align-items-center">POA Value: <span class="badge badge-primary badge-pill" id="address_value">Coming soon.</span></li>
			
		</ul>
	</div>
	<div class="col-md-6">
		<?php
			$ethprice = json_decode(file_get_contents('https://api.coinmarketcap.com/v1/ticker/ethereum/'));
			$price = $ethprice[0]->price_usd;
		?>
		
		<figure class="figure">
			<pre>
				<p class="small">The POA Value was calculated using the ICO purchase price of .00023 ETH per POA token. The current price of Ethereum is $<span class="eth_price"><?php echo $price; ?></span>.</p>
			</pre>
		</figure>
	</div>
	
</div>

<div class="row">

	
	
	<div class="col-md-12">
		<hr />
		<ul class="nav nav-tabs" id="myTabs" role="tablist"> 
			<li role="presentation" class="active"><a href="#transactions" id="transactions-tab" role="tab" data-toggle="tab" aria-controls="transactions" aria-expanded="true">Transactions</a></li> 
			<?php 
				if($contract == 1)
				{
					echo '<li role="presentation" class=""><a href="#contractcode" role="tab" id="contractcode-tab" data-toggle="tab" aria-controls="contractcode" aria-expanded="false">Contract Code</a></li>';
				}
			?>
		</ul>
		
		<div class="tab-content" id="myTabContent"> 
			
		<?php
			echo '<div class="tab-pane active in" role="tabpanel" id="transactions" aria-labelledby="transactions-tab"> ';
			if(!empty($transactions)) {
				echo '<br />';
				echo '<p><i class="fa fa-sort-amount-desc"></i> Showing a total of '.count($transactions).' transactions.</p>';
				echo '<table class="table table-striped transaction_table">';
					echo '<thead>';
					echo '<tr><th>Block</th><th>TX Hash</th><th>Age</th><th>From</th><th></th><th>To</th><th>Value</th></tr>';
					echo '</thead>';
					echo '<tbody>';
					foreach($transactions as $t) {
						echo '<tr>';
							echo '<td><a href="/blocks/block/'.$t->blockNumber.'">'.$t->blockNumber.'</a></td>';
							echo '<td><a href="/txid/search/'.$t->txid.'">'.substr($t->txid, 0, 21).'...</a></td>';
							echo '<td>'.($t->time > 0 ? _ago($t->time) : '-').'</td>';
							echo '<td>'.($t->from_contract == 1 ? '<i class="fa fa-file-text-o" rel="tooltip" data-placement="bottom" title="" data-original-title="Contract"></i>' : '').' '.($t->from == $this->uri->segment(3) ? substr($t->from, 0, 21).'...' : '<a href="/address/search/'.$t->from.'">'.substr($t->from, 0, 21).'....</a>').'</td>';
							
							echo '<td>'.($t->from == $this->uri->segment(3) ? '<span class="label label-warning">&nbsp;OUT&nbsp;</span>' : '<span class="label label-success">&nbsp;IN&nbsp;</span>').'</td>';
							
							echo '<td>'.($t->to_contract == 1 ? '<i class="fa fa-file-text-o" rel="tooltip" data-placement="bottom" title="" data-original-title="Contract"></i>' : '').' '.($t->to == $this->uri->segment(3) ? substr($t->to, 0, 21).'...' : '<a href="/address/search/'.$t->to.'">'.substr($t->to, 0, 21).'....</a>').'</td>';
							echo '<td>'.$t->transactionValue.'</td>';
						echo '</tr>';
					}
					echo '</tbody>';
				echo '</table>';
			} else {
				echo '<p class="lead">Sorry, no transactions could be found!</p>';
			}
			echo '</div>';
		?>
		
		<?php 
			if($contract == 1)
			{
				echo '<div class="tab-pane" role="tabpanel" id="contractcode" aria-labelledby="contractcode-tab"> ';
				echo '<div class="contract_code">';
				echo '<p class="lead">Contract Creation Code</p>';
				echo '<hr />';
				echo '<figure class="highlight contractCode">';
				echo '<pre>';
					
				echo '</pre>';		
				echo '</figure>';
				echo '</div>';
				echo '</div>';
			}	
		?>
	</div>
	
	</div>
</div>
	
	
	

	
	