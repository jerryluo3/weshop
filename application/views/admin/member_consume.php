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
                <a href="javascript:;"onclick="openModel('admin/member/exportMemberConsume/', '导出消费明细');" class="btn btn-sm btn-info btn-down"><i class="glyphicon glyphicon-download-alt" title="导出消费明细"></i> 导出消费明细</a>　
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
                  <th>电话</th>
                  <th>用户名</th>
                  <th>类型</th>
                  <th>金额</th>
                  <th>变化前金额</th>
                  <th>变化后金额</th>
                  <th>备注</th>
                  <th>时间</th>
                </tr>
                <?php
                	foreach($consume_list as $list):
				?>
                <tr id="row<?php echo $list['id'];?>">
                  <td><?php echo $list['id'];?></td>
                  <td><?php echo $list['phone'];?></td>
                  <td><?php echo $list['username'];?></td>
                  <td><?php echo $list['type'] == 1 ? '充值' : '消费';?></td>
                  <td><?php echo $list['amount'];?></td>
                  <td><?php echo $list['s_money'];?></td>
                  <td><?php echo $list['e_money'];?></td>
                  <td><?php echo $list['desc'];?></td>
                  <td><?php echo $list['addtime'] > 0 ? date('Y-m-d H:i:s',$list['addtime']) : '-';?></td>
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
