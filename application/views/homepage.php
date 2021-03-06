	<?php
			$poaprice = json_decode(file_get_contents('https://api.coinmarketcap.com/v1/ticker/poa-network/'));
			$data = json_decode(file_get_contents('https://tuo2uaw74i.execute-api.us-east-1.amazonaws.com/POA/blockInfo'));
	        $aveBlockTime    = $data->Item->stat->M->avgBlockTime->N;
	        $lastBlockTime   = $data->Item->stat->M->lastBlockTimestamp->N;
	        $lastBlockNumber = $data->Item->stat->M->lastBlockNumber->N;
			$price = $poaprice[0]->price_usd;
		?>
	
	<div class="row">
		<div class="col-md-12">
			<h3>POA Network Stats</h3>
			<hr>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<ul class="list-group">
				<li class="list-group-item d-flex justify-content-between align-items-center">Circulating POA: <span class="badge badge-primary badge-pill"><?php echo number_format($totalBalance); ?></span></li>
				<li class="list-group-item d-flex justify-content-between align-items-center">Total Wallets: <span class="badge badge-primary badge-pill"><?php echo number_format($totalWallets); ?></span></li>
				<li class="list-group-item d-flex justify-content-between align-items-center">Smart Contracts Deployed: <span class="badge badge-primary badge-pill"><?php echo number_format($totalContracts); ?></span></li>
				<li class="list-group-item d-flex justify-content-between align-items-center">Market Cap: <span class="badge badge-primary badge-pill">$<?php echo number_format($totalBalance * $price); ?></span></li>
			</ul>
			
			
		
		
		<p class="small">The POA Market Capitalization was calculated using the CoinMarketCap.com API of $<?php echo $price; ?> per POA token!</p>

			<hr />
		</div>
		
		<div class="col-md-8 network-health">
			<figure class="highlight">
				<div class="row">
					<div class="col-sm-3">
						<div class="pull-left">
							<i class="fa fa-cube"></i>
						</div>
						<div class="pull-right">
							<span class="network-label">BLOCK</span><br />
							<span class="network-value"><span class="blocknum"><?php echo $lastBlockNumber; ?></span></span>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="pull-left">
							<i class="fa fa-hourglass-1"></i>
						</div>
						<div class="pull-right">
							<span class="network-label">LAST BLOCK</span><br />
							<span class="network-value"><span class="hourglass"></span></span>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="pull-left">
							<i class="fa fa-clock-o"></i>
						</div>
						<div class="pull-right">
							<span class="network-label">BLOCK TIME</span><br />
							<span class="network-value"><?php echo number_format($aveBlockTime,4); ?>S</span>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="pull-left">
							<i class="fa fa-connectdevelop"></i>
						</div>
						<div class="pull-right">
							<span class="network-label">USD/POA</span> <br />
							<span class="network-value">$<?php echo number_format($price,4); ?></span>
						</div>
					</div>
				</div>
			</figure>
		</div>
		
		
	</div>
	
	<div class="row" style="margin-top:55px;">
		<div class="col-md-6">
			
			<div class="bd-callout bd-callout-info">
				<h3 class="display-5">Recent Blocks</h3>
			</div>
			<table id="blockBlocks" class="table table-striped transaction_table">
				<thead>
					<tr><th>Block</th><th>TXns</th><th>Validator</th></tr>
				</thead>
				<tbody>
					<?php
						if(!empty($blocks))
						{
							foreach($blocks as $b)
							{
								echo '<tr>';
									echo '<td class="td-block"><a href="'.base_url().'blocks/block/'.$b->blocknum.'">'.$b->blocknum.'</a></td>';
									echo '<td>'.$b->transactions.'</td>';
									echo '<td><a href="'.base_url().'validators/address/'.$b->validator.'">'.$b->name.'</a></td>';
								echo '</tr>';
							}	
						}
					?>
				</tbody>
			</table>
		</div>
		
		<div class="col-md-6">
			
			<div class="bd-callout bd-callout-info">
				<h3 class="display-5">Recent Transactions</h3>
			</div>
			<table id="blockTransactions" class="table table-striped transaction_table">
				<thead>
					<tr><th>TXID</th><th>From</th><th></th><th>To</th><th>Value</th></tr>
				</thead>
				<tbody>
					<?php
						if(!empty($transactions))
						{
							foreach($transactions as $tx)
							{
								echo '<tr>';
									echo '<td><a href="'.base_url().'tx/'.$tx->txid.'">'.substr($tx->txid, 0, 12).'...</a></td>';
									
									echo '<td>'.($tx->from_contract == 1 ? '<i class="fa fa-file-text-o" rel="tooltip" data-placement="bottom" title="" data-original-title="Contract"></i>' : '').' <a href="'.base_url().'address/search/'.$tx->from.'">'.substr($tx->from, 0, 12).'....</a></td>';
									
									echo '<td><i class="fa fa-arrow-right"></i></td>';
									
									if(empty($tx->to))
									{
										echo '<td><i class="fa fa-file-text" rel="tooltip" data-placement="bottom" title="" data-original-title="Contract Creation"></i> <a href="'.base_url().'tx/'.$tx->txid.'">Contract Creation</a></td>';
									}
									else
									{
										echo '<td>'.($tx->to_contract == 1 ? '<i class="fa fa-file-text-o" rel="tooltip" data-placement="bottom" title="" data-original-title="Contract"></i>' : '').' <a href="'.base_url().'address/search/'.$tx->to.'">'.substr($tx->to, 0, 12).'....</a></td>';
									}
									
									
									
									echo '<td style="text-align:right;">'.($tx->transactionValue > 1 ? $tx->transactionValue : fixBigDecimal($tx->transactionValue)).'</td>';
								echo '</tr>';
							}	
						}
					?>
				</tbody>
			</table>

		</div>
	</div>