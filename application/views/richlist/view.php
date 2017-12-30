	<?php
			$ethprice = json_decode(file_get_contents('https://api.coinmarketcap.com/v1/ticker/ethereum/'));
			$price = $ethprice[0]->price_usd;
		?>
	
	<div class="row">
		<div class="col-md-12">
			<h3>POA Rich List</h3>
			<p class="lead">The richest wallets on the POA Network.</p>
			<hr>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<table class="table table-striped">
				<tr><th>Rank</th><th>Wallet</th><th>POA Balance</th><th>Value</th></tr>
				<?php
					if(!empty($rich)) {
						$i=1;
						foreach($rich as $r) {
							echo '<tr>';
								echo '<td>'.$i.'</td>';
								echo '<td><a href="/address/search/'.$r->wallet.'">'.$r->wallet.'</a></td>';
								echo '<td>'.number_format($r->balance).'</td>';
								echo '<td>$'.number_format($r->balance * .00023 * $price).'</td>';
							echo '</tr>';
							$i++;
						}
					}
				?>
			</table>
		</div>
	</div>