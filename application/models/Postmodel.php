<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Postmodel extends CI_Model {
	private $posts_table = 'awt_posts';
	private $comments_table = 'awt_comments';

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	/**
	 * get posts for a particular page
	 * @param page number to fetch
	 * @return mixed | array - data about each post on the page - or false (no posts found)
	 */
	function posts($page)
	{
		// constant defined in config/constants.php
		$postOffset = $page * POSTS_PER_PAGE;
		// how many results do we want and where in the result set do we want
		// them to start?  If there are 50 posts, then for, e.g., Page 3, we
		// want select 20 (current value of POSTS_PER_PAGE) AFTER the first
		// 40 (2 previous pages * POSTS_PER_PAGE)
		$this->db->limit(POSTS_PER_PAGE,$postOffset);
		$res = $this->db->get($this->posts_table);
		// if there are no posts in the database, then send back false
		// we'll test for this in the view to display a 'No Posts' message
		if ($res->num_rows() == 0) {
			return false;
		}
		$posts = array();
		foreach ($res->result() as $row) {
			$posts[] = $row;
		}
		return $posts;
	}

	function addpost($url,$title,$user)
	{
		// add the new post to the posts table
		// time() returns the timestamp of the current time on the system
		$this->db->insert($this->posts_table,array('url' => $url,'title' => $title,'user' => $user,'timestamp' => time()));
	}

	/**
	 * get data for a post, including comments
	 */
	function get($postid)
	{
		$this->db->where('id',$postid);
		$res = $this->db->get($this->posts_table);
		if ($res->num_rows() == 0) {
			return false;
		}
		$post = $res->row();
		$res->free_result();

		// now get the comments if there are any
		if ($post->comments > 0) {
			$this->db->where('postid',$postid);
			$res = $this->db->get($this->comments_table);
			$comments = array();
			foreach ($res->result_array() as $row) {
				// put comments into array using their id as index
				$comments[$row['id']] = $row;
			}
			$threads = $this->threadComments($comments);
		}
		else {
			$comments = false;
			$threads = false;
		}

		$post->commentData = array('comments' => $comments,'threads' => $threads);

		return $post;
	}

	/**
	 * this builds a lookup table of comments ids and the ids of their children
	 * e.g., suppose we have the following comments and replies
	 * 1
	 * -  3
	 * -  4
	 *     - 5
	 *     - 6
	 *  - 7
	 * 8
	 *  - 9
	 * (so that 3,4 and 7 are chilren of 1, 5 and 6 are children of 4 etc.)
	 * Then the output of this function is an array like this:
	 * array(1 => [3,4,7],4 => [5,6],8 => [9])
	 * If an comment's index is not an index in this array, then it has no children (e.g., 5 and 6 are not indexes, because
	 * they have no children).
	 * This data structure will be used in the view in a function called printComments to build the threaded display of comments.
	 */
	private function threadComments($cs)
	{
		$threads = array();
		foreach ($cs as $c) {
			$parent = $c['parentid'];
			if (!isset($threads[$parent])) {
				$threads[$parent] = array();
			}
			$threads[$parent][] = $c['id'];
		}
		return $threads;
	}

	function comment($pid,$c,$u,$parent)
	{
		$this->db->insert($this->comments_table,array('postid' => $pid,'comment' => $c,'user' => $u,'parentid' => $parent,'timestamp' => time()));
		
		// need third FALSE argument to stop field from being quoted in SQL query
		// see https://www.codeigniter.com/user_guide/database/query_builder.html#updating-data
		$this->db->set('comments', 'comments+1', FALSE);
		$this->db->where('id', $pid);
		$this->db->update($this->posts_table);


		return true;
	}

	/**
	 * record a vote for a post (up or down)
	 */
	function postvote($postid,$vtype)
	{
		if ($vtype == 'up') {
			$this->db->set('upvotes','upvotes+1',FALSE);
		}
		else {
			$this->db->set('downvotes','downvotes+1',FALSE);
		}
		$this->db->where('id',$postid);
		$this->db->update($this->posts_table);
		return true;
	}
	/**
	 * record a vote for a comment (up or down)
	 */
	function commentvote($postid,$vtype)
	{
		if ($vtype == 'up') {
			$this->db->set('upvotes','upvotes+1',FALSE);
		}
		else {
			$this->db->set('downvotes','downvotes+1',FALSE);
		}
		$this->db->where('id',$postid);
		$this->db->update($this->comments_table);
		return true;
	}
}
