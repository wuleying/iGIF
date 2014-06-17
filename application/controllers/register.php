<?php

if (!defined('BASEPATH'))
{
	exit('No direct script access allowed');
}

/**
 * 用户注册控制器
 *
 */
class Register extends CI_Controller
{

	/**
	 * 用户注册
	 *
	 */
	public function index()
	{
		$action = (int) $this->input->post('action', TRUE);

		// 当用户提交数据
		if ($action)
		{
			$this->load->library('form_validation');
			$this->form_validation->set_rules('email', '邮箱', 'required|valid_email|is_unique[users.email]');
			$this->form_validation->set_rules('password', '密码', 'required');
			$this->form_validation->set_rules('repassword', '重复密码', 'required|matches[password]');
			$this->form_validation->set_message('required', '请填写%s');
			$this->form_validation->set_message('valid_email', '邮箱格式不正确');
			$this->form_validation->set_message('is_unique', '此%s已经注册，请 <a href="' . base_url('/signin') . '">登录</a> 或 <a href="' . base_url('/resetpassword') . '">找回密码</a>');
			$this->form_validation->set_message('matches', '两次密码输入不一致');

			if ($this->form_validation->run() == FALSE)
			{
				$this->load->view('register/index');
			}
			else
			{
				$email = $this->input->post('email', TRUE);
				$password = $this->input->post('password', TRUE);

				// 加载模型
				$this->load->model('Users');
				// 注册新用户
				$this->Users->newUser($email, $password);
				$this->load->view('register/success');
			}
		}
		else
		{
			$data['title'] = '用户注册';
			// 加载头部模板
			$this->load->view('layout/header', $data);
			// 加载模板
			$this->load->view('register/index');
		}
	}

}