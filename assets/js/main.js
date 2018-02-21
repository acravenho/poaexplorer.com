	var POA = require('web3');
    var poa = new Web3();
    poa.setProvider(new poa.providers.HttpProvider('https://core-trace.poa.network'));
    
    $('.peers').html(poa.net.peerCount);
    $('.parity').html(poa.version.node);
    
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
	    $('#trans_blockNumber').html('<a href="/blocks/block/'+transaction.blockNumber+'">'+transaction.blockNumber+'</a>');
	    $('#trans_from').html('<a href="/address/search/'+transaction.from+'">'+transaction.from+'</a>');
	    console.log(transaction.value);
	    var transactionValue = poa.fromWei(transaction.value, 'ether');
	    transactionValue = transactionValue.toFixed(18);
	    //transactionValue = Number(transactionValue);
	    
	    transactionValue = fixNumber(transactionValue);
	    $('#trans_value').text(transactionValue + ' POA');
	    var gasprice = poa.fromWei(transaction.gasPrice.toFixed(9), 'ether');
	    var gasgwei  = poa.fromWei(transaction.gasPrice.toFixed(9), 'gwei');
	    
	    var transfee = gasprice * transaction.gas;
	    
	    $('#trans_txfee').html(createNumber(transfee));
	    $('.trans_gwei').html(gasgwei);
	    $('.trans_gas').html(transaction.gas);
	    $('.trans_gasPrice').text(gasprice + ' POA');
	    $('#trans_nonce').html(transaction.nonce);
	    $('#trans_input .highlight pre').html(transaction.input);
	 }
	 
	 
	 function get_internal_transactions(hash)
	 {
		 poa.currentProvider.sendAsync({
		  method: "trace_replayTransaction",
		  params: [hash, ['trace']],
		  jsonrpc: "2.0",
		  id: "1"
		}, function (err, out) {
			if(!err)
			{
				
				for(var i=0; i<out.result.trace.length; i++) {
	                console.log(out.result.trace[i]);
	                var type = out.result.trace[i].type;
	                
	                if(type == 'call')
	                {
		                var fromt = out.result.trace[i].action.from;
		                var to    = out.result.trace[i].action.to;
		                var value = out.result.trace[i].action.value;
		                var ovalue = poa.fromWei(value, 'ether');
		            }
		            else
		            {
			            var fromt = out.result.trace[i].action.from;
		                var to    = out.result.trace[i].result.address;
		                var value = out.result.trace[i].action.value;
		                var ovalue = poa.fromWei(value, 'ether');
		            }
		            var html = '<tr><td>'+ type +'</td><td><a href="/address/search/'+fromt+'">' + fromt + '</a></td><td><a href="/address/search/'+to+'">' + to + '</a></td><td>' + ovalue + '</td></tr>';
	                $('#internalTransactions tbody').append(html);
	            }
	        } else {
		        console.log(err);
	        }
		 // console.log(out.result.trace);
		});
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
		 $('#author').html('<a href="/validators/address/'+author+'">'+getValidator(author)+'</a>');
		 $('#difficulty').text(difficulty);
		 $('#gasUsed').text(gasUsed);
		 $('#blockHash').html(blockHash);
		 $('#size').html(size);
		 $('#totalDifficulty').text(totalDifficulty).toLocaleString();
		 
		 if(transactionCount > 0) {
			 for(i=0; i<transactionCount; i++) {
				 var transaction = poa.eth.getTransaction(transactions[i]);
				 var transactionValue = poa.fromWei(new BigNumber(transaction.value).toFixed(), 'ether');
				 
				 var string = '<tr><td><a href="/txid/search/'+transactions[i]+'">'+ transactions[i].substring(0,16) +'....</a></td><td><a href="/address/search/'+transaction.to+'">'+ transaction.to.substring(0,16) +'...</a></td><td><a href="/address/search/'+transaction.from+'">'+ transaction.from.substring(0,16) +'...</a></td><td style="text-align:right;">'+transactionValue+' POA</td></tr>';
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
			 $('#author').html('<a href="/validators/address/'+author+'">'+author+'</a>');
			 $('#blockHash').html(blockHash);
			 $('#size').html(size + ' bytes');
			 
			 var newBlock = '<tr><td class="td-block"><a href="/blocks/block/' + block + '">' + block + '</a></td><td>' + transactionCount + '</td><td><a href="/validators/address/' + author + '">'+getValidator(author)+'</a></td></tr>';
			 
			 var tdblock = $('#blockBlocks tbody tr:first .td-block').text();
			 
			 if(block != tdblock)
			 {
				 $('#blockBlocks tbody tr:first').before(newBlock);
				 $('#blockBlocks tbody tr:first').hide().fadeIn('slow');
				 
				 $('#blockBlocks tbody tr:last').remove();
			}
			 
			 
			/* if(transactionCount > 0) {
				 for(i=0; i<transactionCount; i++) {
					 var transaction = poa.eth.getTransaction(transactions[i]);
					 console.log(transaction.value);
					 var transactionValue = poa.fromWei(new BigNumber(transaction.value).toFixed(), 'ether');
					 
					 
					 if(transaction.to == null)
				     {
					    var tostring = '<i class="fa fa-file-text"></i> <a href="/txid/search/'+transactions[i]+'">Contract Creation</a>';
				     }
				     else
				     {
					    var tostring = '<a href="/address/search/'+transaction.to+'">'+ transaction.to.substring(0,16) +'...</a>';
				     }
					 
					 var string = '<tr><td><a href="/txid/search/'+transactions[i]+'">'+ transactions[i].substring(0,16) +'....</a></td><td><a href="/address/search/'+transaction.from+'">'+ transaction.from.substring(0,16) +'...</a></td><td></td><td>'+tostring+'</td><td>'+transactionValue+'</td></tr>';
					 $('#blockTransactions tbody tr:first').before(string);
					 $('#blockTransactions tbody tr:first').hide().fadeIn('slow');
					 $('#blockTransactions tbody tr:last').remove();				 
				}
			 } */
     
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
	
	
	function getValidator(address)
	{
		var add = address.toLowerCase();
		
		var addressArray = new Array();
		addressArray['0xCf260eA317555637C55F70e55dbA8D5ad8414Cb0'] = 'Igor Barinov';
		addressArray['0x3Ab99ec45f706eE52748F24B1CEA4676d91aF64f'] = 'Roman Storm';
		addressArray['0x8E6cdFacDAe218Ae312aD24Cb1e8cf34Bb9f6b61'] = 'Jefferson Flowers';
		addressArray['0x7A6a585dB8cDFa88B9e8403c054ec2e912E9D32E'] = 'John H. LeGassic';
		addressArray['0x49bbdeBd7f3D39f297135d7Af3831Ce152a99b67'] = 'Jim O\'Regan';
		addressArray['0x0f1e7c925D502855dCD31DdE4703770A0CF6cDFC'] = 'Rocco Mancini';
		addressArray['0x8FE38B0349B99C17242d44D5B1b859B0e941DcEd'] = 'Sherina Yang';
		addressArray['0xDC4765D9DAbF6c6c4908fE97E649Ef1F05Cb6252'] = 'Sviataslau Vishneuski';
		addressArray['0x3091AEe5Cb7a290da8E05cC4b70dac7715de39A0'] = 'John D. Storey';
		addressArray['0x6E4F8fc73B93BA5160FADF914603a590D4676494'] = 'Michael Milirud';
		addressArray['0x18Bea833D503341C529a788c82909337e552a44e'] = 'Lillian Chan';
		addressArray['0xf1F51e933D6aAd056236E0a45c1cC5b335ca1A75'] = 'Stephen Arsenault';
		addressArray['0x28E7605a631441870e80A283Aa43Ae4145f82cc3'] = 'Melanie Marsollier';
		
		for(var key in addressArray)
		{
			if(key.toLowerCase() == add)
			{
				return addressArray[key];
			}
		}

		
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
	
	
	function createNumber(value)
	{
		var number = Number(value.toFixed(18));
		return number.toString();
	}
	
	function fixNumber(value)
	{
		return value.replace(/\.?0+$/,"");
	}
    
    
    
    
    
    
    