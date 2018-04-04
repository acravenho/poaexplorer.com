<?php
			$poaprice = json_decode(file_get_contents('https://api.coinmarketcap.com/v1/ticker/poa-network/'));
			$price = $poaprice[0]->price_usd;
?>
<div class="row">
	<div class="col-md-12">
		<h2>Transaction History</h2>
		<p class="lead"><?php echo ($contract == 1 ? '<i class="fa fa-file-text-o"></i> Contract ' : ''); ?> Address: <?php echo $this->uri->segment(3); ?> <?php echo (!empty($wallet->name) ? '('.str_replace(':', '', $wallet->name).')' : ''); ?></p>
		<hr />
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<ul class="list-group">
			<li class="list-group-item d-flex justify-content-between align-items-center">POA Balance: <span class="badge badge-primary badge-pill" id="address_balance"></span></li>
			<li class="list-group-item d-flex justify-content-between align-items-center">Transaction Count: <span class="badge badge-primary badge-pill" id="address_count"><?php echo (!empty($transactions) ? $total_transactions : 0 ); ?></span></li>
			<li class="list-group-item d-flex justify-content-between align-items-center">POA Value: <span class="badge badge-primary badge-pill" id="address_value">Coming soon.</span></li>
			
		</ul>
	</div>
	<div class="col-md-6">
		
		
		<?php
			if($contract == 1)
			{
		?>
		
		<ul class="list-group">
			<li class="list-group-item d-flex justify-content-between align-items-center">Contract Creator:
				<?php
					if(!empty($contract_creation))
					{
						echo '<a href="'.base_url().'address/search/'.$contract_creation->from.'">'.substr($contract_creation->from, 0, 16).'...</a> at txn <a href="'.base_url().'tx/'.$contract_creation->txid.'">'.substr($contract_creation->txid, 0, 16).'...</a>';
					} 
					else
					{
						echo 'Unknown';
					}
				?>
			</li>
		</ul>
		
		<?php } ?>
		
				<p class="small">The POA Value was calculated using the CoinMarketCap.com API price of <span class="poa_price"><?php echo $price; ?></span> per POA token.</p>
			
	</div>
	
</div>

<div class="row">

	
	
	<div class="col-md-12">
		<hr />
		<ul class="nav nav-tabs" id="myTabs" role="tablist"> 
			<li role="presentation" class="active"><a href="#transactions" id="transactions-tab" role="tab" data-toggle="tab" aria-controls="transactions" aria-expanded="true">Transactions</a></li> 
			<?php
				if(!empty($internal_transactions))
				{
					echo '<li role="presentation" class=""><a href="#internaltransactions" role="tab" id="internaltransactions-tab" data-toggle="tab" aria-controls="internaltransactions" aria-expanded="false">Internal Transactions</a></li>';

				}	
				
			?>
			<?php 
				if($contract == 1)
				{
					echo '<li role="presentation" class=""><a href="#contractcode" role="tab" id="contractcode-tab" data-toggle="tab" aria-controls="contractcode" aria-expanded="false">'.(!empty($verified_contract) ? 'Contract Source <sup><span style="color:green;">Yes</span></sup>' : 'Contract Code').'</a></li>';
					
				}
			?>
		</ul>
		
		<div class="tab-content" id="myTabContent"> 
			
		<?php
			echo '<div class="tab-pane active in" role="tabpanel" id="transactions" aria-labelledby="transactions-tab"> ';
			if(!empty($transactions)) {
				echo '<br />';
				echo'<div class="row">';
				echo '<div class="col-md-6">';
				echo '<p><i class="fa fa-sort-amount-desc"></i> Showing '.count($transactions).' out of a total '.$total_transactions.' transactions.</p>';
				echo '</div>';
				echo '<div class="col-md-6">';
				echo $links;
				echo '</div>';
				echo '</div>';
				echo '<table class="table table-striped transaction_table">';
					echo '<thead>';
					echo '<tr><th>Block</th><th>TX Hash</th><th>Age</th><th>From</th><th></th><th>To</th><th>Value</th></tr>';
					echo '</thead>';
					echo '<tbody>';
					foreach($transactions as $t) {
						echo '<tr>';
							echo '<td><a href="'.base_url().'blocks/block/'.$t->blockNumber.'">'.$t->blockNumber.'</a></td>';
							echo '<td><a href="'.base_url().'tx/'.$t->txid.'">'.substr($t->txid, 0, 21).'...</a></td>';
							echo '<td>'.($t->time > 0 ? _ago($t->time) : '-').'</td>';
							
							echo '<td>';
							if($t->from_contract == 1) {
								echo '<i class="fa fa-file-text-o" rel="tooltip" data-placement="bottom" title="" data-original-title="Contract"></i> ';
							}
							
							if(strtolower($t->from) == strtolower($this->uri->segment(3))) {
								if(!empty($t->from_name)) {
									echo str_replace(':', '', $t->from_name);
								} else {
									echo substr($t->from, 0, 21).'...';
								}
							} else {
								if(!empty($t->from_name)) {
									echo '<a href="'.base_url().'address/search/'.$t->from.'">'.str_replace(':', '', $t->from_name).'</a>';
								} else {
									echo '<a href="'.base_url().'address/search/'.$t->from.'">'.substr($t->from, 0, 21).'....</a>';
								}
							}
							echo '</td>';
						
							
							echo '<td>'.($t->from == $this->uri->segment(3) ? '<span class="label label-warning">&nbsp;OUT&nbsp;</span>' : '<span class="label label-success">&nbsp;IN&nbsp;</span>').'</td>';
							
							if(empty($t->to))
							{
								echo '<td><i class="fa fa-file-text"></i> <a href="'.base_url().'address/search/'.$t->creates.'">Contract Creation</a></td>';
							}
							else
							{
								echo '<td>';
								if($t->to_contract == 1) {
									echo '<i class="fa fa-file-text-o" rel="tooltip" data-placement="bottom" title="" data-original-title="Contract"></i> ';
								}
								
								if(strtolower($t->to) == strtolower($this->uri->segment(3))) {
									if(!empty($t->to_name)) {
										echo str_replace(':', '', $t->to_name);
									} else {
										echo substr($t->to, 0, 21).'...';
									}
								} else {
									if(!empty($t->to_name)) {
										echo '<a href="'.base_url().'address/search/'.$t->to.'">'.str_replace(':', '', $t->to_name).'</a>';
									} else {
										echo '<a href="'.base_url().'address/search/'.$t->to.'">'.substr($t->to, 0, 21).'....</a>';
									}
								}
								echo '</td>';
							}
							
							
							echo '<td style="text-align:right;">'.($t->transactionValue > 1 ? $t->transactionValue : fixBigDecimal($t->transactionValue)).' POA</td>';
						echo '</tr>';
					}
					echo '</tbody>';
				echo '</table>';
				echo $links;
			} else {
				echo '<p class="lead">Sorry, no transactions could be found!</p>';
			}
			echo '</div>';
		?>
		
		<?php
			if(!empty($internal_transactions))
			{
				echo '<div class="tab-pane" role="tabpanel" id="internaltransactions" aria-labelledby="internaltransactions-tab"> ';
					echo '<table class="table table-striped transaction_table">';
					echo '<thead>';
						echo '<tr><th>ParentTxHash</th><th>Block</th><th>Age</th><th>From</th><th></th><th>To</th><th>Value</th></tr>';
					echo '</thead>';
					echo '<tbody>';
						foreach($internal_transactions as $internal)
						{
							echo '<tr>';
								echo '<td><a href="'.base_url().'tx/'.$internal->parent.'">'.substr($internal->parent,0,21).'</a></td>';
								echo '<td><a href="'.base_url().'blocks/block/'.$internal->block.'">'.$internal->block.'</a></td>';
								echo '<td>'.(!empty($internal->age) ? _ago($internal->age) : '-').'</td>';
								echo '<td>'.($internal->from !== $this->uri->segment(3) ? '<a href="'.base_url().'address/search/'.$internal->from.'">'.substr($internal->from,0,21).'...</a>' : substr($internal->from,0,21).'...').'</td>';
								
								echo '<td><i class="fa fa-arrow-right"></i></td>';
								
								echo '<td>'.($internal->to !== $this->uri->segment(3) ? '<a href="'.base_url().'address/search/'.$internal->to.'">'.substr($internal->to,0,21).'...</a>' : substr($internal->to,0,21).'...').'</td>';
								echo '<td>'.$internal->value.'</td>';
							echo '</tr>';
						}
					echo '</tbody>';
					echo '</table>';
				echo '</div>';
			}
		?>
		
		<?php 
			if($contract == 1)
			{	
				if(!empty($verified_contract)) {
					echo '<div class="tab-pane" role="tabpanel" id="contractcode" aria-labelledby="contractcode-tab"> ';
					echo '<div class="contract_code">';
					echo '<p><span style="font-weight:bold; color:green;"><i class="fa fa-check-circle-o"></i></span> Contract Source Code Verified!</p>';
					echo '<div class="row" style="margin: 35px 0;">';
						echo '<div class="col-md-6">';
							echo '<table class="table">';
								echo '<tr><th>Contract Name:</th><td>'.str_replace(':', '', $verified_contract->contractName).'</td></tr>';
								echo '<tr><th>Compiler Version:</th><td>'.$verified_contract->compilerVersion.'</td></tr>';
							echo '</table>';
						echo '</div>';
						echo '<div class="col-md-6">';
							echo '<table class="table">';
								echo '<tr><th>Optimization Enabled:</th><td>'.($verified_contract->optimization == 1 ? 'Yes': 'No').'</td></tr>';
							echo '</table>';
						echo '</div>';
					echo '</div>';
					echo '<hr />';
					echo '<h4><strong>Contract Source Code <i class="fa fa-code"></i></strong></h4>';
					echo '<figure class="highlight" style="margin-bottom:55px;">';
					echo '<pre style="height: 200px; max-height: 350px; margin-top:7px;">';
						echo $verified_contract->sourceCode;
					echo '</pre>';		
					echo '</figure>';
					
					
					echo '<h4><strong>Contract ABI <i class="fa fa-cogs"></i></strong> </h4>';
					echo '<figure class="highlight" style="margin-bottom:55px;">';
					echo '<pre style="height: 200px; max-height: 350px; margin-top:15px;">';
						echo $verified_contract->abi;
					echo '</pre>';		
					echo '</figure>';
					
					
					echo '<h4><strong>Contract Creation Code <i class="fa fa-building-o"></i></strong> </h4>';
					echo '<figure class="highlight" style="margin-bottom:55px;">';
					echo '<pre style="height: 200px; max-height: 350px;">';
						echo $verified_contract->bytecode;
					echo '</pre>';		
					echo '</figure>';
					
					echo '<h4><strong>Swarm Source <i class="fa fa-database"></i></strong> </h4>';
					echo '<figure class="highlight" style="margin-bottom:55px;">';
					echo '<pre>';
						echo $verified_contract->swarm;
					echo '</pre>';		
					echo '</figure>';
					
					echo '</div>';
					echo '</div>';
				} else {
					echo '<div class="tab-pane" role="tabpanel" id="contractcode" aria-labelledby="contractcode-tab"> ';
					echo '<div class="contract_code">';
					
					echo '<p><i class="fa fa-info-circle"></i> Are you The Contract Creator? <a href="/tools/verify/'.$wallet->wallet.'">Verify And Publish</a> your Contract Source Code Today!</p>';
					
					echo '<br />';
					echo '<p><i class="fa fa-cogs"></i> Contract Creation Code</p>';
					echo '<hr />';
					echo '<figure class="highlight contractCode">';
					echo '<pre>';
						
					echo '</pre>';		
					echo '</figure>';
					echo '</div>';
					echo '</div>';
				}
			}	
		?>
	</div>
	
	</div>
</div>
	
	
	

	
	