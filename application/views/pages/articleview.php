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
                
                <div class="news_content">
            	<div class="news_content_header">
                	<h1><?php echo $row['title'];?></h1>
                    <p>发布日期：<?php echo date('Y-m-d',$row['addtime'])?>  来源：<?php echo !empty($row['author']) ? $row['author'] : $sysconfig['5']['cfg_value'];?>  点击次数：<?php echo $row['count'];?>次</p>
                </div>
                <div class="news_content_c">
                	<?php echo $row['content'];?>
               	</div>
                <div class="news_content_b">	【<a href="<?php echo !empty($prev) ? site_url('articleview/'.$prev['id']) : 'javascript:;';?>" target="_blank">上一篇</a>】	【<a href="<?php echo !empty($next) ? site_url('articleview/'.$next['id']) : 'javascript:;';?>" target="_blank">下一篇</a>】	【<a href="javascript:;" style="color:#888888;cursor:hand; font-size:12px;">返回顶部</a>】 【<a href="javascript:window.close()" style="cursor:hand; font-size:12px; color:#888888;">关闭窗口</a>】</div>
            </div>
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
