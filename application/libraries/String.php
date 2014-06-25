<?php

if (!defined('BASEPATH'))
{
	exit('No direct script access allowed');
}

class MY_String
{

	/**
	 * 根据用户ID生成用户附件目录
	 *
	 * @param integer $userid
	 * @return string
	 *
	 */
	public function makeUserDir($userid)
	{
		return implode('/', str_split(sprintf('%08d', $userid), 2));
	}

	/**
	 * 生成图片地址
	 *
	 * @param string $imagePath
	 * @return string
	 */
	public function imageCacheUrl($imagePath, $thubm = TRUE)
	{
		if ($thubm)
		{
			$imageInfo = pathinfo($imagePath);
			$imagePath = $imageInfo['dirname'] . '/' . $imageInfo['filename'] . '_thumb.' . $imageInfo['extension'];
		}

		return config_item('image_cache_url') . '/' . $imagePath;
	}

	/**
	 * 输出JSON格式数据
	 *
	 * @param array $data
	 *
	 */
	public function outputJSON($data)
	{
		echo json_encode($data);
		die();
	}

}