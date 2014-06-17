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
			$data['userInfo'] = & $this->_userInfo;

			// 页面标题
			$titles = array(
				'index' => '用户首页',
				'add' => '上传',
				'profiled' => $this->_userInfo['email'] . ' - 个人资料'
			);

			$data['title'] = (isset($titles[$this->router->method])) ? $titles[$this->router->method] : '';
			unset($titles);
			// 加载头部模板
			$this->load->view('layout/header', $data);
		}
	}

	/**
	 * 用户首页
	 *
	 */
	public function index()
	{
		$this->load->view('user/index');
	}

	/**
	 * 上传
	 *
	 */
	public function add()
	{
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
		show_error('提交成功能，请耐心等待审核，请<a href="' . base_url('/user/add') . '">返回</a>');
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
		$this->load->library('string');
		// 用户目录 /00/00/00/01/2014/06
		$fileDir = $this->string->makeUserDir($this->_userInfo['userid']) . DS . mdate('%Y', TIME_NOW) . DS . mdate('%m', TIME_NOW) . DS . mdate('%d', TIME_NOW);
		;
		$targetDir = $this->config->item('attachment_dir') . DS . $fileDir;
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
		$file = $fileDir . DS . TIME_NOW . '.' . $ext;
		$filePath = $this->config->item('attachment_dir') . DS . $file;

		$chunk = isset($_REQUEST["chunk"]) ? (int) $_REQUEST["chunk"] : 0;
		$chunks = isset($_REQUEST["chunks"]) ? (int) $_REQUEST["chunks"] : 0;
		if (!$out = @fopen("{$filePath}.part", $chunks ? "ab" : "wb"))
		{
			echo json_encode(array('error' => array('code' => 102, 'message' => 'Failed to open output stream')));
		}

		if (!empty($_FILES))
		{
			if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"]))
			{
				echo json_encode(array('error' => array('code' => 103, 'message' => 'Failed to move uploaded file.')));
			}

			if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb"))
			{
				echo json_encode(array('error' => array('code' => 101, 'message' => 'Failed to open input stream.')));
			}
		}
		else
		{
			if (!$in = @fopen("php://input", "rb"))
			{
				echo json_encode(array('error' => array('code' => 101, 'message' => 'Failed to open input stream.')));
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

		echo json_encode(array('path' => $file));
	}

	/**
	 * 个人资料
	 *
	 */
	public function profiled()
	{
		$action = (int) $this->input->post('action');

		// 用户提交数据
		if ($action)
		{
			$this->load->library('form_validation');
			$this->form_validation->set_rules('username', '呢称', 'required|min_length[2]|max_length[16]|is_unique[users.username]');
			if(empty($this->_userInfo['urltoken']))
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
			$data['userInfo'] = & $this->_userInfo;
			$this->load->view('user/profiled', $data);
		}
	}

	/**
	 * 修改密码
	 *
	 */
	public function password()
	{
		$this->load->view('user/password');
	}

	/**
	 *  我的上传
	 *
	 */
	public function uploads()
	{
		$this->load->view('user/uploads');
	}

	/**
	 *  我的上传
	 *
	 */
	public function favorites()
	{
		$this->load->view('user/favorites');
	}

}