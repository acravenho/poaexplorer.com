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
    
    function getContractCode(wallet) 
    {
	    var contractCode = poa.eth.getCode(wallet);
	    $('.contractCode pre').html(contractCode);
    }
    
    
    
    function getTransaction(hash) {
	    var transaction = poa.eth.getTransaction(hash);
	    
	    var to = transaction.to;
	    if(to == null)
	    {
		    $('#trans_to').html('[Contract <a href="/address/search/'+transaction.creates+'">'+transaction.creates+'</a>. Created] <span style="color:green;"><i class="fa fa-check-circle-o"></i></span>');
	    }
	    else
	    {
		    $('#trans_to').html('<a href="/address/search/'+transaction.to+'">'+transaction.to+'</a>');
	    }
	    
	    $('#trans_hash').html(transaction.blockHash);
	    $('#trans_blockNumber').html(transaction.blockNumber);
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
				 var string = '<tr><td><a href="/txid/search/'+transactions[i]+'">'+ transactions[i].substring(0,16) +'....</a></td><td><a href="/address/search/'+transaction.to+'">'+ transaction.to.substring(0,16) +'...</a></td><td><a href="/address/search/'+transaction.from+'">'+ transaction.from.substring(0,16) +'...</a></td><td>'+transactionValue+' POA</td></tr>';
				 $('#blockTransactions tbody').append(string);
			 }
		 } else {
			 var string = '<tr><td colspan="4">Sorry, no transactions could be found for this block.</td></tr>';
			 $('#blockTransactions tbody').append(string);
		 }
		 
	 }
	 
	 function getBlockNumber() {
		 
		 poa.eth.filter('latest').watch(function() {
            var block = poa.eth.blockNumber;
            $('.blocknum').html('#'+block);
            
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
			 var time_stamp      = getBlock.timestamp;
			 
			 $('.hourglass').html(dateTime(time_stamp));
			 
			 
			 $('#blockHeight').html(block);
			 $('#gasPrice').html(gasPrice);
			 $('#transactionCount').html(transactionCount);
			 $('#author').html('<a href="/address/search/'+author+'">'+author+'</a>');
			 $('#blockHash').html(blockHash);
			 $('#size').html(size + ' bytes');
			 
			 var newBlock = '<tr><td><a href="/blocks/block/' + block + '">' + block + '</a></td><td>' + transactionCount + '</td><td><a href="/address/search/' + author + '">'+author.substring(0,21)+'...</a></td></tr>';
			 
			 $('#blockBlocks tbody tr:first').before(newBlock);
			 $('#blockBlocks tbody tr:first').hide().fadeIn('slow');
			 
			 $('#blockBlocks tbody tr:last').remove();
			 
			 
			 if(transactionCount > 0) {
				 for(i=0; i<transactionCount; i++) {
					 var transaction = poa.eth.getTransaction(transactions[i]);
					 var transactionValue = poa.fromWei(transaction.value, 'ether').toLocaleString();
					 
					 if(transaction.to == null)
				     {
					    var tostring = '<i class="fa fa-file-text"></i> <a href="/txid/search/'+transactions[i]+'">Contract Creation</a>';
				     }
				     else
				     {
					    var tostring = '<a href="/address/search/'+transaction.to+'">'+ transaction.to.substring(0,16) +'...</a>';
				     }
					 
					 var string = '<tr><td><a href="/txid/search/'+transactions[i]+'">'+ transactions[i].substring(0,16) +'....</a></td><td><a href="/address/search/'+transaction.from+'">'+ transaction.from.substring(0,16) +'...</a></td><td>'+tostring+'</td><td>'+transactionValue+'</td></tr>';
					 $('#blockTransactions tbody tr:first').before(string);
					 $('#blockTransactions tbody tr:first').hide().fadeIn('slow');
					 $('#blockTransactions tbody tr:last').remove();				 
				}
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
    
    function timeSince(time){
	  var units = [
	    { name: "second", limit: 60, in_seconds: 1 },
	    { name: "minute", limit: 3600, in_seconds: 60 },
	    { name: "hour", limit: 86400, in_seconds: 3600  },
	    { name: "day", limit: 604800, in_seconds: 86400 },
	    { name: "week", limit: 2629743, in_seconds: 604800  },
	    { name: "month", limit: 31556926, in_seconds: 2629743 },
	    { name: "year", limit: null, in_seconds: 31556926 }
	  ];
	  var diff = (new Date() - new Date(time*1000)) / 1000;
	  if (diff < 5) return "now";
	  
	  var i = 0, unit;
	  while (unit = units[i++]) {
	    if (diff < unit.limit || !unit.limit){
	      var diff =  Math.floor(diff / unit.in_seconds);
	      return diff + " " + unit.name + (diff>1 ? "s" : "");
	    }
	  };
	}
	
	
	function dateTime(timestamp)
	{
		var date = new Date(timestamp*1000);
		var hours = date.getHours();
		var minutes = "0" + date.getMinutes();
		var seconds = "0" + date.getSeconds();
		var formattedTime = hours + ':' + minutes.substr(-2) + ':' + seconds.substr(-2);
		return formattedTime;
	}
    
    
    
    
    
    
    