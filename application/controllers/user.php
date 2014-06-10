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

	public function __construct()
	{
		parent::__construct();

		// 检查用户是否已登录
		$email = $this->input->cookie('email');
		$password = $this->input->cookie('password');

		$this->load->model('Users');
		$this->_userInfo = $this->Users->getUserByEmail($email);
		if (empty($this->_userInfo) || $this->_userInfo['password'] != $password)
		{
			redirect(base_url('/signin'));
		}
	}

	/**
	 * 用户首页
	 *
	 */
	public function index()
	{
		$data['title'] = '用户首页';
		$this->load->view('user/index', $data);
	}

	/**
	 * 上传
	 *
	 */
	public function add()
	{
		$data['title'] = '上传';
		$this->load->view('user/add', $data);
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
		$targetDir = $this->config->item('attachment_dir') . DS . $this->string->makeUserDir($this->_userInfo['userid']);
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

		$filePath = $targetDir . DS . time() . '.' . $ext;

		$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
		$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;

		if (!$out = @fopen("{$filePath}.part", $chunks ? "ab" : "wb"))
		{
			die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
		}

		if (!empty($_FILES))
		{
			if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"]))
			{
				die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
			}

			if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb"))
			{
				die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
			}
		}
		else
		{
			if (!$in = @fopen("php://input", "rb"))
			{
				die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
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

		die('{"jsonrpc" : "2.0", "result" : ' . $filePath . ', "id" : "id"}');
	}

	/**
	 * 个人资料
	 *
	 */
	public function profiled()
	{
		$data['title'] = '个人资料';
		$this->load->view('user/profiled', $data);
	}

}