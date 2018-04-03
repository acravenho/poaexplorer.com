$(function(){
	$('#verifyContractForm').validator().on('submit', function (e) {
	  if (e.isDefaultPrevented()) {
	  	console.log('invalid');
	  } else {
		
	    e.preventDefault();
	    $('#success_message').removeClass('alert alert-danger');
	    $('#success_message').html('<img src="https://loading.io/spinners/coolors/lg.palette-rotating-ring-loader.gif" height="40px" /> Verifying your contract data now. This may take up to 30 seconds.');
	    $('#success_message').show();
	    
	    var address = $('#contractAddress').val();
	    var name = $('#contractName').val();
	    var compiler = $('#compilerVersions').val();
	    var optimization = $('#optimization').val();
	    var source = $('#sourceCode').val();
	    
	    $.post( "/assets/files/php/smartcontract.php", { address: address, name: name, compiler: compiler, optimization: optimization, source: source }, function( data ) {
		    console.log(data);
		    var response = $.parseJSON(data);
		    if(typeof response =='object')
			{
			  console.log(response);
			  var verified = response.verified;
			  var error = response.error;
			  var address = response.address;
			  if(verified == 'yes') {
				  window.location = "/address/search/" + address + "#contractcode";
			  } else {
				  $( "#success_message" ).addClass('alert alert-danger').html( error );
			  }
			}
		});
	    
	  }
	})
	  
});

