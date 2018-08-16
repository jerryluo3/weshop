<section class="content-header">
  <h1>
    管理员管理
    <small>Manager List</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo site_url(admin_url().'home');?>"><i class="fa fa-dashboard"></i> 首页</a></li>
    <li class="active">管理员管理</li>
  </ol>
</section>

<!-- Main content -->
<section class="content container-fluid">
	<div class="box">
            <!-- /.box-header -->
            <div class="box-body no-padding">
              <table class="table table-striped table-bordered">
                <tbody>
                <tr>
                  <th>ID</th>
                  <th>用户名</th>
                  <th>真实姓名</th>
                  <th>用户组</th>
                  <th>注册时间</th>
                  <th>注册IP</th>
                  <th>上次登录时间</th>
                  <th>上次登录IP</th>
                  <th>登录次数</th>
                  <th>操作</th>
                </tr>
                <?php
                	foreach($manager_list as $list):
				?>
                <tr id="row<?php echo $list['id'];?>">
                  <td><?php echo $list['id'];?></td>
                  <td><?php echo $list['username'];?></td>
                  <td><?php echo $list['realname'];?></td>
                  <td><?php echo $list['title'];?></td>
                  <td><?php echo $list['regtime'] > 0 ? date('Y-m-d H:i:s',$list['regtime']) : '-';?></td>
                  <td><?php echo $list['regip'];?></td>
                  <td><?php echo $list['lastlogintime'] > 0 ? date('Y-m-d H:i:s',$list['lastlogintime']) : '-';?></td>
                  <td><?php echo $list['lastloginip'];?></td>
                  <td><?php echo $list['logincounts'];?></td>
                  <td><a href="javascript:;" onClick="openModel('<?php echo admin_url();?>manager/mod/<?php echo $list['id']?>', '编辑管理员信息');"><i class="fa fa-edit"></i></a>　<a href="javascript:;" onClick="delmsg('<?php echo admin_url();?>manager/del/<?php echo $list['id']?>', this, <?php echo $list['id'];?>);"><i class="fa fa-trash"></i></a></td>
                </tr>
                <?php
                	endforeach;
				?>
              </tbody>
              </table>
            </div>
            <div class="box-footer clearfix">
              <?php echo $page_list;?>
            </div>
            <!-- /.box-body -->
          </div>
</section>
