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
</head>

<body>
<header class="header">
	<section class="top_line">
    	<div class="w">
        	<p class="fl">欢迎访问<?php echo $sysconfig[0]['cfg_value'];?></p>
            <p class="fr t_l_r">
            	<a href="javascript:;">加入收藏</a>
                <a href="javascript:;">返回首页</a>
                <a href="javascript:;">邮箱登录</a>
            </p>
        </div>
    </section>
    <section class="top_bar">
    	<div class="w">
        	<div class="logo"><a href="<?php echo site_url();?>"><img src="data/asset/images/logo.png" /></a></div>
        </div>
    </section>
    <?php
    	$fname = $this->uri->segment(1);
		$fname1 = $this->uri->segment(2);
	?>
    <section class="mbar">
    	<div class="menu">
        	<ul>
            	<li<?php echo !isset($fname) || $fname == 'home' ? ' class="in"' : ''; ?>><a href="<?php echo site_url();?>">首页</a></li>
                <li<?php echo $fname == 'article' && in_array($cid,array(1,12,13)) ? ' class="in"' : ''; ?>><a href="<?php echo site_url('article/1');?>">新闻资讯</a>
                	<ul><?php  foreach($cat1_list as $c1): ?>
                    	<li><a href="<?php echo site_url('article/'.$c1['cat_id']);?>"><?php echo $c1['cat_name'];?></a></li>
                        <?php endforeach;?>
                    </ul>
                </li>
                <li<?php echo $fname == 'article' && in_array($cid,array(8)) ? ' class="in"' : ''; ?>><a href="<?php echo site_url('article/8');?>">通知公告</a></li>
                <li<?php echo $fname == 'article' && in_array($cid,array(2,9,10,11)) ? ' class="in"' : ''; ?>><a href="<?php echo site_url('article/2');?>">标准制修订</a>
                	<ul><?php  foreach($cat2_list as $c2): ?>
                    	<li><a href="<?php echo site_url('article/'.$c2['cat_id']);?>"><?php echo $c2['cat_name'];?></a></li>
                        <?php endforeach;?>
                    </ul>
                </li>
                <li<?php echo $fname == 'article' && in_array($cid,array(3)) ? ' class="in"' : ''; ?>><a href="<?php echo site_url('article/3');?>">政策法规</a>
                	<ul><?php  foreach($cat3_list as $c3): ?>
                    	<li><a href="<?php echo site_url('article/'.$c3['cat_id']);?>"><?php echo $c3['cat_name'];?></a></li>
                        <?php endforeach;?>
                    </ul>
                </li>
                <li><a href="javascript:;">委员互动</a></li>
                <li<?php echo $fname == 'query' ? ' class="in"' : ''; ?>><a href="<?php echo site_url('query');?>">标准查询</a></li>
                <li<?php echo $fname == 'about' ? ' class="in"' : ''; ?>><a href="<?php echo site_url('about');?>">关于我们</a>
                	<ul><?php  foreach($cat6_list as $c6): ?>
                    	<li><a href="<?php echo site_url('article/'.$c6['cat_id']);?>"><?php echo $c6['cat_name'];?></a></li>
                        <?php endforeach;?>
                    </ul>
                </li>
            </ul>
            <div class="search">
            	<input type="text" name="keys" value="" placeholder="请输入搜索关键字" class="search_txt" />
            </div>
        </div>
    </section>
</header>