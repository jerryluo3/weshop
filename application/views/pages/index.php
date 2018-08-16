<?php
	$this->load->view('pages/header');
?>

<section class="section_block">
	<div class="fl index_pic_news">
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
    <div class="fr index_main_news m_l_30">
    	<div class="picScroll-top">
            <div class="index_main_news_list bd">
                <ul><?php
                        foreach($pic_list as $k=>$plist):
                    ?>
                    <li class="top_news">
                        <h2><?php echo getSubstr($plist['title'],40);?></h2>
                        <p><?php echo getSubstr($plist['content'],100);?></p>
                    </li>
                    <?php
                        endforeach;
                    ?>
                </ul>
            </div>
        </div>
        <script src="data/asset/js/jquery.SuperSlide2.11.js"></script>
        <script type="text/javascript">
			jQuery(".picScroll-top").slide({mainCell:".bd ul",autoPage:true,effect:"top",autoPlay:true,vis:1,trigger:"click"});
		</script>
        <div class="index_main_news_list">
       		<ul><?php
            		foreach($news_list as $k=>$nlist):
				?>
            	<li><a href="<?php echo site_url('articleview/'.$nlist['id']);?>"><?php echo $nlist['title'];?></a><span><?php echo date('Y-m-d',$nlist['addtime']);?></span></li>
                <?php
                	endforeach;
				?>
            </ul>
        </div>
    </div>
    
    <div class="clear"></div>
</section>

<section class="section_block">
	<img src="<?php echo $mini_banner['ads_picture']?>" />
    <div class="clear"></div>
</section>


<section class="section_block">
	<div class="fl news_block">
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
    </div>
    <div class="fl news_block m_l_30">
    	<div class="block_title"><h2>标准制修定</h2><span><a href="<?php echo site_url('article/2');?>">查看更多></a></span></div>
        <div class="news_list">
            <ul>
                <?php
                	foreach($n2_list as $n2):
				?>
                <li><span><?php echo date('Y-m-d',$n2['addtime']);?></span><a href="<?php echo site_url('articleview/'.$n2['id']);?>"><?php echo getSubstr($n2['title'],34);?></a></li>
                <?php
                	endforeach;
				?>
            </ul>
        </div>
    </div>
    <div class="fr news_block">
    	<div class="block_title"><h2>政策法规</h2><span><a href="<?php echo site_url('article/3');?>">查看更多></a></span></div>
        <div class="news_list">
            <ul>
            	<?php
                	foreach($n1_list as $n1):
				?>
                <li><span><?php echo date('Y-m-d',$n1['addtime']);?></span><a href="<?php echo site_url('articleview/'.$n1['id']);?>"><?php echo getSubstr($n1['title'],34);?></a></li>
                <?php
                	endforeach;
				?>
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
            	<li><input type="text" class="login_txt" name="login_user" id="login_user" value="" placeholder="账号" /></li>
                <li><input type="password" class="login_txt" name="login_pass" id="login_pass" value="" placeholder="密码" /></li>
                <li><input type="button" class="login_btn" name="login_btn" value="立即登录" onClick="userLogin();" /></li>
            </ul>
        </div>
    </div>
    <div class="fl  m_l_20">
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
                <li><input type="password" class="login_txt" name="login_pass" value="" placeholder="" /></li>
                <li><input type="button" class="login_btn" name="login_btn" value="立即查询" onclick="location.href='http://www.hzsis.cn/'" /></li>
            </ul>
        </div>
    </div>
    <div class="clear"></div>
</section>


<?php
	$this->load->view('pages/footer');
?>