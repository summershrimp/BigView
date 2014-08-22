<?php

if (!defined("IN_ACE") || !($aceman->is_login())) die("Forbidden.");

if (!isset($_GET['direction']) || !isset($_GET['location'])) die("Forbidden.");
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>提示</title>
<?php

if ($_GET['direction'] == "server2hdfs") {
	$aceman->hdfs_put($_GET['location']);
}
else if ($_GET['direction'] == "hdfs2server") {
	$aceman->hdfs_get($_GET['location']);
}

alertAndBack("导入/导出成功！");

?>