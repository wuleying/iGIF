<?php

if (!defined('BASEPATH'))
{
	exit('No direct script access allowed');
}

/**
 * 用户组模型
 *
 */
class Groups extends CI_Model
{

	/**
	 * 构造函数
	 *
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * 获取所有用户组
	 *
	 * @return array
	 *
	 */
	public function getGroups()
	{
		$this->db->start_cache();
		$query = $this->db->get('groups');
		$this->db->stop_cache();
		return $query;
	}

}