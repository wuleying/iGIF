<?php

if (!defined('BASEPATH'))
{
	exit('No direct script access allowed');
}

/**
 * 用户登录控制器
 *
 */
class Signin extends CI_Controller
{

	/**
	 * 用户登录
	 *
	 */
	public function index()
	{
		$action = (int) $this->input->post('action', TRUE);
		$data['title'] = '用户登录';

		// 当用户提交数据
		if ($action)
		{
			$this->load->library('form_validation');
			$this->form_validation->set_rules('email', '邮箱', 'required|valid_email');
			$this->form_validation->set_rules('password', '密码', 'required');
			$this->form_validation->set_rules('email', '邮箱', 'callback_check_user');
			$this->form_validation->set_message('required', '请填写%s');
			$this->form_validation->set_message('valid_email', '邮箱格式不正确');
			$this->form_validation->set_message('check_user', '邮箱或密码不正确');

			// 登录失败
			if ($this->form_validation->run() == FALSE)
			{
				$this->load->view('layout/header', $data);
				$this->load->view('signin/index');
			}
			// 登录成功
			else
			{
				redirect(base_url('/user'));
			}
		}
		else
		{
			// 检查用户是否已经登录
			$email = $this->input->cookie('email');
			$password = $this->input->cookie('password');

			$this->load->model('Users');
			$userInfo = $this->Users->getUserByEmail($email);
			if (!empty($userInfo))
			{
				// 通过验证
				if ($userInfo['password'] == $password)
				{
					redirect(base_url('/user'));
				}
			}

			$data['userInfo'] = & $userInfo;
			// 加载头部模板
			$this->load->view('layout/header', $data);
			// 加载模板
			$this->load->view('signin/index');
		}
	}

	/**
	 * 检查用户信息
	 *
	 * @return boolean
	 *
	 */
	public function check_user()
	{
		$email = $this->input->post('email', TRUE);
		$password = $this->input->post('password', TRUE);

		if (empty($email) || empty($password))
		{
			show_error('非法请求');
		}

		// 加载模型
		$this->load->model('Users');
		$userInfo = $this->Users->getUserByEmail($email);
		if (empty($userInfo))
		{
			return FALSE;
		}

		// 封禁用户
		if(USER_GROUP_BAN == $userInfo['groupid'])
		{
			show_error('您已被封禁，没有权限访问此页面，<a href="' . base_url() . '">返回首页</a>');
		}

		// 检查密码
		$hashPassword = do_hash(do_hash($password) . $userInfo['salt']);

		if ($hashPassword == $userInfo['password'])
		{
			$this->input->set_cookie('email', $email, 0);
			$this->input->set_cookie('password', $hashPassword, 0);
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

}