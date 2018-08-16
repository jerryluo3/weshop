<?php
	$this->load->view('pages/header');
?>
<script src="data/asset/js/jquery.jslides.js"></script>
<script src="data/asset/js/scroll.js"></script>
<section class="banner">
	<div id="full-screen-slider">
        <ul id="slides">
          <?php
          	foreach($index_banner as $i=> $ibanner):
		  ?>
          <li style="<?php echo $i == 0 ? 'z-index:800;' : 'z-index:900;display:none;';?> background: url(<?php echo base_url($ibanner['ads_picture']);?>) 50% 0% no-repeat;"><a href="<?php echo !empty($ibanner['ads_url']) ? $ibanner['ads_url'] : 'javascript:;'?>" target="_blank"></a></li>
          <?php
          	endforeach;
		  ?>
        </ul>            
    </div>
</section>

<section class="section_block">
	<div class="fl index_pic_news">
    	<div class="block_title"><h2>图片新闻</h2></div>
        <div class="pic_news_list">
       		<div class="yx-rotaion">
                <ul class="rotaion_list">
                	<?php
                    	foreach($pic_list as $plist):
					?>
                	<li><a href="<?php echo site_url('articleview/'.$plist['id']);?>"><img src="<?php echo base_url($plist['picture']);?>" alt="<?php echo getSubstr($plist['title'],20);?>"></a></li>
                    <?php
                    	endforeach;
					?>
            	</ul>
            </div>
            <script src="data/asset/js/jquery.rotaion.js"></script>
			<script type="text/javascript">
            $(".yx-rotaion").rotaion({auto:true});
            </script>
        </div>
    </div>
    <div class="fl index_main_news m_l_30">
    	<div class="block_title"><h2>新闻动态</h2><span><a href="<?php echo site_url('article/7');?>">查看更多></a></span></div>
        <div class="index_main_news_list">
       		<ul><?php
            		foreach($news_list as $nlist):
				?>
            	<li><a href="<?php echo site_url('articleview/'.$nlist['id']);?>"><?php echo $nlist['title'];?></a><span><?php echo date('Y-m-d',$nlist['addtime']);?></span></li>
                <?php
                	endforeach;
				?>
            </ul>
        </div>
    </div>
    <section class="fr index_announce">
    	<div class="block_title"><h2>通知公告</h2><span><a href="<?php echo site_url('article/8');?>">查看更多></a></span></div>
        <div class="announce_list">
        	<ul>
            	<?php
            		foreach($announce_list as $k=>$alist):
					$k++;
				?>
            	<li><span>0<?php echo $k;?></span><a href="<?php echo site_url('articleview/'.$alist['id']);?>"><?php echo $alist['title'];?></a></a></li>
                <?php
                	endforeach;
				?>
            </ul>
        </div>
    </section>
    <div class="clear"></div>
</section>


<section class="section_block">
	<img src="<?php echo $mini_banner['ads_picture']?>" />
    <div class="clear"></div>
</section>

<section class="section_block">
	<div class="fl news_block">
    	<div class="block_title"><h2>政策法规</h2><span><a href="<?php echo site_url('article/3');?>">查看更多></a></span></div>
        <div class="news_list">
            <ul>
            	<?php
                	foreach($n1_list as $n1):
				?>
                <li><span><?php echo date('Y-m-d',$n1['addtime']);?></span><a href="<?php echo site_url('articleview/'.$n1['id']);?>"><?php echo getSubstr($n1['title'],30);?></a></li>
                <?php
                	endforeach;
				?>
            </ul>
        </div>
    </div>
    <div class="fl news_block m_l_30">
    	<div class="block_title"><h2>标准制修定</h2><span><a href="<?php echo site_url('article/2');?>">查看更多></a></span></div>
        <div class="news_list">
            <ul>
                <?php
                	foreach($n2_list as $n2):
				?>
                <li><span><?php echo date('Y-m-d',$n2['addtime']);?></span><a href="<?php echo site_url('articleview/'.$n2['id']);?>"><?php echo getSubstr($n2['title'],20);?></a></li>
                <?php
                	endforeach;
				?>
            </ul>
        </div>
    </div>
    <div class="fr news_block">
    	<div class="block_title"><h2>委员互动</h2><span><a href="javascript:;">查看更多></a></span></div>
        <div class="news_list">
            <ul>
				
            </ul>
        </div>
    </div>
    <div class="clear"></div>
</section>



<section class="section_block">
	<div class="fl login_block">
    	<div class="cblock_title"><i><img src="data/asset/images/login_ico.png" /></i><h2>委员登陆</h2><h4>/Login</h4></div>
        <div class="login_block_list">
       		<ul>
            	<li><input type="text" class="login_txt" name="login_user" value="" placeholder="账号" /></li>
                <li><input type="password" class="login_txt" name="login_pass" value="" placeholder="密码" /></li>
                <li><input type="button" class="login_btn" name="login_btn" value="立即登录" class="login_btn" /></li>
            </ul>
        </div>
    </div>
    <div class="fl index_main_news m_l_30">
    	<div class="fl about_block">
            <div class="cblock_title"><i><img src="data/asset/images/about_ico.png" /></i><h2>关于我们</h2><h4>/About us</h4></div>
            <div class="about_list">
            	<ul>
                	<li><a href="<?php echo site_url('about/14');?>"><i><img src="data/asset/images/about_ico1.png" /></i><h3>标技委简介</h3></a></li>
                    <li><a href="<?php echo site_url('about/15');?>"><i><img src="data/asset/images/about_ico2.png" /></i><h3>领导设置</h3></a></li>
                    <li><a href="<?php echo site_url('about/16');?>"><i><img src="data/asset/images/about_ico3.png" /></i><h3>委员名单</h3></a></li>
                    <li><a href="<?php echo site_url('about/17');?>"><i><img src="data/asset/images/about_ico4.png" /></i><h3>规章制度</h3></a></li>
                    <li><a href="<?php echo site_url('about/18');?>"><i><img src="data/asset/images/about_ico5.png" /></i><h3>下载专区</h3></a></li>
                    <li><a href="<?php echo site_url('about/19');?>"><i><img src="data/asset/images/about_ico6.png" /></i><h3>联系我们</h3></a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="fr login_block">
    	<div class="cblock_title"><i><img src="data/asset/images/query_ico.png" /></i><h2>标准查询</h2><h4>/Search</h4></div>
        <div class="login_block_list">
       		<ul>
            	<li><p style="font-size:16px;color:#999;margin:7px 0;">请输入搜索标准关键字：</p></li>
                <li><input type="password" class="login_txt" name="login_pass" value="" placeholder="密码" /></li>
                <li><input type="button" class="login_btn" name="login_btn" value="立即登录" onclick="location.href='http://www.hzsis.cn/search/search.html?r=0.31715981224146983'" /></li>
            </ul>
        </div>
    </div>
    <div class="clear"></div>
</section>


<?php
	$this->load->view('pages/footer');
?>
