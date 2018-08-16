<link rel="stylesheet" href="data/<?php echo admin_url();?>plugins/datepicker/bootstrap-datepicker.min.css">
<script src="data/<?php echo admin_url();?>plugins/datepicker/bootstrap-datepicker.min.js"></script>
<!-- Main content -->
<section class="content container-fluid">
	<form role="form" id="dataForm">
          <div class="box-body">
          	<div class="alert alert-warning alert-dismissible fade in" style="display:none;" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
              <strong id="warninginfo"></strong>
            </div>
            <div class="form-group">
              <label for="cid">栏目<b style="color:#f00;">*</b></label>
              <select class="form-control" name="cid" id="cid">
              	 <option value="">请选择</option>
                 <?php
					echo $catesoption;
				?>
                </select>
            </div>
            <div class="form-group">
              <label for="title">名称<b style="color:#f00;">*</b></label>
              <span class="input-group-btn">
                  <input type="text" class="form-control" id="title" name="title" value="<?php echo isset($row['title']) ? $row['title'] : set_value('title');?>" placeholder="">
                </span>
            </div>
            <div class="form-group">
              <label for="picture">图片<b style="color:#f00;">*</b></label>
              <div class="input-group input-group-sm">
                <input type="text" class="form-control" name="picture" id="picture" value="<?php echo isset($row['picture']) ? $row['picture'] : set_value('picture');?>">
                    <span class="input-group-btn">
                      <button type="button" class="btn btn-info btn-flat" onClick="popBox('<?php echo site_url('uploadmyfile/');?>','文件上传',500,300);">上传图片</button>
                    </span>
              </div>
            </div>
            
            <div class="form-group">
              <label for="nums">数量</label>
              <input type="number" class="form-control" id="nums" placeholder="" name="nums" value="<?php echo isset($row['nums'])  ? $row['nums'] : '';?>">
            </div>
            
            <div class="form-group">
              <label for="nums">有效期至</label>
              <input type="text" class="form-control" id="endtime" placeholder="" name="endtime" value="<?php echo isset($row['endtime']) && $row['endtime'] > 0  ? date('Y-m-d',$row['endtime']) : '';?>">
            </div>
            
            <div class="form-group">
              <label for="usetime">使用时间</label>
              <input type="text" class="form-control" id="usetime" placeholder="" name="usetime" value="<?php echo isset($row['usetime'])  ? $row['usetime'] : '';?>">
            </div>
            <div class="form-group">
              <label for="desc">说明</label>
              <input type="text" class="form-control" id="desc" name="desc" value="<?php echo isset($row['desc']) ? $row['desc'] : '';?>" placeholder="">
            </div>
            <div class="form-group">
              <label for="address">地址</label>
              <input type="text" class="form-control" id="address" name="address" value="<?php echo isset($row['address']) ? $row['address'] : '';?>" placeholder="">
            </div>
            <div class="form-group">
              <label for="phone">电话</label>
              <input type="text" class="form-control" id="phone" name="phone" value="<?php echo isset($row['phone']) ? $row['phone'] : '';?>" placeholder="">
            </div>
            
            <div class="form-group">
              <label for="rank">排序ID<b style="color:#f00;">*</b></label>
              <input type="text" class="form-control" id="rank" placeholder="" name="rank" value="<?php echo isset($row['rank']) ? $row['rank'] : 100;?>">
            </div>
            
            <div class="form-group">
              <label for="iscommend">推荐</label>
              
                <div class="form-group" style="display:inline-block">
                  <div class="radio" style="display:inline-block">
                  	　
                    <label>
                      <input type="radio" name="iscommend" id="iscommend" value="1" <?php echo !isset($row['iscommend']) || (isset($row['iscommend']) && $row['iscommend'] == 1) ? 'checked' : ''?>>
                      是
                    </label>
                  </div>
                  <div class="radio" style="display:inline-block">
                    <label>
                      <input type="radio" name="iscommend" id="iscommend1" value="0" <?php echo (isset($row['iscommend']) && $row['iscommend'] == 0) ? 'checked' : ''?>>
                      否
                    </label>
                  </div>
                </div>
            </div>
            
            <div class="form-group">
              <label for="state">状态</label>
              
                <div class="form-group" style="display:inline-block">
                  <div class="radio" style="display:inline-block">
                  	　
                    <label>
                      <input type="radio" name="state" id="state" value="1" <?php echo !isset($row['state']) || (isset($row['state']) && $row['state'] == 1) ? 'checked' : ''?>>
                      是
                    </label>
                  </div>
                  <div class="radio" style="display:inline-block">
                    <label>
                      <input type="radio" name="state" id="state1" value="0" <?php echo (isset($row['state']) && $row['state'] == 0) ? 'checked' : ''?>>
                      否
                    </label>
                  </div>
                </div>
            </div>
            
                      
          </div>

          <div class="box-footer">
          	<input type="hidden" name="id" value="<?php echo isset($row['id']) ? $row['id'] : set_value('id');?>" />
            <input type="hidden" name="action" value="<?php echo isset($act) ? $act : set_value('action');?>" />
            <button type="button" class="btn btn-primary" onClick="checkDiscountMod();">确定提交</button>　
            <button type="button" class="btn btn-default" class="close" data-dismiss="modal" aria-label="Close">关闭</button>
          </div>
    </form>
</section>
<script>

$('#endtime').datepicker({
  autoclose: true
})

function checkDiscountMod(){

	var title = $("#title").val();
	var cid = $("#cid").val();
	
	if(cid == ''){
		alert('请选择类型');
		return false;	
	}
	if(title == ''){
		alert('请输入标题');
		return false;	
	}	
	
	var params = $('#dataForm').serialize();
	var url = "ajax_admin/discountMod";
	
//	alert(params);
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
				go('<?php echo admin_url()."discount/index"?>');	
			}
		}
	});
	
}
</script>