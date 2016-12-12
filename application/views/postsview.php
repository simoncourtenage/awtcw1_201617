<!doctype html>
<html>
<head>
	<title> <?php print SITE_TITLE ?></title>
	
	<!-- load bootstrap from CDN -->
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<!-- use a nice Google font for display of titles and headers etc. -->
	<link href="https://fonts.googleapis.com/css?family=Coming+Soon" rel="stylesheet"> 
	<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
	<script src="http://momentjs.com/downloads/moment.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/livestamp/1.1.2/livestamp.js"></script>
	<style>
		/* use google font for site title */
		.site-title {
			font-family: 'Coming Soon', cursive;
		}
		.postvotes {
			margin-right:10px;
		}
		.postitem {
			border-bottom: 1px dashed #c6c6c6;
		}
		.vote {
			color:#808080;
		}
		.vote-icon {
			font-size: 10px;
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

<!-- main post list -->

<div class="container">
	<div class="row">
		<div class="col-md-8">
		<?php 
			if ($posts === false) {
				// no posts to display - see Postmodel::posts() for explanation
		?>
				<div class="well">
					<p>No links found!  Why not add a link?</p>
				</div> <!-- well -->
		<?php

			}
			else {
		?>
			<!-- we'll use an unordered list to display the links -->
			<div class="postlist">
		<?php
			foreach ($posts as $p) {
				
				?>
					<!-- each list item will be one link -->
					<!-- set the CSS id attribute to the post id value - this allows us to identify the post
					     when they click to up or downvote the link -->
					<div class="postitem" id="<?php print $p->id?>">
						
							<div style="float:left" class="postvotes">
								<div>
									<a href="<?php echo base_url()?>index.php/Post/vote?v=up&pid=<?php echo $p->id?>">
										<span class="glyphicon glyphicon-arrow-up vote vote-icon upvote" aria-hidden="true"></span>
									</a>
								</div>
								<div class="vote">
									<?php
										// calculate votes
										$votes = $p->upvotes - $p->downvotes;
										// don't go below 0
										if ($votes < 0) { $votes = 0; }
										print $votes;
									?>
								</div>
								<div>
									<a href="<?php echo base_url()?>index.php/Post/vote?v=down&pid=<?php echo $p->id?>">
										<span  class="glyphicon glyphicon-arrow-down vote vote-icon downvote" aria-hidden="true"></span>
									</a>
								</div>
							</div>
							<div style="float:left">
								<div>
									<a href="<?php echo $p->url ?>">
										<?php echo $p->title ?>
									</a>
									<span style="font-size:11px">
									<?php
										// this prints out the hostname component of the url, just like reddit
										$urlparts = parse_url($p->url);
										print '(' . $urlparts['host'] . ')';
									?>
								</span>
								</div>
								<div style="font-size:11px">
									submitted <span data-livestamp="<?php echo $p->timestamp ?>"></span> by <?php print $p->user; ?> 	
								</div>
								<div>
									<span style="font-size:10px">
										<a href="<?php echo base_url()?>index.php/Post/post/<?php echo $p->id ?>">
										<?php
											// if there are no comments yet, then just write "Comment", else write
											// the number of comments, like reddit...
											if ($p->comments == 0) {
										?>
											Comment
										<?php
											}
											else {
										?>
											<?php print $p->comments ?> comments
										<?php

											}
										?>
										</a>
									</span>
								</div>
							</div>
						
					</div>
					<div style="clear:both"></div>
				<?php
			}
		?>

			</div> <!-- .postlist -->
		<?php
			}
		?>
	</div> <!-- col-md-8 -->
	<div class="col-md-4">
		<a href="<?php print base_url()?>index.php/Post/add">
			<button class="btn btn-primary btn-lg">Add a new link</button>
		</a>
	</div> <!-- col-md-4 -->
	</div> <!-- row -->

</div> <!-- container -->

</body>
</html>