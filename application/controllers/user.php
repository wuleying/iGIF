<?php

if (!defined('BASEPATH'))
{
	exit('No direct script access allowed');
}

/**
 * 用户控制器
 *
 */
class User extends CI_Controller
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
		$this->load->model('Gifs');

		$this->_userInfo = $this->Users->getUserByEmail($email);
		if (empty($this->_userInfo) || $this->_userInfo['password'] != $password)
		{
			redirect(base_url('/signin'));
		}
		else
		{
			// 封禁用户
			if (USER_GROUP_BAN == $this->_userInfo['groupid'])
			{
				redirect(base_url('/logout'));
			}

			$this->_data['userInfo'] = & $this->_userInfo;
		}
	}

	/**
	 * 用户首页
	 *
	 */
	public function index()
	{
		$this->_data['title'] = '用户首页';
		$this->load->view('layout/header', $this->_data);
		$this->load->view('user/index');
	}

	/**
	 * 上传
	 *
	 */
	public function add()
	{
		$this->_data['title'] = '上传';
		$this->load->view('layout/header', $this->_data);
		$this->load->view('user/add');
	}

	/**
	 * 提交数据
	 *
	 */
	public function doadd()
	{
		$path = $this->input->post('path');
		$description = $this->input->post('description');

		if (empty($path))
		{
			show_error('请上传图片，请<a href="' . base_url('/user/add') . '">返回</a>');
		}

		if (empty($description))
		{
			show_error('请填写简介，请<a href="' . base_url('/user/add') . '">返回</a>');
		}

		$gifid = $this->Gifs->newGif($this->_userInfo['userid'], $path, $description);

		if (empty($gifid))
		{
			show_error('提交失败，请<a href="' . base_url('/user/add') . '">返回</a>');
		}
		else
		{
			redirect('/user/addsuccess');
		}
	}

	/**
	 * 提交成功
	 *
	 */
	public function addsuccess()
	{
		show_error('提交成功，请耐心等待审核，<a href="' . base_url('/user/add') . '">返回</a>');
	}

	/**
	 * 上传图片
	 *
	 */
	public function upload()
	{
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		@set_time_limit(300);
		// 用户目录 /00/00/00/01/2014/06
		$fileDir = $this->string->makeUserDir($this->_userInfo['userid']) . '/' . mdate('%Y/%m/%d', TIME_NOW);

		$targetDir = $this->config->item('attachment_dir') . '/' . $fileDir;
		if (!file_exists($targetDir))
		{
			@mkdir($targetDir, DIR_READ_MODE, TRUE);
		}
		if (isset($_REQUEST["name"]))
		{
			$ext = pathinfo($_REQUEST["name"], PATHINFO_EXTENSION);
		}
		elseif (!empty($_FILES))
		{
			$ext = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
		}

		// 文件格式不正确
		if (!in_array($ext, $this->config->item('image_allow_extension')))
		{
			echo json_encode(array('error' => array('code' => 104, 'message' => 'File extension error')));
			exit();
		}

		$file = $fileDir . '/' . TIME_NOW . '.' . $ext;
		$filePath = $this->config->item('attachment_dir') . '/' . $file;

		$chunk = isset($_REQUEST["chunk"]) ? (int) $_REQUEST["chunk"] : 0;
		$chunks = isset($_REQUEST["chunks"]) ? (int) $_REQUEST["chunks"] : 0;
		if (!$out = @fopen("{$filePath}.part", $chunks ? "ab" : "wb"))
		{
			echo json_encode(array('error' => array('code' => 102, 'message' => 'Failed to open output stream')));
			exit();
		}

		if (!empty($_FILES))
		{
			if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"]))
			{
				echo json_encode(array('error' => array('code' => 103, 'message' => 'Failed to move uploaded file.')));
				exit();
			}

			if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb"))
			{
				echo json_encode(array('error' => array('code' => 101, 'message' => 'Failed to open input stream.')));
				exit();
			}
		}
		else
		{
			if (!$in = @fopen("php://input", "rb"))
			{
				echo json_encode(array('error' => array('code' => 101, 'message' => 'Failed to open input stream.')));
				exit();
			}
		}

		while ($buff = fread($in, 4096))
		{
			fwrite($out, $buff);
		}

		@fclose($out);
		@fclose($in);

		if (!$chunks || $chunk == $chunks - 1)
		{
			rename("{$filePath}.part", $filePath);
		}

		// 生成缩略图
		$config['image_library'] = 'gd2';
		$config['source_image'] = $filePath;
		$config['create_thumb'] = TRUE;
		$config['maintain_ratio'] = TRUE;
		$config['width'] = 300;
		$this->load->library('image_lib', $config);
		if (!$this->image_lib->resize())
		{
			echo $this->image_lib->display_errors();
		}

		echo json_encode(array('path' => $file));
	}

	/**
	 * 个人资料
	 *
	 */
	public function profiled()
	{
		$action = (int) $this->input->post('action');
		$this->_data['title'] = '账号';

		// 用户提交数据
		if ($action)
		{
			$this->load->library('form_validation');
			$this->form_validation->set_rules('username', '呢称', 'required|min_length[2]|max_length[16]|is_unique[users.username]');
			if (empty($this->_userInfo['urltoken']))
			{
				$this->form_validation->set_rules('urltoken', '个性网址', 'required|min_length[4]|max_length[32]|alpha|is_unique[users.urltoken]');
			}
			$this->form_validation->set_message('required', '请填写%s');
			$this->form_validation->set_message('min_length', '%s小于%s个字符，请重新填写');
			$this->form_validation->set_message('max_length', '%s大于%s个字符，请重新填写');
			$this->form_validation->set_message('alpha', '%s只允许由英文字母组成，请重新填写');
			$this->form_validation->set_message('is_unique', '此%s已经存在，请重新填写');



			if ($this->form_validation->run() == FALSE)
			{
				$this->load->view('layout/header', $this->_data);
				$this->load->view('user/profiled');
			}
			else
			{
				$username = $this->input->post('username', TRUE);
				$urltoken = $this->input->post('urltoken', TRUE);

				// 加载模型
				$this->load->model('Users');
				// 更新用户信息
				$this->Users->saveUserProfiled($this->_userInfo['userid'], $username, $urltoken);

				redirect('/user/profiled');
			}
		}
		else
		{
			$this->load->view('layout/header', $this->_data);
			$this->load->view('user/profiled', $this->_data);
		}
	}

	/**
	 * 修改密码
	 *
	 */
	public function password()
	{
		$action = (int) $this->input->post('action');

		$this->_data['title'] = '密码';

		// 用户提交数据
		if ($action)
		{
			$this->load->library('form_validation');
			$this->form_validation->set_rules('oldpassword', '旧密码', 'callback_check_password');
			$this->form_validation->set_rules('newpassword', '新密码', 'required|min_length[6]|max_length[16]');
			$this->form_validation->set_rules('repassword', '重复密码', 'required|min_length[6]|max_length[16]|matches[newpassword]');
			$this->form_validation->set_message('check_password', '旧密码不匹配，请重新填写');
			$this->form_validation->set_message('required', '请填写%s');
			$this->form_validation->set_message('min_length', '%s小于%s个字符，请重新填写');
			$this->form_validation->set_message('max_length', '%s大于%s个字符，请重新填写');
			$this->form_validation->set_message('matches', '两次密码输入不一致');

			if ($this->form_validation->run() == FALSE)
			{
				$this->load->view('layout/header', $this->_data);
				$this->load->view('user/password');
			}
			else
			{
				show_error('修改密码成功，请<a href="' . base_url('/signin') . '">重新登录</a>');
			}
		}
		else
		{
			$this->load->view('layout/header', $this->_data);
			$this->load->view('user/password');
		}
	}

	/**
	 * 检查用户密码
	 *
	 */
	public function check_password()
	{
		$oldPassword = $this->input->post('oldpassword', TRUE);
		if (empty($oldPassword))
		{
			return FALSE;
		}

		// 检查密码
		$hashPassword = do_hash(do_hash($oldPassword) . $this->_userInfo['salt']);
		if ($hashPassword == $this->_userInfo['password'])
		{
			// 加载模型
			$this->load->model('Users');
			$newPassword = $this->input->post('newpassword', TRUE);
			$hashNewPassword = do_hash(do_hash($newPassword) . $this->_userInfo['salt']);
			$this->Users->changePassword($this->_userInfo['userid'], $hashNewPassword);

			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	/**
	 *  我的上传
	 *
	 */
	public function uploads()
	{
		$this->_data['title'] = '我的上传';
		$this->load->view('layout/header', $this->_data);
		$this->load->view('user/uploads');
	}

	/**
	 *  我的收藏
	 *
	 */
	public function favorites()
	{
		$this->_data['title'] = '我的收藏';
		$this->load->view('layout/header', $this->_data);
		$this->load->view('user/favorites');
	}

}