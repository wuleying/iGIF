<?php

if (!defined('BASEPATH'))
{
	exit('No direct script access allowed');
}

/**
 * 首页控制器
 *
 */
class Home extends CI_Controller
{
	/**
	 * 首页
	 *
	 */
	public function index()
	{
		$this->load->view('home/index');
	}

	/**
	 * 热门
	 *
	 */
	public function hot()
	{
		$this->load->view('home/hot');
	}
}