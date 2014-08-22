<?php

if (!defined("IN_ACE") || !($aceman->is_login() && $aceman->privileges[MAN_SYS])) die("Forbidden.");

if (!isset($_GET['action'])) die("Forbidden.");

?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>提示</title>
<?php

switch ($_GET['action']) {
case "adduser":
	if (
		!isset($_POST['ace_username']) || !isset($_POST['ace_defpassword']) ||
		!isset($_POST['ace_reppassword']) || !isset($_POST['ace_usergroup']) ||
		!isset($_POST['ace_priv'])
	) alertAndBack("请将信息填写完整！");
	if ($_POST['ace_defpassword'] != $_POST['ace_reppassword']) alertAndBack("两次输入的密码不一致！");
	$user = array();
	$user['username'] = $_POST['ace_username'];
	$user['passwd'] = md5($_POST['ace_defpassword']);
	$user['user_group'] = $_POST['ace_usergroup'];
	$user['privileges'] = "";
	foreach ($_POST['ace_priv'] as $key => $value) $user['privileges'] .= $value;
	$aceman->add_user($user);
	break;
case "edituser":
	if (!isset($_GET['uid'])) die("Forbidden.");
	if (isset($_POST['ace_defpassword']) && ($_POST['ace_defpassword'] != $_POST['ace_reppassword'])) alertAndBack("两次输入的密码不一致！");
	$user = array();
	if (isset($_POST['ace_username'])) $user['username'] = $_POST['ace_username'];
	if (isset($_POST['ace_defpassword'])) $user['passwd'] = md5($_POST['ace_defpassword']);
	if (isset($_POST['ace_usergroup'])) $user['user_group'] = $_POST['ace_usergroup'];
	if (isset($_POST['ace_priv'])) {
		$user['privileges'] = "";
		foreach ($_POST['ace_priv'] as $key => $value) $user['privileges'] .= $value;
	}
	$aceman->change_user($_GET['uid'], $user);
	break;
case "deluser":
	if (!isset($_GET['uid'])) die("Forbidden.");
	$aceman->del_user($_GET['uid']);
	break;
case "addusergroup":
	if (!isset($_POST['ace_groupname']) || !isset($_POST['ace_priv'])) alertAndBack("请将信息填写完整！");
	$group = array();
	$group['groupname'] = $_POST['ace_groupname'];
	$group['privileges'] = "";
	foreach ($_POST['ace_priv'] as $key => $value) $group['privileges'] .= $value;
	$aceman->add_group($group);
	break;
case "editusergroup":
	if (!isset($_GET['gid'])) die("Forbidden.");
	$group = array();
	if (isset($_POST['ace_groupname'])) $group['groupname'] = $_POST['ace_groupname'];
	if (isset($_POST['ace_priv'])) {
		$group['privileges'] = "";
		foreach ($_POST['ace_priv'] as $key => $value) $group['privileges'] .= $value;
	}
	$aceman->change_group($_GET['gid'], $group);
	break;
case "delgroup":
	if (!isset($_GET['gid'])) die("Forbidden.");
	$aceman->del_group($_GET['gid']);
	break;
default:
	die("Forbidden.");
	break;
}

alertAndBack("操作成功！");

?>