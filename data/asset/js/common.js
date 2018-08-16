

function AddFavorite(sURL, sTitle) {
    try {
        window.external.addFavorite(sURL, sTitle);
    } catch (e) {
        try {
            window.sidebar.addPanel(sTitle, sURL, "");
        } catch (e) {
            alert("加入收藏失败，请使用Ctrl+D进行添加");
        }
    }
}
//设为首页 <a onclick="SetHome(this,window.location)" >设为首页</a>
function SetHome(obj, vrl) {
    try {
        obj.style.behavior = 'url(#default#homepage)';
        obj.setHomePage(vrl);
    } catch (e) {
        if (window.netscape) {
            try {
                netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
            } catch (e) {
                alert("此操作被浏览器拒绝！\n请在浏览器地址栏输入“about:config”并回车\n然后将 [signed.applets.codebase_principal_support]的值设置为'true',双击即可。");
            }
            var prefs = Components.classes['@mozilla.org/preferences-service;1'].getService(Components.interfaces.nsIPrefBranch);
            prefs.setCharPref('browser.startup.homepage', vrl);
        }
    }
}


function userLogin(){
	var login_user = $("#login_user").val();
	var login_pass = $("#login_pass").val();
	
	if(login_user == ''){
		alert('请输入账号');
		return false;	
	}
	
	if(login_pass == ''){
		alert('请输入密码');
		return false;	
	}
	
	var params = 'login_user='+login_user+'&login_pass='+login_pass;
	var url = "ajax/userLogin";
	
	//alert(params);
//	return false;
	
	$.ajax({
		type: "post",
		url: url,
		dataType: "json",
		data: params,
		success: function(msg){
			if(msg.status == 200){
				alert('登录成功');
				location.href = 'forum/index'
			}
			if(msg.status == 1){
				alert('账号密码错误');
				return false
			}
		}
	});	
	
}

function loginQuit(){
	if(confirm('确定要退出吗？')){
		var params = '';
		var url = "ajax/loginQuit";
		
		//alert(params);
	//	return false;
		
		$.ajax({
			type: "post",
			url: url,
			dataType: "json",
			data: params,
			success: function(msg){
				if(msg.status == 200){
					alert('退出成功');
					location.href = 'forum/index'
				}
			}
		});	
	}
}