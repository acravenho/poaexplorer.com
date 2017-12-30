	<?php
			$ethprice = json_decode(file_get_contents('https://api.coinmarketcap.com/v1/ticker/ethereum/'));
			$price = $ethprice[0]->price_usd;
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
				<li class="list-group-item d-flex justify-content-between align-items-center">Total POA: <span class="badge badge-primary badge-pill"><?php echo number_format($totalBalance->totalBalance); ?></span></li>
				<li class="list-group-item d-flex justify-content-between align-items-center">Total Wallets: <span class="badge badge-primary badge-pill"><?php echo number_format($totalWallets); ?></span></li>
				<li class="list-group-item d-flex justify-content-between align-items-center">Market Cap: <span class="badge badge-primary badge-pill">$<?php echo number_format($totalBalance->totalBalance * .00023 * $price); ?></span></li>
			</ul>
			
			
		
		
		<p class="small">The POA Market Capitalization was calculated using the ICO purchase price of .00023 ETH per POA token. The current price of Ethereum is $<span class="eth_price"><?php echo $price; ?></span>.</p>

			<hr />
		</div>
		
		<div class="col-md-8">
			<figure class="highlight">
			<table class="table table-striped">
				<tr><th>Block Height:</th><td id="blockHeight"></td></tr>
				<tr><th>Transactions:</th><td id="transactionCount"></td></tr>
				<tr><th>Author:</th><td id="author"></td></tr>
				<tr><th>Size:</th><td id="size"></td></tr>
			</table>
			</figure>
			<hr />
		</div>
		
		
	</div>
	
	<div class="row" style="margin-top:55px;">
		<div class="col-md-12">
			
			<div class="bd-callout bd-callout-info">
				<h3 class="display-5">Transactions for Block #<span class="blocknum"></span></h3>
			</div>
			<table id="blockTransactions" class="table table-striped">
				<thead>
					<tr><th>TXID</th><th>To</th><th>From</th><th>Value</th></tr>
				</thead>
				<tbody>
					
				</tbody>
			</table>
		</div>
	</div>