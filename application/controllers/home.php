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
	 * 构造函数
	 *
	 */
	public function __construct()
	{
		parent::__construct();

		// 检查用户是否已登录
		$email = $this->input->cookie('email');
		$password = $this->input->cookie('password');

		$this->load->model('Users');
		$this->_userInfo = $this->Users->getUserByEmail($email);
		$data['userInfo'] = array();
		if ($this->_userInfo && $this->_userInfo['password'] == $password)
		{
			$data['userInfo'] = $this->_userInfo;
		}

		// 页面标题
		$titles = array(
			'index' => '最新',
			'hot' => '热门'
		);
		$data['title'] = $titles[$this->router->method];
		unset($titles);

		// 加载头部模板
		$this->load->view('layout/header', $data);
	}

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