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
	 * @return integer
	 *
	 */
	public function newGif($userid, $path)
	{
		$this->db->insert('gifs', array(
			'path' => $path,
			'userid' => $userid,
			'dateline' => TIME_NOW
		));
		return $this->db->insert_id();
	}

	/**
	 * 更新图片说明
	 *
	 * @param integer $id
	 * @param string $description
	 *
	 */
	public function saveDescription($id, $description)
	{
		$this->db->where('gifid', $id);
		$this->db->update('gifs', array(
			'description' => $description
		));
	}

}