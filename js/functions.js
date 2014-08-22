function createWindow(url, text){
	var wall=document.createElement("div");
	wall.id="popup_wall";
	wall.innerHTML="<table id='popup_table'><tr><td><div id='popup'><div class='title'>确认操作</div><div class='text'>"+
	"<form action='"+url+"' method='post' enctype='multipart/form-data'>"+
		"<table><tr><td>"+text+"</td></tr><tr><td align='right'><input class='btn btn-success' type='submit' value='确定' /> <input class='btn btn-warning' type='button' value='取消' onclick='cancelPopup()' /></td></tr></table></form>"+
	"</div></div></td></tr></table>";
	document.body.appendChild(wall);
	document.getElementById("text").focus();
}
function cancelPopup(){
	var wall=document.getElementById("popup_wall");
	if(wall!=null)wall.parentNode.removeChild(wall);
}
function ajaxGetJson(url,callback){
	var xmlhttp=new XMLHttpRequest();
	xmlhttp.open("get",url,true);
	xmlhttp.onreadystatechange=function(){if(xmlhttp.readyState==4&&xmlhttp.status==200)callback(JSON.parse(xmlhttp.responseText));}
	xmlhttp.send(null);
}