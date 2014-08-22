<?php

if (!defined("IN_ACE") || !($aceman->is_login())) die("Forbidden.");

?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>提示</title>
<?php

$filename = $_GET['filename'];
$param = (isset($_POST['ace_param']) ? $_POST['ace_param'] : NULL);

if (strtolower(substr($filename, -4)) == ".jar")
	$aceman->exec_jar($filename, $param);
else if (strtolower(substr($filename, -2)) == ".r")
	$aceman->exec_rscript($filename, $param);

alertAndBack("程序已运行。");

?>