<?php

if (!defined('BASEPATH'))
{
	exit('No direct script access allowed');
}

/**
 * 用户模型
 *
 */
class Gifs extends CI_Model
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
	 * 上传图片
	 *
	 * @param integer $userid
	 * @param string $path
	 * @param string $description
	 * @return integer
	 *
	 */
	public function newGif($userid, $path, $description)
	{
		$this->db->insert('gifs', array(
			'path' => $path,
			'description' => $description,
			'userid' => $userid,
			'dateline' => TIME_NOW
		));
		return $this->db->insert_id();
	}
}