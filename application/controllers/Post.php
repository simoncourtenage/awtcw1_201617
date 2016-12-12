<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Post extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model('Postmodel');
	}

	/**
	 * display home page
	 */
	function index()
	{
		// which page of links should we display?
		$page = $this->input->get('pg');
		// if no page specified, then display first page
		if ($page == false) {
			$page = 0;
		}
		$posts = $this->Postmodel->posts($page);
		$this->load->view('postsview',array('posts' => $posts,'page' => $page));
	}

	function add()
	{
		$this->load->view('addview');
	}

	/**
	 * add a new link
	 */
	function post()
	{
		// POST means we are adding data
		if ($this->input->method(TRUE) == 'POST') {
			$url = $this->input->post('url');
			$user = $this->input->post('user');
			$title = $this->input->post('title');
			$this->Postmodel->addpost($url,$title,$user);
			$this->load->view('addedview');
		}
		elseif ($this->input->method(TRUE) == 'GET') {
			// GET means we are fetching data
			// post id passed as part of the URL after the name of the controller
			// and the action.  We could also use query string e.g., '?postid=XXX'
			$postid = $this->uri->segment(3);
			$post = $this->Postmodel->get($postid);
			$this->load->view('postview',array('post' => $post));
		}
		else {
			print 'Operation not supported';
		}
	}

	function comment()
	{
		$postid = $this->uri->segment(3);
		$comment = $this->input->post('commenttext');
		$user = $this->input->post('user');
		$parent = $this->input->get('parent');
		if ($parent == false) {
			// top-level comment
			$parent = -1;
		}
		$this->Postmodel->comment($postid,$comment,$user,$parent);
		// after we've added the comment, send them back to the post page again
		redirect('/Post/post/' . $postid);
	}

	function vote()
	{
		$postid = $this->input->get('pid');
		$votetype = $this->input->get('v');
		$this->Postmodel->postvote($postid,$votetype);
		redirect("/Post/");
	}

	function commentvote()
	{
		$postid = $this->input->get('pid');
		$commentid = $this->input->get('cid');
		$votetype = $this->input->get('v');
		$this->Postmodel->commentvote($commentid,$votetype);
		redirect("/Post/post/$postid");
	}
} 