<?php
	if(empty($_SESSION['wy_id'])){
		header('location:forum/login');	
	}
	$this->load->view('pages/header');
?>
<link rel="stylesheet" href="data/asset/css/forum.css">

<div class="forum_block">
	<div class="forum_add_list">
    	<ul>
        	<form role="form" id="dataForm">
        	<li>
            	 <label for="title">标题<b style="color:#f00;">*</b></label>
                 <input type="text" class="forum_txt" id="title" name="title" value="" placeholder="">
            </li>
            <li>
            	<label for="content">描述<b style="color:#f00;">*</b></label>
              	<textarea  name="content" id="content" rows="10" placeholder=""></textarea>
            </li>
            <li><input type="button" class="forum_submit" onClick="forum_submit();" name="submit" value="立即提交" /></li>
            </form>
        </ul>
    </div>
</div>

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

function forum_submit(){
	var title = $("#title").val();
	if(title == ''){
		alert('请输入标题');
		return false;	
	}
	
	var params = $('#dataForm').serialize();
	var url = "ajax/forumSubmit";
	
	//alert(params);
//	return false;
	
	$.ajax({
		type: "post",
		url: url,
		dataType: "json",
		data: params,
		success: function(msg){
			if(msg.status == 200){
				location.href = 'forum/index'
			}
		}
	});	
}
</script>

<?php
	$this->load->view('pages/footer');
?>