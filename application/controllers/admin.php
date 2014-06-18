<?php

if (!defined('BASEPATH'))
{
	exit('No direct script access allowed');
}

/**
 * 管理员控制器
 *
 */
class Admin extends CI_Controller
{

	// 用户信息
	private $_userInfo = array();
	// 模板数据
	private $_data = array();

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

		// 加载模型
		$this->load->model('Users');

		$this->_userInfo = $this->Users->getUserByEmail($email);
		if (empty($this->_userInfo) || $this->_userInfo['password'] != $password || USER_GROUP_ADMIN != $this->_userInfo['groupid'])
		{
			show_error('您没有权限访问此页面');
		}
		else
		{
			$this->_data['userInfo'] = & $this->_userInfo;
		}
	}

	/**
	 * 审核图片
	 *
	 */
	public function review()
	{
		// 加载模型
		$this->load->model('Gifs');
		$this->_data['gifs'] = $this->Gifs->getGifs();

		$this->_data['title'] = '审核图片';
		$this->load->view('layout/header', $this->_data);
		$this->load->view('admin/review', $this->_data);
	}

	/**
	 * 管理用户
	 *
	 */
	public function users()
	{
		$this->_data['title'] = '管理用户';
		$this->load->view('layout/header', $this->_data);
		$this->load->view('user/index');
	}
}