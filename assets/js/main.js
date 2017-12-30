	var POA = require('web3');
    var poa = new Web3();
    poa.setProvider(new poa.providers.HttpProvider('https://core.poa.network'));
    
    function getNewBlocks() {
	    $.get('/api/getblocks');
    }
    
    function addressBalance(poa_address) {
	    var ethprice = $('.eth_price').text();
	    var conversion = ethprice * .00023;
	    var originalBalance = poa.eth.getBalance(poa_address);
	    originalBalance = poa.fromWei(originalBalance, 'ether');

	    var usdollar    = originalBalance * conversion;
	    usdollar = usdollar.toLocaleString();
	    
	    originalBalance = originalBalance.toLocaleString();
	    
	    
	    
	    $('#address_balance').text(originalBalance);
        $('#address_value').text('$'+usdollar).toString(10);
		poa.eth.filter('latest').watch(function() {
            var currentBalance = poa.eth.getBalance(poa_address);
            currentBalance = poa.fromWei(currentBalance, 'ether');
            var usdollar = currentBalance * conversion;
            usdollar = usdollar.toLocaleString();
            currentBalance = currentBalance.toLocaleString();
            $("#address_balance").text(currentBalance);
            $('#address_value').text('$'+usdollar);
     
        });
    }
    
    function getTransaction(poa_address) {
	    var transactions = poa.eth.getTransaction(poa_address);
	    $('#address_transactions').html(transactions);
    }
    
    function transactionCount(poa_address) {
	    var number = poa.eth.getTransactionCount(poa_address);
	    
	    //$('#address_count').html(number);
	    //poa.eth.filter('latest').watch(function() {
            //var currentTransactionCount = poa.eth.getTransactionCount(poa_address);
           // $('#address_count').html(currentTransactionCount);
     
        //});
    }
    
    
    function getTransaction(hash) {
	    var transaction = poa.eth.getTransaction(hash);
	    $('#trans_hash').html(transaction.blockHash);
	    $('#trans_blockNumber').html(transaction.blockNumber);
	    $('#trans_to').html('<a href="/address/search/'+transaction.to+'">'+transaction.to+'</a>');
	    $('#trans_from').html('<a href="/address/search/'+transaction.from+'">'+transaction.from+'</a>');
	    var transactionValue = poa.fromWei(transaction.value, 'ether').toLocaleString();
	    $('#trans_value').text(transactionValue + ' POA');
	    $('#trans_gas').html(transaction.gas);
	    $('#trans_gasPrice').text(transaction.gasPrice).toString(10);
	    $('#trans_nonce').html(transaction.nonce);
	 }
	 
	 function getStartBlock() {
		var first = poa.eth.blockNumber;
		var end = first - 100;
		
		var string;
		
		for (i = first; i >= end; i--) { 
			var transactionCount = poa.eth.getBlockTransactionCount(i);
			var getBlock = poa.eth.getBlock(i);
			var author = getBlock.author;
			
			
			var string = '<tr><td><a href="/blocks/block/'+i+'">'+i+'</a></td><td>'+ transactionCount +'</td><td><a href="/address/search/'+ author +'">'+ author +'</a></td></tr>';
			
			$('#blockTable tbody').append(string);
		}	 
	 }
	 
	 
	 function getBlockInfo(block) {
		 var transactionCount = poa.eth.getBlockTransactionCount(block);
		 var getBlock = poa.eth.getBlock(block);
		 
		 console.log(getBlock);
		 
		 var author = getBlock.author;
		 var difficulty = getBlock.difficulty.toLocaleString();
		 var gasUsed    = getBlock.gasUsed;
		 var blockHash  = getBlock.hash;
		 var size       = getBlock.size;
		 var totalDifficulty = getBlock.totalDifficulty;
		 var transactions    = getBlock.transactions;
		 
		 
		 
		 $('#transactionCount').html(transactionCount);
		 $('#author').html('<a href="/address/search/'+author+'">'+author+'</a>');
		 $('#difficulty').text(difficulty);
		 $('#gasUsed').text(gasUsed);
		 $('#blockHash').html(blockHash);
		 $('#size').html(size);
		 $('#totalDifficulty').text(totalDifficulty).toLocaleString();
		 
		 if(transactionCount > 0) {
			 for(i=0; i<transactionCount; i++) {
				 var transaction = poa.eth.getTransaction(transactions[i]);
				 var transactionValue = poa.fromWei(transaction.value, 'ether').toLocaleString();
				 var string = '<tr><td><a href="/txid/search/'+transactions[i]+'">'+ transactions[i].substring(0,20) +'....</a></td><td><a href="/address/search/'+transaction.to+'">'+ transaction.to.substring(0,20) +'...</a></td><td><a href="/address/search/'+transaction.from+'">'+ transaction.from.substring(0,20) +'...</a></td><td>'+transactionValue+' POA</td></tr>';
				 $('#blockTransactions tbody').append(string);
			 }
		 } else {
			 var string = '<tr><td colspan="4">Sorry, no transactions could be found for this block.</td></tr>';
			 $('#blockTransactions tbody').append(string);
		 }
		 
	 }
	 
	 function getBlockNumber() {
		 var block = poa.eth.blockNumber;
		 $('.blocknum').html(block);
		 
		 var gasPrice = poa.eth.gasPrice;
		 gasPrice = poa.fromWei(gasPrice, 'ether').toString(10);
		 
		 
		 var transactionCount = poa.eth.getBlockTransactionCount(block);
		 var getBlock = poa.eth.getBlock(block);
		 
		 
		 var author = getBlock.author;
		 var difficulty = getBlock.difficulty.toLocaleString();
		 var gasUsed    = getBlock.gasUsed;
		 var blockHash  = getBlock.hash;
		 var size       = getBlock.size;
		 var totalDifficulty = getBlock.totalDifficulty;
		 var transactions    = getBlock.transactions;
		 
		 
		 
		 $('#blockHeight').html(block);
		 $('#gasPrice').html(gasPrice);
		 $('#transactionCount').html(transactionCount);
		 $('#author').html('<a href="/address/search/'+author+'">'+author+'</a>');
		 $('#blockHash').html(blockHash);
		 $('#size').html(size + ' bytes');
		 
		 
		 
		 if(transactionCount > 0) {
			 for(i=0; i<transactionCount; i++) {
				 var transaction = poa.eth.getTransaction(transactions[i]);
				 var transactionValue = poa.fromWei(transaction.value, 'ether').toLocaleString();
				 var string = '<tr><td><a href="/txid/search/'+transactions[i]+'">'+ transactions[i].substring(0,20) +'....</a></td><td><a href="/address/search/'+transaction.to+'">'+ transaction.to.substring(0,20) +'...</a></td><td><a href="/address/search/'+transaction.from+'">'+ transaction.from.substring(0,20) +'...</a></td><td>'+transactionValue+' POA</td></tr>';
				 $('#blockTransactions tbody').append(string);
			 }
		 } else {
			 var string = '<tr><td colspan="4">Sorry, no transactions could be found for this block.</td></tr>';
			 $('#blockTransactions tbody').append(string);
		 }
		 
		 
		 
		 
		 
		 poa.eth.filter('latest').watch(function() {
            var block = poa.eth.blockNumber;
            $('.blocknum').html(block);
            $('#blockTransactions tbody').html('');
			 var gasPrice = poa.eth.gasPrice;
			 gasPrice = poa.fromWei(gasPrice, 'ether').toString(10);
			 
			 
			 var transactionCount = poa.eth.getBlockTransactionCount(block);
			 var getBlock = poa.eth.getBlock(block);
			 
			 var author = getBlock.author;
			 var difficulty = getBlock.difficulty.toLocaleString();
			 var gasUsed    = getBlock.gasUsed;
			 var blockHash  = getBlock.hash;
			 var size       = getBlock.size;
			 var totalDifficulty = getBlock.totalDifficulty.toString(10);
			 var transactions    = getBlock.transactions;
			 
			 
			 
			 $('#blockHeight').html(block);
			 $('#gasPrice').html(gasPrice);
			 $('#transactionCount').html(transactionCount);
			 $('#author').html('<a href="/address/search/'+author+'">'+author+'</a>');
			 $('#blockHash').html(blockHash);
			 $('#size').html(size + ' bytes');
			 
			 
			 if(transactionCount > 0) {
				 for(i=0; i<transactionCount; i++) {
					 var transaction = poa.eth.getTransaction(transactions[i]);
					 var transactionValue = poa.fromWei(transaction.value, 'ether').toLocaleString();
					 var string = '<tr><td><a href="/txid/search/'+transactions[i]+'">'+ transactions[i].substring(0,20) +'....</a></td><td><a href="/address/search/'+transaction.to+'">'+ transaction.to.substring(0,20) +'...</a></td><td><a href="/address/search/'+transaction.from+'">'+ transaction.from.substring(0,20) +'...</a></td><td>'+transactionValue+' POA</td></tr>';
					 $('#blockTransactions tbody').append(string);
				 }
			 } else {
				 var string = '<tr><td colspan="4">Sorry, no transactions could be found for this block.</td></tr>';
				 $('#blockTransactions tbody').append(string);
			 }
     
        });
		 
		 
		 
	 }
	 
	 
    
    function getLatestBlocks() {
	    $.post('/api/lastblock', function(data) {
		   var lastBlock    = data;
		   var currentBlock = poa.eth.blockNumber;
		   
		   
		   for(i=lastBlock; i <= currentBlock; i++) {
		   		var transactionCount = poa.eth.getBlockTransactionCount(i);
		   		var getBlock = poa.eth.getBlock(i);
		   		
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
						 
						 
						 
						 $.post( "/api/post", { blockHash: blockHash, blockNumber: blockNumber, to: to, tfrom: tfrom, transactionValue: transactionValue, gasPrice: gasPrice, nonce: nonce, txid: transactions[x] } );
						 
						 
						 
					 }
				 } 
				$.post( "/api/addblock", { block: i } );	 
			}
		});
		   
	   
    }
    
    function contractInit() {
	    var myContract = poa.eth.Contract([], '0xde0B295669a9FD93d5F28D9Ec85E40f4cb697BAe', {
		    from: '0x1234567890123456789012345678901234567891', // default from address
		    gasPrice: '20000000000' // default gas price in wei, 20 gwei in this case
		});
		
		console.log(myContract);
    }
    
    
    
    
    
    
    