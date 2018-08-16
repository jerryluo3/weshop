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
						?>
                        <li<?php echo $cid == $clist['cat_id'] ? ' class="in"' : '';?>><a href="<?php echo site_url('about/'.$clist['cat_id']);?>"><?php echo $clist['cat_name'];?></a></li>
                        <?php
                        	endforeach;
						?>
                    </ul>
                </div>
                
                <div class="left_query"><img src="data/asset/images/query.png" /></div>
            </div>
            <div class="fr right_block">
            	<div class="sitepath">当前位置 :<a href="<?php echo site_url();?>">首页</a> &gt; <a href="<?php echo site_url('about/'.$cid);?>"><?php echo $row['cat_name'];?></a></div>
                
                <div class="news_content" style="padding:20px 0;line-height:30px;font-size:14px;">
            		<?php echo $row['content'];?>
            	</div>
            </div>
        </div>
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
