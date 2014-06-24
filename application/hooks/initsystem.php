<?php

if (!defined('BASEPATH'))
{
	exit('No direct script access allowed');
}

/**
 * 初始化系统
 *
 */
class InitSystem extends CI_Controller
{

	/**
	 * 初始化系统
	 *
	 */
	public function index()
	{
		// 设置时区
		date_default_timezone_set('PRC');

		// 当前时间
		define('TIME_NOW', now());
	}

}