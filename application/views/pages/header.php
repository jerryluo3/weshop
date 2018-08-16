<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $sysconfig[1]['cfg_value'];?></title>
<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
<meta charset="UTF-8" name="description" content="<?php echo $sysconfig[3]['cfg_value'];?>">
<meta name="keywords" content="<?php echo $sysconfig[2]['cfg_value'];?>">
<base href="<?php echo base_url();?>" />
<link rel="stylesheet" href="data/asset/css/main.css">
<script src="data/asset/js/jquery.js"></script>
<script src="data/asset/js/common.js"></script>
</head>

<header class="header">
	<section class="top_line">
    	<div class="w">
        	<p class="fl">欢迎访问<?php echo $sysconfig[0]['cfg_value'];?></p>
            <p class="fr t_l_r">
            	<a href="javascript:;" onClick="AddFavorite(window.location, '<?php echo $sysconfig[0]['cfg_value'];?>')">加入收藏</a>
                <a href="<?php echo site_url();?>">返回首页</a>
                <a href="<?php echo site_url('forum/index');?>">委员登陆</a>
            </p>
        </div>
    </section>
    <section class="top_bar">
    	<div class="w">
        	<div class="logo"><a href="<?php echo site_url();?>"><img src="data/asset/images/logo.png" /></a></div>
            <div class="search">
                <input type="text" name="keys" value="" placeholder="请输入搜索关键字" class="search_txt" />
                <input type="image" src="data/asset/images/s.png" class="search_btn" />
            </div>
        </div>
    </section>
    <?php
    	$fname = $this->uri->segment(1);
		$fname1 = $this->uri->segment(2);
	?>
    <div class="menu">
        <ul>
            <li<?php echo !isset($fname) || $fname == 'home' ? ' class="in"' : ''; ?>><a href="<?php echo site_url();?>"><i class="i1"></i> 首页</a></li>
            <li<?php echo $fname == 'article' && in_array($cid,array(1,12,13)) ? ' class="in"' : ''; ?>><a href="<?php echo site_url('article/1');?>"><i class="i2"></i> 新闻资讯</a>
                <ul><?php  foreach($cat1_list as $c1): ?>
                    <li><a href="<?php echo site_url('article/'.$c1['cat_id']);?>"><?php echo $c1['cat_name'];?></a></li>
                    <?php endforeach;?>
                </ul>
            </li>
            <li<?php echo $fname == 'article' && in_array($cid,array(8)) ? ' class="in"' : ''; ?>><a href="<?php echo site_url('article/8');?>"><i class="i3"></i> 通知公告</a></li>
            <li<?php echo $fname == 'article' && in_array($cid,array(2,9,10,11)) ? ' class="in"' : ''; ?>><a href="<?php echo site_url('article/2');?>"><i class="i4"></i> 标准制修订</a>
                <ul><?php  foreach($cat2_list as $c2): ?>
                    <li><a href="<?php echo site_url('article/'.$c2['cat_id']);?>"><?php echo $c2['cat_name'];?></a></li>
                    <?php endforeach;?>
                </ul>
            </li>
            <li<?php echo $fname == 'article' && in_array($cid,array(3)) ? ' class="in"' : ''; ?>><a href="<?php echo site_url('article/3');?>"><i class="i5"></i> 政策法规</a>
                <ul><?php  foreach($cat3_list as $c3): ?>
                    <li><a href="<?php echo site_url('article/'.$c3['cat_id']);?>"><?php echo $c3['cat_name'];?></a></li>
                    <?php endforeach;?>
                </ul>
            </li>
            <li><a href="<?php echo site_url('forum/index');?>"><i class="i6"></i> 委员互动</a></li>
            <li<?php echo $fname == 'query' ? ' class="in"' : ''; ?>><a href="<?php echo site_url('query');?>"><i class="i7"></i> 标准查询</a></li>
            <li<?php echo $fname == 'about' ? ' class="in"' : ''; ?>><a href="<?php echo site_url('about');?>"><i class="i8"></i> 关于我们</a>
                <ul><?php  foreach($cat6_list as $c6): 
						$tname = $c6['cat_id'] == 17 ? 'article' : 'about';
					?>
                    <li><a href="<?php echo site_url($tname.'/'.$c6['cat_id']);?>"><?php echo $c6['cat_name'];?></a></li>
                    <?php endforeach;?>
                </ul>
            </li>
        </ul>
        
    </div>
</header>