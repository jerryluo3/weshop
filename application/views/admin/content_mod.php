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
              <label for="title">标题<b style="color:#f00;">*</b></label>
              <span class="input-group-btn">
                  <input type="text" class="form-control" id="title" name="title" value="<?php echo isset($row['title']) ? $row['title'] : set_value('title');?>" placeholder="">
                </span>
            </div>
            <div class="form-group">
              <label for="picture">图片<b style="color:#f00;">*</b></label>
              <div class="input-group input-group-sm">
                <input type="text" class="form-control" name="picture" id="picture" value="<?php echo isset($row['picture']) ? $row['picture'] : set_value('picture');?>">
                    <span class="input-group-btn">
                      <button type="button" class="btn btn-info btn-flat" onClick="popBox('<?php echo site_url('uploadmyfile/');?>','文件上传',500,300);">上传图片</button><input type="button" name="button" class="btn btn-success btn-flat" value="+" onclick="addUploadrow(this);">
                    </span>
              </div>
              <?php
                if(isset($row['pics']) && $row['pics'] != 'null' && !empty($row['pics'])){
                    $pics = json_decode($row['pics'],true);
                    foreach($pics as $k=> $pic){
                ?>
                <div class="input-group input-group-sm" style="margin-top:5px;">
                <input type="text" class="form-control" name="pictures[]" id="pictures<?php echo $k;?>" value="<?php echo isset($pic) ? $pic : '';?>">
                    <span class="input-group-btn">
                      <button type="button" class="btn btn-info btn-flat" onClick="popBox('<?php echo site_url('uploadmyfile/index/pictures'.$k);?>','文件上传',500,300);">上传图片</button><input type="button" name="button" class="btn btn-success btn-flat" value="-" onclick="delUploadrow(this);">
                    </span>
              </div>
                <?php
                    } }
				?>
              
            </div>
            
            <div class="form-group">
              <label for="rank">排序ID<b style="color:#f00;">*</b></label>
              <input type="text" class="form-control" id="rank" placeholder="" name="rank" value="<?php echo isset($row['rank']) ? $row['rank'] : 100;?>">
            </div>
            
            
            <div class="form-group">
              <label for="author">来源<b style="color:#f00;">*</b></label>
              <input type="text" class="form-control" id="author" placeholder="" name="author" value="<?php echo isset($row['author']) ? $row['author'] : '';?>">
            </div>
            
            <div class="form-group">
              <label for="addtime">发布时间<b style="color:#f00;">*</b></label>
              <input type="text" class="form-control" id="addtime" placeholder="" name="addtime" value="<?php echo isset($row['addtime']) ? date('Y-m-d H:i:s',$row['addtime']) : '';?>">
            </div>
            
            <div class="form-group">
              <label for="content">描述<b style="color:#f00;">*</b></label>
              <textarea  name="content" id="content" rows="10" placeholder=""><?php echo isset($row['content']) ? $row['content'] : '';?></textarea>
            </div>
            
            <div class="form-group">
              <label for="state">状态</label>　　
                <div class="input-group input-group-sm" style="display:inline-block;">
                  
                  <label>
                    <input type="radio" name="state" id="state" value="1" <?php echo !isset($row['state']) || (isset($row['state']) && $row['state'] == 1) ? 'checked' : '';?>>已审核
                  </label>
                  <label>
                    <input type="radio" name="state" id="state1" value="0"  <?php echo isset($row['state']) && $row['state'] == 0 ? 'checked' : '';?>>未审核
                  </label>
                </div>
            </div>
            
            <div class="form-group">
              <label for="iscommend">推荐</label>　　
                <div class="input-group input-group-sm" style="display:inline-block;">
                  
                  <label>
                    <input type="radio" name="iscommend" id="iscommend" value="1" <?php echo (isset($row['iscommend']) && $row['iscommend'] == 1) ? 'checked' : '';?>>是
                  </label>
                  <label>
                    <input type="radio" name="iscommend" id="iscommend1" value="0"  <?php echo !isset($row['iscommend']) || isset($row['iscommend']) && $row['iscommend'] == 0 ? 'checked' : '';?>>否
                  </label>
                </div>
            </div>
                      
          </div>

          <div class="box-footer">
          	<input type="hidden" name="id" value="<?php echo isset($row['id']) ? $row['id'] : set_value('id');?>" />
            <input type="hidden" name="action" value="<?php echo isset($act) ? $act : set_value('action');?>" />
            <button type="button" class="btn btn-primary" onClick="checkcontentMod();">确定提交</button>　
            <button type="button" class="btn btn-default" class="close" data-dismiss="modal" aria-label="Close">关闭</button>
          </div>
    </form>
</section>
<script src="data/editor/kindeditor.js"></script>
<script src="data/editor/lang/zh_CN.js"></script>
<script>
function kedit(kedit){
    var editor = KindEditor.create(kedit,{
		uploadJson : '<?php echo base_url();?>data/editor/php/upload_json.php',
		fileManagerJson : '<?php echo base_url();?>data/editor/php/upload_json.php',
		width: '100%',
        height: '300px',
		pasteType : 2,
		allowFileManager : true,
		afterBlur:function(){this.sync();}       

    });
} 
$(function(){
    kedit('textarea[id="content"]');
})

$('#addtime').datetimepicker({
    format: 'yyyy-mm-dd hh:ii:ss',
    autoclose: true,
    minView: 1,
    minuteStep:0
});
</script>
<script>

function checkcontentMod(){

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
	var url = "ajax_admin/contentMod";
	
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
				go('<?php echo admin_url()."content/index/".$page;?>');	
			}
		}
	});
	
}
</script>