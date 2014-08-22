<?php

if (!defined("IN_ACE")) die("Forbidden.");
if (!isset($_GET['action'])) die("Forbidden.");

switch ($_GET['action']) {
case "getUserInfo":
	if (!isset($_GET['uid']) || !($aceman->is_login() && $aceman->privileges[MAN_SYS])) die("Forbidden.");
	$return = $aceman->get_user_info($_GET['uid']);
	echo json_encode($return);
	break;
case "getGroupInfo":
	if (!isset($_GET['gid']) || !($aceman->is_login() && $aceman->privileges[MAN_SYS])) die("Forbidden.");
	$return = $aceman->get_group_info($_GET['gid']);
	echo json_encode($return);
	break;
case "getFile":
	if (!isset($_GET['filename'])) die("Forbidden.");
	echo $aceman->get_file($_GET['filename']);
	break;
default:
	die("Forbidden.");
	break;
}

?>