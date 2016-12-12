<?php
	// define a function to help with printing threaded comments

	function printComments($pid,$ids,$comments,$threads,$borderleft=false)
	{
		$classes = 'commentThreadList';
		if ($borderleft) {
			// this creates a line down the left of the child comments
			$classes .= ' threadBorderLeft';
		}
		print "<ul class='$classes'>\n";

		foreach ($ids as $id) {
			// calculate votes
			$votes = $comments[$id]['upvotes'] - $comments[$id]['downvotes'];
			// don't go below 0
			if ($votes < 0) { $votes = 0; }
		?>
			<!-- note that we insert the comment id as the value of the CSS id attribute of the <li>
			     tag (i.e., the data is in the DOM!).  We use this in the event handlers of the 'reply'
			     and 'cancel' -->
			<li class='commentItem' id="<?php print $id ?>">
			<div>
				<div style="float:left" class="postvotes">
								<div>
									<a href="<?php echo base_url()?>index.php/Post/commentvote?v=up&cid=<?php echo $id?>&pid=<?php echo $pid?>">
										<span class="glyphicon glyphicon-arrow-up vote vote-icon upvote" aria-hidden="true"></span>
									</a>
								</div>
								<div>
									<a href="<?php echo base_url()?>index.php/Post/commentvote?v=down&cid=<?php echo $id?>&pid=<?php echo $pid?>">
										<span class="glyphicon glyphicon-arrow-down vote vote-icon downvote" aria-hidden="true"></span>
									</a>
								</div>
				</div>


				<div class='comment' style="float:left"> <!-- floating it left ups it next to the vote arrows -->
					<p class="commenthdr"> by <?php print $comments[$id]['user'] ?> (<?php print $votes ?>)</p>
					<p> <?php print $comments[$id]['comment'] ?> </p>
				</div>
			</div>
			<div style="clear:both"></div>
			<div style="padding-left:20px">
				<!-- see end for jquery event handlers on these two spans - which will insert a reply form
				     or remove it -->
				<span class="commentreply">reply</span> <span class="commentcancel">cancel</span>
			</div>

			<div id="reply-<?php print $id ?>"></div> <!-- empty div to hold reply form -->
		<?php
			// does this comment have children?
			if (isset($threads[$id])) {
				// we have child comments
				$childComments = $threads[$id];
				// recursion!  yuck!
				printComments($pid,$childComments,$comments,$threads,true);
			}
			print "</li>\n";
		}

		print "</ul>\n";
	}

?>
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

		.postarea {
			padding-bottom: 10px;
			border-bottom: 1px solid #c6c6c6;
		}

		.postarea > p {
			font-size: 10px;
			margin-bottom: 0px;
		}

		.commentarea {
			padding-top: 10px;
		}

		.commentreply,.commentcancel {
			color:#808080;
			font-size: 10px;
		}
		.commentreply:hover,.commentcancel:hover {
			text-decoration: underline;
			cursor: pointer;
		}
		.commentThreadList {
			list-style-type: none;
			padding: 10px 10px 10px 25px;
		}
		.threadBorderLeft {
			margin-left: 5px;
			border-left: 1px dashed #c6c6c6;
		}
		.commenthdr {
			font-size: 10px;
			color: darkblue;
		}
		.comment > p {
			margin-bottom: 0px;
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
			<div class="postarea">
				<div>
				<a href="<?php print $post->url ?>">
					<?php print $post->title ?>
				</a>
				<span style="font-size:11px">
					<?php
						// this prints out the hostname component of the url, just like reddit
						$urlparts = parse_url($post->url);
						print '(' . $urlparts['host'] . ')';
					?>
				</span>
				</div>
				<p> submitted <span data-livestamp="<?php echo $post->timestamp ?>"></span> ago by <?php echo $post->user ?> </p>
				<p>
					<span style="padding-right:5px;">
					<?php
					// calculate votes
					$votes = $post->upvotes - $post->downvotes;
					// don't go below 0
					if ($votes < 0) { $votes = 0; }
					print $votes;
					?> votes
					</span>
					<span>
						<?php
							print $post->comments
						?> comments
					</span>
				</p>
			</div>
			<div class="commentarea">
				<form method=POST action="<?php echo base_url()?>index.php/Post/comment/<?php echo $post->id?>">
					<div class="form-group">
						<textarea name="commenttext" id="commenttext" class="form-control" rows="4" style="width:50%"></textarea>
					</div>
					<div class="form-group">
						<input type=text name="user" id="user" class="form-control" style="width:50%" placeholder="Your name"/>
					</div>
					<button type="submit" class="btn btn-default btn-sm">Submit comment</button>
				</form>
				<?php
					$threads = $post->commentData['threads'];
					$comments = $post->commentData['comments'];
					$noOfComments = $post->comments;

					if ($noOfComments == 0) {
						// no comments
				?>
					<div style="padding-top:15px; color:red">No-one has commented yet.  Be the first!</div>
				<?php
					}
					else {
						$toplevelids = $threads[-1];
						printComments($post->id,$toplevelids,$comments,$threads);
					}
				?>
			</div>

		</div> <!-- col-md-8 -->
	</div> <!-- row -->

</div> <!-- container -->
<script language="javascript">
	// if the user clicks on a comment reply...
	$('.commentreply').click(function () {
		// add a reply form under the comment
		// first get comment id from the <li> tag that contains comment
		var commentid = $(this).closest('li').attr('id');
		$('#reply-' + commentid).html(
					'<form style="padding-bottom:10px" method=POST action="<?php echo base_url()?>index.php/Post/comment/<?php echo $post->id?>?parent=' + commentid
					+ '"><div class="form-group"><textarea name="commenttext" id="commenttext" class="form-control" rows="3" style="width:40%"></textarea></div><div class="form-group"><input type=text name="user" id="user" class="form-control input-sm" style="width:40%" placeholder="Your name"/></div><button type="submit" class="btn btn-default btn-xs">Submit comment</button></form>'
					);
	});

	$('.commentcancel').click(function () {
		// remove a reply form under the comment
		// first get comment id
		var commentid = $(this).closest('li').attr('id');
		$('#reply-' + commentid).html('');
	});
</script>
</body>
</html>