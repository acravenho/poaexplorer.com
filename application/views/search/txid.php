<div class="row">
	<div class="col-md-12">
		<p class="lead">TXID: <?php echo $this->uri->segment(3); ?></p>
		<hr>

		<table id="trans_table" class="table">
			<tr><th>Hash:</th><td id="trans_hash"></td></tr>
			<tr><th>Block Number:</th><td id="trans_blockNumber"></td></tr>
			<tr><th>From:</th><td id="trans_from"></td></tr>
			<tr><th>To:</th><td id="trans_to"></td></tr>
			<tr><th>Value:</th><td id="trans_value"></td></tr>
			<tr><th>Gas:</th><td id="trans_gas"></td></tr>
			<tr><th>Gas Price:</th><td id="trans_gasPrice"></td></tr>
			<tr><th>Nonce:</th><td id="trans_nonce"></td></tr>
			
		</table>
	</div>
</div>