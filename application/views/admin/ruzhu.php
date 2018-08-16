<link rel="stylesheet" href="data/<?php echo admin_url();?>plugins/datepicker/bootstrap-datepicker.min.css">
<script src="data/<?php echo admin_url();?>plugins/datepicker/bootstrap-datepicker.min.js"></script>
<section class="content-header">
  <h1>
    入驻管理
    <small>Orders List</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo site_url(admin_url().'home');?>"><i class="fa fa-dashboard"></i> 首页</a></li>
    <li class="active">入驻管理</li>
  </ol>
</section>

<!-- Main content -->
<section class="content container-fluid">
	<div class="box">
            
            <div class="box-body no-padding">
              <table class="table table-bordered table-hover dataTable">
                <tbody>
                <tr>
                  <th>ID</th>
                  <th>用户</th>
                  <th>LOGO</th>
                  <th>名称</th>
                  <th>联系人</th>
                  <th>电话</th>
                  <th>地址</th>
                  <th>类别</th>
                  <th>添加时间</th>
                  <th>状态</th>
                  <th>操作</th>
                </tr>
                <?php
                	foreach($ruzhu_list as $list):
				?>
                <tr id="row<?php echo $list['shop_id'];?>">
                  <td><?php echo $list['shop_id'];?></td>
                  <td><?php echo $list['mem_nickname'];?></td>
                  <td><img src="<?php echo base_url($list['shop_logo']);?>" width="30" height="30"></td>
                  <td><?php echo $list['shop_name'];?></td>
                  <td><?php echo $list['shop_contacter'];?></td>
                  <td><?php echo $list['shop_mobile'];?></td>
                  <td><?php echo $list['shop_address'];?></td>
                  <td><?php echo $list['shop_cat'];?></td>
                  <td><?php echo date('Y-m-d H:i:s',$list['shop_addtime']);?></td>
                  <td><?php echo $list['shop_state'] == 1 ? '已通过' : '待审核';?></td>
                  <td><?php if($list['shop_state'] == 0){ ?><button type="button" class="btn btn-info btn-flat" onClick="checkRuzhu(<?php echo $list['shop_id'];?>);">审核</button><?php } ?></td>
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


function  checkRuzhu(id){
	if(confirm('确认要审核通过吗？')){
		var params = 'id='+id;
		var url = "ajax_admin/checkRuzhu";
		
	//	alert(params);
	//	return false;
	
		
		loadingshow('数据更新中...');
		$.ajax({
			type: "post",
			url: url,
			dataType: "json",
			data: params,
			success: function(msg){
				//
				loadinghide();
	
				if(msg.status == 200){
					loadingshow('更新成功');
					loadinghide();
					$(".close").click();
					go('<?php echo admin_url()."ruzhu/index"?>');	
				}
			}
		});
	}
}

</script>