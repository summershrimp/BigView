(function(){
	var hash=raw.models.hash();
	var chart=raw.chart()
		.title('饼图')
		.description("饼图显示一个数据系列中各项的大小与各项总和的比例，饼图中的数据点显示为整个饼图的百分比。")
		.thumbnail("../imgs/pie.png")
		.model(hash);
	var width=chart.number()
		.title("宽度")
		.defaultValue(1000)
		.fitToWidth(true);
	var height=chart.number()
		.title("高度")
		.defaultValue(500);
	var colors=chart.color()
		.title("颜色")
	var pie=d3.layout.pie()
		.value(function(d){return d.value;});
	chart.draw(function(selection,data){
		colors.domain(data,function(d){return d.color;});
		var key=hash.dimensions().get('key'),
			value=hash.dimensions().get('value');
		var diam=Math.min(width(),height()),
			radius=diam/2;
		var g=selection
			.attr("width",width())
			.attr("height",height())
			.append("g")
			.attr("transform","translate("+width()/2+","+height()/2+")");
		maxValue=d3.max(data,function(d){return d.value;});
		var arc;
		g.selectAll()
			.data(pie(data))
			.enter()
			.append("path")
			.attr("d",function(d){
				return d3.svg.arc()
					.innerRadius(0)
					.outerRadius(function(){return radius*d.value/maxValue;})(d);
			})
			.attr("fill",function(d){return colors()?colors()(d.data.color):"#09F";})
			.attr("stroke","#FFF")
			.attr("stroke-width",1);
	})
})();