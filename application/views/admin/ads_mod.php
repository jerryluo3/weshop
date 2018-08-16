<!-- Main content -->
<link rel="stylesheet" href="data/<?php echo admin_url();?>plugins/datepicker/bootstrap-datepicker.min.css">
<script src="data/<?php echo admin_url();?>plugins/datepicker/bootstrap-datepicker.min.js"></script>
<section class="content container-fluid">
	<form role="form" id="dataForm">
          <div class="box-body">
          	<div class="alert alert-warning alert-dismissible fade in" style="display:none;" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
              <strong id="warninginfo"></strong>
            </div>
            <div class="form-group">
              <label for="title">栏目<b style="color:#f00;">*</b></label>
              <select class="form-control" name="ads_cid" id="ads_cid">
              	 <option value="">请选择</option>
                 <?php
					foreach($cate_list as $clist):
						$checked = isset($row['ads_cid']) && ($clist['cat_id'] == $row['ads_cid']) ? 'selected' : '';
						echo '<option value="'.$clist['cat_id'].'" '.$checked.'>'.$clist['cat_name'].'</option>';
					endforeach
				  ?>
                </select>
            </div>
            <div class="form-group">
              <label for="ads_title">名称<b style="color:#f00;">*</b></label>
              <input type="text" class="form-control" name="ads_title" id="ads_title" value="<?php echo isset($row['ads_title']) ? $row['ads_title'] : '';?>">
            </div>
            <div class="form-group">
              <label for="picture">图片<b style="color:#f00;">*</b></label>
              <div class="input-group input-group-sm">             
                  <div class="input-group-addon">
                        <i class="fa fa-image"></i>
                      </div>
                    <input type="text" class="form-control" name="ads_picture" id="picture" value="<?php echo isset($row['ads_picture']) ? $row['ads_picture'] : set_value('ads_picture');?>">
                        <span class="input-group-btn">
                          <button type="button" class="btn btn-info btn-flat" onClick="popBox('<?php echo site_url('uploadmyfile/');?>','文件上传',500,300);">上传图片</button>
                        </span>
                  </div>
              </div>
            </div>
            
            <div class="form-group">
              <label for="ads_url">链接</label>
              <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-link"></i>
                  </div>
                  <input type="text" class="form-control" id="ads_url" placeholder="" name="ads_url" value="<?php echo isset($row['ads_url']) ? $row['ads_url'] : set_value('ads_url');?>">
                </div>
            </div>
            
            <div class="form-group">
              <label for="stime">开始时间</label>

                <div class="input-group input-group-sm no-padding">
                  <div class="input-group-addon">
                    <i class="fa fa-clock-o"></i>
                  </div>
                  <input type="text" class="form-control" id="stime" placeholder="" name="ads_starttime" value="<?php echo isset($row['ads_starttime']) && $row['ads_starttime'] > 0 ? date('Y-m-d',$row['ads_starttime']) : set_value('ads_starttime');?>">
                </div>
				<span>开始生效时间，默认立即生效</span>
            </div>
            
            <div class="form-group">
              <label for="etime">结束时间</label>

                <div class="input-group input-group-sm">
                  <div class="input-group-addon">
                    <i class="fa fa-clock-o"></i>
                  </div>
                  <input type="text" class="form-control" id="etime" placeholder="" name="ads_endtime" value="<?php echo isset($row['ads_endtime']) && $row['ads_endtime'] > 0 ? date('Y-m-d',$row['ads_endtime']) : set_value('ads_endtime');?>">
                </div>
				<span>结束时间，默认永久有效</span>
            </div>
            
            <div class="form-group">
              <label for="ads_state">状态</label>　　
                <div class="input-group input-group-sm" style="display:inline-block;">
                  <label>
                    <input type="radio" name="ads_state" id="ads_state" value="1"  <?php echo !isset($row['ads_state']) || isset($row['ads_state']) && $row['ads_state'] == 1 ? 'checked' : '';?>>是
                  </label>
                  <label>
                    <input type="radio" name="ads_state" id="ads_state1" value="0" <?php echo (isset($row['ads_state']) && $row['ads_state'] == 0) ? 'checked' : '';?>>否
                  </label>
                </div>
            </div>
            
            <div class="form-group">
              <label for="ads_rank">排序ID<b style="color:#f00;">*</b></label>
              <input type="number" class="form-control" name="ads_rank" id="ads_rank" value="<?php echo isset($row['ads_rank']) ? $row['ads_rank'] : '100';?>">
              <span>数字越小，排名越靠前</span>
            </div>
            
                        
          </div>

          <div class="box-footer">
          	<input type="hidden" name="ads_id" value="<?php echo isset($row['ads_id']) ? $row['ads_id'] : set_value('ads_id');?>" />
                <input type="hidden" name="action" value="<?php echo isset($act) ? $act : set_value('action');?>" />
            <button type="button" class="btn btn-primary" onClick="checkAdsMod();">确定提交</button>　
            <button type="button" class="btn btn-default" class="close" data-dismiss="modal" aria-label="Close">关闭</button>
          </div>
    </form>
</section>
<script>
$(function () {

	$('#stime').datepicker({
	  autoclose: true
	})
	$('#etime').datepicker({
	  autoclose: true
	})

});
  
  
function checkAdsMod(){

	var ads_cid = $("#ads_cid").val();
	var ads_title = $("#ads_title").val();
	//var title 	= $("#title").val();
	
	if(ads_cid == ''){
		$("#warninginfo").html('请输入选择类别').parent().show(300);
		$("#ads_cid").focus();
		return false;		
	}else{
		$("#warninginfo").html('').parent().hide(300);		
	}
	
	if(ads_title == ''){
		$("#warninginfo").html('请输入标题').parent().show(300);
		$("#ads_title").focus();
		return false;		
	}else{
		$("#warninginfo").html('').parent().hide(300);		
	}

	var params = $('#dataForm').serialize();
	var url = "ajax_admin/adsMod";
	
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
				go('<?php echo admin_url()."ads/index"?>');	
			}
		}
	});
	
}
</script>