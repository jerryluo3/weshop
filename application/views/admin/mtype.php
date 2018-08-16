<section class="content-header">
  <h1>
    管理员类型列表
    <small>Manager Group List</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo site_url(admin_url().'home');?>"><i class="fa fa-dashboard"></i> 首页</a></li>
    <li class="active">管理员类型</li>
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
                  <th>用户组名称</th>
                  <th>发布时间</th>
                  <th>排序ID</th>
                  <th>操作</th>
                </tr>
                <?php
                	foreach($mtype_list as $list):
				?>
                <tr id="row<?php echo $list['id'];?>">
                  <td><?php echo $list['id'];?></td>
                  <td><?php echo $list['title'];?></td>
                  <td><?php echo $list['addtime'] > 0 ? date('Y-m-d H:i:s',$list['addtime']) : '-';?></td>
                  <td><?php echo $list['rank'];?></td>
                  <td><a href="javascript:;" onClick="openModel('<?php echo admin_url();?>mtype/mod/<?php echo $list['id']?>', '编辑管理员类型');"><i class="fa fa-gear"></i></a>　<a href="javascript:;" onClick="delmsg('<?php echo admin_url();?>mtype/del/<?php echo $list['id']?>', this, <?php echo $list['id'];?>);"><i class="fa fa-trash"></i></a></td>
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
