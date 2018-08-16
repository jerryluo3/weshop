<section class="content-header">
  <h1>
    会员管理
    <small>Manager List</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo site_url(admin_url().'home');?>"><i class="fa fa-dashboard"></i> 首页</a></li>
    <li class="active">会员管理</li>
  </ol>
</section>

<!-- Main content -->
<section class="content container-fluid">
	<div class="box box-primary">
    		<div class="box-header with-border">
            	<div class="box-header row">
            
              <div class="pull-left col-sm-4">
                <a href="javascript:;"onclick="openModel('admin/member/exportMember/', '导出会员');" class="btn btn-sm btn-info btn-down"><i class="glyphicon glyphicon-download-alt" title="导出二维码"></i> 导出会员</a>　
              </div>
              <div class="pull-right col-sm-7">
              		<div class="row">
                        <div class="col-sm-10">
                            <div class="input-group">
                              <span class="input-group-addon"><i class="fa fa-search"></i></span>
                              <input type="text" name="keys" id="keys" placeholder="请输入搜索关键字" class="form-control">
                            </div>
                            
                        </div>
                        <div class="col-xm-2">
                            <button type="button" class="btn btn-info btn-flat" onClick="memberSeach();">查找</button>
                        </div>
                    </div>
              </div>
            </div>
              	
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
              <table class="table table-striped table-bordered">
                <tbody>
                <tr>
                  <th>ID</th>
                  <th>图像</th>
                  <th>电话</th>
                  <th>用户名</th>
                  <th>邮箱</th>
                  <th>上次登录时间</th>
                  <th>注册时间</th>
                  <th>用户类型</th>
                  <th>手机归属地</th>
                  <th>操作</th>
                </tr>
                <?php
                	foreach($member_list as $list):
				?>
                <tr id="row<?php echo $list['id'];?>">
                  <td><?php echo $list['id'];?></td>
                  <td><img src="<?php echo base_url('../upload/'.$list['image']);?>" width="30" height="30"></td>
                  <td><?php echo $list['phone'];?></td>
                  <td><?php echo $list['username'];?></td>
                  <td><?php echo $list['email'];?></td>
                  <td><?php echo $list['last_login'] > 0 ? date('Y-m-d H:i:s',$list['last_login']) : '-';?></td>
                  <td><?php echo $list['add_time'] > 0 ? date('Y-m-d H:i:s',$list['add_time']) : '-';?></td>
                  <td><?php echo $list['type'] == 1 ? '紫恩会员' : ( $list['type'] == 2 ? '管理员' : '普通会员');?></td>
                  <td><?php echo $list['useraddress'];?></td>
                  <td><a href="javascript:;" onClick="delmsg('<?php echo admin_url();?>member/del/<?php echo $list['id']?>', this, <?php echo $list['id'];?>);"><i class="fa fa-trash"></i></a></td>
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

<script>

function  memberSeach(){
	var keys = $("#keys").val();
	if(keys == '') return false;
	
	go('<?php echo admin_url()."member/search/"?>'+keys+'');
	
}



</script>
