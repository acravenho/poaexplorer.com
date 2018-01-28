	<?php
			$ethprice = json_decode(file_get_contents('https://api.coinmarketcap.com/v1/ticker/ethereum/'));
			$price = $ethprice[0]->price_usd;
		?>
	
	<div class="row">
		<div class="col-md-12">
			<h3>POA Network Accounts</h3>
			<p class="lead">List of accounts on the POA Network.</p>
			<hr>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<?php echo $links; ?>
			<table class="table table-striped">
				<tr><th>Rank</th><th>Wallet</th><th>POA Balance</th><th>Percentage</th><th>USD Value</th></tr>
				<?php
					if(!empty($rich)) {
						$uri = $this->uri->segment(3);
						$i= ( empty($uri) ? 1 : $uri+1);
						foreach($rich as $r) {
							$percentage = $r->balance/$total_balance * 100;
							echo '<tr>';
								echo '<td>'.$i.'</td>';
								echo '<td>'.($r->contract == 1 ? '<i class="fa fa-file-text-o"></i>' : '').' <a href="'.base_url().'/address/search/'.$r->wallet.'">'.$r->wallet.'</a></td>';
								echo '<td>'.number_format($r->balance).'</td>';
								echo '<td>'.number_format($percentage,2).'%</td>';
								echo '<td>$'.number_format($r->balance * .00023 * $price).'</td>';
							echo '</tr>';
							$i++;
						}
					}
				?>
			</table>
			<?php echo $links; ?>
		</div>
	</div>