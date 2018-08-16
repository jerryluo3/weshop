<section class="content-header">
  <h1>
    广告类型列表
    <small>Manager Group List</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo site_url(admin_url().'home');?>"><i class="fa fa-dashboard"></i> 首页</a></li>
    <li class="active">广告类型</li>
  </ol>
</section>

<!-- Main content -->
<section class="content container-fluid">
	<div class="box">
            <div class="box-header row">
            
              <div class="pull-left col-sm-8">
                <a href="javascript:;" onclick="openModel('<?php echo site_url(admin_url().'adscates/mod/add');?>', '编辑广告类别信息');" class="btn btn-sm btn-info btn-flat"><i class="fa fa-plus-circle" title="添加广告类别"></i> 添加广告类别</a>
                
              </div>
              <div class="pull-right col-sm-4">
              		<div class="row">
                        <div class="col-sm-10">
                            <div class="input-group">
                              <span class="input-group-addon"><i class="fa fa-search"></i></span>
                              <input type="text" name="keys" id="keys" placeholder="请输入搜索关键字" value="<?php echo isset($title) ? $title : '';?>" class="form-control">
                            </div>
                            
                        </div>
                        <div class="col-xm-2">
                            <button type="button" class="btn btn-primary btn-flat" onClick="productSeach();">查找</button>
                        </div>
                    </div>
              </div>
            </div>
            <div class="box-body no-padding">
              <table class="table table-striped table-bordered">
                 <tbody>
                    <tr>
                      <th><input type="checkbox" onclick="setC()" id="deletec" name="deletec" ></th>
                      <th>编号</th>
                      <th>名称</th>
                      <th>上级ID</th>
                      <th>排序ID</th>
                      <th>操作</th>
                    </tr>
                    <?php
						foreach($adscates_list as $list):
					?>
					<tr>
					<td><input type="checkbox" class="deletec"  name="id[]" value="<?php echo $list['cat_id']; ?>" ></td>
					<td><?php echo $list['cat_id']?></td>
					<td><?php echo $list['cat_name']?></td>
					<td><?php echo $list['cat_fid']?></td>
					<td><?php echo $list['cat_rank']?></td>
					<td><a href="javascript:;" onclick="openModel('<?php echo admin_url();?>adscates/mod/<?php echo $list['cat_id'];?>', '编辑广告类别信息');"><i class="fa fa-edit"></i></a>　<a href="javascript:;" onClick="delmsg('<?php echo admin_url();?>adscates/del/<?php echo $list['cat_id'];?>', this, <?php echo $list['cat_id'];?>);"><i class="fa fa-trash"></i></a></td>
					</tr> 
					<?php endforeach ?>
                  </tbody>
              </table>
            </div>
            <div class="box-footer clearfix">              
              <?php echo $page_list;?>
            </div>
            <!-- /.box-body -->
          </div>
</section>
