 </div>
 </main>
 
 <footer>
	 <div class="row">
		 
		 <div class="container">
			 <hr />
	        <p>&copy; <?php echo date("Y", strtotime("now")); ?> POAExplorer.com.  Donations: 0x9A04B5c65870cd7bB35556DEB854556e6a7A65F8</p>
		 </div>
	 </div>
 </footer>
        
        
        
        <script src="<?php echo base_url(); ?>assets/js/vendor/modernizr-3.5.0.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
        <script>window.jQuery || document.write('<script src="/assets/js/vendor/jquery-3.2.1.min.js"><\/script>')</script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/web3.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/plugins.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/main.js"></script>
        
        <?php echo (!empty($scripts) ? $scripts : '');		?>
		
		
	    
	        
	     
        

        <script>
	        $('.dropdown-toggle').dropdown();
            window.ga=function(){ga.q.push(arguments)};ga.q=[];ga.l=+new Date;
            ga('create','UA-111442253-1','auto');ga('send','pageview')
        </script>
        <script src="https://www.google-analytics.com/analytics.js" async defer></script>
    </body>
</html>
