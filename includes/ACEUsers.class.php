<?php
require_once 'ACEGroups.class.php';

class ACEUsers extends ACEGroups
{
	protected $user_info = NULL;
	
	public $privileges = NULL;
	
	function ACEUsers()
	{
		if(isset($_SESSION['user_info']))
		{
			$this->user_info = $_SESSION['user_info'];
			$this->privileges = $_SESSION['privileges'];
		}
	}
	
	public function login($username, $password)
	{
		$sql = "Select * From `users` Where `username` = '$username' and `passwd` = '".md5($password)."' LIMIT 1";
		$arr = $GLOBALS['db']->getRow($sql);
		if($arr['user_id'])
		{
			unset($arr['passwd']);
			$this->user_info = $arr;
			$_SESSION['user_info'] = $arr;
			$this->privileges = $this->get_privileges();
			$_SESSION['privileges'] = $this->privileges;
			return true;
		}
		else return false;
	}
	
	public function get_privileges()
	{
		$priv = $this->group_privileges($this->user_info['user_group']);
		$upr = $this->user_info['privileges'];
		$upl = strlen($upr);
		for($i = 0; $i < $upl; $i++)
			$priv[substr($upr, $i, 1)] = true;
		return $priv;
	}
	
	public function is_login()
	{
		if(isset($_SESSION['user_info']['user_id'])&&isset($this->user_info['user_id']))
			return $this->user_info['user_id'];
		else return false;
	}
	
	public function logout()
	{
		unset($_SESSION['user_info']);
		$this->user_info = NULL;
	}
}
?>