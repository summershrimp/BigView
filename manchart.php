<?php if (!defined("IN_ACE") || !($aceman->is_login())) die("Forbidden."); ?>
<!doctype html>
<html lang="zh">
<head>
	<title>图表管理 - Ace数据可视化系统</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css"/>
	<link href="bower_components/font-awesome/css/font-awesome.css" rel="stylesheet">
	<link rel="stylesheet" href="css/raw.css"/>
	<link rel="stylesheet" href="css/popups.css"/>
	<link rel="icon" href="favicon.ico?v=2" type="image/x-icon">
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<style>
		th:nth-child(3){
			width:6em;
		}
	</style>
	<script>
		function createChartUploadForm(){
			var wall=document.createElement("div");
			wall.id="popup_wall";
			wall.innerHTML="<table id='popup_table'><tr><td><div id='popup'><div class='title'>上传图表</div><div class='text'>"+
			"<form action='?page=upload&filetype=chart' method='post' enctype='multipart/form-data'>"+
				"<table><tr><td colspan='2'><input type='file' id='file' name='ace_file' required /></tr><tr><th>图表名称</th><td><input type='text' name='ace_filename' required /></td></tr><tr><th>图表描述</th><td><input type='text' name='ace_desc' required /></td></tr><tr><td></td><td align='right'><input class='btn btn-success' type='submit' value='确定' /> <input class='btn btn-warning' type='button' value='取消' onclick='cancelPopup()' /></td></tr></table></form>"+
			"</div></div></td></tr></table>";
			document.body.appendChild(wall);
			document.getElementById("file").focus();
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
					<li><a onclick="window.top.location.href='?page=manfile'">文件管理</a></li>
					<li><a onclick="window.top.location.href='?page=manchart'" class="curpage">图表管理</a></li>
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
				<i class="fa fa-bar-chart-o"></i>
				图表管理
				<button class="btn btn-success" style="margin-left:1em" onclick="createChartUploadForm()">上传图表</button>
			</h3>
			<div class="col-lg-12">
				<h4><strong>现有图表</strong></h4>
				<table class="table table-hover">
					<thead><th>图表</th><th>描述</th><th>操作</th></thead>
					<tbody>
					<?php
						$charts = $aceman->list_chart();
						foreach ($charts as $value) {
							echo "<tr><td>" . $value['chartname'] . "</td>" .
								"<td>" . $value['description'] . "</td>" .
								"<td>" .
								" <button class='btn btn-danger' onclick='createWindow(\"?page=delfile&filetype=chart&location=" . $value['chart_id'] . "\",\"确定要删除 " . $value['chartname'] . " ？\")'>删除</button>" .
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