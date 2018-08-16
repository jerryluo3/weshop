<?php
	$this->load->view('pages/header');
?>

<div class="inner_bar">
	<div class="w">
    	<div class="inner_bar_bg">
        	<div class="cat_name"><?php echo $row['cat_name'];?></div>
        </div>
    </div>
</div>
<div class="inner_body_bg">
	<div class="w">
    	<div class="section_main">
            <div class="fl left_block">
            	<div class="news_block_hotnews_title"><h4>栏目</h4></div>
            	<div class="news_block_cats">
                    <ul>
                    	<?php
                        	foreach($cat_list as $clist):
							$tname = $clist['cat_id'] == 17 ? 'article' : 'about';
						?>
                        <li<?php echo $cid == $clist['cat_id'] ? ' class="in"' : '';?>><a href="<?php echo site_url($tname.'/'.$clist['cat_id']);?>"><?php echo $clist['cat_name'];?></a></li>
                        <?php
                        	endforeach;
						?>
                    </ul>
                </div>
                
                <div class="left_query"><img src="data/asset/images/query.png" /></div>
            </div>
            <div class="fr right_block">
            	<div class="sitepath">当前位置 :<a href="<?php echo site_url();?>">首页</a> &gt; <a href="<?php echo site_url('article/'.$cid);?>"><?php echo $row['cat_name'];?></a></div>
                
                <div class="news_block_list">
                    <ul>
                    	<?php foreach($article_list as $list): ?>
                        <li>
                            <h3><a href="<?php echo site_url('articleview/'.$list['id']);?>" target="_blank"><?php echo $list['title'];?></a></h3>
                            <p><?php echo getSubstr($list['content'],200);?><a href="<?php echo site_url('articleview/'.$list['id']);?>" target="_blank">详细&gt;&gt;</a></p>
                            <span>发布日期：<?php echo date('Y-m-d',$list['addtime'])?>  来源：<?php echo $list['author'] ? $list['author'] : $sysconfig[5]['cfg_value'];?>  点击次数：<?php echo $list['count'];?>次</span>
                        </li>
                        <?php endforeach;?>                
                    </ul>
                </div>
                <?php echo $this->pagination->create_links();?>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
</div>
<script>
 $(function(){
	var c_height = $(".section_main").height();
	var cc = c_height+80;
	$(".inner_body_bg").css('height',cc+'px');	 
 })
</script>

<?php
	$this->load->view('pages/footer');
?>
