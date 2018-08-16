<!-- Main content -->
<section class="content container-fluid">
	<form role="form" id="dataForm">
          <div class="box-body">
          	<div class="alert alert-warning alert-dismissible fade in" style="display:none;" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
              <strong id="warninginfo"></strong>
            </div>
            <div class="form-group">
              <label for="title">上级栏目<b style="color:#f00;">*</b></label>
              <select class="form-control" name="fid" id="fid">
              	 <option value="">请选择</option>
                 <?php
                 	foreach($cate_list as $clist):
					$t_sel = isset($row['storeid']) && $clist['cat_id'] == $row['storeid']  ? 'selected' : '';
				 ?>
                 <option value="<?php echo $clist['cat_id'];?>" <?php echo $t_sel;?>><?php echo $clist['cat_name'];?></option>
                  <?php
                  	endforeach;
				  ?>
                </select>
            </div>
            <div class="form-group">
              <label for="cat_name">名称<b style="color:#f00;">*</b></label>
              <input type="text" class="form-control" name="cat_name" id="cat_name" value="<?php echo isset($row['cat_name']) ? $row['cat_name'] : '';?>">
            </div>
            <div class="form-group">
              <label for="cat_rank">排序ID<b style="color:#f00;">*</b></label>
              <input type="number" class="form-control" name="cat_rank" id="cat_rank" value="<?php echo isset($row['cat_rank']) ? $row['cat_rank'] : '100';?>">
              <span>数字越小，排名越靠前</span>
            </div>
            
                        
          </div>

          <div class="box-footer">
          	<input type="hidden" name="cat_id" value="<?php echo isset($row['cat_id']) ? $row['cat_id'] : set_value('cat_id');?>" />
                <input type="hidden" name="action" value="<?php echo isset($act) ? $act : set_value('action');?>" />
            <button type="button" class="btn btn-primary" onClick="checkAdsCatesMod();">确定提交</button>　
            <button type="button" class="btn btn-default" class="close" data-dismiss="modal" aria-label="Close">关闭</button>
          </div>
    </form>
</section>
<script>

function checkAdsCatesMod(){

	var cat_name = $("#cat_name").val();
	//var title 	= $("#title").val();
	
	if(cat_name == ''){
		$("#warninginfo").html('请输入类别名称').parent().show(300);
		$("#start_number").focus();
		return false;		
	}else{
		$("#warninginfo").html('').parent().hide(300);		
	}

	var params = $('#dataForm').serialize();
	var url = "ajax_admin/adsCatesMod";
	
	//alert(params);
//	return false;

	
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
				go('<?php echo admin_url()."adscates/index"?>');	
			}
		}
	});
	
}
</script>