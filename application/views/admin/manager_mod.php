<!-- Main content -->
<section class="content container-fluid">
	<form role="form">
          <div class="box-body">
          	<div class="alert alert-warning alert-dismissible fade in" style="display:none;" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
              <strong id="warninginfo"></strong>
            </div>
            <div class="form-group">
              <label for="title">用户组<b style="color:#f00;">*</b></label>
              <select class="form-control" class="usergroup" id="usergroup">
              	 <option value="">请选择</option>
                 <?php
                 	foreach($cate_list as $clist):
					$t_sel = $clist['id'] == $row['usergroup'] ? 'selected' : '';
				 ?>
                 <option value="<?php echo $clist['id'];?>" <?php echo $t_sel;?>><?php echo $clist['title'];?></option>
                  <?php
                  	endforeach;
				  ?>
                </select>
            </div>
            <div class="form-group">
              <label for="username">用户名<b style="color:#f00;">*</b></label>
              <input type="text" class="form-control" id="username" name="username" value="<?php echo isset($row['username']) ? $row['username'] : '';?>" placeholder="请输入用户名">
            </div>
            <div class="form-group">
              <label for="realname">真实姓名</label>
              <input type="text" class="form-control" id="realname" name="realname" value="<?php echo isset($row['realname']) ? $row['realname'] : '';?>" placeholder="请输入真实姓名">
            </div>
            <div class="form-group">
              <label for="password">密码</label>
              <input type="password" class="form-control" id="password" name="password" value="" placeholder="请输入密码 ">
            </div>
            <div class="form-group">
              <label for="repassword">确认密码</label>
              <input type="password" class="form-control" id="repassword" name="repassword" value="" placeholder="请输入确认密码 ">
            </div>
            <div class="form-group">
              <label for="InputRank">排序ID<b style="color:#f00;">*</b></label>
              <input type="text" class="form-control" id="rank" name="rank" value="<?php echo isset($row['rank']) ? $row['rank'] : '';?>" placeholder="请输入排序ID">
            </div>
            <div class="radio">
              <label>
                <input type="radio" name="state" id="state" value="1"  <?php echo isset($row['state']) && $row['state'] == 1 ? 'checked' : '';?>>是
              </label>
              <label>
                <input type="radio" name="state" id="state1" value="0" <?php echo !isset($row['state']) || (isset($row['state']) && $row['state'] == 0) ? 'checked' : '';?>>否
              </label>
            </div>
          </div>

          <div class="box-footer">
          	<input type="hidden" name="id" id="id" value="<?php echo isset($row['id']) ? $row['id'] : '';?>" />
            <input type="hidden" name="action" id="action" value="<?php echo isset($act) ? $act : 'add';?>" />
            <button type="button" class="btn btn-primary" onClick="checkManagerMod();">确定提交</button>　
            <button type="button" class="btn btn-default" class="close" data-dismiss="modal" aria-label="Close">关闭</button>
          </div>
    </form>
</section>
<script>
function checkManagerMod(){
	var usergroup 	= $("#usergroup").val();
	var username  	= $("#username").val();
	var realname	= $("#realname").val();
	var password  	= $("#password").val();
	var repassword  = $("#repassword").val();
	var rank  		= $("#rank").val();
	var state		= $("#state").val();
	var action  	= $("#action").val();
	var id  		= $("#id").val();
	
	

	if( usergroup == '' || typeof(usergroup) == 'undefined' ){
		$("#warninginfo").html('请选择用户组').parent().show(300);
		$("#usergroup").focus();
		return false;	
	}else{
		$("#warninginfo").html('').parent().hide(300);	
	}
	
	if( username == '' ){
		$("#warninginfo").html('请输入用户名').parent().show(300);
		$("#username").focus();
		return false;	
	}else{
		$("#warninginfo").html('').parent().hide(300);	
	}
	
	if(action == 'add'){
		if( password == '' ){
			$("#warninginfo").html('请输入密码').parent().show(300);
			$("#password").focus();
			return false;	
		}else{
			$("#warninginfo").html('').parent().hide(300);	
		}
		
		if( repassword == '' ){
			$("#warninginfo").html('请输入确认密码').parent().show(300);
			$("#repassword").focus();
			return false;	
		}else{
			$("#warninginfo").html('').parent().hide(300);	
		}	
	}
	
	if(action == 'mod'){
		if( password !== repassword ){
			$("#warninginfo").html('两次密码输入不一致').parent().show(300);
			$("#password").focus();
			return false;	
		}else{
			$("#warninginfo").html('').parent().hide(300);	
		}	
	}
	
	if( rank == '' ){
		$("#warninginfo").html('请输入排序ID').parent().show(300);
		$("#rank").focus();
		return false;	
	}else{
		$("#warninginfo").html('').parent().hide(300);	
	}
	loadingshow('数据更新中...');
	$.post("ajax_admin/managerMod", { Action: "post", usergroup: usergroup, username: username, realname: realname, password: password,  repassword: repassword, rank: rank, state: state, action: action, id: id }
    , function(value, textStatus) {
			loadinghide();
			if (value.err == '') {
				//alert('更新成功');
				loadingshow('更新成功');
				loadinghide();
				$(".close").click();
				refreshData();
			}else{
				alert(value.err);	
			}
		}, "json");
	
	
	
		
}


function refreshData(){
	go('<?php echo site_url(admin_url().'manager/index');?>');	
}
</script>