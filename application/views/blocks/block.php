<div class="row">
	<div class="col-md-12">
		<h3>Block #<?php echo $this->uri->segment(3); ?></h3>
		<p class="lead">Information on block #<?php echo $this->uri->segment(3); ?>.</p>
		
		<table class="table">
			<table class="table">
				<tr><th>Block:</th><td id="blockHeight"><?php echo $this->uri->segment(3); ?></td></tr>
				<tr><th>Transactions:</th><td id="transactionCount"></td></tr>
				<tr><th>Validator:</th><td id="author"></td></tr>
				<tr><th>Block Hash:</th><td id="blockHash"></td></tr>
				<tr><th>Size:</th><td id="size"></td></tr>
				
			</table>
		</table>
	</div>
</div>


<div class="row" style="margin-top:35px;">
	<div class="col-md-12">
		<h3>Transactions for Block #<?php echo $this->uri->segment(3); ?></h3>
		<table id="blockTransactions" class="table">
			<thead>
				<tr><td>TXID</td><td>To</td><td>From</td><td>Value</td></tr>
			</thead>
			<tbody>
				
			</tbody>
		</table>
	</div>
</div>