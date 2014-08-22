<?php if (!defined("IN_ACE") || !($aceman->is_login())) die("Forbidden."); ?>
<!doctype html>
<html lang="zh">
<head>
	<title>文件管理 - Ace数据可视化系统</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css"/>
	<link href="bower_components/font-awesome/css/font-awesome.css" rel="stylesheet">
	<link rel="stylesheet" href="css/raw.css"/>
	<link rel="stylesheet" href="css/popups.css"/>
	<link rel="icon" href="favicon.ico?v=2" type="image/x-icon">
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<style>
		th:nth-child(2){
			width:20em;
		}
	</style>
	<script>
		function createFileUploadForm(){
			var wall=document.createElement("div");
			wall.id="popup_wall";
			wall.innerHTML="<table id='popup_table'><tr><td><div id='popup'><div class='title'>上传文件</div><div class='text'>"+
			"<form action='?page=upload&filetype=file' method='post' enctype='multipart/form-data'>"+
				"<table><tr><td><input type='file' id='file' name='ace_file' required /></tr><tr><td align='right'><input class='btn btn-success' type='submit' value='确定' /> <input class='btn btn-warning' type='button' value='取消' onclick='cancelPopup()' /></td></tr></table></form>"+
			"</div></div></td></tr></table>";
			document.body.appendChild(wall);
			document.getElementById("file").focus();
		}
		function createRunWindow(){
			var wall=document.createElement("div");
			wall.id="popup_wall";
			wall.innerHTML="<table id='popup_table'><tr><td><div id='popup'><div class='title'>运行参数设置</div><div class='text'>"+
			"<form action='?page=run' method='post' enctype='multipart/form-data'>"+
				"<table><tr><td><input type='text' id='text' name='ace_param' /></tr><tr><td align='right'><input class='btn btn-success' type='submit' value='确定' /> <input class='btn btn-warning' type='button' value='取消' onclick='cancelPopup()' /></td></tr></table></form>"+
			"</div></div></td></tr></table>";
			document.body.appendChild(wall);
			document.getElementById("text").focus();
		}
		function pushToFront(filename){
			sessionStorage.setItem("data-filename",filename);
			window.top.location.href="./";
		}
	</script>
	<script src="js/functions.js"></script>
</head>
<body data-spy="scroll" data-target="#raw-nav">
	<nav class="navbar" role="navigation" id="raw-nav">
		<div class="container autowidth">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#raw-navbar">
					<span class="sr-only">切换导航</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<span>Ace数据可视化系统</span>
			</div>
			<div class="collapse navbar-collapse navbar-right" id="raw-navbar">
				<ul class="nav navbar-nav">
					<li><a onclick="window.top.location.href='./'">主页</a></li>
					<li><a onclick="window.top.location.href='?page=manfile'" class="curpage">文件管理</a></li>
					<li><a onclick="window.top.location.href='?page=manchart'">图表管理</a></li>
						<?php if ($aceman->privileges[MAN_SYS]) { ?>
						<li><a onclick="window.top.location.href='?page=admin'">后台管理</a></li>
						<?php } ?>
					<li><a onclick="window.top.location.href='?page=logout'">注销</a></li>
				</ul>
			</div>
		</div>
	</nav>
	<div id="maincont" class="col-lg-12">
		<section>
			<h3 style="margin-left:0.75em">
				<i class="fa fa-file-o"></i>
				文件管理
				<button class="btn btn-success" style="margin-left:1em" onclick="createFileUploadForm()">上传文件</button>
			</h3>
			<div class="col-lg-6">
				<h4><strong>服务器端文件</strong></h4>
				<table class="table table-hover">
					<thead><th>文件</th><th>操作</th></thead>
					<tbody>
					<?php
						$ftp_files = $aceman->list_ftp_dir("/");
						foreach ($ftp_files as $filename => $type) {
							echo "<tr><td>" . $filename . "</td><td>" .
								"<button class='btn btn-danger' onclick='createWindow(\"?page=delfile&filetype=file&location=" . $filename . "\",\"确定要删除 " . $filename . " ？\")'>删除</button>" .
								" <button class='btn btn-warning' onclick='createWindow(\"?page=transfer&direction=server2hdfs&location=" . $filename . "\",\"确定将 " . $filename . " 导入到HDFS？\")'>导入到HDFS</button>";
							if (
								strtolower(substr($filename, -4)) == ".jar" ||
								strtolower(substr($filename, -2)) == ".r"
							)
								echo " <button class='btn btn-success' onclick='createRunWindow()'>运行程序</button>";
							else if (
								strtolower(substr($filename, -4)) == ".csv" ||
								strtolower(substr($filename, -4)) == ".txt"
							)
								echo " <button class='btn btn-success' onclick='pushToFront(\"" . $filename . "\")'>导出到前台</button>";
							echo "</td></tr>";
						}
					?>
					</tbody>
				</table>
			</div>
			<div class="col-lg-6">
				<h4><strong>HDFS文件</strong></h4>
				<table class="table table-hover">
					<thead><th>文件</th><th>操作</th></thead>
					<tbody>
					<?php
						$hdfs_files = $aceman->list_hdfs_dir("/");
						foreach ($hdfs_files as $filename => $type) {
							echo "<tr><td>" . $filename . "</td><td>" .
								"<button class='btn btn-danger' onclick='createWindow(\"?page=delfile&filetype=hdfs&location=" . $filename . "\",\"确定要删除 " . $filename . " ？\")'>删除</button>" .
								" <button class='btn btn-warning' onclick='createWindow(\"?page=transfer&direction=hdfs2server&location=" . $filename . "\",\"确定将 " . $filename . " 从HDFS导出？\")'>从HDFS导出</button>";
								"</td></tr>";
						}
					?>
					</tbody>
				</table>
			</div>
		</section>
	</div>
</body>
</html>