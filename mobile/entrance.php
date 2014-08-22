<!doctype html>
<html lang="zh" ng-app="raw">
<head>
	<title>Ace数据可视化系统</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,user-scaleable=no">
	<script>
		document.write('<base href="'+document.location+'" />');
		var mobile=true;
	</script>
	<!-- d3 -->
	<script type="text/javascript" src="../bower_components/d3/d3.min.js"></script>
	<script type="text/javascript" src="../bower_components/d3-plugins/sankey/sankey.js"></script>
	<script type="text/javascript" src="../bower_components/d3-plugins/hexbin/hexbin.js"></script>
	<!-- jquery -->
	<script type="text/javascript" src="../bower_components/jquery/dist/jquery.min.js"></script>
	<script type="text/javascript" src="../bower_components/jquery-ui/ui/minified/jquery-ui.min.js"></script>
	<script type="text/javascript" src="../bower_components/jqueryui-touch-punch/jquery.ui.touch-punch.min.js"></script>
	<!-- added by skywalker_z -->
	<script>
		var step=1,
			maxStep=3,
			width=jQuery(window).width();
		window.onresize=function(){
			width=jQuery(window).width();
			jQuery("#wrapper").css({"left":-width*(step-1)+"px"});
		}
		function checkMaxStep(){
			if(document.getElementsByTagName("section")[3].className=="ng-hide")maxStep=3;
			else maxStep=4;
			if(step==3){
				if(maxStep==4){
					jQuery("#nextStep").attr("disabled",null);
					jQuery("#nextStep").removeClass("disabled");
				}
				else{
					jQuery("#nextStep").attr("disabled","disabled");
					jQuery("#nextStep").addClass("disabled");
				}
			}
		}
		function prevStep(){
			if(step>1){
				step--;
				jQuery("#wrapper").animate({"left":-width*(step-1)+"px"});
				jQuery("#nextStep").attr("disabled",null);
				jQuery("#nextStep").removeClass("disabled");
			}
			if(step==1){
				jQuery("#prevStep").attr("disabled","disabled");
				jQuery("#prevStep").addClass("disabled");
			}
		}
		function nextStep(){
			if(step<maxStep){
				step++;
				jQuery("#wrapper").animate({"left":-width*(step-1)+"px"});
				jQuery("#prevStep").attr("disabled",null);
				jQuery("#prevStep").removeClass("disabled");
			}
			if(step==maxStep){
				jQuery("#nextStep").attr("disabled","disabled");
				jQuery("#nextStep").addClass("disabled");
			}
		}
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
		function scaleChart(d){
			if(dragging==true)return;
			if(event.wheelDelta>0&&scale<10)scale+=0.02;
			if(event.wheelDelta<0&&scale>0.2)scale-=0.02;
			d3.select(d)
				.attr("transform",function(){
					return "translate("+
						d3.select(d).attr("trX")+","+
						d3.select(d).attr("trY")+")"+
						" scale("+scale+")";
				});
		}
		function startChartActivation(){
			scale=1;
			trX=trY=0;
			d3.select("svg>g")
				.attr("scale",scale)
				.on("mousedown",function(){return startDrag(this);})
				.on("mousemove",function(){return duringDrag(this);})
				.on("mouseup",function(){return endDrag();})
				.on("mousewheel",function(){return scaleChart(this);});
			if(d3.select("svg#drag")[0][0]==null)
				d3.select("svg>g")
					.append("rect")
					.attr("id","drag")
					.attr("fill","rgba(0,0,0,0)")
					.attr("width",100000)
					.attr("height",100000)
					.attr("x",-50000)
					.attr("y",-50000);
			d3.select("svg>g").attr("trX",trX);
			d3.select("svg>g").attr("trY",trY);
		}
	</script>
	<link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css"/>
	<link rel="stylesheet" type="text/css" href="../bower_components/angular-bootstrap-colorpicker/css/colorpicker.css">
	<link href="../bower_components/font-awesome/css/font-awesome.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="../bower_components/codemirror/lib/codemirror.css">	
	<link rel="stylesheet" href="css/raw.css"/>
	<link rel="icon" href="../favicon.ico?v=2" type="image/x-icon">
	<link rel="shortcut icon" href="../favicon.ico" type="image/x-icon">
</head>
<body data-spy="scroll" data-target="#raw-nav">
	<nav class="navbar" role="navigation" id="raw-nav">
		<div class="container">
		 <div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#raw-navbar">
					<span class="sr-only">切换导航</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand">Ace数据可视化系统</a>
			</div>
			<div class="collapse navbar-collapse navbar-right" id="raw-navbar">
				<ul class="nav navbar-nav">
					<li><a onclick="window.top.location.href='./'" class="curpage">主页</a></li>
					<li><a onclick="window.top.location.href='?page=manfile'">文件管理</a></li>
					<li><a onclick="window.top.location.href='?page=manchart'">图表管理</a></li>
					<li><a onclick="window.top.location.href='?page=admin'">后台管理</a></li>
					<li><a onclick="window.top.location.href='?page=login'">登录</a></li>
					<li><a onclick="window.top.location.href='?page=logout'">退出</a></li>
				</ul>
			</div>
		</div>
	</nav>
	<div ng-view class="wrap"></div>
	<div id="steps">
		<button id="prevStep" disabled class="disabled" onclick="prevStep()"><i class="fa fa-angle-left"></i> 上一步</button>
		<button id="nextStep" disabled class="disabled" onclick="nextStep()">下一步 <i class="fa fa-angle-right"></i></button>
	</div>
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
	<script type="text/javascript" src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="../bower_components/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
	<!-- codemirror -->
	<script type="text/javascript" src="../bower_components/codemirror/lib/codemirror.js"></script>
	<script type="text/javascript" src="../bower_components/codemirror/addon/display/placeholder.js"></script>
	<!-- canvastoblob -->
	<script type="text/javascript" src="../bower_components/canvas-toBlob.js/canvas-toBlob.js"></script>
	<!-- filesaver -->
	<script type="text/javascript" src="../bower_components/FileSaver/FileSaver.js"></script>
	<!-- zeroclipboard -->
	<script type="text/javascript" src="../bower_components/zeroclipboard/ZeroClipboard.min.js"></script>
	<!-- canvg -->
	<script type="text/javascript" src="../bower_components/canvg/rgbcolor.js"></script> 
	<script type="text/javascript" src="../bower_components/canvg/StackBlur.js"></script>
	<script type="text/javascript" src="../bower_components/canvg/canvg.js"></script>
	<!-- moment -->
	<script type="text/javascript" src="../bower_components/momentjs/min/moment-with-langs.min.js"></script>
	<!-- raw -->
	<script type="text/javascript" src="../lib/raw.js"></script>
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
	<script src="charts/bar.js"></script>
	<script src="charts/line.js"></script>
	<script src="charts/pie.js"></script>
	<!-- angular -->
	<script type="text/javascript" src="../bower_components/angular/angular.min.js"></script>
	<script type="text/javascript" src="../bower_components/angular-route/angular-route.min.js"></script>
	<script type="text/javascript" src="../bower_components/angular-animate/angular-animate.min.js"></script>
	<script type="text/javascript" src="../bower_components/angular-sanitize/angular-sanitize.min.js"></script>
	<script type="text/javascript" src="../bower_components/angular-strap/dist/angular-strap.min.js"></script>
	<script type="text/javascript" src="../bower_components/angular-ui/build/angular-ui.min.js"></script>
	<script type="text/javascript" src="../bower_components/angular-bootstrap-colorpicker/js/bootstrap-colorpicker-module.js"></script>
	<script src="../js/app.js"></script>
	<script src="../js/services.js"></script>
	<script src="../js/controllers.js"></script>
	<script src="../js/filters.js"></script>
	<script src="js/directives.js"></script>
	<!-- google analytics -->
	<script src="../js/analytics.js"></script>
</body>
</html>