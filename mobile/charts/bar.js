(function(){
	var points=raw.models.points();
	points.dimensions().remove('size');
	points.dimensions().remove('label');
	var chart=raw.chart()
		.title('柱状图')
		.description("由一系列高度不等的纵向条纹表示数据分布的情况，只有一个变量，通常利用于较小的数据集分析。")
		.thumbnail("../imgs/bar.png")
		.model(points);
	var width=chart.number()
		.title("宽度")
		.defaultValue(1000)
		.fitToWidth(true);
	var height=chart.number()
		.title("高度")
		.defaultValue(500);
	var colors=chart.color()
		.title("颜色")
	chart.draw(function(selection,data){
		var x=points.dimensions().get('x'),
			y=points.dimensions().get('y');
		var marginLeft=d3.max(data,function(d){return(String(d.y).length/2.302585092994046)+2.5;})*9,
			marginBottom=40,
			w=width(),
			h=height();
		var g=selection
			.attr("width",w)
			.attr("height",h)
			.append("g");
		var barWidth=w/(data.length+3)/2;
		var xExtent=d3.extent(data,function(d){return d.x;}),
			yExtent=d3.extent(data,function(d){return d.y;});
		var xExtr=(xExtent[1]-xExtent[0])*0.05,
			yExtr=(yExtent[1]-yExtent[0])*0.1;
		xExtent[0]-=xExtr,xExtent[1]+=xExtr;
		yExtent[0]-=yExtr,yExtent[1]+=yExtr;
		var xScale=x.type()=="Date"
				?d3.time.scale().range([marginLeft+barWidth,width()-marginLeft-barWidth-1]).domain(xExtent)
				:d3.scale.linear().range([marginLeft+barWidth,width()-marginLeft-barWidth-1]).domain(xExtent),
			yScale=y.type()=="Date"
				?d3.time.scale().range([h-marginBottom,0]).domain(yExtent)
				:d3.scale.linear().range([h-marginBottom,0]).domain(yExtent),
			xScale2=x.type()=="Date"
				?d3.time.scale().range([marginLeft,width()-1]).domain(xExtent)
				:d3.scale.linear().range([marginLeft,width()-1]).domain(xExtent);
		var xAxis=d3.svg.axis()
			.scale(xScale)
			.orient("bottom")
			.tickSize(6,marginBottom-h);
		var xAxis2=d3.svg.axis()
			.scale(xScale2)
			.orient("bottom")
			.tickSize(6,marginBottom-h);
		var yAxis=d3.svg.axis()
			.scale(yScale)
			.orient("left")
			.tickSize(6,marginLeft-w);
		h-=marginBottom;
		g.append("g")
			.attr("class","x axis")
			.attr("transform","translate(0,"+(h+10)+")")
			.call(xAxis);
		g.append("g")
			.attr("class","x2 axis")
			.attr("transform","translate(0,"+(h+10)+")")
			.call(xAxis2);
		g.append("g")
			.attr("class","y axis")
			.attr("transform","translate("+marginLeft+",10)")
			.call(yAxis);
		g.selectAll(".axis")
			.selectAll("text")
			.style("font","10px Arial,Helvetica");
		g.selectAll(".axis")
			.selectAll("line,path")
			.style("fill","none")
			.style("stroke","#000000")
			.style("shape-rendering","crispEdges");
		d3.select(".x2").selectAll(".tick").style("display","none");
		d3.select(".x path").style("display","none");
		d3.select(".x2 path").style("display","inline");
		colors.domain(data,function(d){return d.y;});
		var returnY,return0;
		var bar = g.append("g")
			.attr("class","draw_area")
			.selectAll("g")
			.data(data)
			.enter().append("rect")
			.attr("class","bar")
			.attr("x",function(d){return xScale(d.x)-barWidth;})
			.attr("y",function(d){
				return0=yScale(0);
				if(return0<0)return0=0;
				if(return0>h)return0=h;
				return return0+10;
			})
			.attr("width",barWidth*2)
			.attr("height",0)
			.style("fill",function(d){return colors()?colors()(d.color):"#09F";})
			.transition()
			.duration(500)
			.attr("y",function(d){
				return0=yScale(0);
				returnY=yScale(d.y);
				if(return0<returnY){
					var t=return0;
					return0=returnY;
					returnY=t;
				}
				if(returnY<0)returnY=0;
				return returnY+10;
			})
			.attr("height",function(d){
				return0=yScale(0);
				returnY=yScale(d.y);
				if(return0<returnY){
					var t=return0;
					return0=returnY;
					returnY=t;
				}
				if(returnY<0)returnY=0;
				if(return0>h)return0=h;
				return return0-returnY;
			});
	})
})();