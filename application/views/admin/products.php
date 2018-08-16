<section class="content-header">
  <h1>
    产品管理
    <small>Products List</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo site_url(admin_url().'home');?>"><i class="fa fa-dashboard"></i> 首页</a></li>
    <li class="active">产品管理</li>
  </ol>
</section>

<!-- Main content -->
<section class="content container-fluid">
	<div class="box">
            <div class="box-header row">
            
              <div class="pull-left col-sm-6">
                <a href="javascript:;" onclick="openModel('<?php echo site_url(admin_url().'products/mod/add');?>', '编辑产品信息');" class="btn btn-sm btn-info btn-flat"><i class="fa fa-plus-circle" title="添加产品"></i> 添加产品</a>
                
              </div>
              <div class="pull-right col-sm-6">
              		<div class="row">
                        <div class="col-sm-6">
                            <div class="input-group">
                              <span class="input-group-addon"><i class="fa fa-search"></i></span>
                              <input type="text" name="keys" id="keys" placeholder="请输入搜索关键字" value="<?php echo isset($title) ? $title : '';?>" class="form-control">
                            </div>
                            
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                              <select class="form-control" id="cid" id="cid">
                                 <option value="0">类型</option>
                                 <?php
									echo $catesoption;
								?>
                                </select>
                            </div>
                            
                        </div>
                        <div class="col-xm-2">
                            <button type="button" class="btn btn-primary btn-flat" onClick="productsSeach();">查找</button>
                        </div>
                    </div>
              </div>
            </div>
            <div class="box-body no-padding">
              <table class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th><input type="checkbox" name="ids" id="ids" value="" class="my_checkbox" onclick="selectall('checkbox')" /></th>
                  <th>ID</th>
                  <th>图片</th>
                  <th>名称</th>
                  <th>类型</th>
                  <th>价格</th>
                  <th>市场价</th>
                  <th>库存</th>
                  <th>销量</th>
                  <th>发布时间</th>
                  <th>状态</th>
                  <th>推荐</th>
                  <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <?php
                	foreach($products_list as $list):
				?>
                <tr>
                  <td><input type="checkbox" name="checkbox" value="<?php echo $list['id'];?>" class="my_checkbox" /></td>
                  <td><?php echo $list['id'];?></td>
                  <td><img src="<?php echo !empty($list['picture']) ? base_url($list['picture']) : base_url('data/'.admin_url().'images/nopic.jpg');?>" width="30" height="30" /></td>
                  <td><?php echo $list['title'];?></td>
                  <td><?php echo $list['cat_name'];?></td>
                  <td><?php echo $list['price'];?></td>
                  <td><?php echo $list['mprice'];?></td>
                  <td><?php echo $list['stocks'];?></td>
                  <td><?php echo $list['salenums'];?></td>                  
                  <td><?php echo date("Y-m-d",$list['addtime']);?></td>
                  <td><?php echo $list['state'] == 1 ? '已审核' : '待审核';?></td>
                  <td><?php echo $list['iscommend'] == 1 ? '是' : '否';?></td>
                  <td><a href="javascript:;" onclick="openModel('<?php echo admin_url();?>products/mod/<?php echo $list['id'];?>', '编辑优惠券信息');"><i class="fa fa-edit"></i></a>　<a href="javascript:;" onClick="delmsg('<?php echo admin_url();?>products/del/<?php echo $list['id'];?>', this, <?php echo $list['id'];?>);"><i class="fa fa-trash"></i></a></td>
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

function productsSeach(){
	var keys = $("#keys").val();
	var cid = $("#cid").val();
	if(keys == '' && cid == ''){
		 return false;	
	}
	var kstr = '';
		kstr = keys+'-'+cid;
	
	go('<?php echo admin_url()."products/search/"?>'+kstr+'');
	
}


</script>
