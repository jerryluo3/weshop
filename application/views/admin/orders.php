<link rel="stylesheet" href="data/<?php echo admin_url();?>plugins/datepicker/bootstrap-datepicker.min.css">
<script src="data/<?php echo admin_url();?>plugins/datepicker/bootstrap-datepicker.min.js"></script>
<section class="content-header">
  <h1>
    订单管理
    <small>Orders List</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo site_url(admin_url().'home');?>"><i class="fa fa-dashboard"></i> 首页</a></li>
    <li class="active">订单管理</li>
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
                  <th>订单号</th>
                  <th>商品</th>
                  <th>数量</th>
                  <th>总价</th>
                  <th>成交时间</th>
                  <th>状态</th>
                  <th>收货人</th>
                  <th>收货人电话</th>
                  <th>配送地址</th>
                  <th>配送收货人</th>
                  <th>配送电话</th>
                </tr>
                <?php
                	foreach($orders_list as $list):
				?>
                <tr id="row<?php echo $list['id'];?>">
                  <td><?php echo $list['id'];?></td>
                  <td><?php echo $list['mem_nickname'];?></td>
                  <td><?php echo $list['oid'];?></td>
                  <td><?php echo $list['title'];?></td>
                  <td><?php echo $list['snum'];?></td>
                  <td><?php echo $list['amount'];?></td>
                  <td><?php echo date("Y-m-d H:i:s",$list['addtime']);?></td>
                  <td><?php echo getOrderStatus($list['status']);?></td>
                  <td><?php echo $list['user_name'];?></td>
                  <td><?php echo $list['user_mobile'];?></td>
                  <td><?php echo $list['a_address'];?></td>
                  <td><?php echo $list['a_contacter'];?></td>
                  <td><?php echo $list['a_mobile'];?></td>
                </tr>
                <?php
                	endforeach;
				?>
              </tbody>
              </table>
            </div>
            <div class="box-footer clearfix">              
              <?php if(isset($all_amount['amount'])){ ?>总金额：<?php echo $all_amount['amount'];?><?php } ?>  <?php echo $page_list;?>
            </div>
            <!-- /.box-body -->
          </div>
</section>
<script>
$('#stime').datepicker({
  autoclose: true
})
$('#etime').datepicker({
  autoclose: true
})

function  orderSeach(){
	var keys = $("#keys").val();
	var status = $("#status").val();
	var stime = $("#stime").val();
	stime = Date.parse(stime)/1000;
	var etime = $("#etime").val();
	etime = Date.parse(etime)/1000;
	var zhifu_type = $("#zhifu_type").val();
	var storeid = $("#storeid").val();
	if(keys == '' && stime == '' && etime == '' && status == '' && zhifu_type == '' && storeid == ''){
		 return false;	
	}
	var kstr = '';
		kstr = keys+'-'+status+'-'+zhifu_type+'-'+storeid+'-'+stime+'-'+etime;
	
	go('<?php echo admin_url()."orders/search/"?>'+kstr+'');
	
}

function  orderExport(){
	var keys = $("#keys").val();
	var status = $("#status").val();
	var stime = $("#stime").val();
	stime = Date.parse(stime)/1000;
	var etime = $("#etime").val();
	etime = Date.parse(etime)/1000;
	var zhifu_type = $("#zhifu_type").val();
	var storeid = $("#storeid").val();

	if(keys == '' && stime == 'NaN' && etime == 'NaN' && status == 99 && zhifu_type == 99 && storeid == 99){
		alert('请选择条件');
		 return false;	
	}

	var kstr = '';
		kstr = keys+'-'+status+'-'+zhifu_type+'-'+storeid+'-'+stime+'-'+etime;
	
	go('<?php echo admin_url()."orders/exportOrder/"?>'+kstr+'','location');
	loadinghide();
}

</script>