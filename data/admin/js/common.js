$(function(){
	$(".tabMenu").click(function(){
		$(".sidebar-menu li").removeClass('active');
		$(this).parent().addClass('active');	
	})
	
});

function resetLeftDivHeight(){
	var winH = $(window).height();
	var headH = $(".head").height();
	var leftDivH = winH;
	
	$(".leftdiv").css('height',leftDivH+'px');

}

function openModel(url,title){
	loadingshow();
	$("#modal_default").show();
	$("#modal_title").html(title);
	$("#modal_body").load(url, function () {			
		loadinghide();
		$("#modal_btn").click();
	});
	
}



function must(){
	var mustflag = true;
	$(".must").parent("td").find("i").remove();
	$(".must").each(function(){
		if($(this).val() == "" && mustflag){
			mustflag = false;
			$(this).parent("td").append("<i style=\"font-style:normal; color:#F00; margin-left:10px;\">不能为空</i>");
			$(this).focus();
		}
		$(this).keydown(function(){
			$(this).parent("td").find("i").remove();
		});
	});
	return mustflag;
}

function checkall(obj){
	if($(obj).is(":checked")){
		$("input[type='checkbox']").attr("checked", true);
	}
	else{
		$("input[type='checkbox']").attr("checked", false);
	}
}

function selectall(name) {
	var checkboxs=document.getElementsByName(name);
	 for (var i=0;i<checkboxs.length;i++) {
	  var e=checkboxs[i];
	  e.checked=!e.checked;
	 }
}


function go(url, type, top) {
	$.ajaxSetup({cache: false });
    top = arguments[2] ? arguments[2] : "true";
    loadingshow();
	if(type == "location"){
		location=url;
	}
	else
	{
	    $("#main").load(url, function () {			
			//alert(result);
            if(top == "true")
	            $("html,body").animate({ scrollTop: 0 }, 300);				 
	        loadinghide();
        });
	}
}

function goload(url, obj){
	loadingshow();
	$("#"+obj).load(url, function(){
		loadinghide();
	});
}

function loadingshow(v){
	var vv = (v == '' || typeof(v) == 'undefined') ? '数据加载中' : v;
	$("body").append("<div id=\"loadingToast\"><div class=\"weui-mask_transparent\"></div><div class=\"weui-toast\"><i class=\"weui-loading weui-icon_toast\"></i><p class=\"weui-toast__content\">"+vv+"</p></div></div>");
}
function loadinghide(){
	$("#loadingToast").remove();
}
function confirmshow(title, content, href1txt, href1, href2txt, href2){
	$("body").append("<div class=\"js_dialog\" id=\"iosDialog1\"><div class=\"weui-mask\"></div><div class=\"weui-dialog\"><div class=\"weui-dialog__hd\"><strong class=\"weui-dialog__title\">"+title+"</strong></div><div class=\"weui-dialog__bd\">"+content+"</div><div class=\"weui-dialog__ft\"><a href=\""+href1+"\" class=\"weui-dialog__btn weui-dialog__btn_default\">"+href1txt+"</a><a href=\""+href2+"\" class=\"weui-dialog__btn weui-dialog__btn_primary\">"+href2txt+"</a></div></div></div>");
}
function confirmhide(){
	$("#iosDialog1").remove();
}


function jsgo(id, url){
    go(url + (url.indexOf("?") >= 0 ? "&" : "?") + "page=" + id);
    //$("html,body").animate({ scrollTop: $(".rightdl").offset().top }, 300);
}

function del(obj, url, id, str){
	if(confirm(str)){
		$.post(url,
			{"r": Math.random(), "id": id },
			function (data) {
			    if (data.indexOf("alert") >= 0)
			        eval(data);
                else
				    $(obj).parents("tr:first").remove();
			}
		);
	}
}

function delmsg(url, obj, id){
	if(confirm('确认删除？')){
		$.post(url,
			{"id": id, "r": Math.random() },
			function (data) {
			    if (data == "ok")
			        $(obj).parents("tr:first").remove();
			    else
			        eval(data);
			}
		);
	}
}

function windowOpen(url){
　	var aa=window.open("","newurl","width=900,height=600,scrollbars=no");
	setTimeout(function(){
		aa.location=url;
	}, 100);
	aa.focus();　
}

function readnickname(obj){
	$.post('/user/readnickname', 
		{'value': $(obj).val()}, 
		function(data){
			$(obj).siblings('span').html(data);
		});
}

function isMobile(str){
	if(typeof(str)!='undefined' && str!=null && str!=""){
		var pattern =  /^(1[3|4|5|7|8]\d{9}$)/; 
		return pattern.test(str);
	}
	else
		return false;
}

function addUploadrow(obj){
	var s = obj.parentNode.innerHTML;
	s = s.replace('<input type="button" name="button" value="+" class="weui-btn weui-btn_mini weui-btn_warn" onclick="addUploadrow(this);">', '');
	
	var l = document.getElementsByName("pictures[]").length;
	var e;
	var a;
	var siteurl = "uploadmyfile\/index\/pictures"+l+".html";

  var newrow = '<p style="padding:2px 0;"><label>&nbsp;</label><input name="pictures[]" type="text" id="pictures'+l+'" class="inputtxt width400" value=""/> <input name="button" type="button" class="weui-btn weui-btn_mini weui-btn_primary" onClick="popBox(\''+siteurl+'\',\'文件上传\',500,300);" value="上传图片"/> <input type="button" name="buttons'+l+'" class="weui-btn weui-btn_mini weui-btn_warn" value="-" onclick="delUploadrow(this);"></p>';
	$(obj.parentNode).append(newrow);
  }
  
function delUploadrow(obj){	
	//var s = obj.parentNode.innerHTML;
	$(obj.parentNode).remove(); 
}


function reset_tippos(){
	var winW = $(window).width();
	var winH = $(window).height();
	var tipW = $("#tip_block").width();
	var tipH = $("#tip_block").height();	
	var ml = (winW-tipW)/2;
	var mt = (winH-tipH)/2;
	$("#tip_block").css("margin-left",ml);
	$("#tip_block").css("margin-top",mt);
}

function popBox(srcUrl,title,width,height){
		
	if(srcUrl == "" || srcUrl == "undefined"){
		alert('处理文件不能为空');
		return false;	
	}
	var popid = "p"+parseInt(1000*Math.random());
	var str = '<div id="'+popid+'" class="popBox_bg" onclick="close_popBox(\''+popid+'\');"></div><div id="pop'+popid+'" class="popBox"><div class="popBox_title"><h2>'+title+'</h2><span onclick="close_popBox(\''+popid+'\');"></span></div><div class="popBox_content" id="pop_'+popid+'_content"></div><div>';
	$("body").append(str);
	var if_width = width-20;
	var if_height = height-50;
	//var srcUrl = srcUrl+popid;
	$("#pop_"+popid+"_content").html('<iframe id="if_'+popid+'" frameborder="0" src="'+srcUrl+'" style="width: '+if_width+'px; height: '+if_height+'px;background:#fff;"></iframe>');

	reset_pos(popid,width,height);
}

function viewImg(id){
	var pic = $("#"+id).val();
	if(pic.length < 10){ return false; }
	var popid = "p"+parseInt(1000*Math.random());
	var str = '<div id="'+popid+'" class="popBox_bg" onclick="close_popBox(\''+popid+'\');"></div><div id="pop'+popid+'" class="popBox" style="width:auto;height:auto;margin:100px 40%;"><div class="popBox_content" id="pop_'+popid+'_content"><img src="'+pic+'" /></div><div>';
	$("body").append(str);
	var if_width = width-20;
	var if_height = height-50;
	//var srcUrl = srcUrl+popid;

	reset_pos(popid,width,height);
	
}

function reset_pos(popid,width,height){
	var w = $(window).width();
	var h = document.body.clientHeight;
	var toTop = document.body.scrollTop;
	var dh = $(window).height();
	//$$("popbox_bg").style.top = toTop;
	$("#pop"+popid).css("width",width-20);
	$("#pop"+popid).css("height",height-20);

	var t = (dh-height)/2+toTop;
	var s = (w-width+10)/2;
	$("#pop"+popid).css("margin-top",t);
	$("#pop"+popid).css("margin-left",s);
	$("body").css("overflow","hidden");
}

function close_popBox(popid){
	$("#"+popid).remove();
	$("#pop"+popid).remove();
	$("#if"+popid).remove();
	$("body").css("overflow","");
}

function innerClosePopbox(){
	var a = $(".popBox_bg").attr("id");
	//$("#"+a)
	close_popBox(a);
	//$(".popBox_bg").remove();
//	$(".popBox").remove();
//	$("body").css("overflow","");
	location.reload();
}

function setvalue(id,val){
	$("#"+id).val(val);
}

function setundisplay(){
	$(".popBox").remove();
	$(".popBox_bg").remove();
	$("body").css("overflow","");	
}
