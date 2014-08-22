<?php

if (!defined("IN_ACE")) die("Forbidden.");

?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>提示</title>
<?php

isset($_POST['ace_username']) ? ($username = $_POST['ace_username']) : alertAndBack("请将信息填写完整！");
isset($_POST['ace_password']) ? ($password = $_POST['ace_password']) : alertAndBack("请将信息填写完整！");
if (!$aceman->login($username, $password)) alertAndBack("用户名或密码不正确！");

echo "<script>window.top.location.href='./';</script>";

?>