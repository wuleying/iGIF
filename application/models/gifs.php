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

	/**
	 * 获取图片
	 *
	 * @param integer $status
	 * @return array
	 *
	 */
	public function getGifs($status = IMAGE_STATUS_PENDING, $orderby = '', $offset = 0, $limit = 20)
	{
		$query = $this->db->get_where('gifs', array(
			'status' => $status
				), $limit, $offset);

		if ($orderby)
		{
			$orderbyArray = explode(' ', $orderby);
			if (!empty($orderbyArray))
			{
				//$this->db->order_by($orderbyArray[0], $orderbyArray[1]);
			}
		}

		$gifs = array();
		foreach ($query->result() as $row)
		{
			$gifs[$row->gifid] = array(
				'userid' => $row->userid,
				'path' => $this->string->imageCacheUrl($row->path),
				'description' => $row->description,
				'dateline' => $row->dateline,
			);
		}
		return $gifs;
	}

	/**
	 * 获取图片数量
	 *
	 * @param integer $status
	 * @return integer
	 *
	 */
	public function getGifsTotal($status = IMAGE_STATUS_PENDING)
	{
		$this->db->select('COUNT(*) AS total');
		$this->db->where('status', $status);
		$query = $this->db->get('gifs');
		return (int) $query->row_array()['total'];
	}

	/**
	 * 审核图片
	 *
	 * @param integer $id
	 * @param integer $status
	 *
	 * @return integer
	 *
	 */
	public function reviewStatus($id, $status)
	{
		if (empty($id) || empty($status))
		{
			return 0;
		}
		$data['status'] = $status;
		if (IMAGE_STATUS_REVIEWED == $status)
		{
			$data['reviewdateline'] = TIME_NOW;
		}

		$this->db->where('gifid', $id);
		$this->db->update('gifs', $data);

		return $this->db->affected_rows();
	}

}