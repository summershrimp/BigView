<?php

if (!defined("IN_ACE") || !($aceman->is_login())) die("Forbidden.");

if (!isset($_GET['filetype']) || !isset($_FILES['ace_file'])) die("Forbidden.");
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>提示</title>
<?php

if ($_GET['filetype'] == "file") {
	$aceman->upload($_FILES['ace_file']);
}
else if ($_GET['filetype'] == "chart") {
	if (!isset($_POST['ace_filename']) || !isset($_POST['ace_desc'])) die("Forbidden.");
	$aceman->upload_chart($_POST['ace_filename'], $_POST['ace_desc'], $_FILES['ace_file']['tmp_name'], $_FILES['ace_file']['size']);
}

alertAndBack("上传成功！");

?>