<?php

if (!defined("IN_ACE") || !($aceman->is_login())) die("Forbidden.");

if (!isset($_GET['filetype']) || !isset($_GET['location'])) die("Forbidden.");
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>提示</title>
<?php

if ($_GET['filetype'] == "file") {
	$aceman->del_file($_GET['location']);
}
else if ($_GET['filetype'] == "hdfs") {
	$aceman->hdfs_del($_GET['location']);
}
else if ($_GET['filetype'] == "chart") {
	$aceman->remove_chart($_GET['location']);
}

alertAndBack("删除成功！");

?>