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
		return implode(DS, str_split(sprintf('%08d', $userid), 2));
	}

}