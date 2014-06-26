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
		if ($this->_userInfo && $this->_userInfo['password'] == $password)
		{
			$this->_data['userInfo'] = & $this->_userInfo;
		}
	}

	/**
	 * 首页
	 *
	 */
	public function index($page = 1)
	{
		$this->_data['title'] = '最新';
		$this->load->view('layout/header', $this->_data);

		// 分页
		$page = (int) max(1, $page);
		$this->load->library('pagination');
		$pageConfig = config_item('pagination');
		$pageConfig['base_url'] = base_url('home/index/');

		// 获取图片数量
		$pageConfig['total_rows'] = $this->Gifs->getGifsTotal(IMAGE_STATUS_REVIEWED);

		// 计算偏移量
		$offset = ($page - 1) * $pageConfig['per_page'];

		// 最新数据
		$this->_data['images'] = $this->Gifs->getGifs(IMAGE_STATUS_REVIEWED, 'reviewdateline DESC', $offset, $pageConfig['per_page']);
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

		$this->pagination->initialize($pageConfig);
		$this->_data['pagination'] = $this->pagination->create_links();

		$this->load->view('home/index', $this->_data);
	}

	/**
	 * 热门
	 *
	 */
	public function hot($page = 1)
	{
		$this->_data['title'] = '热门';
		$this->load->view('layout/header', $this->_data);

		// 分页
		$page = (int) max(1, $page);
		$this->load->library('pagination');
		$pageConfig = config_item('pagination');
		$pageConfig['base_url'] = base_url('home/index/');

		// 获取图片数量
		$pageConfig['total_rows'] = $this->Gifs->getGifsTotal(IMAGE_STATUS_REVIEWED);

		// 计算偏移量
		$offset = ($page - 1) * $pageConfig['per_page'];

		// 最新数据
		$this->_data['images'] = $this->Gifs->getGifs(IMAGE_STATUS_REVIEWED, 'reviewdateline DESC', $offset, $pageConfig['per_page']);
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

		$this->pagination->initialize($pageConfig);
		$this->_data['pagination'] = $this->pagination->create_links();

		$this->load->view('home/index', $this->_data);
	}

	/**
	 * 查看图片
	 *
	 * @param integer $id
	 *
	 */
	public function view($id)
	{
		$this->_data['title'] = '';
		$this->load->view('layout/header', $this->_data);
		$this->load->view('home/view');
	}

	public function people($username)
	{
		$this->_data['title'] = '';
		$this->load->view('layout/header', $this->_data);
		$this->load->view('home/people');
	}

}