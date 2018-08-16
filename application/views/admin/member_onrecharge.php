<!-- Main content -->
<section class="content container-fluid">
	<form role="form" id="dataForm" class="form-horizontal">
          <div class="box-body no-padding">
          	<div class="alert alert-warning alert-dismissible fade in" style="display:none;" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
              <strong id="warninginfo"></strong>
            </div>
            
            <div class="form-group">
              <label for="card_number" class="col-sm-2 control-label">充值卡卡号</label>

              <div class="col-sm-10">
                <input type="text" class="form-control" id="card_number" placeholder="" name="card_number" value="">
              </div>
            </div>
            
            <div class="form-group">
              <label for="card_pass" class="col-sm-2 control-label">充值卡密码</label>

              <div class="col-sm-10">
                <input type="text" class="form-control" id="card_pass" placeholder="" name="card_pass" value="">
              </div>
            </div>
            
            <div class="form-group">
              <label for="phone" class="col-sm-2 control-label">充值手机号</label>

              <div class="col-sm-10">
                <input type="text" class="form-control" id="phone" placeholder="" name="phone" value="">
              </div>
            </div>
            
            <div class="form-group">
              <label for="rephone" class="col-sm-2 control-label">确认手机号</label>

              <div class="col-sm-10">
                <input type="text" class="form-control" id="rephone" placeholder="" name="rephone" value="">
              </div>
            </div>
            
                     
          </div>

          <div class="box-footer">
            <button type="button" class="btn btn-primary" onClick="onrecharge();">确定提交</button>　
            <button type="button" class="btn btn-default" class="close" data-dismiss="modal" aria-label="Close">关闭</button>
          </div>
    </form>
</section>
<script>

function onrecharge(){

	var card_number = $("#card_number").val();
	var card_pass = $("#card_pass").val();
	var phone = $("#phone").val();
	var rephone = $("#rephone").val();
	
	if(card_number == '' || card_number.length != 10){
		$("#warninginfo").html('请输入正确的充值卡卡号').parent().show(300);
		$("#card_number").focus();
		return false;		
	}else{
		$("#warninginfo").html('').parent().hide(300);		
	}
	
	if(card_pass == '' || card_pass.length != 6){
		$("#warninginfo").html('请输入正确的充值卡密码').parent().show(300);
		$("#card_pass").focus();
		return false;		
	}else{
		$("#warninginfo").html('').parent().hide(300);		
	}
	
	if(phone == '' || phone.length != 11){
		$("#warninginfo").html('请输入正确的手机号码').parent().show(300);
		$("#phone").focus();
		return false;		
	}else{
		$("#warninginfo").html('').parent().hide(300);		
	}
	
	if(rephone == '' || rephone.length != 11){
		$("#warninginfo").html('请输入正确的确认手机号码').parent().show(300);
		$("#rephone").focus();
		return false;		
	}else{
		$("#warninginfo").html('').parent().hide(300);		
	}
	
	if(rephone !== phone){
		$("#warninginfo").html('两次手机号码输入不一致').parent().show(300);
		$("#rephone").focus();
		return false;		
	}else{
		$("#warninginfo").html('').parent().hide(300);		
	}
	
	var params = $('#dataForm').serialize();
	var url = "ajax_admin/onrecharge";
	
	//alert(params);

	
	loadingshow('数据更新中...');
	$.ajax({
		type: "post",
		url: url,
		dataType: "json",
		data: params,
		success: function(msg){
			//
			loadinghide();
			if(msg.status == 200){
				// $.toast("充值成功",2000);
				 alert('充值成功');
				 $(".close").click();
				 go('<?php echo admin_url()."member/recharge/"?>');	
			}
			else if(msg.status == 1){
				alert('请核对卡号信息');
			}
			else if(msg.status == 2){
				alert('请核对密码信息');
			}
			else if(msg.status == 3){
				alert('请核对手机号码');
			}
			else if(msg.status == 4){
				alert('请核对确认手机号码');
			}
			else if(msg.status == 5){
				alert('两次手机输入不一致');
			}
			else if(msg.status == 6){
				alert('请先注册');
			}
			else if(msg.status == 7){
				alert('充值卡已用过或不存在');
			}
			else if(msg.status == 8){
				alert('请先完善个人信息再充值');
			}
			else if(msg.status == 9){
				alert('一个账号只能用一张赠送卡');
			}
		}
	});
	
}
</script>