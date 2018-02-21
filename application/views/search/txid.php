<div class="row">
	<div class="col-md-12">
		<p class="lead">TXID: <?php echo $this->uri->segment(3); ?></p>
		<hr>

		<table id="trans_table" class="table table-striped transaction_table">
			<tr><th style="width:20%;">Hash:</th><td id="trans_hash"></td></tr>
			<tr><th style="width:20%;">Block Number:</th><td id="trans_blockNumber"></td></tr>
			<tr><th style="width:20%;">From:</th><td id="trans_from"></td></tr>
			<tr><th style="width:20%;">To:</th><td id="trans_to"></td></tr>
			<tr><th style="width:20%;">Value:</th><td id="trans_value"></td></tr>
			<tr><th style="width:20%;">Gas:</th><td><span class="trans_gas"></span></td></tr>
			<tr><th style="width:20%;">Gas Price:</th><td><span class="trans_gasPrice"></span> (<span class="trans_gwei"></span> GWEI)</td></tr>
			<tr><th style="width:20%;">Actual Tx Cost/Fee:</th><td id="trans_txfee"></td></tr>
			<tr><th style="width:20%;">Nonce:</th><td id="trans_nonce"></td></tr>
			<tr><th style="width:20%;">Input:</th><td id="trans_input"><figure class="highlight"><pre></pre></figure></td></tr>
			
			
		</table>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<p class="lead">Internal Tranactions</p>
		<hr />
		<table class="table table-striped transaction_table" id="internalTransactions">
			<thead>
				<tr>
					<th>Type</th>
					<th>From</th>
					<th>To</th>
					<th>Value</th>
				</tr>
			</thead>
			<tbody>
				
			</tbody>
		</table>
	</div>
</div>
	
