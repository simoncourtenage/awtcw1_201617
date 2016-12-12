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
			<h2> Post a new link</h2>
			<form action="<?php echo base_url()?>index.php/Post/post" method="POST">
				<div class="form-group">
    				<label for="url">Give us the URL</label>
    				<input type="text" class="form-control" id="url" name="url" placeholder="url">
  				</div>
  				<div class="form-group">
    				<label for="title">Write a nice title</label>
    				<input type="text" class="form-control" id="title" name="title" placeholder="title">
  				</div>
  				<div class="form-group">
    				<label for="user">What's your name?</label>
    				<input type="text" class="form-control" id="user" name="user" placeholder="your name">
  				</div>
  
 
 				 <button type="submit" class="btn btn-default">Submit your link!</button>

			</form>
		</div> <!-- col-md-8 -->
	</div> <!-- row -->

</div> <!-- container -->



</body>
</html>