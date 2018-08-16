<link rel="stylesheet" href="data/<?php echo admin_url();?>plugins/datepicker/bootstrap-datepicker.min.css">
<script src="data/<?php echo admin_url();?>plugins/datepicker/bootstrap-datepicker.min.js"></script>
<section class="content-header">
  <h1>
    销售排行
    <small>Products List</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo site_url(admin_url().'home');?>"><i class="fa fa-dashboard"></i> 首页</a></li>
    <li class="active">销售排行</li>
  </ol>
</section>

<!-- Main content -->
<section class="content container-fluid">
	<div class="box">
            <div class="box-header with-border">
                <div class="row">
                	<div class="col-sm-3">
                    	<div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-search"></i></span>
                          <input type="text" name="keys" id="keys" placeholder="请输入搜索关键字" value="<?php echo isset($title) ? $title : '';?>" class="form-control">
                        </div>
                        
                    </div>
                    <div class="col-sm-2">
                    	<div class="input-group">
                          <div class="input-group-addon">
                            <i class="fa fa-clock-o"></i>
                          </div>
                          <input type="text" class="form-control pull-right" id="stime" value="<?php echo isset($stime) && $stime > 0 ? date('m/d/Y',$stime) : '';?>">
                        </div>
                    </div>
                    <div class="col-sm-2">
                    	<div class="input-group">
                          <div class="input-group-addon">
                            <i class="fa fa-clock-o"></i>
                          </div>
                          <input type="text" class="form-control pull-right" id="etime" value="<?php echo isset($etime) && $etime > 0 ? date('m/d/Y',$etime) : '';?>">
                        </div>
                    </div>
                    <div class="col-sm-2">
                    	<div class="form-group">
                          <select class="form-control" id="storeid" id="storeid">
                             <option value="99">门店</option>
                             <?php
                             	foreach($store_list as $slist):
							 ?>
                             <option value="<?php echo $slist['cat_id'];?>" <?php echo isset($storeid) && $slist['cat_id'] == $storeid ? 'selected' : '';?>><?php echo $slist['cat_name'];?></option>
                             <?php
                             	endforeach;
							 ?>
                            </select>
                        </div>
                        
                    </div>
                    <div class="col-xm-1">
                		<button type="button" class="btn btn-info btn-flat" onClick="orderSeach();">查找</button>
                	</div>
                </div>
            </div>
            <div class="box-body no-padding">
              <table class="table table-striped table-bordered">
                <tbody>
                <tr>
                  <th><input type="checkbox" name="ids" id="ids" value="" class="my_checkbox" onclick="selectall('checkbox')" /></th>
                  <th>ID</th>
                  <th>图片</th>
                  <th>名称</th>
                  <th>价格</th>
                  <th>销售数量</th>
                  
                </tr>
                <?php
                	foreach($ranking_list as $list):
				?>
                <tr>
                  <td><input type="checkbox" name="checkbox" value="<?php echo $list['id'];?>" class="my_checkbox" /></td>
                  <td><?php echo $list['id'];?></td>
                  <td><img src="<?php echo base_url('../upload/'.$list['image']);?>" width="50" height="50" /></td>
                  <td><?php echo $list['title'];?></td>
                  <td><?php echo $list['price'];?></td>
                  <td><?php echo $list['snums'];?></td>
                </tr>
                <?php
                	endforeach;
				?>
              </tbody>
              </table>
            </div>
            <div class="box-footer clearfix">              
        
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
	if(keys == '' && stime == '' && etime == '' && storeid == ''){
		 return false;	
	}
	var kstr = '';
		kstr = keys+'-'+storeid+'-'+stime+'-'+etime;
	go('<?php echo admin_url()."orders/rankingsearch/"?>'+kstr+'');
	
}


</script>
