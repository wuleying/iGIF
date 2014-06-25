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
		$this->_data['title'] = '审核图片';
		$this->load->view('layout/header', $this->_data);

		// 加载模型
		$this->load->model('Gifs');
		$this->_data['images'] = $this->Gifs->getGifs();

		$this->_data['users'] = array();
		// 获取用户ID
		if (!empty($this->_data['images']))
		{
			$userids = array();
			foreach ($this->_data['images'] as $gif)
			{
				$userids[] = $gif['userid'];
			}
			$userids = array_unique($userids);

			$this->_data['users'] = $this->Users->getUserInfoByIds($userids);
		}

		$this->load->view('admin/review', $this->_data);
	}

	/**
	 * 执行审核
	 *
	 * @param integer $id
	 * @param integer $status
	 *
	 */
	public function doreview($id, $status)
	{
		$id = (int) $id;
		$status = (int) $status;

		if (empty($id) || empty($status))
		{
			$this->string->outputJSON(array('code' => 400, 'message' => '参数不正确'));
		}

		// 加载模型
		$this->load->model('Gifs');
		if ($this->Gifs->reviewStatus($id, $status))
		{
			$this->string->outputJSON(array('code' => 200, 'message' => '审核成功'));
		}
		else
		{
			$this->string->outputJSON(array('code' => 401, 'message' => '审核失败'));
		}
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