<?php

if (!defined("IN_ACE") || !($aceman->is_login() && $aceman->privileges[MAN_SYS])) die("Forbidden.");

$privlist = array(
	'编辑用户' => 'a',
	'编辑用户组' => 'b',
	'编辑个人图表' => 'd',
	'编辑个人文件' => 'e',
	'系统管理员' => 'z'
);

?>
<!doctype html>
<html lang="zh">
<head>
	<title>后台管理 - Ace数据可视化系统</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css"/>
	<link href="bower_components/font-awesome/css/font-awesome.css" rel="stylesheet">
	<link rel="stylesheet" href="css/raw.css"/>
	<link rel="stylesheet" href="css/popups.css"/>
	<link rel="icon" href="favicon.ico?v=2" type="image/x-icon">
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<style>
		#manuser th:nth-child(4),#manusergroup th:nth-child(4),#manchart th:nth-child(3){
			width:10em;
		}
	</style>
	<script src="js/functions.js"></script>
	<script>
		function createAddUserWindow(){
			var wall=document.createElement("div");
			wall.id="popup_wall";
			wall.innerHTML="<table id='popup_table'><tr><td><div id='popup'><div class='title'>添加用户</div><div class='text'>"+
			"<form action='?page=users&action=adduser' method='post'>"+
				"<table>"+
					"<tr><th>用户名</th><td><input id='username' type='text' name='ace_username' required /></td></tr>"+
					"<tr><th>用户组ID</th><td><input id='usergroup' type='text' name='ace_usergroup' required /></td></tr>"+
					"<tr><th>默认密码</th><td><input type='password' name='ace_defpassword' required /></td></tr>"+
					"<tr><th>重复默认密码</th><td><input type='password' name='ace_reppassword' required /></td></tr>"+
					"<tr><th>权限</th><td>"+
						<?php
							foreach ($privlist as $name => $char) {
								echo '"<p><input type=\'checkbox\' name=\'ace_priv[]\' value=\'' . $char . '\' />';
								echo $name . '</p>"+';
							}
						?>
					"</td></tr>"+
					"<tr><td></td><td align='right'><input class='btn btn-success' type='submit' value='确定' /> <input class='btn btn-warning' type='button' value='取消' onclick='cancelPopup()' /></td></tr>"+
				"</table></form>"+
			"</div></div></td></tr></table>";
			document.body.appendChild(wall);
			document.getElementById("username").focus();
		}
		function createAddUsergroupWindow(){
			var wall=document.createElement("div");
			wall.id="popup_wall";
			wall.innerHTML="<table id='popup_table'><tr><td><div id='popup'><div class='title'>添加用户组</div><div class='text'>"+
			"<form action='?page=users&action=addusergroup' method='post'>"+
				"<table>"+
					"<tr><th>组名</th><td><input id='groupname' type='text' name='ace_groupname' required /></td></tr>"+
					"<tr><th>权限</th><td>"+
						<?php
							foreach ($privlist as $name => $char) {
								echo '"<p><input type=\'checkbox\' name=\'ace_priv[]\' value=\'' . $char . '\' />';
								echo $name . '</p>"+';
							}
						?>
					"<tr><td></td><td align='right'><input class='btn btn-success' type='submit' value='确定' /> <input class='btn btn-warning' type='button' value='取消' onclick='cancelPopup()' /></td></tr>"+
				"</table></form>"+
			"</div></div></td></tr></table>";
			document.body.appendChild(wall);
			document.getElementById("groupname").focus();
		}
		function createEditUserWindow(uid){
			var wall=document.createElement("div");
			wall.id="popup_wall";
			wall.innerHTML="<table id='popup_table'><tr><td><div id='popup'><div class='title'>修改用户</div><div class='text'>"+
			"<form action='?page=users&action=edituser&uid="+uid+"' method='post'>"+
				"<table>"+
					"<tr><th>用户名</th><td><input id='username' type='text' name='ace_username' required /></td></tr>"+
					"<tr><th>用户组</th><td><input id='usergroup' type='text' name='ace_usergroup' required /></td></tr>"+
					"<tr><th>新密码</th><td><input type='password' name='ace_newpassword' /></td></tr>"+
					"<tr><th>重复新密码</th><td><input type='password' name='ace_reppassword' /></td></tr>"+
					"<tr><th>权限</th><td>"+
						<?php
							foreach ($privlist as $name => $char) {
								echo '"<p><input id=\'priv_' . $char . '\' type=\'checkbox\' name=\'ace_priv[]\' value=\'' . $char . '\' />';
								echo $name . '</p>"+';
							}
						?>
					"</td></tr>"+
					"<tr><td></td><td align='right'><input class='btn btn-success' type='submit' value='确定' /> <input class='btn btn-warning' type='button' value='取消' onclick='cancelPopup()' /></td></tr>"+
				"</table></form>"+
			"</div></div></td></tr></table>";
			document.body.appendChild(wall);
			ajaxGetJson("?page=ajax&action=getUserInfo&uid="+uid,function(d){
				document.getElementById("username").value=d.username;
				document.getElementById("usergroup").value=d.user_group;
				var DOM;
				for(var i=0;i<d.privileges.length;i++)
					if((DOM=document.getElementById("priv_"+d.privileges[i]))!=null)
						DOM.checked=1;
				document.getElementById("username").focus();
			});
		}
		function createEditUsergroupWindow(gid){
			var wall=document.createElement("div");
			wall.id="popup_wall";
			wall.innerHTML="<table id='popup_table'><tr><td><div id='popup'><div class='title'>修改用户组</div><div class='text'>"+
			"<form action='?page=users&action=editusergroup&gid="+gid+"' method='post'>"+
				"<table>"+
					"<tr><th>组名</th><td><input id='groupname' type='text' name='ace_groupname' required /></td></tr>"+
					"<tr><th>权限</th><td>"+
						<?php
							foreach ($privlist as $name => $char) {
								echo '"<p><input id=\'priv_' . $char . '\' type=\'checkbox\' name=\'ace_priv[]\' value=\'' . $char . '\' />';
								echo $name . '</p>"+';
							}
						?>
					"</td></tr>"+
					"<tr><td></td><td align='right'><input class='btn btn-success' type='submit' value='确定' /> <input class='btn btn-warning' type='button' value='取消' onclick='cancelPopup()' /></td></tr>"+
				"</table></form>"+
			"</div></div></td></tr></table>";
			document.body.appendChild(wall);
			ajaxGetJson("?page=ajax&action=getGroupInfo&gid="+gid,function(d){
				document.getElementById("groupname").value=d.groupname;
				var DOM;
				for(var i=0;i<d.privileges.length;i++)
					if((DOM=document.getElementById("priv_"+d.privileges[i]))!=null)
						DOM.checked=1;
				document.getElementById("groupname").focus();
			});
		}
	</script>
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
					<li><a onclick="window.top.location.href='?page=manchart'">图表管理</a></li>
					<li><a onclick="window.top.location.href='?page=admin'" class="curpage">后台管理</a></li>
					<li><a onclick="window.top.location.href='?page=logout'">注销</a></li>
				</ul>
			</div>
		</div>
	</nav>
	<div id="maincont" class="col-lg-12">
		<section>
			<h3 style="margin-left:0.75em">
				<i class="fa fa-cogs"></i>
				后台管理
				<button class="btn btn-success" style="margin-left:1em" onclick="createAddUserWindow()">添加用户</button>
				<button class="btn btn-success" style="margin-left:1em" onclick="createAddUsergroupWindow()">添加用户组</button>
			</h3>
			<div class="col-lg-12">
				<h4><strong>用户管理</strong></h4>
				<table id="manuser" class="table table-hover">
					<thead><th>用户名</th><th>所属用户组</th><th>权限</th><th>操作</th></thead>
					<tbody>
					<?php
						$users = $aceman->list_users();
						foreach ($users as $value) {
							$privileges = "";
							foreach ($privlist as $name => $char) {
								if (stripos($value['privileges'], $char) === false) continue;
								$privileges .= (($privileges == "") ? "" : "、") . $name;
							}
							echo "<tr><td>" . $value['username'] . "</td>" .
								"<td>" . $value['user_group'] . "</td>" .
								"<td>" . $privileges . "</td>" .
								"<td>" .
								"<button class='btn btn-warning' onclick='createEditUserWindow(" . $value['user_id'] . ")'>修改</button>" .
								" <button class='btn btn-danger' onclick='createWindow(\"?page=users&action=deluser&uid=" . $value['user_id'] . "\",\"确定要删除 " . $value['username'] . " ？\")'>删除</button>" .
								"</td></tr>";
						}
					?>
					</tbody>
				</table>
			</div>
			<div class="col-lg-12">
				<h4><strong>用户组管理</strong></h4>
				<table id="manusergroup" class="table table-hover">
					<thead><th>用户组ID</th><th>用户组名称</th><th>权限</th><th>操作</th></thead>
					<tbody>
					<?php
						$usergroups = $aceman->list_group();
						foreach ($usergroups as $value) {
							$privileges = "";
							foreach ($privlist as $name => $char) {
								if (stripos($value['privileges'], $char) === false) continue;
								$privileges .= (($privileges == "") ? "" : "、") . $name;
							}
							echo "<tr><td>" . $value['group_id'] . "</td>" .
								"<td>" . $value['groupname'] . "</td>" .
								"<td>" . $privileges . "</td>" .
								"<td>" .
								"<button class='btn btn-warning' onclick='createEditUsergroupWindow(" . $value['group_id'] . ")'>修改</button>" .
								" <button class='btn btn-danger' onclick='createWindow(\"?page=users&action=delgroup&gid=" . $value['group_id'] . "\",\"确定要删除 " . $value['groupname'] . " ？\")'>删除</button>" .
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