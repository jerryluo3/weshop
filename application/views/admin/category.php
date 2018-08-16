<section class="content-header">
  <h1>
    类型列表
    <small>Manager Group List</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo site_url(admin_url().'home');?>"><i class="fa fa-dashboard"></i> 首页</a></li>
    <li class="active">内容类型</li>
  </ol>
</section>

<!-- Main content -->
<section class="content container-fluid">
	<div class="box">
            <div class="box-header row">
            
              <div class="pull-left col-sm-8">
                <a href="javascript:;" onclick="openModel('<?php echo site_url(admin_url().'category/mod/add');?>', '编辑类别信息');" class="btn btn-sm btn-info btn-flat"><i class="fa fa-plus-circle" title="添加内容类别"></i> 添加类别</a>
                
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
                      <th>名称</th>
                      <th>图片</th>
                      <th>编号</th>                      
                      <th>排序ID</th>
                      <th>操作</th>
                    </tr>
                    <?php
						echo $cates;
					?>
					
                  </tbody>
              </table>
            </div>
            <div class="box-footer clearfix">  
            </div>
            <!-- /.box-body -->
          </div>
</section>
