<?php

if (!defined('BASEPATH'))
{
	exit('No direct script access allowed');
}

/**
 * 用户注销
 *
 */
class Logout extends CI_Controller
{

	/**
	 * 用户注销
	 *
	 */
	public function index()
	{
		delete_cookie('email');
		delete_cookie('password');
		redirect(base_url('/signin'));
	}

}