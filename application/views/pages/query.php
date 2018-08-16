<?php
	$this->load->view('pages/header');
?>

<div class="inner_bar">
	<div class="w">
    	<div class="inner_bar_bg">
        	<div class="cat_name">标准查询</div>
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
                    	
                    </ul>
                </div>
                
                <div class="left_query"><img src="data/asset/images/query.png" /></div>
            </div>
            <div class="fr right_block">
            	<div class="sitepath">当前位置 :<a href="<?php echo site_url();?>">首页</a> &gt; <a href="<?php echo site_url('query');?>">标准查询</a></div>
                
                
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
