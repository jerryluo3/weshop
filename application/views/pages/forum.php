<?php
	if(empty($_SESSION['wy_id'])){
		header('location:'.site_url('forum/login'));	
	}
	$this->load->view('pages/header');
?>
<link rel="stylesheet" href="data/asset/css/forum.css">

<div class="forum_block">
	<div class="forum_block_btn"><a href="<?php echo site_url('forum/add');?>" class="addForum fl">发布内容</a><p class="login_mess fr">欢迎回来：<?php echo $_SESSION['wy_zhiwu'];?>　<?php echo $_SESSION['wy_name'];?>,　<a href="javascript:;" onClick="loginQuit();">退出登录</a></p></div>
    <div class="forum_list">
    	<ul>
        	<li class="forum_th">
            	<div class="forum_title">
                	主题
                </div>
                <div class="forum_author">
                	作者
                </div>
                <div class="forum_reply">
                	回复/查看
                </div>
                <div class="last_post">
                	最后发表
                </div>
            </li>
        	<?php
            	foreach($forum_list as $list):
			?>
            <li>
            	<div class="forum_title">
                	<a href="<?php echo site_url('forum/thread/'.$list['f_id']);?>"><?php echo $list['f_title'];?></a>
                </div>
                <div class="forum_author">
                	<p><?php echo $list['wy_name'];?></p>
                    <p class="font_gray"><?php echo date('Y-m-d H:i:s',$list['f_addtime']);?></p>
                </div>
                <div class="forum_reply">
                	<p class="font_gray"><?php echo $list['f_counts']?></p>
                    <p class="font_gray"><?php echo $list['f_nums']?></p>
                </div>
                <div class="last_post">
                	<?php
                    	$lastedit = $this->db->where(array('fp_fid'=>$list['f_id']))->order_by('fp_id desc')->join('weiyuan','weiyuan.wy_id = forum_post.fp_uid','left')->get('forum_post')->row_array();
					?>
                	<p><?php echo isset($lastedit['wy_name']) && $lastedit['wy_name'] != '' ? $lastedit['wy_name'] : '';?></p>
                    <p class="font_gray"><?php echo $lastedit['fp_addtime'] > 0 ? date('Y-m-d H:i:s',$lastedit['fp_addtime']) : '';?></p>
                </div>
            </li>
            <?php
            	endforeach
			?>
        </ul>
    </div>
</div>


<?php
	$this->load->view('pages/footer');
?>