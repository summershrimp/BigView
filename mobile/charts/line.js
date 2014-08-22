(function(){
	var points=raw.models.points();
	points.dimensions().remove('size');
	points.dimensions().remove('label');
	points.dimensions().remove('color');
	var chart=raw.chart()
		.title('折线图')
		.description("折线图可以显示随时间（根据常用比例设置）而变化的连续数据，因此非常适用于显示在相等时间间隔下数据的趋势。")
		.thumbnail("../imgs/line.png")
		.model(points);
	var width=chart.number()
		.title("宽度")
		.defaultValue(1000)
		.fitToWidth(true);
	var height=chart.number()
		.title("高度")
		.defaultValue(500);
	var radius=chart.number()
		.title("圆点半径")
		.defaultValue(5);
	var strokeWidth=chart.number()
		.title("折线宽度")
		.defaultValue(2);
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
		var xExtent=d3.extent(data,function(d){return d.x;}),
			yExtent=d3.extent(data,function(d){return d.y;});
		var xExtr=(xExtent[1]-xExtent[0])*0.05,
			yExtr=(yExtent[1]-yExtent[0])*0.1;
		xExtent[0]-=xExtr,xExtent[1]+=xExtr;
		yExtent[0]-=yExtr,yExtent[1]+=yExtr;
		var xScale=x.type()=="Date"
				?d3.time.scale().range([marginLeft,width()-marginLeft-1]).domain(xExtent)
				:d3.scale.linear().range([marginLeft,width()-marginLeft-1]).domain(xExtent),
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
		// colors.domain(data,function(d){return d.y;});
		var returnY,return0;
		var line = d3.svg.line()
			.x(function(d){return xScale(d.x);})
			.y(function(d){return yScale(d.y)+10;})
			.interpolate('linear');
		var drawArea=g.append("g")
			.attr("class","draw_area");
		drawArea.append("path")
			.attr("d",line(data))
			.attr("stroke","#09C")
			.attr("stroke-width",strokeWidth())
			.attr("fill","none")
			.attr("opacity",0)
			.transition()
			.duration(1000)
			.attr("opacity",1);
		var circle=drawArea
			.selectAll("g")
			.data(data)
			.enter().append("circle")
			.attr("class","circle")
			.attr("cx",function(d){return xScale(d.x);})
			.attr("cy",function(d){return yScale(d.y)+10;})
			.attr("r",0)
			.style("fill","#09C")
			// .style("fill",function(d){return colors()?colors()(d.color):"#EEE";})
			.transition()
			.duration(500)
			.attr("r",radius());
	})
})();