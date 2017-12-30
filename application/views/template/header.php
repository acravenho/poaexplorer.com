<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title><?php echo (!empty($title) ? $title : 'POA Explorer'); ?></title>
        <meta name="description" content="<?php echo (!empty($description) ? $description : 'POA Network block explorer.'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="manifest" href="site.webmanifest">
        <!-- Place favicon.ico in the root directory -->

        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/main.css">
        <link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url(); ?>favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url(); ?>favicon-16x16.png">
		<link rel="shortcut icon" href="<?php echo base_url(); ?>favicon.ico">
    </head>
    <body>
	    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
	      <a class="navbar-brand" href="/"><img src="<?php echo base_url(); ?>assets/img/poaimage.png" /></a>
	      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
	        <span class="navbar-toggler-icon"></span>
	      </button>
	
	      <div class="collapse navbar-collapse" id="navbarsExampleDefault">
	        <ul class="navbar-nav mr-auto">
		        <?php
			        $active = $this->uri->segment(1); 
			     ?>
	          <li class="nav-item <?php echo (empty($active) ? 'active' : ''); ?>">
	            <a class="nav-link" href="<?php echo base_url(); ?>">Home</a>
	          </li>
	          <li class="nav-item <?php echo ($active == 'blocks' ? 'active' : ''); ?>">
	            <a class="nav-link" href="<?php echo base_url(); ?>blocks">Blocks</a>
	          </li>
	          <li class="nav-item <?php echo ($active == 'transactions' ? 'active' : ''); ?>">
	            <a class="nav-link" href="<?php echo base_url(); ?>transactions">Transactions</a>
	          </li>
	          <li class="nav-item <?php echo ($active == 'richlist' ? 'active' : ''); ?>">
	            <a class="nav-link" href="<?php echo base_url(); ?>richlist">Rich List</a>
	          </li>
	          
	          
	        </ul>
	        <form class="form-inline my-2 my-lg-0" method="post" action="<?php echo base_url(); ?>search">
	          <input class="form-control mr-sm-2" type="text" placeholder="Search Address / TX / Block" aria-label="Search" name="hash">
	          <button class="btn btn-outline-primary my-2 my-sm-0" type="submit">Search</button>
	        </form>
	      </div>
	    </nav>
	    
	    <main role="main">
		    <div class="container">