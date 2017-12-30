<?php 
	$first = $last+1;
	$end   = $first+1000;	
?>

 <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
 <script src="/assets/js/web3.min.js"></script>
 
 
 <script>
	 
	 var POA = require('web3');
    var poa = new Web3();
    poa.setProvider(new web3.providers.HttpProvider('https://core.poa.network'));
	 
	 function getBlockInfo(block) {
		 var currentBlock = poa.eth.blockNumber;
		 
		 if(block > currentBlock) {
			 die();
		 }
		 
		 var transactionCount = poa.eth.getBlockTransactionCount(block);
		 var getBlock = poa.eth.getBlock(block);
		 
		 var author = getBlock.author;
		 var difficulty = getBlock.difficulty;
		 var gasUsed    = getBlock.gasUsed;
		 var blockHash  = getBlock.hash;
		 var size       = getBlock.size;
		 var totalDifficulty = getBlock.totalDifficulty;
		 var transactions    = getBlock.transactions;
		 
		 if(transactionCount > 0) {
			 console.log("transaction count " + transactionCount);
			 for(x=0; x<transactionCount; x++) {
				 var transaction = poa.eth.getTransaction(transactions[x]);
				 var val = transaction.value.toString(10);
				 var transactionValue = poa.fromWei(val, 'ether');
				 var to = transaction.to;
				 var tfrom = transaction.from;
				 var blockHash = transaction.blockHash;
				 var blockNumber = transaction.blockNumber;
				 var gas = transaction.gas;
				 var gasPrice = transaction.gasPrice.toString(10);
				 var nonce = transaction.nonce;
				 
				 console.log(transactionValue);
				 console.log(to);
				 console.log(tfrom);
				 console.log(blockHash);
				 console.log(blockNumber);
				 console.log(gas);
				 console.log(gasPrice);
				 console.log(nonce);
				 
				 
				 $.post( "/api/post", { blockHash: blockHash, blockNumber: blockNumber, to: to, tfrom: tfrom, transactionValue: transactionValue, gasPrice: gasPrice, nonce: nonce, txid: transactions[x] } );
				 
				 
				 
			 }
		 } 
		 
	 }
	 
	 for(i=<?php echo $first; ?>; i<<?php echo $end; ?>; i++) {
		 $('.hello').append(i);
		 console.log(i);
		getBlockInfo(i);	 
		$.post( "/api/addblock", { block: i } );
	 }
	 

	 
	 
	 
	 
	 
</script>


<div class="hello">
	
</div>