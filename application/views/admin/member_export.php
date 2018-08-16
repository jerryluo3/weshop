<!-- Main content -->
<section class="content container-fluid">
	<form role="form" id="dataForm" class="form-horizontal">
          <div class="box-body no-padding">
          	<div class="alert alert-warning alert-dismissible fade in" style="display:none;" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
              <strong id="warninginfo"></strong>
            </div>
            
            
            <div class="form-group">
              <label for="s_id" class="col-sm-2 control-label">开始ID</label>

              <div class="col-sm-10">
                <input type="text" class="form-control" id="s_id" placeholder="" name="s_id" value="">
              </div>
            </div>
            
            <div class="form-group">
              <label for="e_id" class="col-sm-2 control-label">结束ID</label>

              <div class="col-sm-10">
                <input type="text" class="form-control" id="e_id" placeholder="" name="e_id" value="">
              </div>
            </div>
            
                     
          </div>

          <div class="box-footer">
            <button type="button" class="btn btn-primary" onClick="exportMember();">确定提交</button>　
            <button type="button" class="btn btn-default" class="close" data-dismiss="modal" aria-label="Close">关闭</button>
          </div>
    </form>
</section>
<script>

function exportMember(){

	var s_id = $("#s_id").val();
	var e_id = $("#e_id").val();	

	var params = $('#dataForm').serialize();
	var url = "ajax_admin/exportMember";
	
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
				loadingshow('更新成功');
				loadinghide();
				$(".close").click();
			}
		}
	});
	
}
</script>