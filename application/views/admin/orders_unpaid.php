<section class="content-header">
  <h1>
    订单核查
    <small>Order Query</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo site_url(admin_url().'home');?>"><i class="fa fa-dashboard"></i> 首页</a></li>
    <li class="active">订单核查</li>
  </ol>
</section>

<!-- Main content -->
<section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">订单核查</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            
            <form role="form" id="dataForm" class="form-horizontal">	
              <div class="box-body">
               	<div class="alert alert-warning alert-dismissible fade in" style="display:none;" role="alert">
                  <strong id="warninginfo"></strong>
                </div>
                <div class="form-group">
                  <label for="oid" class="col-sm-2 control-label">订单号</label>

                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="oid" placeholder="" name="oid" value="">
                    <span class="help-block">请输入查询订单号</span>
                  </div>
                </div>
                
              </div>
              <!-- /.box-body -->

              <div class="box-footer text-center">
                <button type="button" class="btn btn-primary" onClick="orderQuery();">确定提交</button>
              </div>
            </form>
            
            <form role="form" id="dataForm1" class="form-horizontal" style="display:none">	
              <div class="box-body">
               	<div class="alert alert-warning alert-dismissible fade in" style="display:none;" role="alert">
                  <strong id="warninginfo"></strong>
                </div>
                <div class="form-group" style="padding:0 0 100px;">
                  
                  <div class="col-sm-12"><p>订单号：<span id="paid_number"></span></p></div>
                  <div class="col-sm-12"><p>订单状态：<span id="paid_type"></span></p></div>
                  <div class="col-sm-12"><p>支付时间：<span id="paid_time"></span></p></div>
                  <div class="col-sm-12"><p>支付金额：<span id="paid_amount"></span></p></div>
                  <div class="col-sm-12"><p>商户状态：<span id="paid_status"></span></p></div>
                </div>
                
              </div>
              <!-- /.box-body -->

              <div class="box-footer text-center" style="display:none" id="btn">
              	<input type="hidden" name="toid" id="toid" value="" />
                <input type="hidden" name="time_end" id="time_end" value="" />
                <button type="button" class="btn btn-primary" onClick="orderUpdate();">更新订单</button>
              </div>
            </form>
            
            <div class="form-group" id="norecord" style="display:none">
              暂无记录
            </div>
            
          </div>
          <!-- /.box -->

        </div>
        <!--/.col (left) -->
      </div>
      <!-- /.row -->
    </section>
<script>
function orderQuery(){

	var oid = $("#oid").val();
	
	if(oid == ''){
		$("#warninginfo").html('请输入正确的订单号').parent().show(300);
		$("#oid").focus();
		return false;		
	}else{
		$("#warninginfo").html('').parent().hide(300);		
	}

	var params = $('#dataForm').serialize();
	var url = "ajax_admin/orderQuery";

	
	loadingshow('数据更新中...');
	$.ajax({
		type: "post",
		url: url,
		dataType: "json",
		data: params,
		success: function(msg){
			loadinghide();
			if(msg.result_code == 'SUCCESS' && msg.return_code == 'SUCCESS'){
				$("#dataForm1").show();
				$("#dataForm").hide();
				$("#paid_number").html(msg.out_trade_no);
				var otype = msg.result_code == 'SUCCESS' ? '已支付' : '未支付';
				$("#paid_type").html(otype);	
				$("#paid_time").html(msg.time_end);	
				$("#paid_amount").html(msg.total_fee/100);
				$("#toid").val(msg.out_trade_no);
				$("#time_end").val(msg.time_end);
				var status = msg.status == 0 ? '待付款' : ( msg.status == 4 ? '已付款' : '其它' );
				$("#paid_status").html(status);
				if(msg.status == 0){
					$("#btn").show();
				}
			}else{
				$("#norecord").show();		
			}
		}
	});
	
}

function orderUpdate(){
	var toid = $("#toid").val();
	
	if(toid == ''){
		$("#warninginfo").html('请输入正确的订单号').parent().show(300);
		$("#oid").focus();
		return false;		
	}else{
		$("#warninginfo").html('').parent().hide(300);		
	}

	var params = $('#dataForm1').serialize();
	var url = "ajax_admin/orderUpdate";

	
	loadingshow('数据更新中...');
	$.ajax({
		type: "post",
		url: url,
		dataType: "json",
		data: params,
		success: function(msg){
			loadinghide();
			if(msg.status == 200){
				go('<?php echo admin_url()."orders/wepayUnpaidOrder";?>');	
			}else{
				$("#norecord").show();		
			}
		}
	});	
}
</script>