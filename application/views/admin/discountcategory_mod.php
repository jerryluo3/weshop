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
              <select class="form-control" name="cat_fid" id="cat_fid">
              	 <option value="">请选择</option>
                 <?php
					echo $catesoption;
				?>
                </select>
            </div>
            <div class="form-group">
              <label for="title">名称<b style="color:#f00;">*</b></label>
              <span class="input-group-btn">
                  <input type="text" class="form-control" id="cat_name" name="cat_name" value="<?php echo isset($list['cat_name']) ? $list['cat_name'] : set_value('cat_name');?>" placeholder="">
                </span>
            </div>
            <div class="form-group">
              <label for="title">图片<b style="color:#f00;">*</b></label>
              <div class="input-group input-group-sm">
                <input type="text" class="form-control" name="cat_img" id="picture" value="<?php echo isset($list['cat_img']) ? $list['cat_img'] : set_value('cat_img');?>">
                    <span class="input-group-btn">
                      <button type="button" class="btn btn-info btn-flat" onClick="popBox('<?php echo site_url('uploadmyfile/');?>','文件上传',500,300);">上传图片</button>
                    </span>
              </div>
            </div>
            <div class="form-group">
              <label for="cat_rank">排序ID<b style="color:#f00;">*</b></label>
              <input type="text" class="form-control" id="cat_rank" placeholder="" name="cat_rank" value="<?php echo isset($list['cat_rank']) ? $list['cat_rank'] : 100;?>">
            </div>
                      
          </div>

          <div class="box-footer">
          	<input type="hidden" name="cat_id" value="<?php echo isset($list['cat_id']) ? $list['cat_id'] : set_value('cat_id');?>" />
            <input type="hidden" name="action" value="<?php echo isset($act) ? $act : set_value('action');?>" />
            <button type="button" class="btn btn-primary" onClick="checkVideoCatMod();">确定提交</button>　
            <button type="button" class="btn btn-default" class="close" data-dismiss="modal" aria-label="Close">关闭</button>
          </div>
    </form>
</section>
<script>

$(function(){	

})

function checkVideoCatMod(){

	var cat_name = $("#cat_name").val();
	//var title 	= $("#title").val();
	
	if(cat_name == ''){
		alert('请输入栏目名称');
		return false;	
	}	
	
	var params = $('#dataForm').serialize();
	var url = "ajax_admin/discountCatMod";
	
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
				go('<?php echo admin_url()."discountcategory/index"?>');	
			}
		}
	});
	
}
</script>