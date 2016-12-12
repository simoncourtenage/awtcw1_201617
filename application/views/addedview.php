<!doctype html>
<html>
<head>
	<title> <?php print SITE_TITLE ?></title>
	
	<!-- load bootstrap from CDN -->
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<!-- use a nice Google font for display of titles and headers etc. -->
	<link href="https://fonts.googleapis.com/css?family=Coming+Soon" rel="stylesheet"> 

	<style>
		/* use google font for site title */
		.site-title ,h2{
			font-family: 'Coming Soon', cursive;
		}
	</style>
</head>
<!-- padding-top style needed to allow space for bootstrap nav-bar -->
<body style="padding-top: 70px">
<!-- bootstrap nav-bar fixed to top
  -- see http://getbootstrap.com/components/#navbar-fixed-top 
-->
<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">
      <a class="navbar-brand" href="<?php echo base_url()?>/index.php/Post">
        <span class="site-title">linkkit</span>
      </a>
    </div>
  </div>
</nav>

<div class="container">
	<div class="row">
		<div class="col-md-8">
			<h2> Your link has been added.  Thank you!</h2>
			<a href="<?php echo base_url()?>index.php/Post/">Go back</a>
		</div> <!-- col-md-8 -->
	</div> <!-- row -->

</div> <!-- container -->



</body>
</html>