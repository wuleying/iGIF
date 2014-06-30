<?php

if (!defined('BASEPATH'))
{
	exit('No direct script access allowed');
}

/**
 * 用户模型
 *
 */
class Users extends CI_Model
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
	 * 新用户注册
	 *
	 * @param string $email
	 * @param string $password
	 */
	public function newUser($email, $password)
	{
		$salt = random_string('alnum', 4);
		$hashPassword = do_hash(do_hash($password) . $salt);

		$this->db->insert('users', array(
			'email' => $email,
			'groupid' => USER_GROUP_GENERAL,
			'password' => $hashPassword,
			'salt' => $salt,
			'registertime' => TIME_NOW
		));
	}

	/**
	 * 根据邮箱获取用户信息
	 *
	 * @param string $email
	 * @return array
	 *
	 */
	public function getUserByEmail($email)
	{
		if (empty($email))
		{
			return array();
		}

		$query = $this->db->get_where('users', array(
			'email' => $email
				), 1);
		return $query->row_array();
	}

	/**
	 * 根据用户名获取用户信息
	 *
	 * @param string $username
	 * @return array
	 *
	 */
	public function getUserByName($username)
	{
		if (empty($username))
		{
			return array();
		}

		$query = $this->db->get_where('users', array(
			'username' => $username
				), 1);
		return $query->row_array();
	}

	/**
	 * 根据用户ID获取用户信息
	 *
	 * @param type $userids
	 * @return type
	 *
	 */
	public function getUserInfoByIds($userids)
	{
		if (empty($userids))
		{
			return array();
		}

		$this->db->select('userid, groupid, username, email, urltoken');
		$this->db->where_in('userid', $userids);
		$query = $this->db->get('users');
		$users = array();
		foreach ($query->result() as $row)
		{
			$users[$row->userid] = $row;
		}
		return $users;
	}

	/**
	 * 保存用户信息
	 *
	 * @param integer $userid
	 * @param string $username
	 * @param string $urltoken
	 * @return
	 *
	 */
	public function saveUserProfiled($userid, $username, $urltoken = '')
	{
		if (empty($userid) || empty($username))
		{
			return;
		}

		$data['username'] = $username;
		if ($urltoken)
		{
			$data['urltoken'] = $urltoken;
		}

		$this->db->where('userid', $userid);
		$this->db->update('users', $data);
	}

	/**
	 * 修改密码
	 *
	 * @param integer $userid
	 * @param string $password
	 * @return
	 *
	 */
	public function changePassword($userid, $password)
	{
		if (empty($userid) || empty($password))
		{
			return;
		}
		$data['password'] = $password;
		$this->db->where('userid', $userid);
		$this->db->update('users', $data);
	}

	/**
	 * 修改用户组
	 *
	 * @param integer $userid
	 * @param integer $groupid
	 * @return
	 *
	 */
	public function changeGroup($userid, $groupid)
	{
		if (empty($userid) || empty($groupid))
		{
			return;
		}
		$data['groupid'] = $groupid;
		$this->db->where('userid', $userid);
		$this->db->update('users', $data);
	}

}