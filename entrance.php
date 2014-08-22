<?php if (!defined("IN_ACE")) die("Forbidden."); ?>
<!doctype html>
<html lang="zh" ng-app="raw">
<head>
	<title>主页 - Ace数据可视化系统</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<!-- jquery -->
	<script type="text/javascript" src="bower_components/jquery/dist/jquery.min.js"></script>
	<script type="text/javascript" src="bower_components/jquery-ui/ui/minified/jquery-ui.min.js"></script>
	<script type="text/javascript" src="bower_components/jqueryui-touch-punch/jquery.ui.touch-punch.min.js"></script>
	<script type="text/javascript" src="lib/jQuery_mousewheel_plugin.js"></script>
	<!-- d3 -->
	<script type="text/javascript" src="bower_components/d3/d3.min.js"></script>
	<script type="text/javascript" src="bower_components/d3-plugins/sankey/sankey.js"></script>
	<script type="text/javascript" src="bower_components/d3-plugins/hexbin/hexbin.js"></script>
	<script src="js/functions.js"></script>
	<script>
		Date.name=Date.prototype.name="Date";
		Number.name=Number.prototype.name="Number";
		String.name=String.prototype.name="String";
		function loadData(DOM){
			if(sessionStorage.getItem("data-content")!=null)DOM.innerHTML=sessionStorage.getItem("data-content");
		}
		document.write('<base href="'+document.location+'" />');
		// add by rex
		var dragging=false;
		var origx=0,origy=0,trX,trY,scale;
		var point;
		var dx=0,dy=0;
		d3.select("svg").on("dragstart",function(){return false;});
		function startDrag(d){
			point=d3.mouse(d);
			origx=point[0];
			origy=point[1];
			trX=d3.select(d).attr("trX");
			trY=d3.select(d).attr("trY");
			dragging=true;
		}
		function duringDrag(d){
			if(dragging==false)return;
			point=d3.mouse(d);
			dx=(point[0]-origx)*scale;
			dy=(point[1]-origy)*scale;
			d3.select(d)
				.attr("trX",trX=parseInt(trX)+dx)
				.attr("trY",trY=parseInt(trY)+dy);
			d3.select(d)
				.attr("transform",function(){
					return "translate("+
						d3.select(d).attr("trX")+","+
						d3.select(d).attr("trY")+")"+
						" scale("+scale+")";
				});
		}
		function endDrag(){
			dragging=false;
		}
		function scaleChart(d,delta){
			if(dragging==true)return;
			if(delta>0&&scale<10)scale+=0.02;
			if(delta<0&&scale>0.2)scale-=0.02;
			jQuery(d)
				.attr("transform",function(){
					return "translate("+trX+","+trY+")"+" scale("+scale+")";
				});
		}
		function startChartActivation(){
			if(d3.select(".brush")[0][0]!=null)return;
			scale=1;
			trX=trY=0;
			if(d3.select("svg#drag")[0][0]==null)
				d3.select("svg>g")
					.append("rect")
					.attr("id","drag")
					.attr("fill","rgba(0,0,0,0)")
					.attr("width",100000)
					.attr("height",100000)
					.attr("x",-50000)
					.attr("y",-50000);
			d3.select("svg>g")
				.attr("scale",scale)
				.on("mousedown",function(){return startDrag(this);})
				.on("mousemove",function(){return duringDrag(this);})
				.on("mouseup",function(){return endDrag();});
			jQuery("svg>g").mousewheel(function(objEvent,delta){scaleChart(this,delta);});
				// .on("mousewheel",function(){return scaleChart(this);});
			if(d3.select("svg>g").attr("trX")==null){
				d3.select("svg>g").attr("trX",trX);
				d3.select("svg>g").attr("trY",trY);
			}
		}
		function createLoginForm(){
			var wall=document.createElement("div");
			wall.id="popup_wall";
			wall.innerHTML="<table id='popup_table'><tr><td><div id='popup'><div class='title'>登录</div><div class='text'>"+
			"<form action='?page=login' method='post'>"+
				"<table><tr><th>用户名</th><td><input type='text' id='username' name='ace_username' required /></tr><tr><th>密码</th><td><input type='password' name='ace_password' required /></tr><tr><td></td><td align='right'><input class='btn btn-success' type='submit' value='登录' /> <input class='btn btn-warning' type='button' value='取消' onclick='cancelPopup()' /></td></tr></table></form>"+
			"</div></div></td></tr></table>";
			document.body.appendChild(wall);
			document.getElementById("username").focus();
		}
	</script>
	<link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css"/>
	<link rel="stylesheet" type="text/css" href="bower_components/angular-bootstrap-colorpicker/css/colorpicker.css">
	<link href="bower_components/font-awesome/css/font-awesome.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="bower_components/codemirror/lib/codemirror.css">	
	<link rel="stylesheet" href="css/raw.css"/>
	<link rel="stylesheet" href="css/popups.css"/>
	<link rel="icon" href="favicon.ico?v=2" type="image/x-icon">
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
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
					<li><a onclick="window.top.location.href='./'" class="curpage">主页</a></li>
					<?php if ($aceman->is_login()) { ?>
					<li><a onclick="window.top.location.href='?page=manfile'">文件管理</a></li>
					<li><a onclick="window.top.location.href='?page=manchart'">图表管理</a></li>
						<?php if ($aceman->privileges[MAN_SYS]) { ?>
						<li><a onclick="window.top.location.href='?page=admin'">后台管理</a></li>
						<?php } ?>
					<li><a onclick="window.top.location.href='?page=logout'">退出</a></li>
					<?php } else { ?>
					<li><a onclick="createLoginForm()">登录</a></li>
					<?php } ?>
				</ul>
			</div>
		</div>
	</nav>
	<div ng-view class="wrap"></div>
 	<!-- <div id="footer">
		<div class="container">
			<div class="row">
				<div class="col-lg-6">
					<p><strong>RAW</strong> is a project by <a href="http://www.densitydesign.org">DensityDesign Lab</a></p>
					<p><a href="https://github.com/giorgiocaviglia">Giorgio Caviglia,</a> Michele Mauri, Giorgio Uboldi, Matteo Azzi</p>
					<p>&copy; 2013-2014 (<a href="https://raw.github.com/densitydesign/raw/master/LICENSE">LGPL License</a>)</p>
				</div>
				<div class="col-lg-3 col-md-6 col-sm-6">
					<p><a href="mailto:raw@densitydesign.org"><i class="fa fa-envelope breath-right"></i>raw@densitydesign.org</a></p>
					<p><a href="http://twitter.com/densitydesign"><i class="fa fa-twitter breath-right"></i>densitydesign</a></p>
				</div>
				<div class="col-lg-3 col-md-6 col-sm-6">
					<p><a href="http://github.com/densitydesign/raw"><i class="fa fa-github breath-right"></i>GitHub</a></p>
					<p><a href="https://groups.google.com/forum/?hl=en#!forum/densitydesign-raw"><i class="fa fa-google breath-right"></i>Google group</a></p>
				</div>
			</div>
		</div>
	</div> -->
	<!-- bootstrap -->
	<script type="text/javascript" src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="bower_components/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
	<!-- codemirror -->
	<script type="text/javascript" src="bower_components/codemirror/lib/codemirror.js"></script>
	<script type="text/javascript" src="bower_components/codemirror/addon/display/placeholder.js"></script>
	<!-- canvastoblob -->
	<script type="text/javascript" src="bower_components/canvas-toBlob.js/canvas-toBlob.js"></script>
	<!-- filesaver -->
	<script type="text/javascript" src="bower_components/FileSaver/FileSaver.js"></script>
	<!-- zeroclipboard -->
	<script type="text/javascript" src="bower_components/zeroclipboard/ZeroClipboard.min.js"></script>
	<!-- canvg -->
	<script type="text/javascript" src="bower_components/canvg/rgbcolor.js"></script> 
	<script type="text/javascript" src="bower_components/canvg/StackBlur.js"></script>
	<script type="text/javascript" src="bower_components/canvg/canvg.js"></script>
	<!-- moment -->
	<script type="text/javascript" src="bower_components/momentjs/min/moment-with-langs.min.js"></script>
	<!-- raw -->
	<script type="text/javascript" src="lib/raw.js"></script>
	<!-- charts -->
	<script src="charts/treemap.js"></script>
	<script src="charts/streamgraph.js"></script>
	<script src="charts/scatterPlot.js"></script>
	<script src="charts/packing.js"></script>
	<script src="charts/clusterDendrogram.js"></script>
	<script src="charts/voronoi.js"></script>
	<script src="charts/delaunay.js"></script>
	<script src="charts/alluvial.js"></script>
	<script src="charts/clusterForce.js"></script>
	<script src="charts/convexHull.js"></script>
	<script src="charts/hexagonalBinning.js"></script>
	<script src="charts/reingoldTilford.js"></script>
	<script src="charts/parallelCoordinates.js"></script>
	<script src="charts/circularDendrogram.js"></script>
	<script src="charts/smallMultiplesArea.js"></script>
	<!-- add by rex -->
	<script src="charts/pie.js"></script>
	<!-- user -->
	<script src="charts/getCustomChart.php"></script>
	<!-- angular -->
	<script type="text/javascript" src="bower_components/angular/angular.min.js"></script>
	<script type="text/javascript" src="bower_components/angular-route/angular-route.min.js"></script>
	<script type="text/javascript" src="bower_components/angular-animate/angular-animate.min.js"></script>
	<script type="text/javascript" src="bower_components/angular-sanitize/angular-sanitize.min.js"></script>
	<script type="text/javascript" src="bower_components/angular-strap/dist/angular-strap.min.js"></script>
	<script type="text/javascript" src="bower_components/angular-ui/build/angular-ui.min.js"></script>
	<script type="text/javascript" src="bower_components/angular-bootstrap-colorpicker/js/bootstrap-colorpicker-module.js"></script>
	<script src="js/app.js"></script>
	<script src="js/services.js"></script>
	<script src="js/controllers.js"></script>
	<script src="js/filters.js"></script>
	<script src="js/directives.js"></script>
	<!-- google analytics -->
	<script src="js/analytics.js"></script>
</body>
</html>