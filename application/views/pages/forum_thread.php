<?php
	if(empty($_SESSION['wy_id'])){
		header('location:'.site_url('forum/login'));	
	}
	$this->load->view('pages/header');
?>
<link rel="stylesheet" href="data/asset/css/forum.css">

<div class="forum_block">
	<div class="forum_block_btn"><a href="<?php echo site_url('forum/add');?>" class="addForum fl">发布内容</a>　<a href="<?php echo site_url('forum/index');?>" class="addForum fl" style="margin-left:10px;">返回列表</a> <p class="login_mess fr">欢迎回来：<?php echo $_SESSION['wy_zhiwu'];?>　<?php echo $_SESSION['wy_name'];?>,　<a href="javascript:;" onClick="loginQuit();">退出登录</a></p></div>
    <div class="thread_list">
    	<ul>
        	<li class="thread_home">
            	<div class="thread_author fl">
                	<div class="author_avatar"><img src="data/asset/images/avatar.png" /></div>
                    <div class="author_name"><?php echo $thread['wy_name'];?></div>
                    <div class="author_zhiwu"><?php echo $thread['wy_zhiwu'];?></div>
                    <div class="author_danwei"><?php echo $thread['wy_danwei'];?></div>
                </div>
                <div class="thread_info fr">
                	<div class="thread_title"><?php echo $thread['f_title'];?></div>
                    <div class="thread_content"><?php echo $thread['f_content'];?></div>
                    <div class="thread_mess">发布时间：<?php echo date('Y-m-d H:i:s',$thread['f_addtime']);?>　浏览次数：<?php echo $thread['f_counts'];?>　回复次数：<?php echo $thread['f_nums'];?></div>
                </div>
                <div class="clear"></div>
            </li>
        	<?php
            	foreach($post_list as $list):
			?>
            <li>
            	<div class="thread_author fl">
                	<div class="author_avatar"><img src="data/asset/images/avatar.png" /></div>
                    <div class="author_name"><?php echo $list['wy_name'];?></div>
                    <div class="author_zhiwu"><?php echo $list['wy_zhiwu'];?></div>
                    <div class="author_danwei"><?php echo $list['wy_danwei'];?></div>
                </div>
                <div class="thread_info fr" style="background:#fafafa;">
                    <div class="thread_content"><?php echo $list['fp_content'];?></div>
                    <div class="thread_mess">发布时间：<?php echo date('Y-m-d H:i:s',$list['fp_addtime']);?></div>
                </div>
                <div class="clear"></div>
            </li>
            <?php
            	endforeach
			?>
        </ul>
        <div class="clear"></div>
    </div>
   
    <div class="forum_add_form">
    	<form role="form" id="dataForm">
    	<h2>快速回复</h2>
        <p><textarea name="fp_content" id="fp_content" class="fp_content"></textarea></p>
        <p style="text-align:center;padding:10px 0;"><input type="hidden" name="f_id" id="f_id" value="<?php echo $thread['f_id'];?>" /><input type="button" class="form_btn" onClick="thread_submit();" name="submit" value="立即提交" /></p>
        </form>
    </div>
</div>
<script>
function thread_submit(){
	var fp_content = $("#fp_content").val();
	if(fp_content.length < 10 ){
		alert('请至少添加10个字');
		return false	
	}
	var params = $('#dataForm').serialize();
	var url = "ajax/threadSubmit";
	
	//alert(params);
//	return false;
	
	$.ajax({
		type: "post",
		url: url,
		dataType: "json",
		data: params,
		success: function(msg){
			if(msg.status == 200){
				location.href = 'forum/thread/'+<?php echo $thread['f_id'];?>
			}
		}
	});	
}


</script>

<?php
	$this->load->view('pages/footer');
?>