(function(){
	var points=raw.models.points();
	points.dimensions().remove('size');
	points.dimensions().remove('label');
	var chart=raw.chart()
		.title('柱状图')
		.description("由一系列高度不等的纵向条纹表示数据分布的情况，只有一个变量，通常利用于较小的数据集分析。")
		.thumbnail("data:image/png;base64,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r4h6YD/9y18r05t1c20//TMRJaJE1PAj6lou70yfKLPG+Vj5WxElouZElIgagRwiqjvHRtRempWPHzMiqlpXREXE+Yqoj5T0018raVY3146/gy+iWhBRB/sVUSLqEBElokTUdomobokoEbW6roiKCBElokSUiBJRIkpEiSgR1aMcIqo7Im3HotIAABp5SURBVKoioioiqiKiltcVUSJqQUSJKBElokTUYOUQUd0RURURVTkpop4q6edPVf88Mt8o6Weji6iX03+ey+TC4flEufeSiJqvK6JE1IKIElEiSkSJqMHKIaK6I6IqIqpyniIqlzT7jYb59WMCSkSJKBElokSUiBJRImqAcoio7oioioiqiCgRdXRdESWiFkSUiBJRIkpEDVYOEdUdEVURURURJaKOriuiRNSCiBJRIkpEiajByiGiuiOiKiKqIqJE1NF1RZSIWhBRIkpEiSgRNVg5RFR3RFRFRFVElIg6uq6IElELIkpEiSgRJaIGK4eI6o6IqoioiogSUUfXFVE7jaiX7/yb9HJ5u3Ze2v+JiBJRy0SUiBJR2yWiuiWiRNTquiJKRIkoEXXaiLqz/7P0cpnVzkvHBJSI2piIElEiqmFPIkpE7YiIElGr64ooESWiRJSIElEiSkSJKBHVrxwiqjsiqiKiKiJKRB1dV0SJqAURJaJElIgSUYOVQ0R1R0RVRFRFRImoo+uKKBG1IKJE1BAi6ulU/vLZSblcN99O5WERJaLaElHdElEianVdESWiRJSIElG9RVT6bLmePltK00wfKe+qu52IGm9EPZvK3z6XyqxuvnVMQImo8xlRJX38pyV9fFY3+5O9/yCiuiOiRNTquiJKRIkoESWi+oyokj5bZk0z+Vy5UHc7ESWiRFTDnkSUiNoRESWiVtcVUSJKRIkoESWiRJSIElEiql85RFR3RFRFRFVElIg6uq6IElELIkpEiSgRJaIGK4eI6o6IqoioiogSUUfXFVEiakFEiSgRJaJE1GDlEFHdEVEVEVURUSLq6LoiSkQtiCgRJaJElIgarBwiqjsiqiKiKiJKRB1dd1wRdV/J/+uk3LxaO/tPPSOiRNSciBJRIqphTyJKRO2IiBJRq+uKKBElomrm87N094s/SeV3326YR5vW3CSipuVrP0vlD2b189QxASWiRJSIElEiKkJEiajdEVEianVdESWiRFR9RJXfbZxp+VLj16OIElEiqp6IElEian0ianf2ImIWEbdb/vciSkStriuiRJSIElEiSkSJKBElokRUv3J0GFEXI+Ktg7f3IuJKi9uIKBG1uq6IElEiSkSJKBElokSUiBJR/crRYURdiiqe5m/faHEbESWiVtcVUSJKRIkoESWiRJSIElEiql85OoyoK7G4+nQxjkbUNyLinx6av46BRVSZTC6UyeRy3dyZ3vsr+/dP31/um1yunY9Mny4fTaVu9vfSG3cfTK+Wj6dSO785ebN8MpXaeSi9Xj6dSt3sfya9uv/Z6fvL5yaXa+d30o/K9VTqZv+L0xfKY5PLtXMj3VOeSK+UJ1Kpm/3fm9woNyeXa+ep9HB5JpX6mb6y/8x0rzw7uVw3d29NXii3UqmdP04/Ks9PLtfNnefv/ZX9F6fvLy9OLtdOmT59EFJH5s5+evXF/en7XyyTy3XzfEk/er6kUj/Tp79dJpfr5o/2J3vP3Z38+XMllbp5ukzefKqkUjc3S3r96yWVunmyTF/7apm+9tWSSt18eT+98VhJpW6ul/T675RU6uYLZfraw2X62sMllbr51H5646GSSt3kMn3zN0oqdfPr++nVB++mNz5WUqmbj5T0418tqdTNh8v0zfvvplfvL6kcmf3pq+nuB99I5YOlbqbl/qcn5drl2tn/yN7k7t6fp/JAqZtJ+SdvpvKJUjdPPPWrrzd9Td3+0gdf+dx3H7xnUvLluknlMz9K5bOlfn779VQeLXUzLV94bVq++FoqXyp1M9n/0o1Jeexy3aTyxMOpPFnqZrp/49X7737ojdrzW1KZlq/8ZSp/WOpmWp5+M5VnSu3sP/tquvudN1L5TqmfP/5xKt8vtbP//Bvp7ouvpvJiqZtJ+bM3U3m51M7+/uvp5VJq58/230i3776aXiylbj7wg/Lw5Fa5XDfTPy7/3eTb5XLdpOfKPemZ8kp6ppS6mTy1f2Nys1yumy//8Lcebvr+8LUf/uZr9339pdfSk6XUzuP7b6QbpdTN9LHyZvpSKXUz/d39P59+fn9v8mi5XDfTz5WnD0Lq6Hxm/9Xpp/bfP8nl8uH5QN5/f/pE+VH6RCm182D5cXqglNr56P4b6VfvvpqulVI3k/vKm+mDpdTNfam8fn8qpW4+NC2vfCiVe65NyuW62ZvcfeFjqZS6+UQqr/9GKqVuPjktr/3WtLz2UCqlbj6b9t94OJVSN49Oy5u/k0qpn/1Xv5TuvvFYKqVuHk/lx19NpdTNE9Py5tdTKfWz/+ofprtvPJVKqZtnU/nxc6mUuvlW2n/ju+nuq99LpdTN9yflzedTKbUz3X/hxUm5XDcvTfb39lN5ZT+VUjdl8tKbJb1U6mZ/+qcvlMnzl2sn/fE9Jd16paRbpW7207ffKOnbpXamz71Z+0Wcnin76elX76ZvvFHSzVI3+5Mnb5TJ45drJ3354ZK+XOpmP3351bvpi2+UdL3Uz+d/XNIjpW720+feuJs+/WpJny518/AL973Z9DP7S3/04RfLJF+um+c+8KX3P3jvS7/S9HXzcvr1H5X08VI/e6+X9NFSO9OPvFLStXvK5L7LtXPvfb/cdwMckmNAV6LeHRH/9aH5fgwtolK6VlKaNc6Hjp3G33zvbL+/nd5XHkmzxnk0Nf52tXw5XS9fTrPGeaz5N+rlZnq73Eyzpmm83bem7ynfSrNjpvm3OT9I18oLaVY7P0yz8tKx0/j33bxYJldvlzRrmhfvTHLTbW+VdOt7Jc2a5lZpvkrIbqVy7VYq12ZNMy3Xtv5nU4XSb82a56FTXe04SSo33k7lxqxpdrHmWZP+pPw0/UmZ1c73ys/Td8qsaSbfbr7akf6gvJ3+oMyapsMPMSIiJp8rF9KjZXbMNH6vnORyNf1WmTXNJN/JHX4oJ7o2KZd/LZVZ40ybrxKyudvTcvN2KrOmefGYq4THKemH10t6YdY8PzzVXwexiZKe+IuSnpg1zrWbp3oUza6U9MlbJX1yVjfPpseav2aqOdXPsTK573JJH5o1zvTDQ/t6zOE5UesRUSJKRJ0NIkpErUNEiSi2S0SJqJU1RdSJRv/qfCJKRImos0FEiah1iCgRxXaJKBG1sqaI2joRtel+RZSIopaIElHrOC6iJt8r/z59p5SmmX6rNH4uiaj+iKh+iSgRtbKmiNo6EbXpfkWUiKKWiBJR6zj2StSflMZXAzvx/yuiOKdE1DgjqqRPzso0b/3no4jaPhG16X5FlIiilogSUesQUSKK7RJRImplTRG1dSJq0/2KKBFFLRElotYhokQU2yWiRNTKmiJq60TUpvsVUSKKWiJKRK1DRIkotktEiaiVNUXU1o0vou5PL9750CTXTbl/crXz/YooEUUtESWi1iGiRBTb9eKkPHg7ldI0L0ybX5DlOCJqcyKqlRwiaj0nRlRK1/re4zIRJaKoJ6JE1DpElIhiHETU5kRUKzlE1HpE1M4j6l3lW5OrzTO91HhbEcUaRJSIWoeIElGMg4janIhqJYeIWo+I2m1EbfSxiijWIKJE1DpElIhiHETU5kRUKzlE1HpElIgSUWeDiBJR6xBRIopxEFGbE1Gt5BBR6xFRIkpEnQ0iSkStQ0SJKMZBRG1ORLWSQ0StR0SJKBF1NogoEbUOESWiGAcRtTkR1UoOEbUeESWiRNTZIKJE1DpElIhiHETU5kRUKzlE1HpElIgSUWeDiBJR6xBRIopxEFGbE1Gt5BBR6xFRIkpEnQ0iSkStQ0SJKMZBRG1ORLWSQ0StR0SJKBF1NpyziHp0Wh6/2TS7WPOsEVEiinEQUZsTUa3kEFHrEVEiSkSdDecpoticiBJRjIOI2pyIaiWHiFqPiGoXUX0QUaxDRLEOESWiGAcRtTkR1UoOEbUeESWiRNTZIKJYh4gSUYyDiNqciGolh4haj4gSUSLqbBBRrENEiSjGQURtTkS1kkNErUdEiSgRdTaIKNYhokQU4yCiNieiWskhotYjokSUiDobRBTrEFEiinEQUZsTUa3kEFHrEVEiSkSdDSKKdYgoEcU4DDOivnbtzuRruWm63s9JRFQrOUTUekSUiBJRZ4OIYh0iSkQxDkOMqLERUa3kEFHrEVEiSkSdDSKKdYgoEcU4iKjNiahWcoio9YgoESWizgYRxTpElIhiHETU5kRUKzlE1HpElIgSUWeDiGIdIkpEMQ4ianMiqpUcImo9IkpEiaizQUSxDhElohgHEbU5EdVKDhG1HhElokTU2SCiWIeIElGMg4janIhqJYeIWo+IElEi6mwQUaxDRIkoxkFEbU5EtZJDRK1HRIkoEXU2TMu190zKtctNc7Vc/YVtrymixktEiSjGQURtTkS1kkNErUdEiSgRxWmJqPESUSKKcRBRmxNRreQQUesRUSJKRHFaImq8RJSIYhxE1OZEVCs5RNR6RJSIElGclogaLxElohgHEbU5EdVKDhG1HhElokQUpyWixktEiSjGQURtTkS1kkNErUdEnb+I2oSIYpmIGi8RJaIYBxG1ORHVSg4RtR4RJaLWIaJYJqLGS0SJKMZBRG1ORLWSQ0StR0SJqHWIKJaJqPESUSKKcRBRmxNRreQQUesRUSJqHSKKZSJqvESUiGIcRNTmRFQrOUTUekTU4CPq7fqZ/isRRd9E1HiJKBHFOIiozYmoVnKIqPWIqOFG1HHKi5MLIoq+iajxElEiinEQUZsTUa3kEFHrEVEiah0iimUiarxElIhiHETU5kRUKzlE1HpElIhah4himYgaLxElohgHEbU5EdVKDhG1HhElotYholgmosZLRIkoxkFEbU5EtZJDRK1HRImodYgolomo8RJRIopxEFGbE1Gt5BBR6xFRImodIoplImq8dhVR9zxd3j25WS40zRY/hFZEFGMnojZXpvm9ZZKvNs40v2Pra57jiHorImYHM7d38P7tE44dR0RtSERVRBRDIKLGa1cRNTQiirETUeN0XiPqUkTcOHj7ysFcjCqsIqpwajp2EhG1IRFVEVEMgYgaLxF1ckRNc7k0fajcbJqUizuv7JyIGqfzGlHLLi3N3tKxGw3HTiKiNiSiKiKKIRBR4yWiTo4oGAIRNU7nIaJuxOJhe7NYXFmKqK40zR+mN78iNT9+o+HYshQRjxya10NEbUREVUQUQyCixktEiSjGQUSN03mIqCaXYjWoTnMl6h9HxAOH5n8OEbUREVURUQyBiBovESWiGAcRNU7nOaKWXyjCc6IGRERVRBRDIKLGS0SJKMZBRI3TeY2oK7H6EL95HHl1vgEQUZW+Iup7ZfLgrTK92TTPl3t/cRfrMkwiarxElIhiHETUOJ3XiNolEbUhEVXpK6JgmYgaLxElohgHETVOImr7RNSGRFRFRDEEImq8RJSIYhxE1DiJqO0TURsSURURxRCIqPESUSKKcRBR4ySitk9EbUhEVUq59xfLS9ObjfOnkwf73iNnn4gaLxElohgHETVOImr7RNSGRBQMh4gaLxElohgHETVOImr7RNSGRBQMh4gaLxElohgHETVOImr7RNSGRBQMh4gaLxElohgHETVOImr7RNSGRBQMh4gaLxElohgHETVO5d77frlMP3yzcdKH7ul7j4fkEFHrKdPpe8pkcrVxptNf6nuPy0QUDIeIGi8RJaIYBxFFR3KIqLNNRMFwiKjxElEiinEQUXQkh4g620QUDIeIGq/0/VIa50/Ks33vb1tEFGMnouhIDhF1tokoGA4RxdCJKMZORNGRHCLqbBNRMBwiiqETUYydiKIjOUTU2SaiYDhEFEMnohg7EUVHcoios01EwXCI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")
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